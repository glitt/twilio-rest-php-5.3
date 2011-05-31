<?php

namespace Twilio\Rest;

require_once 'PHPUnit/Autoload.php';

class TestSms extends \Twilio\TestCase
{
    public $endpoint;
    public $client;

    public function setUp()
    {
        $this->setTestCredentials();
        $this->endpoint = "/{$this->configuration['apiVersion']}/Accounts/{$this->configuration['accountSid']}/SMS/Messages";
        $this->client = new Client($this->configuration['accountSid'], $this->configuration['authToken']);
    }

    public function testSMS()
    {
        try{
            $response = $this->client->request(
                $this->endpoint,
                'POST',
                array(
                    'From' => $this->configuration['phoneNumberOne'],
                    'To' => $this->configuration['phoneNumberTwo'],
                    'Body' => 'Omg, touche mackice.'
                )
            );

            if($response->IsError){
                echo "Error: {$response->ErrorMessage}";
                $this->fail('ZOMG, failed.');
            }else{
                //test passed
            }
        }catch(\Exception $e){
            $this->fail('Exception fired with message:' . $e->getMessage());
        }
    }

    public function testError()
    {
        $response = $this->client->request(
            $this->endpoint,
            'POST',
            array(
                'From' => '',
                'To' => '',
                'Body' => 'Omg, touche mackice.'
            )
        );

        $this->assertEquals($response->IsError, TRUE);
    }

    /**
     * @expectedException Twilio\Exception
     */
    public function testException()
    {
        $response = $this->client->request(
            $this->endpoint,
            'BOGUS',
            array(
                'From' => '',
                'To' => '',
                'Body' => 'Omg, touche mackice.'
            )
        );
    }
}
