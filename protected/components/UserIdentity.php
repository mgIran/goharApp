<?php
class UserIdentity extends CUserIdentity
{
	public function authenticate()
	{
        $admin = Admins::model()->findByAttributes(array('user_name'=>$this->username),'status = 1 AND deleted = 0');
        if(empty($admin))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $bCrypt = new Bcrypt;
            if(!$bCrypt->verify(md5(sha1($this->password)), $admin->password))
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            else
            {
                $this->setState('userID', $admin->id);
                $this->errorCode=self::ERROR_NONE;
            }
        }
        return !$this->errorCode;
	}
}