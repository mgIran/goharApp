<?php
class AdminsLogin extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;
    public $verifyCode;

	private $_identity;

	public function rules()
	{
        return array(
            // username and password are required
            array('username, password', 'required'),
            array('verifyCode', 'required','on'=>'login'),
            // rememberMe needs to be a boolean
            array('rememberMe', 'boolean'),
            // password needs to be authenticated
            array('password', 'authenticate'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(),'on'=>'login')
        );
	}

	public function attributeLabels()
	{
		return array(
            'rememberMe'=>'مرا بخاطر بسپار',
            'username'=>'پست الکترونیکی',
            'password'=>'رمز عبور',
            'verifyCode' => 'کد امنیتی',
		);
	}

	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new ModulesAdminIdentity($this->username,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','نام کاربری یا کلمه عبور اشتباه است.');
		}
	}

	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new ModulesAdminIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===ModulesAdminIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
