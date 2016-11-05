<?php
class ModulesUserIdentity extends CUserIdentity
{
    const USERNAME_PASSWORD = 'username_password';
    const TOKEN = 'token';
    const ERROR_TOKEN_INVALID=3;
    const ERROR_NOT_VERIFIED = 101;
    const ERROR_INACTIVE = 102;
    const ERROR_DISABLE_LOGIN = 103;

    public $authMode = self::USERNAME_PASSWORD;
    public $appToken = NULL;
    private $_appLoginArray = array();

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

    public function authenticate($withoutCheck=false)
    {
        $loginFlag = false;
        if($this->authMode == self::USERNAME_PASSWORD) {
            $user = Users::model()->findByAttributes(array('user_name' => $this->username), 'deleted = 0');
            if(empty($user))
                $this->errorCode=self::ERROR_PASSWORD_INVALID;
            elseif($user->activePlan->plansBuys->plan->disable_login == 1){
                $this->errorCode = self::ERROR_DISABLE_LOGIN;
                return $this->errorCode;
            }
            elseif($user->status == Users::STATUS_NOT_VERIFIED){
                $this->errorCode = self::ERROR_NOT_VERIFIED;
                return $this->errorCode;
            }
            elseif($user->status == Users::STATUS_INACTIVE){
                $this->errorCode = self::ERROR_INACTIVE;
                return $this->errorCode;
            }
            else
            {
                /* log for last login */
                $log = new UsersLogins;
                $log->user_id = $user->id;
                if(!$withoutCheck){
                    $bCrypt = new Bcrypt;
                    if(!$bCrypt->verify(md5(sha1($this->password)), $user->password)){
                        $this->errorCode=self::ERROR_PASSWORD_INVALID;
                    }
                    else
                        $loginFlag = true;
                }
                else
                    $loginFlag = true;

                if($loginFlag) {
                    $log->status = 1;
                    $this->login($user);
                    $this->errorCode=self::ERROR_NONE;
                }
                $log->save();
                /* log for last login */
            }
        }elseif($this->authMode == self::TOKEN) {
            $user = Users::model()->findByAttributes(array('app_token' => $this->appToken), 'deleted = 0');
            if(empty($user))
                $this->errorCode=self::ERROR_TOKEN_INVALID;
            elseif($user->activePlan->plansBuys->plan->disable_login == 1){
                $this->errorCode = self::ERROR_DISABLE_LOGIN;
                return $this->errorCode;
            }
            elseif($user->status == Users::STATUS_NOT_VERIFIED){
                $this->errorCode = self::ERROR_NOT_VERIFIED;
                return $this->errorCode;
            }
            elseif($user->status == Users::STATUS_INACTIVE){
                $this->errorCode = self::ERROR_INACTIVE;
                return $this->errorCode;
            }
            else
            {
                /* log for last login */
                $log = new UsersLogins;
                $log->user_id = $user->id;
                $loginFlag = true;
                if($loginFlag) {
                    $log->status = 1;
                    $this->login($user);
                    $this->errorCode=self::ERROR_NONE;
                }
                $log->save();
                /* log for last login */
            }
        }

        return !$this->errorCode;
    }

    public function login($user){
        $this->setState('userID', $user->id);
        $this->setState('fullName', $user->first_name. ' '.$user->last_name);
        $this->setState('role', $user->UsersRoles->title);
        if(!@class_exists('PlansBuys'))
            Yii::import('application.modules.plans.models.PlansBuys');
        $planUser = PlansBuys::model()->find(array(
            'with' => 'buy',
            'condition'=>'user_id = :user AND status = :status AND active = 1',
            'params' => array(':user'=>$user->id,':status'=>Buys::STATUS_DONE),
        ));
        if(!@class_exists('Plans'))
            Yii::import('application.modules.plans.models.Plans');

        $plan = $planUser->plan;
        $planArray = array(
            'id' => $plan->id,
            'date' => $planUser->buy->date,
            'name' => $plan->name,
            'expire_date' => $planUser->expire_date,
        );
        $this->setState('plan', json_encode($planArray));
        $this->setState('avatar', $user->avatar);
        $this->setState('type', 'user');

        $this->_appLoginArray = array(
            'type'=> 'user',
            'userID' => $user->id,
            'fullName' => $user->first_name. ' '.$user->last_name,
            'role'=> $user->UsersRoles->title,
            'plan'=> json_encode($planArray),
            'avatar'=> $user->avatar
        );

        $user->userInfoStatus();
    }

    public function getErrorMessage()
    {
        switch ($this->errorCode)
        {
            case self::ERROR_USERNAME_INVALID:
            case self::ERROR_PASSWORD_INVALID:
                $text = 'نام کاربری یا کلمه عبور اشتباه است.';
                break;
            case self::ERROR_TOKEN_INVALID:
                $text = 'Token is invalid.';
                break;
            case self::ERROR_DISABLE_LOGIN:
                $text = 'ورود به سیستم غیرفعال است.';
                break;
            case self::ERROR_INACTIVE:
                $text = 'کاربر موردنظر غیرفعال است.';
                break;
            case self::ERROR_NOT_VERIFIED:
                $text = 'کاربر موردنظر احراز هویت نشده است.';
                break;
            case self::ERROR_UNKNOWN_IDENTITY:
                $text = 'Unknown Identity.';
                break;
            default:
                $text = '';
                break;
        }
        return $text;
    }

    public function getAppLoginArray()
    {
        return $this->_appLoginArray;
    }
}