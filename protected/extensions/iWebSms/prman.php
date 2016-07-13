<?php
require_once("iWebSms.php");

class prman extends iWebSms {
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
        if(is_string($recipients))
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

        $recipientText = "";

        eval('$recipientText = $this->make'.ucfirst($type).'Recipients($recipients,$message,$originator);');
        $request =
            '<xmsrequest>
                <userid>'.$this->userId.'</userid>
                <password>'.$this->password.'</password>
                <action>smssend</action>
                <body>
                  <type>'.$type.'</type>'
                .$recipientText.
                '</body>
            </xmsrequest>';
        $client = null;
        $result = $this->execute($request,$client);

        if ($client->fault)
            return array(
                'status' => 'fault',
                'result' =>$result
            );
        else
        {
            // Check for errors
            $err = $client->getError();
            if ($err)
                return array(
                    'status' => 'error',
                    'message' => $err
                );
            else
                return array(
                    'status' => 'success',
                    'result' => $result
                );
        }
        /*echo "<br/>";
        echo '<h2>Request</h2><pre>'.htmlspecialchars($client->request, ENT_QUOTES).'</pre>';
        echo '<h2>Response</h2><pre>'.htmlspecialchars($client->response, ENT_QUOTES).'</pre>';
        echo '<h2>Debug</h2><pre>'.htmlspecialchars($client->debug_str, ENT_QUOTES).'</pre>';
        echo "\n";*/
    }

    public function getBank($id=0){
        $request = '
        <xmsrequest>
            <userid>'.$this->userId.'</userid>
            <password>'.$this->password.'</password>
            <action>treenodes</action>
            <body>
                <node id="'.$id.'"/>
            </body>
        </xmsrequest>';
        $result = $this->execute($request);

        return $result["XmsRequestResult"];
    }

    private function fetchCount($xml){
        $xmlDoc = new DOMDocument("1.0", "UTF-8" );
        $xmlDoc->loadXML($xml);
        var_dump($xmlDoc);exit;
        $node = $xmlDoc->getElementsByTagName('count');
        return $node->item(0)->nodeValue;
    }

    public function nodeCount($id,$startAge = "",$endAge = "",$gender = "",$type = "",$preNumber = ""){
        if(!empty($startAge))
            $startAge = "<sa>$startAge</sa>";
        if(!empty($endAge))
            $endAge = "<ea>$endAge</ea>";
        if(!empty($gender))
            $gender = "<g>$gender</g>";
        if(!empty($type))
            $type = "<t>$type</t>";
        if(!empty($preNumber))
            $preNumber = "<p>$preNumber</p>";
        $request = "
        <xmsrequest>
            <userid>$this->userId</userid>
            <password>$this->password</password>
            <action>nodecount</action>
            <body>
                <r id=\"zone\">
                    <n>$id</n>
                    $startAge
                    $endAge
                    $gender
                    $type
                    $preNumber
                </r>
            </body>
        </xmsrequest>
        ";
        $result = $this->execute($request);

        return $this->fetchCount($result["XmsRequestResult"]);
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

    private function execute($request,&$client = null){
        $client = new nusoap_client($this->url, 'wsdl');
        $client->soap_defencoding = 'UTF-8';
        $client->decode_utf8 = false;
        $param = array('requestData'=>$request);
        return $client->call('XmsRequest', $param);
    }

    public function fetchXML($xml,$type='bank',$fetchAssoc = false){
        $xmlDoc = new DOMDocument("1.0", "UTF-8");
        $xmlDoc->loadXML($xml);
        switch($type){
            case 'bank':
                return $this->returnBankArray($xmlDoc,$fetchAssoc);
                break;
            case 'sms':
                return $this->returnSmsArray($xmlDoc);
                break;
        }
    }

    private function returnBankArray($xmlDoc,$fetchAssoc){
        $node = $xmlDoc->getElementsByTagName('node');
        $returnArray = array();
        foreach ($node as $childNode)
        {
            $name = $childNode->getElementsByTagName('name')->item(0);
            $id = $childNode->getElementsByTagName('id')->item(0);
            if($fetchAssoc)
                $returnArray[] = array(
                    'id' => $id->nodeValue,
                    'name' => $name->nodeValue
                );
            else
                $returnArray[$id->nodeValue] =  $name->nodeValue;

        }
        return $returnArray;
    }

    private function returnSmsArray($xmlDoc){
        $node = $xmlDoc->getElementsByTagName('message');
        $returnArray = array();
        foreach ($node as $childNode) {
            $from = $childNode->getAttribute('from');
            $to = $childNode->getAttribute('id');
            $date = $childNode->getAttribute('date');
            $id = $childNode->getAttribute('id');
            $text = $childNode->nodeValue;

            $returnArray[] = array(
                'sender' => $from,
                'to' => $to,
                'date' => $date,
                'body' => $text,
                'lastId' => $id,
            );

        }
        return $returnArray;
    }

    public function getSms($lastSmsId=NULL,$count=100){
        if(!is_null($lastSmsId))
            $lastSmsId = '<lastsmsid>'.$lastSmsId.'</lastsmsid>';
        else
            $lastSmsId = '<lastsmsid>0</lastsmsid>';
        if(!is_null($count))
            $count = '<count>'.$count.'</count>';
        $request = '
        <xmsrequest>
            <userid>'.$this->userId.'</userid>
            <password>'.$this->password.'</password>
            <action>smsreceive</action>
            <body>
                '.$lastSmsId.$count.'
            </body>
        </xmsrequest>';
        $result = $this->execute($request);

        return $result["XmsRequestResult"];
    }

    public function analysisResponse($xml, $tagName)
    {
        $xmlDoc = new DOMDocument("1.0", "UTF-8");
        $xmlDoc->loadXML($xml);
        $tags = $xmlDoc->getElementsByTagName($tagName);
        $returnArray = array();
        foreach($tags as $tag)
        {
            $temp = new stdClass();
            $temp->value=$tag->nodeValue;
            $temp->attributes=new stdClass();
            foreach($tag->attributes as $attr)
            {
                $attrName=$attr->name;
                $temp->attributes->$attrName=$attr->value;
            }
            $returnArray[] = $temp;
        }
        return $returnArray;
    }

    public function getSmsStatus($smsID)
    {
        $request =
            '<xmsrequest>
                <userid>'.$this->userId.'</userid>
                <password>'.$this->password.'</password>
                <action>smsstatus</action>
                <body>
                  <message>'.$smsID.'</message>
                </body>
            </xmsrequest>';
        $client = null;
        $result = $this->execute($request,$client);

        if ($client->fault)
            return array(
                'status' => 'fault',
                'result' =>$result
            );
        else
        {
            // Check for errors
            $err = $client->getError();
            if ($err)
                return array(
                    'status' => 'error',
                    'message' => $err
                );
            else
                return array(
                    'status' => 'success',
                    'result' => $result
                );
        }
    }

    public function getStatusMessage($statusCode)
    {
        $messages = array(
            '40' => 'پیام شما منتظر ارسال می باشد',
            '41' => 'پیام شما ارسال شده است',
            '42' => 'پیام شما بعد از چند بار تلاش ارسال نشده است',
            '43' => 'پیام شما منقضی شده است',
            '44' => 'پیام شما در حال ارسال است',
            '45' => 'ارسال پیام شما کنسل شده است',
            '46' => 'شماره شما در لیست سیاه است',
            '47' => 'ارسال پیام شما با خطا مواجه شده است؛ سامانه چند دقیقه دیگر برای ارسال تلاش خواهد کرد',
            '48' => 'پیام شما تحویل گوشی شده است',
            '49' => 'پیام شما در انتظار تحویل به گوشی می باشد',
            '52' => 'پیام شما حذف شده است',
            '53' => 'پیام شما منتظر تایید می باشد',
            '55' => 'اعتبار شما برای ارسال این پیام کافی نیست',
            '58' => 'سامانه در حال آماده سازی پیام های شما می باشد',
            '57' => 'پیام های شما برای ارسال آماده شده است',
            '6900' => 'شما دسترسی انجام این کار را ندارید',
            '6906' => 'برای انجام این عملیات اعتبار شما کافی نیست',
            '6908' => 'متن پیام شما خالی است',
            '6950' => 'XML داده شده نامعتبر است',
            '6951' => 'نام کاربری یا رمز عبور اشتباه است',
            '6953' => 'متد استفاده شده نامعتبر است',
            '6955' => 'فرستنده نامعتبر است',
            '6954' => 'موبایل نامعتبر است',
            '6956' => 'هیچ گیرنده ای مشخص نشده است',
        );
        $needToFollow = array(
            '40' => true,
            '41' => false,
            '42' => false,
            '43' => false,
            '44' => true,
            '45' => false,
            '46' => false,
            '47' => true,
            '48' => false,
            '49' => true,
            '52' => false,
            '53' => true,
            '55' => false,
            '58' => true,
            '57' => true,
            '6900' => false,
            '6906' => false,
            '6908' => false,
            '6950' => false,
            '6951' => false,
            '6953' => false,
            '6955' => false,
            '6954' => false,
            '6956' => false,
        );

        return array(
            'message' => $messages[$statusCode],
            'needToFollow' => $needToFollow[$statusCode],
        );
    }
} 