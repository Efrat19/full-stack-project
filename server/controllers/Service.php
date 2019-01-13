<?php
include './Response.php';
include './User.php';

class Service
{
    static function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    static function checkDate($date)
    {
        $unixNow = time();
        $unix18Years = 567993600;
        $unix100Years=3155760000;
        if ($date >= $unixNow - $unix18Years|| $date<=$unixNow - $unix100Years)
            return false;
        return true;
    }

    static function sendResponse($res)
    {
        echo json_encode($res);
        exit();
    }

    static function validationFailed($nameErr, $emailErr, $phoneErr, $birthErr, $termsErr,$mailingErr)
    {
        $res = new Response("validationFailed", array($nameErr, $emailErr, $phoneErr, $birthErr, $termsErr,$mailingErr));
        Service::sendResponse($res);
    }

    static function success($link, $query, $user)
    {
//    global $link,$query,$email;
        if (!$link->query($query)) {
            Service::DBConnectionFailed("error: " . $link->error.'query:'.$query);
        }
        $user->log=$user->getLog();
        $res = new Response("success", $user);
        $link->close();
        Service::sendResponse($res);
    }

    static function DBConnectionFailed($error)
    {
        $errors=array($error,'','','','','');
        $res = new Response("DBConnectionFailed", $errors);
        Service::sendResponse($res);
    }
}