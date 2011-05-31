<?php

namespace Twilio\Rest;

class Response
{
    public $ResponseText;
    public $ResponseXml;
    public $HttpStatus;
    public $Url;
    public $QueryString;
    public $IsError;
    public $ErrorMessage;

    public function __construct($url, $text, $status)
    {
        preg_match('/([^?]+)\??(.*)/', $url, $matches);
        $this->Url = $matches[1];
        $this->QueryString = $matches[2];
        $this->ResponseText = $text;
        $this->HttpStatus = $status;
        if($this->HttpStatus != 204){
            $this->ResponseXml = @simplexml_load_string($text);
        }

        if($this->IsError = ($status >= 400)){
            $this->ErrorMessage = (string)$this->ResponseXml->RestException->Message;
        }
    }
}
