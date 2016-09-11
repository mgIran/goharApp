<?php

class ModulesAdminIdentity extends CUserIdentity
{
    const ERROR_TOKEN_INVALID=3;
    const USERNAME_PASSWORD = 'username_password';
    const TOKEN = 'token';

    public $authMode = self::USERNAME_PASSWORD;
    public $appToken = NULL;

    /**
     * Override Constructor.
     * @param string $username username or app token
     * @param string $password password
     */
    public function __construct($username,$password=NULL)
    {
        if(!empty($password))
        {
            parent::__construct($username,$password);
        }else
            $this->appToken = $username;
    }

    public function authenticate()
    {
        if($this->authMode == self::USERNAME_PASSWORD) {
            $admin = Admins::model()->findByAttributes(array('user_name' => $this->username), 'status = 1 AND deleted = 0');
            if (empty($admin))
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            else {
                $bCrypt = new Bcrypt;
                if (!$bCrypt->verify(md5(sha1($this->password)), $admin->password))
                    $this->errorCode = self::ERROR_PASSWORD_INVALID;
                else {
                    $this->setState('userID', $admin->id);
                    $this->setState('fullName', $admin->first_name . ' ' . $admin->last_name);
                    $this->setState('role', $admin->AdminsRoles->title);
                    $this->setState('avatar', $admin->avatar);
                    $this->setState('type', 'admin');
                    if ($admin->AdminsRoles->name = 'admin')
                        $this->username = 'admin';

                    $this->errorCode = self::ERROR_NONE;
                }
            }
        }elseif($this->authMode == self::TOKEN) {
            $admin = Admins::model()->findByAttributes(array('app_token' => $this->appToken), 'status = 1 AND deleted = 0');
            if (empty($admin))
                $this->errorCode = self::ERROR_TOKEN_INVALID;
            else {
                $bCrypt = new Bcrypt;
                if (!$bCrypt->verify(md5(sha1($this->password)), $admin->password))
                    $this->errorCode = self::ERROR_PASSWORD_INVALID;
                else {
                    $this->setState('userID', $admin->id);
                    $this->setState('fullName', $admin->first_name . ' ' . $admin->last_name);
                    $this->setState('role', $admin->AdminsRoles->title);
                    $this->setState('avatar', $admin->avatar);
                    $this->setState('type', 'admin');
                    if ($admin->AdminsRoles->name = 'admin')
                        $this->username = 'admin';

                    $this->errorCode = self::ERROR_NONE;
                }
            }
        }
        return !$this->errorCode;
    }

}