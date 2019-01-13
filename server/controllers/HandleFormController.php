<?php

/**
 * Created by PhpStorm.
 * User: compie-3
 * Date: 16/08/18
 * Time: 11:12
 */
include './Service.php';

class HandleFormController
{
    public function index()
    {

//        if(!empty($_GET['name'])) {
//            $_POST  = $_GET;
//        }

        $mode = $name = $email = $phone = $birth = $terms = $query = null;
        $terms = $timeChecked = $ipChecked = $mailing = $timeMailingChecked = $ipMailingChecked = null;
        $nameErr = $emailErr = $phoneErr = $birthErr = $termsErr = $mailingErr = "";
        $mode = Service::test_input($_POST['mode']);

        //validation
        if (!empty($_POST)) {
            if (empty($_POST["name"])) {
                $nameErr = "Name field is missing.";
            } else {
                $name = Service::test_input($_POST["name"]);
                if (!preg_match("/^([a-zA-Zא-ת]+[']*)+([\s]?([a-zA-Zא-ת]+[']*)+)+$/", $name)) {
                    $nameErr = "Only letters allowed in the name field";
                }
            }

            if (empty($_POST["email"])) {
                $emailErr = "Email field is missing.";
            } else {
                $email = Service::test_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $emailErr = "Invalid email format";
                }
            }

            if (empty($_POST["phone"])) {
                $phoneErr = "Phone field is missing.";
            } else {
                $phone = Service::test_input($_POST['phone']);
                if (!preg_match("/^05\d{8}$/", $phone)) {//"/^(?:(?:(\+?972|\(\+?972\)|\+?\(972\))(?:\s|\.|-)?([1-9]\d?))|(0[23489]{1})|(0[57]{1}[0-9]))(?:\s|\.|-)?([^0\D]{1}\d{2}(?:\s|\.|-)?\d{4})$/im", $phone)) {
                    $emailErr = "Invalid phone format";
                }
            }

            if (empty($_POST["dateOfBirth"])) {
                $birthErr = "Date Of Birth field is missing.";
            } else {
                $birth = $_POST['dateOfBirth'];
                if (!Service::checkDate($birth))
                    $birthErr = "illegal date.";
            }

            if ($_POST["acceptTerms"] != 'true') {
                $termsErr = "Notice: You should accept the terms before you move on";
            } else {
                $terms = 1;//$_POST["acceptTerms"];
            }
            if (empty($_POST["unixTimeChecked"]) || $_POST["unixTimeChecked"] > time()) {
                $termsErr = "Accepting terms error. Try again.";
            } else {
                $timeChecked = $_POST["unixTimeChecked"];
                $ipChecked = $_SERVER['REMOTE_ADDR'];
            }
            if ($_POST["acceptMailing"] != 'true' && $_POST["acceptMailing"] != 'false') {
                $mailingErr = "Error on accept mailing field. Check up what's wrong.";
            } else {
                $mailing = $_POST["acceptMailing"] == 'true' ? 1 : 0;//$_POST["acceptMailing"];
            }
            if (empty($_POST["unixTimeMailingChecked"]) || $_POST["unixTimeMailingChecked"] > time()) {
                $mailingErr = "Error on accept mailing field. Check up what's wrong.";
            } else {
                $timeMailingChecked = $_POST["unixTimeMailingChecked"];
                $ipMailingChecked = $_SERVER['REMOTE_ADDR'];
            }
//convert from unix to date:
            $birth = date("Ymd", $birth);
            $timeChecked = date("Y-m-d H:i:s", $timeChecked);
            $timeMailingChecked = date("Y-m-d H:i:s", $timeMailingChecked);
//This script handles 5 cases:
//case 1: new user
//case 2: exist user
//case 3: illegal enter- mail exists but other details don't match
//case 4: edit details
//case 5: illegal details

//cases 1,2,4 are correct. success function fires and sends the data object response from db to client.
//cases 3,5 are incorrect. validationFailed function fires and sends errors response to client.
//db connection errors also handled by DBConnectionFailed function.

            if ($nameErr != "" || $emailErr != "" || $phoneErr != "" || $birthErr != "" || $termsErr != "" || $mailingErr != "") {
                Service::validationFailed($nameErr, $emailErr, $phoneErr, $birthErr, $termsErr, $mailingErr);//case5: illegal details
            }

            $link = new mysqli("localhost", "root", "770770");
            if ($link->connect_error)
                Service::DBConnectionFailed("connecting Error: " . $link->connect_error);
            if ($mode == "insert") {
                $query = "select * from dbSite.tblLogin where `email`='" . $email . "'";
                if ($result = mysqli_query($link, $query)) {
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            if ($row['name'] != $name || $row['phone'] != $phone || date("Ymd", strtotime($row['dateOfBirth'])) != $birth) {
                                // echo $row['name'].$name.$row['phone'].$phone.date("Ymd", strtotime($row['dateOfBirth'])).$birth;
                                $emailErr = "User details doesn't match. Please check up for any wrong inputs";
                                Service::validationFailed($nameErr, $emailErr, $phoneErr, $birthErr, $termsErr, $mailingErr);//case 3: illegal enter- mail exists but other details doesn't match
                            } else {
                                $user = User::selectFromDB($email);
                                Service::success($link, $query, $user);//case2:exist user
                            }
                        }
                    } else {
                        //case 1:new user
                        $query = "insert into dbSite.tblLogin(`email`,`name`,`phone`,`dateofbirth`,
            `termsAccepted`,`termsAcceptedTime`,`termsAcceptedIp`) 
            VALUES ('" . $email . "','" . $name . "','" . $phone . "'," . $birth . ",
            " . $terms . ",'" . $timeChecked . "','" . $ipChecked . "');";

                        if (!$link->query($query)) {
                            Service::DBConnectionFailed("error: " . $link->error . 'query:' . $query);
                        };
                        $user = User::selectFromDB($email);
                        $query = "insert into dbSite.tblAcceptMailing(`userID`,`mailingAccepted`,`mailingAcceptedTime`,`mailingAcceptedIp`)
            VALUES (" . $user->userID . "," . $mailing . ",'" . $timeMailingChecked . "','" . $ipMailingChecked . "');";
                        //echo $query;
                        Service::success($link, $query, $user);
                    }
                }
            }
            if ($mode == 'update') {
                $user = User::selectFromDB($email);
                $query = "update dbSite.tblLogin set `name`='" . $name . "',`phone`='" . $phone . "',`dateofbirth`=" . $birth . ",
                `termsAccepted`=" . $terms . ",`termsAcceptedTime`='" . $timeChecked . "',`termsAcceptedIp`='" . $ipChecked . "' where `userID`='" . $user->id . "';";
                if (!$link->query($query)) {
                    Service::DBConnectionFailed("error: " . $link->error . 'query:' . $query);
                };
                $query = "insert into dbSite.tblAcceptMailing(`userID`,`mailingAccepted`,`mailingAcceptedTime`,`mailingAcceptedIp`) 
            VALUES (" . $user->userID . "," . $mailing . ",'" . $timeMailingChecked . "','" . $ipMailingChecked . "');";
                Service::success($link, $query, $user);//case4: edit details
            }
        }
    }
}