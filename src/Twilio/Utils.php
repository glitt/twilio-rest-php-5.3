<?php

namespace Twilio;

class Utils
{
    protected $AccountSid;
    protected $AuthToken;

    public function __construct($id, $token)
    {
        $this->AuthToken = $token;
        $this->AccountSid = $id;
    }

    public function validateRequest($expected_signature, $url, $data = array())
    {
        // sort the array by keys
        ksort($data);

        // append them to the data string in order
        // with no delimiters
        foreach($data AS $key=>$value){$url .= "$key$value";}

        // This function calculates the HMAC hash of the data with the key
        // passed in
        // Note: hash_hmac requires PHP 5 >= 5.1.2 or PECL hash:1.1-1.5
        // Or http://pear.php.net/package/Crypt_HMAC/
        $calculated_signature = base64_encode(hash_hmac("sha1",$url, $this->AuthToken, true));

        return $calculated_signature == $expected_signature;
    }
}
