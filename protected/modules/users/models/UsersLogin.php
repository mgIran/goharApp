<?php
class UsersLogin extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;
    public $verifyCode;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
            array('verifyCode', 'required','on'=>'login'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
            array('username', 'CEmailValidator'),
			// password needs to be authenticated
			array('password', 'authenticate'),
            array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements(),'on'=>'login')
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'مرا بخاطر بسپار',
            'username'=>'پست الکترونیکی',
            'password'=>'رمز عبور',
            'verifyCode' => 'کد امنیتی',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new ModulesUserIdentity($this->username,$this->password);
            if($this->_identity->authenticate() === ModulesUserIdentity::ERROR_NOT_VERIFIED)
                $this->addError('username','این ایمیل هنوز از طرف شما تائید اعتبار نشده است.');
            elseif($this->_identity->authenticate() === ModulesUserIdentity::ERROR_INACTIVE)
                $this->addError('username', 'حساب کاربری شما غیر فعال شده است.');
            elseif($this->_identity->authenticate() === ModulesUserIdentity::ERROR_DISABLE_LOGIN)
                $this->addError('username', 'پلن شما غیر فعال میباشد.');
            elseif(!$this->_identity->authenticate())
                $this->addError('password','نام کاربری یا کلمه عبور اشتباه است.');

		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login($withoutCheck=false)
	{

		if($this->_identity===null)
		{
			$this->_identity=new ModulesUserIdentity($this->username,$this->password);
			$this->_identity->authenticate($withoutCheck);
		}
		if($this->_identity->errorCode===ModulesUserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
}
