<?php

/**
 * Created by PhpStorm.
 * User: compie-3
 * Date: 15/08/18
 * Time: 14:10
 */
class User
{
    public $userID;
    public $name;
    public $email;
    public $phone;
    public $dateOfBirth;
    public $termsAccepted;
    public $termsAcceptedTime;
    public $termsAcceptedIp;
    public $log;
    public function __construct($id,$name,$email,$phone,$dateOfBirth,$termsAccepted,$termsAcceptedTime,$termsAcceptedIp)
    {
        $this->userID=$id;
        $this->name=$name;
        $this->email=$email;
        $this->phone=$phone;
        $this->dateOfBirth=$dateOfBirth;
        $this->termsAccepted=$termsAccepted;
        $this->termsAcceptedTime=$termsAcceptedTime;
        $this->termsAcceptedIp=$termsAcceptedIp;
        $this->log=$this->getLog();
    }

    public static function selectFromDB($email)
    {
        $query = "select * from dbSite.tblLogin where `email`='" . $email . "'";
        $link = new mysqli("localhost", "root", "770770");
        if ($link->connect_error)
            Service::DBConnectionFailed("connecting Error: " . $link->connect_error);
        if ($result = mysqli_query($link, $query)) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    return new User($row['userID'],$row['name'],$row['email'],$row['phone'],
                        $row['dateOfBirth'],$row['termsAccepted'],$row['termsAcceptedTime'],$row['termsAcceptedIp']);
                }
            }
        }
    }

    public function getLog(){
        $query = "select * from dbSite.tblAcceptMailing where `userID`='" . $this->userID . "'";
        $link = new mysqli("localhost", "root", "770770");
        $log= array();
        if ($link->connect_error)
            Service::DBConnectionFailed("connecting Error: " . $link->connect_error);
        if ($result = mysqli_query($link, $query)) {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_array($result)) {
                    $lr=new LogRow($row['rowID'],$row['userID'],$row['mailingAccepted'],$row['mailingAcceptedTime'],$row['mailingAcceptedIp']);
                    array_push($log,$lr);
                }
                }
            }
            return $log;
    }
}