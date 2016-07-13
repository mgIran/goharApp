<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class AdminsForgetPassword extends CFormModel
{
	public $email;

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
			array('email', 'required'),
			array('email', 'CEmailValidator'),
			array('email','isRegisteredAdmin'),
		);
	}
	public function isRegisteredAdmin()
    {
		$admin=Admins::model()->find("`email`=:email",array(":email"=>$this->email));
        if(!$admin)
		{
			$this->addError('email','مدیری با این ایمیل وجود ندارد !');
            return false;
		}
		else
		{
			return $this->hasToken($admin['id']);
		}
    }
	public function hasToken($adminId)
    {		
		$parameters = array(":adminId"=>$adminId,":options"=>'forget_token');
		$check=AdminsOptions::model()->findAll("`admin_id`=:adminId AND `options`=:options",$parameters);
        if($check)
		{
			$this->addError('email','درخواست شما قبلا ثبت شده است !');
			return false;
		}
		else
		{
			return true;
		}
    }
	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'email'=>'ایمیل',
		);
	}
}
