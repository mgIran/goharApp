<?php

class ContactForm extends CFormModel
{
	public $name,$tel,$email,$text,$verifyCode;

	public function rules()
	{
		return array(
			array('name, tel, text, email,verifyCode', 'required'),
            array('email','email'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements()),
		);
	}

	public function attributeLabels()
	{
		return array(
            'name'=>'نام و نام خانوادگی',
            'tel'=>'شماره تماس',
            'email'=>'ایمیل',
            'verifyCode'=>'کد امنیتی',
            'text'=>'متن پیام'
		);
	}
}
