<?php
class UsersLoginController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'کاربران',
            'index' => array(
                'title' => 'صفحه ورود',
                'type' => 'user'
            ),
            'forgetPassword' => array(
                'title' => 'فراموشی رمز عبور',
                'type' => 'user'
            ),
            'recoverPassword' => array(
                'title' => 'بازیابی رمز عبور',
                'type' => 'user'
            ),
            'logout' => array(
                'title' => 'خروج',
                'type' => 'all'
            ),
            'captcha' => array(
                'type' => 'all'
            )
        );


    public function filters()
    {

        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

	public function actions()
	{

		return array(
			'captcha'=>array(
				'class'=>'iWebCaptchaAction',
				'backColor'=>0xFFFFFF,
                'foreColor' => 0x7e55a1,
                'height' => 36,
                'minLength' => 7,
                'maxLength' => 7,
                'padding' => 0,
                'testLimit' => 1,
                'type' => 'number'
			),
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionIndex(){
        $disableLogin = SiteOptions::getOption('disable_login');
        if($disableLogin['status'] == "1")
            throw new CHttpException('ورود کاربران',$disableLogin['message']);


        if(!Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->user->loginUrl);

		$model=new UsersLogin;
        $model->scenario = 'login';

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['UsersLogin']))
		{
			$model->attributes=$_POST['UsersLogin'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->loginUrl);
		}
		// display the login form

        $page = Pages::model()->findByPk(6);
		$this->render('index',array(
            'model'=>$model,
            'page'=>$page
        ));
	}

	public function actionLogout(){
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->baseUrl);
	}
    
    public function actionForgetPassword(){
		$model = new UsersForgetPassword;
        
        if(isset($_POST['UsersForgetPassword']))
		{
			$model->attributes=$_POST['UsersForgetPassword'];
			if($model->isRegistereduser()){
				$user = Users::model()->find("`email`=:email AND status = 1 AND deleted = 0",array(":email"=>$model->email));
				$limit = array("_","__","___",",","-","."," ");
				$time=microtime();
				$seed = str_split('abcdefghijklmnopqrstuvwxyz'.'ABCDEFGHIJKLMNOPQRSTUVWXYZ'); 
				shuffle($seed);
				$rand = '';
				foreach (array_rand($seed, 20) as $k) $rand .= $seed[$k];
				$hash= substr($time,10,4).$rand;
				$hash=str_replace($limit,"",$hash);				
				$parameters = array("user_id"=>$user->id,"options"=>'forget_token',"value"=>$hash);
				$userOptions=new UsersOptions;
				$userOptions->attributes=$parameters;
				if($userOptions->save())
				{
                    /*
                     * email
                     */
                    $message = '<html><body>';
                    $link =CHtml::link("تغییر رمز عبور",Yii::app()->getBaseUrl(true).'/users/login/recoverPassword/?id='.$user['id'].'&token='.$hash,
                        array("style"=>"text-decoration:none;color:#63b7ff;display:block;text-align:right;"));
                    $message.='<div style="font-family:tahoma,arial;font-size:12px;width:600px;background:#F5F5F5;min-height:100px;padding:5px 30px 5px;direction:rtl;line-height:25px;color:#4b4b4b;">';
                    $message.='<h1 style="direction:ltr;" title="'.Yii::app()->createAbsoluteUrl('').'"></h1>';
                    $message.='<span>این ایمیل جهت تغییر رمز عبور ارسال شده است. <br/>*توجه داشته باشید که این آدرس فقط برای تغییر رمز عبور می باشد و یکبار می توانید از آن استفاده نمایید</span>';
                    $message.='<br/><br/>'.$link.'<br/>';
                    $message.="</div>";
                    $message .= "</body></html>";

                    $subject = "فراموشی رمز عبور";

                    $headers = "From: Gohar <noreply@gohar.org>\r\n";
                    $headers .= "Reply-To: noreply@gohar.org\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
					if(mail($model->email,$subject,$message,$headers))
					{
                        Yii::app()->user->setFlash('success','ایمیلی جهت تغییر رمز عبور برای شما ارسال شد.');
                        $this->refresh();
                    }
				}
			}
		}
		$this->render('forgetPassword',array('model'=>$model));
	}

    public function actionRecoverPassword($id=NULL,$token=NULL){
        if(!is_null($id) and !is_null($token))
        {
            $parameters = array(":userid"=>$id,":options"=>'forget_token',":value"=>$token);
            $check = UsersOptions::model()->find("`user_id`=:userid AND `options`=:options AND `value`=:value",$parameters);
        }
        else
            $this->redirect(Yii::app()->homeUrl);
        if(is_null($check))
            $this->redirect(Yii::app()->homeUrl);

        $model = Users::model()->findByPk($id);
        $model->scenario = 'recoverPassword';
        $model->password = "";

        if(isset($_POST['Users']))
        {
            $model->attributes = $_POST['Users'];
            if($model->save())
            {
                Yii::app()->user->setFlash('success',"رمز عبور جدید ثبت شد.");
                if(isset($check) and $check)
                    $check->delete();
                $this->redirect(Yii::app()->homeUrl);
            }
            else
                Yii::app()->user->setFlash('danger','خطایی رخ داده است، لطفا دوباره تلاش کنید.');
        }

        $this->render('recoverPassword',array(
            'model'=>$model,
        ));
    }
}