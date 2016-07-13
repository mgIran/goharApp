<?php
/**
 * Created by PhpStorm.
 * User: rezaee
 * Date: 3/9/15
 * Time: 7:17 PM
 */

class iWebCaptchaAction extends CCaptchaAction {
    public $type = 'all';
    protected function generateVerifyCode() {
        if($this->type=='all')
            return parent::generateVerifyCode();
        elseif($this->type=='number')
        {
            $start = "1";
            for($i=0;$i<($this->minLength - 1);$i++)
                $start .= "0";

            $end = "";
            for($i=0;$i<$this->maxLength;$i++)
                $end .= "9";
            return (string)rand(intval($start),intval($end));
        }
    }

} 