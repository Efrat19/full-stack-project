<?php
/**
 * Created by PhpStorm.
 * User: compie-3
 * Date: 13/08/18
 * Time: 09:56
 */

class CreateDBController extends BaseController{
    public function index(){
//        die('OK');
        $link=new mysqli("localhost","root","770770");
        if($link->connect_error)
            die("connecting Error: ".$link->connect_error);
        $query="create database if not exists dbSite;";
        if(!$link->query($query))
            die("error: ".$link->error);
        echo "database created---";

        $query="use dbSite;";
        if(!$link->query($query))
            die("error: ".$link->error);
        echo "database connected---";

        $query="create table if not exists tblLogin(
userID int NOT NULL AUTO_INCREMENT,
email nvarchar(30) not null,
name nvarchar(20) not null,
phone nvarchar(10) not null,
dateOfBirth date not null,
termsAccepted bit not null,
termsAcceptedTime DATETIME not NULL ,
termsAcceptedIp nvarchar(30) not null,
primary key(userID));";
        if(!$link->query($query))
            die("error: ".$link->error);
        echo "table1 created---";

        $query="use dbSite;";
        if(!$link->query($query))
            die("error: ".$link->error);
        echo "table1 connected---";

        $query="create table if not exists tblAcceptMailing(
rowID int NOT NULL AUTO_INCREMENT,
userID int NOT NULL,
mailingAccepted bit not null,
mailingAcceptedTime DATETIME not NULL,
mailingAcceptedIp nvarchar(30) not null,
primary key(rowID),
FOREIGN key (`userID`) REFERENCES dbSite.tblLogin (`userID`));";
        if(!$link->query($query))
            die("error: ".$link->error);
        echo "table2 created---";
        $link->close();
    }

}
