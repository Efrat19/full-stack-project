<?php
include './Service.php';

class SelectFromDBController
{
    function index()
    {
        $value = $emailErr = $nameErr = $phoneErr = "";
        if (!empty($_POST)) {
            if ($_POST['column'] != 'name' && $_POST['column'] != 'phone' && $_POST['column'] != 'email')
                Service::validationFailed('wrong search parameters', '', '', '', '');
            if ($_POST['column'] == 'email') {
                if (empty($_POST["value"])) {
                    $emailErr = "Email value is missing.";
                } else {
                    $value = Service::test_input($_POST["value"]);
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $emailErr = "Invalid email format";
                    }
                }
            }
            if ($_POST['column'] == 'name') {
                if (empty($_POST["value"])) {
                    $emailErr = "name value is missing.";
                } else {
                    $value = Service::test_input($_POST["value"]);
                    if (!preg_match("/^([a-zA-Zא-ת]+[']*)+([\s]?([a-zA-Zא-ת]+[']*)+)+$/", $value)) {
                        $nameErr = "Only letters allowed in the name field";
                    }
                }

            }
        }
        if ($_POST['column'] == 'phone') {
            if (empty($_POST["value"])) {
                $emailErr = "phone value is missing.";
            } else {
                $value = Service::test_input($_POST['value']);
                if (!preg_match("/^05\d{8}$/", $value)) {//"/^(?:(?:(\+?972|\(\+?972\)|\+?\(972\))(?:\s|\.|-)?([1-9]\d?))|(0[23489]{1})|(0[57]{1}[0-9]))(?:\s|\.|-)?([^0\D]{1}\d{2}(?:\s|\.|-)?\d{4})$", $value)) {
                    $emailErr = "Invalid phone format";
                }
            }

        }
        if ($_POST['column'] == 'mailing') {
            if ($_POST["value"] != 'true' && $_POST["value"] != 'false') {
                $emailErr = "invalid input on mailing field.";
            } else {
                $value = $_POST["value"] == 'true' ? 1 : 0;//$_POST["acceptMailing"];
            }
            if ($nameErr != "" || $emailErr != "" || $phoneErr != "") {
                Service::validationFailed($nameErr, $emailErr, $phoneErr, '', '');
            }
        }
        $users = array();
        $link = mysqli_connect("localhost", "root", "770770", "dbUsers");
        // Check connection
        if ($link === false) {
            Service::DBConnectionFailed("ERROR: Could not connect. " . mysqli_connect_error());
        }
        if ($_POST['column'] == 'mailing') {

            $sql = "select * from dbSite.tblLogin inner join dbSite.tblAcceptMailing 
on dbSite.tblLogin.userID=dbSite.tblAcceptMailing.userID 
where `" . $_POST['column'] . "`='" . $value . "' ORDER by `" . $_POST['column'] . "`";

        } else {
            // Attempt select query execution
            $sql = "SELECT * FROM dbSite.tblLogin where `" . $_POST['column'] . "`='" . $value . "' ORDER by `" . $_POST['column'] . "`";
        }
        if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    array_push($users, new User($row['userID'], $row['name'], $row['email'], $row['phone'],
                        $row['dateOfBirth'], $row['termsAccepted'], $row['termsAcceptedTime'], $row['termsAcceptedIp']));
                }
                mysqli_free_result($result);
                echo json_encode(new Response('success', $users));
            } else {
                Service::DBConnectionFailed("No records matching your query were found." . $emailErr);
            }
        } else {
            Service::DBConnectionFailed("ERROR: Could not able to execute $sql. " . mysqli_error($link));
        }
        // Close connection
        mysqli_close($link);
    }

}