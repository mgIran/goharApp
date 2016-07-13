<?php
class ModulesUserIdentity extends CUserIdentity
{
    const ERROR_NOT_VERIFIED = 101;
    const ERROR_INACTIVE = 102;
    const ERROR_DISABLE_LOGIN = 103;

    public function authenticate($withoutCheck=false)
    {
        $loginFlag = false;
        $user = Users::model()->findByAttributes(array('user_name'=>$this->username),'deleted = 0');
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

        $user->userInfoStatus();
    }

}