<?php
class UsersAccountController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'حساب کاربری',
            'index' => array(
                'title' => 'داشبورد',
                'type' => 'user'
            ),
            'register' => array(
                'title' => 'فرم ثبت نام',
                'type' => 'user'
            ),
            'setting' => array(
                'title' => 'تنظیمات حساب کاربری',
                'type' => 'user',
            ),
            'logout' => array(
                'title' => 'خروج',
                'type' => 'all'
            ),
            'activate'=>array(
                'title' => 'فعال سازی حساب',
                'type' => 'all'
            ),
            'captcha' => array(
                'type' => 'all',
                'otherActions' => 'upload,delete,uploadOther,deleteOther,changeTitleOther'
            ),
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
            'captcha' => array(
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
			'page' => array(
				'class'=>'CViewAction',
			),
		);
	}

	public function actionIndex(){
        $criteria = new CDbCriteria;
        $criteria->compare('user_id',Yii::app()->user->userID);
        $criteria->limit = 5;
        $criteria->order = 'id DESC';
        $lastLogin = UsersLogins::model()->findAll($criteria);
		$this->render('index',array(
            'lastLogin' => $lastLogin
        ));
	}

	public function actionLogout(){
		Yii::app()->user->logout();
		$this->redirect(array('login'));
	}

    public function actionRegister($agentId=NULL){
        if(!is_null($agentId)){
            if ( base64_encode(base64_decode($agentId, true)) === $agentId){
                $agentId = base64_decode($agentId);
                $userExist = Users::model()->findByPk($agentId,'status = 1 AND deleted = 0');

                if(!is_null($userExist))
                    setcookie("agentId",$agentId,strtotime("30 minutes"),"/");
            }
            $this->redirect(Yii::app()->homeUrl);
        }
        if(isset($_COOKIE['agentId'])){
            $userExist = Users::model()->findByPk($_COOKIE['agentId'],'status = 1 AND deleted = 0');
            if(!is_null($userExist))
                $agentId = $userExist->id;
        }
        $model = new Users;
        $registerFlag = 0;
        $model->scenario = 'register';
        //$this->performAjaxValidation($model);

        if(isset($_POST['Users'])){

            $model->attributes = $_POST['Users'];

            $model->user_name = $model->email;
            $model->role_id = $model->getDefaultRoleId();
            $model->agent_id = $agentId;
            if(!is_null($this->getModule()->verification)){
                $model->status = 0;
            }
            if($model->save()) {
                $buy = new Buys;
                $buy->scenario = 'user_register';
                $buy->user_id = $model->id;
                $buy->status = Buys::STATUS_DONE;
                $buy->type = Buys::TYPE_PLAN;
                if ($buy->save()) {
                    $registerFlag = 1;
                    Yii::import('application.modules.plans.models.*');
                    $selectFreePlan = new PlansBuys;
                    $selectFreePlan->attributes = array(
                        'buy_id' => $buy->id,
                        'plan_id' => 3,
                        'active' => 1,
                    );
                    if ($selectFreePlan->save()){
                        $registerFlag = 2;
                    }

                    if ($registerFlag == 2) {
                        unset($_COOKIE['agentId']);
                        if (is_null($this->getModule()->verification)) {

                            $loginModel = new UsersLogin;
                            $loginModel->scenario = 'register';
                            $loginModel->username = $model->user_name;
                            $loginModel->password = $_POST['Users']['password'];

                            if ($loginModel->validate() && $loginModel->login())
                                $this->redirect(Yii::app()->user->loginUrl);

                        } elseif ($this->getModule()->verification == 'email') {
                            $this->sendVerificationEmail($model);
                        }
                    } else {
                        $model = Users::model()->findByPk($model->id);
                        $model->delete();
                        Yii::app()->user->setFlash('danger', 'خطا در هنگام ثبت!');
                    }
                }

            }
        }
        $page = Pages::model()->findByPk(5);
        $this->render('register',array(
            'model'=>$model,
            'page' => $page
        ));
    }

    private function sendVerificationEmail($model){
        $emailSend = false;
        $email = $model->email;
        $sender = "noreply@gohar.org";

        $hash = UsersOptions::model()->generateActivateCode($model->id);

        if($hash){

            $text = CHtml::link("لینک فعال سازی",Yii::app()->createAbsoluteUrl("users/account/activate/".$model->id."?activate_code=".$hash));
            $body="
                        <html>
                        <head>
                        <title>
                        گهر میل - ایمیل فعال سازی
                        </title>
                        </head>
                        <body>
                            <div style='padding:15px;'>
                            <h4>گهر میل - ایمیل فعال سازی</h4>
                            $text
                            <hr>
                            </div>
                        </body>
                        </html>
                        ";

            $mailer = Yii::createComponent('application.extensions.mailer.EMailer');
            $mailer->IsHTML();
            $mailer->From = $sender;
            $mailer->AddReplyTo($sender);
            $mailer->AddBCC($email);

            $mailer->FromName = 'گهر میل';
            $mailer->CharSet = 'UTF-8';
            $mailer->Subject = 'گهر میل - ایمیل فعال سازی';
            $mailer->Body = $body;
            if($mailer->Send())
                $emailSend = true;
        }
        if($emailSend)
            Yii::app()->user->setFlash('success','success');
        else
            Yii::app()->user->setFlash('danger','failed');

        $this->redirect(Yii::app()->createAbsoluteUrl('users/account/activate/'.$model->id));

    }

    public function actionActivate($id,$activate_code=NULL,$resend=false){
        if(!is_null($activate_code)){
            $user = UsersOptions::model()->findByAttributes(array('user_id'=>$id,'options'=>'activate_code','value'=>$activate_code));
            if(!is_null($user)){
                $model = Users::model()->findByPk($id);
                $model->scenario = 'activate';
                $model->status = 1;
                $model->save();

                $loginModel=new UsersLogin;
                $loginModel->scenario = 'register';
                $loginModel->username = $model->user_name;
                if($loginModel->login(true))
                {
                    $user->delete();
                    $this->redirect(Yii::app()->user->loginUrl);
                }
                else
                    $this->redirect(Yii::app()->createAbsoluteUrl('index'));
            }
            else
                throw new CHttpException(404,'The requested page does not exist.');
        }
        else{
            $model = Users::model()->findByPk($id);
            if($model->status == 1)
                $this->redirect(Yii::app()->createAbsoluteUrl(''));
            if($resend){
                $this->sendVerificationEmail($model);
            }
            $this->render('activate');
        }
    }

    public function actionSetting(){
        $this->beginClip('pageCaption');
        $this->widget('Caption',array(
            'icon'=>'white-user',
            'title'=>'تنظیمات کاربری'
        ));
        $this->endClip();

        $model=$this->loadModel(Yii::app()->user->userID);
        $model->scenario = 'changePassword';

        if(isset($_POST['Users']) && isset($_POST['ajax']) && $_POST['ajax']==='users-form')
        {
            $model->scenario='changePasswordValidation';
            $model->birth_city_id=$_POST['Users']['birth_city_id'];
            $model->home_city_id=$_POST['Users']['home_city_id'];
            $model->work_city_id=$_POST['Users']['work_city_id'];
        }
        $this->performAjaxValidation($model);

        if(isset($_POST['Users']))
        {
            $model->attributes=$_POST['Users'];
            if($_POST['Users']['passwordSet']==='0')
            {
                unset($model->password);
            }
            if($model->save())
            {
                $model->userInfoStatus(FALSE,TRUE);
                Yii::app()->user->setState('avatar', $model->avatar);
                Yii::app()->user->setFlash('success',"تنظیمات اعمال شدند");
                $this->refresh();
            }
            else
                Yii::app()->user->setFlash('danger','خطا در هنگام ویرایش!');
        }

        $this->render('setting',array(
            'model'=>$model,
        ));
    }

    public function actionUpload($type){
        if(isset(Yii::app()->user->userID)){
            if(Yii::app()->user->type == 'user')
                $userId = Yii::app()->user->userID;
            elseif(Yii::app()->user->type == 'admin')
                $userId = $_GET['user_id'];
            $file = $_FILES['userFile'];
            $filePath = 'upload/users/legal_documents/'.$userId.'/';
            $uploadDirectory = Yii::getPathOfAlias('webroot').'/'.$filePath;

            $imageInfo = pathinfo($file['name']);

            $fileName = time().".".$imageInfo['extension'];

            if(array_key_exists('userFile',$_FILES) && $_FILES['userFile']['error'] == 0 ){
                $model = Users::model()->findByPk($userId);
                $model->$type = $fileName;
                $model->scenario = 'upload';
                iWebHelper::makePath($uploadDirectory);

                if(move_uploaded_file($file['tmp_name'], $uploadDirectory.$fileName)){
                    // Upload image
                    if($model->save()){
                        echo json_encode(array(
                            'result'=>true,
                            'fileName'=>$fileName,
                            'ID'=>'0' // ID of image record in database
                        ));
                        Yii::app()->end();
                    }
                    else{
                        @unlink($uploadDirectory.$fileName);
                    }
                }

            }

            echo json_encode(array(
                'result'=>false,
                'message'=>'خطایی در آپلود فایل شما وجود دارد!'
            ));
            Yii::app()->end();
        }
    }

    public function actionDelete($type){
        if(isset(Yii::app()->user->userID)){
            if(Yii::app()->user->type == 'user')
                $userId = Yii::app()->user->userID;
            elseif(Yii::app()->user->type == 'admin')
                $userId = $_GET['user_id'];
            // load user
            $model = Users::model()->findByPk($userId);

            //delete file
            $file = $model->$type;
            $filePath = 'upload/users/legal_documents/'.$userId.'/';
            $uploadDirectory = Yii::getPathOfAlias('webroot').'/'.$filePath;
            @unlink($uploadDirectory.$file);

            // clear
            $model->$type = null;
            $model->scenario = 'changeValue';
            if($model->save()){

                echo json_encode(
                    array(
                        'result'=>true
                    )
                );
            }
        }
    }

    public function actionUploadOther(){
        if(isset(Yii::app()->user->userID)){
            if(Yii::app()->user->type == 'user')
                $userId = Yii::app()->user->userID;
            elseif(Yii::app()->user->type == 'admin')
                $userId = $_GET['user_id'];
            $filePath = 'upload/users/legal_documents/'.$userId.'/other/';
            $uploadDirectory = Yii::getPathOfAlias('webroot').'/'.$filePath;
            $file = $_FILES['userFile'];
            $fileName = $file['name'];

            if(array_key_exists('userFile',$_FILES) && $_FILES['userFile']['error'] == 0 ){
                // Check availability file
                if(file_exists($uploadDirectory.$fileName))
                {
                    echo json_encode(array(
                        'result'=>false,
                        'message'=>'فایل انتخاب شده تکراری می باشد.'
                    ));
                    Yii::app()->end();
                }

                // Save file data
                $model = new UsersOtherLegalDocuments;
                $model->user_id = $userId;
                $model->scenario = 'insert';
                $model->attributes = array(
                    'file'=>$fileName,
                );
                $save = $model->save();
                if($save){
                    if($save === 5){
                        echo json_encode(array(
                            'result'=>false,
                            'message'=>'شما اجازه آپلود بیش از 5 فایل را ندارید.'
                        ));
                        Yii::app()->end();
                    }
                    iWebHelper::makePath($uploadDirectory);
                    // Upload file
                    move_uploaded_file($file['tmp_name'], $uploadDirectory.$fileName);

                    echo json_encode(array(
                        'result'=>true,
                        'fileName'=>$fileName,
                        'ID'=>$model->id
                    ));
                    Yii::app()->end();
                }
            }

            echo json_encode(array(
                'result'=>false,
                'message'=>'خطایی در آپلود فایل شما وجود دارد!'
            ));
            Yii::app()->end();
        }
    }


    public function actionDeleteOther()
    {
        if(isset(Yii::app()->user->userID)){
            if(Yii::app()->user->type == 'user')
                $userId = Yii::app()->user->userID;
            elseif(Yii::app()->user->type == 'admin')
                $userId = $_GET['user_id'];
            $filePath = 'upload/users/legal_documents/'.$userId.'/other/';
            $uploadDirectory = Yii::getPathOfAlias('webroot').'/'.$filePath;

            $imageID = $_POST['iwiuImageID'];

            $model = new UsersOtherLegalDocuments;
            $model->user_id = $userId;
            $model = $model->find($imageID);

            if(!is_null($model) && $model->delete())
            {
                // Delete image
                if(file_exists($uploadDirectory.$model['file']))
                    @unlink($uploadDirectory.$model['file']);

                echo json_encode(
                    array(
                        'result'=>true
                    )
                );
            }
            else
                echo json_encode(
                    array(
                        'result'=>false,
                        'message'=>'در انجام عملیات خطایی رخ داده است!'
                    )
                );
        }
    }


    public function actionChangeTitleOther(){
        if(isset(Yii::app()->user->userID)){
            if(Yii::app()->user->type == 'user')
                $userId = Yii::app()->user->userID;
            elseif(Yii::app()->user->type == 'admin')
                $userId = $_GET['user_id'];
            $imageNewName = $_POST['iwiuNewTitle'];
            $imageID = $_POST['iwiuImageID'];

            $model = new UsersOtherLegalDocuments;
            $model->user_id = $userId;
            $model->id = $imageID;
            $model->title = $imageNewName;

            if($model->save())
                echo json_encode(
                    array(
                        'result' => true
                    )
                );
            else
                echo json_encode(
                    array(
                        'result' => false,
                        'message' => 'در انجام عملیات خطایی رخ داده است!'
                    )
                );
        }
    }

    public function loadModel($id){
        $model=Users::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}