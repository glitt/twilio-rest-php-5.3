<?php

namespace Twilio;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $configuration;

    public function setTestCredentials()
    {
        $this->configuration = array(
            'accountSid'      => getenv('TWILIO_ACCOUNT_SID'),
            'authToken'       => getenv('TWILIO_AUTH_TOKEN'),
            'callerPin'       => getenv('TWILIO_CALLER_PIN'),
            'phoneNumberOne'  => getenv('TWILIO_PHONE_NUMBER_ONE'),
            'phoneNumberTwo'  => getenv('TWILIO_PHONE_NUMBER_TWO'),
            'apiVersion'      => '2010-04-01'
        );
    }
}
