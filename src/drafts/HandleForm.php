<?php
//include './Service.php';
//function test_input($data) {
//    $data = trim($data);
//    $data = stripslashes($data);
//    $data = htmlspecialchars($data);
//    return $data;
//}
//
//function checkDate($date)
//{
//    $unixNow =time();
//    $unix18Years = 567993600;
//    if ($date>= $unixNow - $unix18Years)
//        return false;
//    return true;
//}
//
//function sendResponse($res,$link)
//{
////    global $link;
//    $link->close();
//    echo json_encode($res);
//    exit();
//}
//function validationFailed($nameErr,$emailErr,$phoneErr,$birthErr,$termsErr;)
//{
////    global $nameErr,$emailErr,$phoneErr,$birthErr,$termsErr;
//    $res=new Response("validationFailed",array($nameErr,$emailErr,$phoneErr,$birthErr,$termsErr));
//    sendResponse($res);
//}
//function success($link,$query,$email)
//{
////    global $link,$query,$email;
//    if (!$link->query($query)) {
//        DBConnectionFailed("error: " . $link->error);
//    }
//    $user=new User();
//    $user->selectFromDB($email);
//    $res=new Response("success",$user);
//    sendResponse($res);
//}
//function DBConnectionFailed($error)
//{
//    $res=new Response("DBConnectionFailed",$error);
//    sendResponse($res);
//}
//
//
//$mode=test_input($__POST['mode']);
//
////validation
//$nameErr = $emailErr = $phoneErr = $birthErr = $termsErr="";
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    if (empty($_POST["name"])) {
//        $nameErr = "Name field is missing.";
//    } else {
//            $name = test_input($_POST["name"]);
//        if (!preg_match("/^[a-zA-Zא-ת]*$/",$name)){
//            $nameErr = "Only letters and white space allowed";
//        }
//    }
//
//    if (empty($_POST["email"])) {
//        $emailErr = "Email field is missing.";
//    } else {
//        $email = test_input($_POST["email"]);
//        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//            $emailErr= "Invalid email format";
//        }
//    }
//
//    if (empty($_POST["phone"])) {
//        $phoneErr = "Phone field is missing.";
//    } else {
//        $phone=test_input($_POST['phone']);
//        if (!preg_match("/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im",$phone)) {
//            $emailErr= "Invalid phone format";
//        }
//    }
//
//    if (empty($_POST["dateOfBirth"])) {
//        $birthErr = "Date Of Birth field is missing.";
//    } else {
//        $birth=$_POST['dateOfBirth'];
//        if(!checkDate($birth))
//            $birthErr = "illegal date of birth. Try signing when your older";
//    }
//
//    if (($_POST["acceptTerms"])!=true) {
//        $termsErr = "Nice try :) You should accept the terms before you move on";
//    } else {
//        //$terms = test_input($_POST["acceptTerms"]);
//    }
//}
////convert $birth from unix to date:
//$birth=date("Ymd",$birth);
//
////This script handles 5 cases:
////case 1: new user
////case 2: exist user
////case 3: illegal enter- mail exists but other details don't match
////case 4: edit details
////case 5: illegal details
//
////cases 1,2,4 are correct. success function fires and sends the data object response from db to client.
////cases 3,5 are incorrect. validationFailed function fires and sends errors response to client.
////db connection errors also handled by DBConnectionFailed function.
//
//if($nameErr!=""&&$emailErr!=""&&$phoneErr!=""&&$birthErr!=""&&$termsErr!=""){
//    validationFailed();//case5: illegal details
//}
//    $link = new mysqli("localhost", "root", "770770");
//    if ($link->connect_error)
//        DBConnectionFailed("connecting Error: " . $link->connect_error);
//
//    if ($mode = "insert") {
//    $query = "select * from tblusers where `email`='" . $email . "'";
//    if ($result = mysqli_query($link, $query)) {
//        if (mysqli_num_rows($result) > 0) {
//            while ($row = mysqli_fetch_array($result)) {
//                if ($row['name'] != $name || $row['phone'] != $phone || $row['dateOfBirth'] != $birth) {
//                    $emailErr = "User details doesn't match. Please check up for any wrong inputs";
//                    validationFailed();//case 3: illegal enter- mail exists but other details doesn't match
//                }
//                else
//                {
//                    success();//case2:exist user
//                }
//            }
//        }
//        else {
//            //case 1:new user
//            $query = "insert into tblusers(`email`,`name`,`phone`,`dateofbirth`)
//            VALUES ('" . $email . "','" . $name . "','" . $phone . "'," . $birth . ");";
//            success();
//        }
//    }
//    }
//if($mode='update'){
//    //    $query = "update tblusers(`email`,`name`,`phone`,`dateofbirth`,`acceptTerms`)
//    //VALUES ('" . $email . "','" . $name . "','" . $phone . "'," . $birth . "," . $accept . ");";
//    success();//case4: edit details
//}
//
