<?php

class iWebSms extends CComponent {
    public $url,$userId,$password;
    public function init(){
        if(is_null($this->url))
            throw new Exception("url must set");
        if(is_null($this->userId))
            throw new Exception("userId must set");
        if(is_null($this->password))
            throw new Exception("password must set");
    }
    public function __construct($url = NULL,$userId = NULL,$password = NULL){
        $this->url = $url;
        $this->userId = $userId;
        $this->password = $password;

    }

    public function send($message,$recipients=array(),$originator="",$type = 'auto'){
        if(is_string($recipients) AND strpos($recipients,","))
            $recipients = explode(",",$recipients);
        elseif(!is_array($recipients) OR empty($recipients))
            throw new Exception("recipients must be array of numbers or a string of numbers with ',' separator");
        if($type == 'auto'){
            $countOfRecipients = count($recipients);
            if($countOfRecipients >= 100 AND $countOfRecipients <= 1000)
                $type = 'ots';
            elseif($countOfRecipients > 1000)
                $type = 'otm';
            else
                $type = 'oto';
        }

        $recipientText = eval('$this->make'.ucfirst($type).'Recipients($recipients,$message,$originator)');

        $client = new nusoap_client($this->url, 'wsdl');
        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = true;
        $param = array('requestData'=>
            '<xmsrequest>
                <userid>'.$this->userId.'</userid>
                <password>'.$this->password.'</password>
                <action>smssend</action>
                <body>
                    <type>'.$type.'</type>'
                    .$recipientText.
                '</body>
            </xmsrequest>');
        $result = $client->call('XmsRequest', $param);
        echo $result["XmsRequestResult"];

        if ($client->fault) {
            echo '<h2>Fault</h2><pre>';
            print_r($result);
            echo '</pre>';
        } else {
            // Check for errors
            $err = $client->getError();
            if ($err) {
                // Display the error
                echo '<h2>Error</h2><pre>' . $err . '</pre>';
            } else {
                // Display the result
                echo '<h2>Result</h2><pre>';
                print_r($result);
                echo '</pre>';
            }
        }
        echo '<h2>Request</h2><pre>'.htmlspecialchars($client->request, ENT_QUOTES).'</pre>';
        echo '<h2>Response</h2><pre>'.htmlspecialchars($client->response, ENT_QUOTES).'</pre>';
        echo '<h2>Debug</h2><pre>'.htmlspecialchars($client->debug_str, ENT_QUOTES).'</pre>';
        echo "\n";

        exit;
    }

    private function makeOtoRecipients($recipients,$message,$originator){
        $recipientText = "";
        foreach($recipients as $key=>$recipient)
            $recipientText .= '<recipient originator="'.$originator.'" doerid="'.$key.'" mobile="'.$recipient.'">'.$message.'</recipient>';
        return $recipientText;
    }

    private function makeOtmRecipients($recipients,$message,$originator){
        $recipientText = '<message originator="'.$originator.'">'.htmlentities($message).'</message>';
        foreach($recipients as $recipient)
            $recipientText .= '<recipient>'.$recipient.'</recipient>';
        return $recipientText;
    }

    private function makeOtsRecipients($recipients,$message,$originator){
        $this->makeOtmRecipients($recipients,$message,$originator);
    }
}