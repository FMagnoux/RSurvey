<?php

/**
 * Created by PhpStorm.
 * User: rfrancois
 * Date: 13/06/2016
 * Time: 12:11
 */
class Mail
{
    private $sName;
    private $sFrom;
    private $sTo;
    private $sSubject;
    private $sMessage;

    /**
     * Mail constructor.
     * @param $sName
     * @param $sFrom
     * @param $sTo
     * @param $sSubject
     * @param $sMessage
     */
    public function __construct($sName, $sFrom, $sTo, $sSubject, $sMessage)
    {
        $this->sName = $sName;
        $this->sFrom = $sFrom;
        $this->sTo = $sTo;
        $this->sSubject = $sSubject;
        $this->sMessage = $sMessage;
    }

    public function sendMail() {
        $sHeaders =
            'MIME-Version: 1.0' . "\r\n" .
            'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
            'From:  '.$this->sName.' <'.$this->sFrom.'>' . "\r\n" .
            'Reply-To: '.$this->sFrom.'' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        @mail($this->sTo, $this->sSubject, $this->sMessage, $sHeaders);
    }
}
