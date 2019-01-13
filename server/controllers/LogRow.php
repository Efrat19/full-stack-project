<?php

/**
 * Created by PhpStorm.
 * User: compie-3
 * Date: 22/08/18
 * Time: 16:20
 */
class LogRow
{
    public $rowID;
    public $userID;
    public $mailingAccepted;
    public $mailingAcceptedTime;
    public $mailingAcceptedIp;

    public function __construct($rowID,$userID,$mailingAccepted,$mailingAcceptedTime,$mailingAcceptedIp)
    {
        $this->rowID=$rowID;
        $this->userID=$userID;
        $this->mailingAccepted=$mailingAccepted;
        $this->mailingAcceptedTime=$mailingAcceptedTime;
        $this->mailingAcceptedIp=$mailingAcceptedIp;
    }


}