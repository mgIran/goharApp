<?php
class MessagesEmailsSendController extends Controller{
    public static $actionsArray =
        array(
            'title' => 'ایمیل',
            'menu' => true,
            'menu_name' => 'emails_send',
            'type' => 'user',
            'module' => 'messages,contacts',
            'index' => array(
                'title' => 'صفحه اصلی',
                'type' => 'user',
                //'menu' => true,
                //'menu_parent' => 'emails_send',
                //'menu_name' => 'emails_send_index',
            ),
            'email' => array(
                'title' => 'ارسال ایمیل',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'emails_send',
                'menu_name' => 'emails_send_email',
                'url' => 'messages/emails_send/email',
                'otherActions' => 'upload,getContactsCategories,getContacts,getTemplate'
            ),
            'sent' => array(
                'title' => 'ایمیل های ارسال شده',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'emails_send',
                'menu_name' => 'emails_send_sent',
                'url' => 'messages/emails_send/sent',

            ),
        );

	public $layout='//layouts/main';

    public $default = "email";

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

    public function actionIndex(){
        $model = new MessagesEmailsSend('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesEmailsSend']))
            $model->attributes=$_GET['MessagesEmailsSend'];

        $this->render('index',array(
            'model'=>$model,
        ));
        //$this->actionEmail();
    }

    public function actionSent(){
        $this->actionIndex();
    }

    public function actionEmail($draft=NULL){
        $model = new MessagesEmailsSend;

        $model->send_time = time();
        $model->end_time = strtotime('+1 day');

        if(!is_null($draft) AND !isset($_POST['MessagesEmailsSend'])){
            $draft = MessagesEmailsDrafts::model()->findByPk($draft);
            if(!is_null($draft))
                $model->body = $draft->body;
        }



        $this->performAjaxValidation($model);

        if(isset($_POST['MessagesEmailsSend'])){
            // set contacts
            $contacts = @json_decode($_POST['MessagesEmailsSend']['contacts']);
            if(is_object($contacts) AND isset($contacts->category) AND !empty($contacts->category)){
                $categories = implode(",",$contacts->category);
                Yii::import("application.modules.contacts.models.Contacts");
                $contactsCategories = Contacts::model()->find(array(
                    'select' => "GROUP_CONCAT(`id` SEPARATOR ',') AS id",
                    'condition' => "cat_id IN($categories) AND email IS NOT NULL",
                ));
                if(!is_null($contactsCategories))
                    $contactsCategories = $contactsCategories->id;
            }
            $contactsTemp = "";
            if(is_object($contacts) AND isset($contacts->contact) AND !empty($contacts->contact))
                $contactsTemp = implode(",",$contacts->contact);
            if(isset($contactsCategories) AND !is_null($contactsCategories))
                $contactsTemp .= (($contactsTemp=="")?"":",").$contactsCategories;
            $_POST['MessagesEmailsSend']['contacts'] = $contactsTemp;

            // set bank
            if($_POST['MessagesEmailsSend']['bank']=="")
                $bank = NULL;
            else
                $bank = trim(implode(',',@json_decode($_POST['MessagesEmailsSend']['bank'])));
            $_POST['MessagesEmailsSend']['bank'] = $bank;

            $bankEmails = MessagesEmailsBank::model()->findAll('FIND_IN_SET (cat_id,:categories)',array(':categories'=>$bank));
            $allEmails = array_map(array($this, 'emailsToArray'), $bankEmails);

            $allEmails = array_merge($allEmails,$_POST['MessagesEmailsSend']['to']);
            $_POST['MessagesEmailsSend']['to'] = implode(',',$_POST['MessagesEmailsSend']['to']);
            $model->attributes = $_POST['MessagesEmailsSend'];
            if($model->save())
                Yii::app()->user->setFlash('success','ارسال ایمیل با موفقیت انجام شد.');
                //$this->emailSend($allEmails,$model->title,$model->body,$model->sender,$model);
            else
                Yii::app()->user->setFlash('failed','خطایی در حین ذخیره ایمیل رخ داده است.');

        }
        $emailsBankCategories = MessagesEmailsBankCategories::model()->findAll('parent_id IS NULL');

        $model->send_time = Yii::app()->jdate->date('Y-m-d H:i:s',$model->send_time);
        $model->end_time = Yii::app()->jdate->date('Y-m-d H:i:s',$model->end_time);

        Yii::import("application.modules.contacts.models.ContactsCategories");
        $contactsCategories = ContactsCategories::model()->findAll();
        $this->render('email',array(
            'model' => $model,
            'emailsBankCategories' => $emailsBankCategories ,
            'contactsCategories' => $contactsCategories,
            'templates' => MessagesEmailsTemplates::model()->findAll('status = 1'),
        ));
    }

    public function emailSend($emailList,$title,$text,$sender,$model=NULL){
        $body = "
                    <html>
                    <head>
                    <meta charset='utf-8' />
                    <title>
                    گهر میل - $title
                    </title>
                    </head>
                    <body>
                        $text
                    </body>
                    </html>
                    ";

        $mailer = Yii::createComponent('application.extensions.mailer.EMailer');
        $mailer->IsHTML();
        $mailer->From = $sender;
        $mailer->AddReplyTo($sender);
        foreach($emailList as $item)
            $mailer->AddBCC($item);

        $mailer->FromName = 'گهر میل';
        $mailer->CharSet = 'UTF-8';
        $mailer->Subject = $title;
        $mailer->Body = $body;
        if($mailer->Send()){
            $model->status = 1;
            $model->save();
            Yii::app()->user->setFlash('success','ایمیل با موفقیت ارسال شد.');
            $this->redirect(Yii::app()->createAbsoluteUrl('messages/emails_send/email'));
        }
        else
            Yii::app()->user->setFlash('danger','خطایی در ارسال ایمیل پیش آمد.');
    }

    public function actionUpload(){
        $message = 'خطایی در آپلود فایل شما وجود دارد!';
        $array = array();
        $file = $_FILES['userFile'];

        if(array_key_exists('userFile',$_FILES) && $_FILES['userFile']['error'] == 0 ){
            // Upload image
            $handle = fopen($file['tmp_name'], "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $line = trim($line);
                    if (filter_var($line, FILTER_VALIDATE_EMAIL))
                        $array[] = $line;
                }
            }
            fclose($handle);
            if($array != array())
            {
                echo json_encode(array(
                    'result'=>true,
                    'fileName'=>$array,
                    'ID'=>'0'
                ));
                Yii::app()->end();
            }
        }


        echo json_encode(array(
            'result'=>false,
            'message'=>$message
        ));
        Yii::app()->end();
    }

    public function emailsToArray($object){
        return $object->email;
    }

    public function actionGetContactsCategories(){
        Yii::import("application.modules.contacts.models.*");
        $categories = ContactsCategories::model()->findAllByAttributes(array(
            'user_id' => Yii::app()->user->userID
        ));
        $temp = array();
        foreach($categories as $category)
        {
            if(count($category->contacts))
                $temp[] = array(
                    'id' => $category->id,
                    'text' => '<span class="js-tree-title">'.$category->title.'</span>' ,
                    'li_attr' => array(
                        'data-type' => 'category',
                    ),
                    'children' => TRUE
                );
        }
        echo json_encode($temp);
    }

    public function actionGetContacts($id){
        Yii::import("application.modules.contacts.models.*");
        $contacts = Contacts::model()->findAll(array(
            'condition' => 'cat_id = :catId AND email IS NOT NULL',
            'params' => array(
                ':catId' => $id
            )
        ));
        $temp = array();
        foreach($contacts as $contact)
        {
            $title = $contact->first_name." ".$contact->last_name." - ".$contact->email;
            $temp[] = array(
                'id' => $contact->id,
                'text' => '<span class="js-tree-title">'.$title.'</span>' ,
                'li_attr' => array(
                    'data-type' => 'contact',
                ),
            );
        }
        echo json_encode($temp);
    }

    public function actionGetTemplate($id){
        $model = MessagesEmailsTemplates::model()->findByPk($id);
        if(!is_null($model))
            echo $model->template;
    }

    protected function performAjaxValidation($model){
        if(isset($_POST['ajax']) && $_POST['ajax']==='messages-emails-send')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
