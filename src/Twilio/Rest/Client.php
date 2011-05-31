<?php

namespace Twilio\Rest;

class Client
{
    protected $Endpoint;
    protected $AccountSid;
    protected $AuthToken;

    /*
     * __construct
     *   $username : Your AccountSid
     *   $password : Your account's AuthToken
     *   $endpoint : The Twilio REST Service URL, currently defaults to
     * the proper URL
     */
    public function __construct($accountSid, $authToken, $endpoint = "https://api.twilio.com")
    {

            $this->AccountSid = $accountSid;
            $this->AuthToken = $authToken;
            $this->Endpoint = $endpoint;
    }

    /*
     * sendRequst
     *   Sends a REST Request to the Twilio REST API
     *   $path : the URL (relative to the endpoint URL, after the /v1)
     *   $method : the HTTP method to use, defaults to GET
     *   $vars : for POST or PUT, a key/value associative array of data to
     * send, for GET will be appended to the URL as query params
     */
    public function request($path, $method = "GET", $vars = array())
    {
        $fp = null;
        $tmpfile = "";
        $encoded = "";
        foreach($vars AS $key=>$value)
            $encoded .= "$key=".urlencode($value)."&";
        $encoded = substr($encoded, 0, -1);

        // construct full url
        $url = "{$this->Endpoint}/$path";

        // if GET and vars, append them
        if($method == "GET"){
            $url .= (FALSE === strpos($path, '?')?"?":"&").$encoded;
        }

        // initialize a new curl object
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        switch(strtoupper($method)){
            case "GET":
                curl_setopt($curl, CURLOPT_HTTPGET, TRUE);
                break;
            case "POST":
                curl_setopt($curl, CURLOPT_POST, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded);
                break;
            case "PUT":
                // curl_setopt($curl, CURLOPT_PUT, TRUE);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $encoded);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                file_put_contents($tmpfile = tempnam("/tmp", "put_"), $encoded);
                curl_setopt($curl, CURLOPT_INFILE, $fp = fopen($tmpfile, 'r'));
                curl_setopt($curl, CURLOPT_INFILESIZE, filesize($tmpfile));
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                throw new \Twilio\Exception("Unknown method $method");
                break;
        }

        // send credentials
        curl_setopt($curl, CURLOPT_USERPWD, $pwd = "{$this->AccountSid}:{$this->AuthToken}");

        // do the request. If FALSE, then an exception occurred
        if(FALSE === ($result = curl_exec($curl))){
            throw new \Twilio\Exception("Curl failed with error " . curl_error($curl));
        }

        // get result code
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // unlink tmpfiles
        if($fp){fclose($fp);}

        if(strlen($tmpfile)){unlink($tmpfile);}

        return new Response($url, $result, $responseCode);
    }
}
