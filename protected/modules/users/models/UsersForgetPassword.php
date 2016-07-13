<?php

/**
 * UsersLogin class.
 * UsersLogin is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class UsersForgetPassword extends CFormModel
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
			array('email','isRegistereduser'),
		);
	}
	public function isRegistereduser()
    {
		$user=Users::model()->find("`email`=:email AND status = 1 AND deleted = 0",array(":email"=>$this->email));
        if(!$user)
		{
			$this->addError('email','کاربری با این ایمیل وجود ندارد !');
            return false;
		}
		else
		{
			return $this->hasToken($user['id']);
		}
    }
	public function hasToken($userId)
    {		
		$parameters = array(":userId"=>$userId,":options"=>'forget_token');
		$check=UsersOptions::model()->findAll("`user_id`=:userId AND `options`=:options",$parameters);
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
