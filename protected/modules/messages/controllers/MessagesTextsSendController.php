<?php
class MessagesTextsSendController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'پیامک',
            'menu' => true,
            'menu_name' => 'texts_send',
            'type' => 'user',
            'module' => 'messages,contacts',
            'index' => array(
                'title' => 'صفحه اصلی',
                'type' => 'user',
            ),
            'rfs' => array(
                'title' => 'ارسال پیامک با استفاده از امکانات سایت',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'texts_send',
                'menu_name' => 'rfs',
                'url' => 'messages/texts_send/sms/rfs/policy',
            ),
            'rft' => array(
                'title' => 'ارسال پیامک با استفاده از امکانات مخابرات',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'texts_send',
                'menu_name' => 'rft',
                'url' => 'messages/texts_send/sms/rft',
            ),
            'sms' => array(
                'type' => 'user',
                'title' => 'ارسال پیامک',
                'otherActions' => 'delete,upload,getContactsCategories,getContacts,getWebserviceBanks,getWebserviceBanksByPostalCode,getWebserviceBanksByCities,getCount,test,getSmsStatus,save,manageRecipientsGroup,addRecipientsFromFile,getSmsRecipientsInfo,deleteRecipient,manageException,getRecipientsException,addManuallyRecipients,searchWhiteList,deselectWLSearchResult,selectWLSearchResult'

            ),
            'sent' => array(
                'title' => 'پیامک های ارسال شده',
                'type' => 'user',
                'menu' => true,
                'url' => 'messages/texts_send/sent',
                'menu_parent' => 'texts_send',
                'menu_name' => 'texts_send_sent',
            ),

            'setXmlToDatabase'=>array(
                'title'=>'ss',
                'type' => 'all'

            )
        );

    public $layout = '//layouts/main';

    public $default = "sms";
    protected $ignoreContacts=array();
    protected $acceptContacts=array();
    protected $ignoreCats=array();
    protected $acceptCats=array();
    protected $hasRule=true;
    protected $selectedCategories=array();

    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }

    public function actionIndex()
    {
        $model = new MessagesTextsSend('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['MessagesTextsSend']))
            $model->attributes = $_GET['MessagesTextsSend'];

        $this->render('index', array(
            'model' => $model,
        ));
        //$this->actionEmail();
    }

    public function actionSent()
    {
        $this->actionIndex();
    }

    public function actionDelete($id)
    {
        $delete = $this->loadModel($id);
        if ($delete->user_id == Yii::app()->user->userID)
            $delete->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }


    public function actionUpload()
    {
        $message = 'خطایی در آپلود فایل شما وجود دارد!';
        $array = array();
        $file = $_FILES['userFile'];

        if (array_key_exists('userFile', $_FILES) && $_FILES['userFile']['error'] == 0) {
            // Upload image
            $handle = fopen($file['tmp_name'], "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    $line = trim($line);
                    if (preg_match('/^(0|\+98){0,1}9{1}\d{9}$/', $line))
                        $array[] = $line;
                }
            }
            fclose($handle);
            if ($array != array()) {
                echo json_encode(array(
                    'result' => true,
                    'fileName' => $array,
                    'ID' => '0'
                ));
                Yii::app()->end();
            }
        }

        echo json_encode(array(
            'result' => false,
            'message' => $message
        ));
        Yii::app()->end();
    }

    public function actionGetContactsCategories()
    {
        Yii::import("application.modules.contacts.models.*");
        $categories = ContactsCategories::model()->findAllByAttributes(array(
            'user_id' => Yii::app()->user->userID
        ));
        $temp = array();
        foreach ($categories as $category) {
            if (count($category->contacts))
                $temp[] = array(
                    'id' => $category->id,
                    'text' => '<span class="js-tree-title">' . $category->title . '</span>',
                    'li_attr' => array(
                        'data-type' => 'category',
                    ),
                    'children' => TRUE
                );
        }
        echo json_encode($temp);
    }

    public function actionGetContacts($id)
    {
        Yii::import("application.modules.contacts.models.*");
        $contacts = Contacts::model()->findAll(array(
            'condition' => 'cat_id = :catId AND mobile IS NOT NULL',
            'params' => array(
                ':catId' => $id
            )
        ));
        $temp = array();
        foreach ($contacts as $contact) {
            $title = $contact->first_name . " " . $contact->last_name . " - " . $contact->mobile;
            $temp[] = array(
                'id' => $contact->id,
                'text' => '<span class="js-tree-title">' . $title . '</span>',
                'li_attr' => array(
                    'data-type' => 'contact',
                ),
            );
        }
        echo json_encode($temp);
    }

    public function actionGetWebserviceBanks($id = 0)
    {
        echo json_encode($this->getWebserviceBanks($id));
    }

    public function getWebserviceBanks($id = 0)
    {
        $xml = Yii::app()->iWebSms->getBank($id);
        $bank = Yii::app()->iWebSms->fetchXML($xml, 'bank', TRUE);
        $temp = array();
        foreach ($bank as $item) {
             $temp[] = array(
                 'id' => $item['id'],
                 'text' => '<span class="js-tree-title">'.$item['name'].'</span>' ,
                 'children' => TRUE
             );
        }
        return $temp;
    }


    public function actionGetWebserviceBanksByCities($id = 0)
    {
        echo json_encode($this->getWebserviceBanksByCities($id));
    }

    public function getWebserviceBanksByCities($id = 0)
    {
        $xml = Yii::app()->iWebSms->getBank($id);
        $bank = Yii::app()->iWebSms->fetchXML($xml, 'bank', TRUE);
        $temp = array();
        foreach ($bank as $item) {
            if ($id == 0) {
                if ($item['id'] === '1' OR $item['id'] === '2' OR $item['id'] === '7' OR $item['id'] === '8')
                    $temp[$item['id']] = $item['name'];
            } else
                $temp[$item['id']] = $item['name'];
        }
        return $temp;
    }

    public function actionGetCount($return = FALSE)
    {
        $webserviceValues = json_decode($_POST['MessagesTextsSend']['webservice']);
        if(trim($webserviceValues->postalCode)!=='') {
            $result = WebservicePostalCodes::model()->findByAttributes(array('name'=>$webserviceValues->postalCode));
            if(!is_null($result))
                $webserviceValues->node = $result->id;
            else {
                echo json_encode(array(
                    'count' => 0
                ));
                exit;
            }

        }

        $count = Yii::app()->iWebSms->nodeCount(
            (isset($webserviceValues->node) ? $webserviceValues->node : ''),
            (isset($webserviceValues->startAge) ? $webserviceValues->startAge : ''),
            (isset($webserviceValues->endAge) ? $webserviceValues->endAge : ''),
            (isset($webserviceValues->gender) ? $webserviceValues->gender : ''),
            (isset($webserviceValues->type) ? $webserviceValues->type : ''),
            (isset($webserviceValues->preNumber) ? $webserviceValues->preNumber : '')
        );
        if ($return)
            return $count;
        echo json_encode(array(
            'count' => $count
        ));
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sending-sms-l2') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function loadModel($id)
    {
        $model = MessagesTextsSend::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionSetXmlToDatabase($id=null)
    {
        $xml = Yii::app()->iWebSms->getBank((is_null($id))?5:$id);
        $bank = Yii::app()->iWebSms->fetchXML($xml, 'bank', TRUE);
        foreach ($bank as $item) {
            $model = new WebservicePostalCodes();
            $model->parent_id = $id;
            $model->id = $item['id'];
            $model->name = $item['name'];
            if($model->save(false))
                $this->actionSetXmlToDatabase($model->id);
        }
    }

    // Send SMS
    public function actionSms($sendtype, $step)
    {
        /*$model->send_time = time();
        $model->end_time = strtotime('+1 day');

        if (!is_null($draft) AND !isset($_POST['MessagesTextsSend'])) {
            $draft = MessagesTextsDrafts::model()->findByPk($draft);
            if (!is_null($draft))
                $model->body = $draft->body;
        }

        $this->performAjaxValidation($model);
        if (isset($_POST['MessagesTextsSend'])) {
            //$this->actionGetCount(TRUE);
            //exit;
            // set contacts
            $contacts = @json_decode($_POST['MessagesTextsSend']['contacts']);
            if (is_object($contacts) AND isset($contacts->category) AND !empty($contacts->category)) {
                $categories = implode(",", $contacts->category);
                Yii::import("application.modules.contacts.models.Contacts");
                $contactsCategories = Contacts::model()->find(array(
                    'select' => "GROUP_CONCAT(`id` SEPARATOR ',') AS id",
                    'condition' => "cat_id IN($categories) AND mobile IS NOT NULL",
                ));
                if (!is_null($contactsCategories))
                    $contactsCategories = $contactsCategories->id;
            }
            $contactsTemp = "";
            if (is_object($contacts) AND isset($contacts->contact) AND !empty($contacts->contact))
                $contactsTemp = implode(",", $contacts->contact);
            if (isset($contactsCategories) AND !is_null($contactsCategories))
                $contactsTemp .= (($contactsTemp == "") ? "" : ",") . $contactsCategories;
            $_POST['MessagesTextsSend']['contacts'] = $contactsTemp;

            // set bank
            if (@$_POST['MessagesTextsSend']['bank'] == "")
                $bank = NULL;
            else
                $bank = trim(implode(',', @json_decode($_POST['MessagesTextsSend']['bank'])));
            $_POST['MessagesTextsSend']['bank'] = $bank;

            if (isset($_POST['MessagesTextsSend']['to']))
                $_POST['MessagesTextsSend']['to'] = implode(',', $_POST['MessagesTextsSend']['to']);

            $model->attributes = $_POST['MessagesTextsSend'];
            if ($model->save()) {
                Yii::app()->user->setFlash('success', 'ارسال پیام با موفقیت ثبت شد.');
                //var_dump($model);exit;
                //$sendSms = new iWebSms("url","userid","password");
                //$sendSms->send($model->body,$model->to,$model->sender);
                //Yii::app()->iWebSms->send($model->body,$model->to);
            }
        }
        $mobilesBankCategories = MessagesMobilesBankCategories::model()->findAll('parent_id IS NULL');

        Yii::import("application.modules.contacts.models.ContactsCategories");
        //$contactsCategories = ContactsCategories::model()->findAll();

        $contactsCategories = (object)array();
        $webserviceCategories = (object)array();

        $numbers = MessagesTextsUsersNumbers::model()->findAll('(user_id IS NULL OR user_id = :userId) AND status = :status', array(
            ':userId' => Yii::app()->user->userID,
            ':status' => MessagesTextsUsersNumbers::STATUS_ENABLE
        ));
        $numbers = CHtml::listData($numbers, 'id', 'number');

        $model->send_time = Yii::app()->jdate->date('Y-m-d H:i:s', $model->send_time);
        $model->end_time = Yii::app()->jdate->date('Y-m-d H:i:s', $model->end_time);

        $this->render('sms', array(
            'model' => $model,
            'mobilesBankCategories' => $mobilesBankCategories,
            'contactsCategories' => $contactsCategories,
            'webserviceCategories' => $webserviceCategories,
            'numbers' => $numbers
        ));*/

        $model = new Sms;
        $this->performAjaxValidation($model);
        if (isset($_POST['Sms']) AND $_POST['saved']=='false') {
            $model->attributes = $_POST['Sms'];
            $model->save();
            $smsID=$model->id;
        }

        $data=array();
        $siteOptions=SiteOptions::model()->findAllByAttributes(array('name' => array('sms_sending_system','sms_send_usage_1','sms_send_usage_2')));
        $pages=Pages::model()->findAllByAttributes(array('name' => array('sms_help_policy_level_1','sms_help_policy_level_2','sms_help_policy_level_3')));
        switch($step)
        {
            case 'policy':
                $data['helpPolicy']=$pages[0];
                $data['sendingSystem']=$siteOptions[0];
                $data['sendUsageA']=$siteOptions[1];
                $data['sendUsageB']=$siteOptions[2];
                $data['nextStepUrl']=$this->createUrl('/messages/texts_send/sms/'.$sendtype.'/info');
                break;
            case 'info':
                $data['helpPolicy']=$pages[1];
                $data['senders']=MessagesTextsUsersNumbers::model()->findAll(array('condition' => 'user_id = ' . Yii::app()->user->userID . ' AND status = 1'));
                $data['nextStepUrl']=$this->createUrl('/messages/texts_send/sms/'.$sendtype.'/recipients');
                break;
            case 'recipients':
                if(!isset($_POST['sid']) and !isset($_GET['sid']) and !isset($_GET['gid']))
                    $this->redirect($this->createUrl('/messages/texts_send/sms/'.$sendtype.'/policy'));
                elseif(isset($_POST['sid']) AND $_POST['sid']=='')
                    $data['smsID']=$smsID;
                else
                {
                    if(isset($_POST['sid']))
                        $data['smsID']=$_POST['sid'];
                    elseif(isset($_GET['sid']))
                        $data['smsID']=$_GET['sid'];
                }
                $data['helpPolicy']=$pages[2];
                $data['mobileBank']=new CArrayDataProvider(Yii::app()->db->createCommand()
                    ->select('categories.id, categories.title, COUNT(mobiles.mobile) AS mobiles_count')
                    ->from('iw_messages_mobiles_bank_categories categories')
                    ->join('iw_messages_mobiles_bank mobiles','mobiles.cat_id=categories.id')
                    ->group('categories.id')
                    ->queryAll()
                );
                $data['contactGroups']=new CArrayDataProvider(Yii::app()->db->createCommand()
                        ->select('categories.id, categories.title, COUNT(contacts.mobile) AS contacts_count')
                        ->from('iw_contacts_categories categories')
                        ->join('iw_contacts contacts','contacts.cat_id=categories.id')
                        ->group('categories.id')
                        ->queryAll()
                );
                Yii::app()->getModule('contacts');
                if(isset($_GET['gid']))
                    $data['contacts']=new CActiveDataProvider('Contacts',array(
                        'criteria'=>array(
                            'condition'=>'cat_id=:cat_id',
                            'params'=>array(':cat_id'=>$_GET['gid'])
                        )
                    ));
                else
                    $data['contacts']=new CActiveDataProvider('Contacts');
                if(isset($_GET['ajax']))
                {
                    if($_GET['ajax']=='message-recipients' or $_GET['ajax']=='group-contacts')
                    {
                        $this->renderPartial('sms/_recipients',array(
                            'model' => $model ,
                            'helpPolicy' => $data['helpPolicy'],
                            'mobileBank'=>$data['mobileBank'],
                            'smsID'=>$data['smsID'],
                            'contactGroups'=>$data['contactGroups'],
                            'contacts'=>$data['contacts'],
                        ));
                        Yii::app()->end();
                    }
                }
                break;
        }

        $this->render('sms', array(
            'model' => $model,
            'step'=>$step,
            'data'=>$data,
            'sendtype' => $sendtype,
        ));
    }

    public function actionTest($sendtype)
    {
        $result = array();
        $allowAdminToSendSMS = (SiteOptions::model()->find("name = 'send_sms'")->value == '1') ? true : false;
        if($allowAdminToSendSMS)
        {
            $result['hasError'] = false;
            $allowedTimeRange = json_decode(SiteOptions::model()->find("name = 'sms_send_range'")->value, true);
            if($sendtype == 'rft')
            {
                $allowedStartTime = $allowedTimeRange[0] . ':' . $allowedTimeRange[1];
                $allowedEndTime = $allowedTimeRange[2] . ':' . $allowedTimeRange[3];
            }
            elseif($sendtype == 'rfs')
            {
                $allowedStartTime = $allowedTimeRange[4] . ':' . $allowedTimeRange[5];
                $allowedEndTime = $allowedTimeRange[6] . ':' . $allowedTimeRange[7];
            }

            if(time() > strtotime($allowedStartTime) && time() < strtotime($allowedEndTime))
            {
                $result['sendTimeRange'] = 'ساعت ارسال در محدوده زمانی مجاز می باشد';
                $result['hasError'] = false;
                $planID = json_decode(Yii::app()->user->plan);
                $ratio = json_decode(Plans::model()->findByPk($planID->id)->ratio, true);
                $senderRatio = $ratio['operator_5000']['value'];
                $contextRatio = (($sendtype == 'rfs') ? $ratio['system']['value'] : $ratio['webservice']['value']) + (($this->getSMSContextType($_POST['Sms']['body']) == 'Farsi') ? $ratio['persian']['value'] : $ratio['english']['value']);
                $recipientRatio = $ratio[$this->getRecipientOperator($_POST['test-contact'])]['value'];
                $neededCharge = ($senderRatio + $contextRatio + $recipientRatio) * $this->getSMSPagesCount($_POST['Sms']['body']);
                $SMSCharge = $this->currentUser->sms_charge;
                if($SMSCharge - $neededCharge > 0)
                {
                    $result['SMSCharge'] = 'اعتبار شارژ گهر پیامک کافی است و اعتبار مورد نیاز کم شد';
                    $result['hasError'] = false;

                    $usersModel = Users::model()->findByPk(Yii::app()->user->userID);
                    $usersModel->scenario = 'changeValue';
                    $usersModel->sms_charge = $SMSCharge - $neededCharge;
                    $usersModel->save();

                    // Send request to Web Service
                    $originator = MessagesTextsUsersNumbers::model()->findByPk($_POST['Sms']['sender_id'])->number;
                    $originator = '';
                    $SMSResult = Yii::app()->iWebSms->send($_POST['Sms']['body'], $_POST['test-contact'], $originator, 'oto');

                    switch($SMSResult['status'])
                    {
                        case 'fault':
                            $result['hasError'] = true;
                            $result['errorSection'] = 'sendingRequest';
                            $result['sendingRequest'] = array(
                                'status' => 'fault',
                                'message' => 'درخواست برای مخابرات ارسال نشد'
                            );
                            break;

                        case 'error':
                            $result['hasError'] = true;
                            $result['errorSection'] = 'sendingRequest';
                            $result['sendingRequest'] = array(
                                'status' => 'error',
                                'message' => 'درخواست برای مخابرات ارسال نشد'
                            );
                            break;
                        case 'success':
                            $xml = $SMSResult['result']['XmsRequestResult'];
                            $webServiceResponse = Yii::app()->iWebSms->analysisResponse($xml, 'recipient');
                            $result['hasError'] = false;
                            $result['sendingRequest'] = array(
                                'status' => 'success',
                                'message' => 'درخواست برای مخابرات ارسال شد',
                                'WSResponse' => Yii::app()->iWebSms->getStatusMessage($webServiceResponse[0]->attributes->status),
                                'smsID' => $webServiceResponse[0]->value,
                            );
                            break;
                    }
                }
                else
                {
                    $result['SMSCharge'] = 'اعتبار شارژ گهر پیامک کافی نیست';
                    $result['hasError'] = true;
                    $result['errorSection'] = 'SMSCharge';
                }
            }
            else
            {
                $result['sendTimeRange'] = 'ساعت ارسال در محدوده زمانی مجاز نمی باشد';
                $result['hasError'] = true;
                $result['errorSection'] = 'sendTimeRange';
            }
        }
        else
        {
            $result['allowAdminToSendSMS'] = 'امکان ارسال پیامک برای کلیه کاربران وجود ندارد';
            $result['hasError'] = true;
            $result['errorSection'] = 'allowAdminToSendSMS';
        }

        echo json_encode($result);
    }

    public function actionSave()
    {
        if(isset($_POST['Sms']))
        {
            $model=new Sms;
            $model->attributes=$_POST['Sms'];
            if($model->validate())
            {
                if($model->save())
                    echo CJSON::encode(array(
                        'hasError'=>false,
                        'status'=>'success',
                        'sid'=>$model->id,
                        'message'=>'اطلاعات پیامک با موفقیت ذخیره شد'
                    ));
                else
                    echo CJSON::encode(array(
                        'hasError'=>false,
                        'status'=>'fail',
                        'message'=>'در ثبت اطلاعات خطایی رخ داده است'
                    ));
            }
            else
                echo CJSON::encode(array(
                    'hasError'=>true,
                ));
        }
    }

    public function actionManageRecipientsGroup()
    {
        if(isset($_POST['recipients_group_info']))
        {
            if($_POST['recipients_group_info']['action']=='add')
            {
                if($_POST['recipients_group_info']['dest']=='wl')
                    $model=SmsRecipientsFromSystemWL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$_POST['recipients_group_info']['sid'],':cat_id'=>$_POST['recipients_group_info']['gid'],':from'=>'contacts'));
                else
                    $model=SmsRecipientsFromSystemBL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$_POST['recipients_group_info']['sid'],':cat_id'=>$_POST['recipients_group_info']['gid'],':from'=>'contacts'));
                if(is_null($model))
                {
                    if($_POST['recipients_group_info']['dest']=='wl')
                        $model=new SmsRecipientsFromSystemWL();
                    else
                        $model=new SmsRecipientsFromSystemBL();
                    $model->from=$_POST['recipients_group_info']['from'];
                    $model->cat_id=$_POST['recipients_group_info']['gid'];
                    $model->sms_id=$_POST['recipients_group_info']['sid'];
                    if($model->save())
                        echo CJSON::encode(array('status'=>'success'));
                    else
                        echo CJSON::encode(array('status'=>'fail'));
                }
                else
                    echo CJSON::encode(array('status'=>'success'));
            }
            elseif($_POST['recipients_group_info']['action']=='remove')
            {
                if($_POST['recipients_group_info']['dest']=='wl')
                    $result=SmsRecipientsFromSystemWL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$_POST['recipients_group_info']['sid'],':cat_id'=>$_POST['recipients_group_info']['gid'],':from'=>$_POST['recipients_group_info']['from']))
                        ->delete();
                else
                    $result=SmsRecipientsFromSystemBL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$_POST['recipients_group_info']['sid'],':cat_id'=>$_POST['recipients_group_info']['gid'],':from'=>$_POST['recipients_group_info']['from']))
                        ->delete();
                if($result)
                    echo CJSON::encode(array('status'=>'success'));
                else
                    echo CJSON::encode(array('status'=>'fail'));
            }
        }
    }

    public function actionAddRecipientsFromFile()
    {
        if(isset(Yii::app()->user->userID))
        {
            $upload=$this->upload($_FILES['userFile']);
            if(!$upload)
                echo CJSON::encode(array('result'=>false));
            elseif(is_array($upload))
            {
                $fileInfo=pathinfo($upload['filePath']);
                if($fileInfo['extension']=='txt')
                {
                    $txtFile=file($upload['filePath']);
                    $mobiles=array();
                    $temp=array();
                    foreach($txtFile as $line)
                    {
                        if(preg_match('/^09\d{9}$/', trim($line)))
                        {
                            $mobiles[]=trim($line);
                            $temp[]=array('sms_id'=>$_POST['sid'],'mobile'=>trim($line),'from'=>'file');
                        }
                    }
                    $builder=Yii::app()->db->schema->commandBuilder;
                    $command=$builder->createMultipleInsertCommand('iw_sms_recipients_manual_wl', $temp);
                    @unlink($upload['filePath']);
                    if($command->execute())
                        echo CJSON::encode(array(
                            'result'=>true,
                            'fileName'=>$upload['fileName'],
                            'ID'=>'0',
                        ));
                    else
                        echo CJSON::encode(array('result'=>false));
                }
                else
                {
                    $sheet_array = Yii::app()->yexcel->readActiveSheet($upload['filePath']);
                    $mobiles=array();
                    $temp=array();
                    foreach($sheet_array as $record)
                    {
                        if(preg_match('/^09\d{9}$/', trim($record['A'])) OR preg_match('/^9\d{9}$/', trim($record['A'])))
                        {
                            $mobile='';
                            if(preg_match('/^09\d{9}$/', trim($record['A'])))
                                $mobile=trim($record['A']);
                            elseif(preg_match('/^9\d{9}$/', trim($record['A'])))
                                $mobile='0'.trim($record['A']);
                            $mobiles[]=$mobile;
                            $temp[]=array('sms_id'=>$_POST['sid'],'mobile'=>$mobile,'from'=>'file');
                        }
                    }
                    $builder=Yii::app()->db->schema->commandBuilder;
                    $command=$builder->createMultipleInsertCommand('iw_sms_recipients_manual_wl', $temp);
                    @unlink($upload['filePath']);
                    if($command->execute())
                        echo CJSON::encode(array(
                            'result'=>true,
                            'fileName'=>$upload['fileName'],
                            'ID'=>'0',
                        ));
                    else
                        echo CJSON::encode(array('result'=>false));
                }
            }
        }
    }

    public function actionAddManuallyRecipients()
    {
        if(isset($_POST['recipients']) and !empty($_POST['recipients']))
        {
            $recipients=$_POST['recipients'];
            $recipients=explode(',',$recipients);
            $mobiles=array();
            foreach($recipients as $mobile)
                if(preg_match('/^09\d{9}$/', $mobile))
                    $mobiles[]=array('sms_id'=>$_POST['sid'],'mobile'=>$mobile,'from'=>'manual');
            $builder=Yii::app()->db->schema->commandBuilder;
            $command=$builder->createMultipleInsertCommand('iw_sms_recipients_manual_wl', $mobiles);
            if($command->execute())
                echo CJSON::encode(array('status'=>true));
            else
                echo CJSON::encode(array('status'=>false));
        }
    }

    public function actionDeleteRecipient($id)
    {
        $model=SmsRecipients::model()->findByPk($id);
        if($model!=null)
            $model->delete();
    }

    public function actionGetSmsRecipientsInfo($id)
    {
        $recordedMobiles=SmsRecipients::model()->findAll('sms_id=:sms_id', array(':sms_id'=>$id));
        echo CJSON::encode(array(
            'status'=>true,
            'recipients_count'=>count($recordedMobiles),
        ));
    }

    public function actionManageException()
    {
        if(isset($_POST['contact_info']))
        {
            if($_POST['contact_info']['scenario']=='ignore')
            {
                $model=SmsRecipientsFromSystemWL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$_POST['contact_info']['sid'],':cat_id'=>$_POST['contact_info']['gid'],':from'=>'contacts'));
                if($model==null)
                {
                    echo CJSON::encode(array('status'=>'fail'));
                    Yii::app()->end();
                }
                if($_POST['contact_info']['action']=='unchecked')
                {
                    if($model->exception==null)
                    {
                        $exception=array('ignore'=>array($_POST['contact_info']['cid']));
                        $model->exception=CJSON::encode($exception);
                        if($model->save())
                            echo CJSON::encode(array('status'=>'success'));
                        else
                            echo CJSON::encode(array('status'=>'fail'));
                    }
                    else
                    {
                        $exception=CJSON::decode($model->exception);
                        if(!in_array($_POST['contact_info']['cid'],$exception['ignore']))
                            $exception['ignore'][]=$_POST['contact_info']['cid'];
                        $model->exception=CJSON::encode($exception);
                        if($model->save())
                            echo CJSON::encode(array('status'=>'success'));
                        else
                            echo CJSON::encode(array('status'=>'fail'));
                    }
                }
                elseif($_POST['contact_info']['action']=='checked')
                {
                    if($model->exception!=null)
                    {
                        $exception=CJSON::decode($model->exception);
                        $temp=array();
                        foreach($exception['ignore'] as $key=>$cid)
                            if($cid!=$_POST['contact_info']['cid'])
                                $temp[]=$cid;
                        $exception['ignore']=$temp;
                        if($exception['ignore']==array())
                            $model->exception=null;
                        else
                            $model->exception=CJSON::encode($exception);
                        if($model->save())
                            echo CJSON::encode(array('status'=>'success'));
                        else
                            echo CJSON::encode(array('status'=>'fail'));
                    }
                }
            }
            elseif($_POST['contact_info']['scenario']=='accept')
            {
                $model=SmsRecipientsFromSystemWL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$_POST['contact_info']['sid'],':cat_id'=>$_POST['contact_info']['gid'],':from'=>'contacts'));
                if($model==null)
                {
                    $model=new SmsRecipientsFromSystemWL();
                    $model->sms_id=$_POST['contact_info']['sid'];
                    $model->cat_id=$_POST['contact_info']['gid'];
                    $model->from='contacts';
                    if(!$model->save())
                    {
                        echo CJSON::encode(array('status'=>'fail'));
                        Yii::app()->end();
                    }
                }
                if($_POST['contact_info']['action']=='checked')
                {
                    if($model->exception==null)
                    {
                        $exception=array('accept'=>array($_POST['contact_info']['cid']));
                        $model->exception=CJSON::encode($exception);
                        if($model->save())
                            echo CJSON::encode(array('status'=>'success'));
                        else
                            echo CJSON::encode(array('status'=>'fail'));
                    }
                    else
                    {
                        $exception=CJSON::decode($model->exception);
                        if(!in_array($_POST['contact_info']['cid'],$exception['accept']))
                            $exception['accept'][]=$_POST['contact_info']['cid'];
                        $model->exception=CJSON::encode($exception);
                        if($model->save())
                            echo CJSON::encode(array('status'=>'success'));
                        else
                            echo CJSON::encode(array('status'=>'fail'));
                    }
                }
                elseif($_POST['contact_info']['action']=='unchecked')
                {
                    if($model->exception!=null)
                    {
                        $exception=CJSON::decode($model->exception);
                        $temp=array();
                        foreach($exception['accept'] as $key=>$cid)
                            if($cid!=$_POST['contact_info']['cid'])
                                $temp[]=$cid;
                        $exception['accept']=$temp;
                        if($exception['accept']==array())
                        {
                            if($model->delete())
                                echo CJSON::encode(array('status'=>'success'));
                            else
                                echo CJSON::encode(array('status'=>'fail'));
                        }
                        else
                        {
                            $model->exception=CJSON::encode($exception);
                            if($model->save())
                                echo CJSON::encode(array('status'=>'success'));
                            else
                                echo CJSON::encode(array('status'=>'fail'));
                        }
                    }
                }
            }
        }
    }

    public function actionDeselectWLSearchResult()
    {
        if(isset($_POST['contacts_info']))
        {
            $contacts=CJSON::decode($_POST['contacts_info']['ids']);
            $smsID=$_POST['contacts_info']['sid'];
            foreach($contacts as $contact)
            {
                if($contact[1]=='ignore')
                {
                    $model=SmsRecipientsFromSystemWL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$smsID,':cat_id'=>$contact[2],':from'=>'contacts'));
                    if($model==null)
                    {
                        echo CJSON::encode(array('status'=>'fail'));
                        Yii::app()->end();
                    }
                    if($model->exception==null)
                    {
                        $exception=array('ignore'=>array($contact[0]));
                        $model->exception=CJSON::encode($exception);
                        if(!$model->save())
                        {
                            echo CJSON::encode(array('status'=>'fail'));
                            Yii::app()->end();
                        }
                    }
                    else
                    {
                        $exception=CJSON::decode($model->exception);
                        if(!in_array($contact[0],$exception['ignore']))
                            $exception['ignore'][]=$contact[0];
                        $model->exception=CJSON::encode($exception);
                        if(!$model->save())
                        {
                            echo CJSON::encode(array('status'=>'fail'));
                            Yii::app()->end();
                        }
                    }
                }
                elseif($contact[1]=='accept')
                {
                    $model=SmsRecipientsFromSystemWL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$smsID,':cat_id'=>$contact[2],':from'=>'contacts'));
                    if($model==null)
                    {
                        $model=new SmsRecipientsFromSystemWL();
                        $model->sms_id=$smsID;
                        $model->cat_id=$contact[2];
                        $model->from='contacts';
                        if(!$model->save())
                        {
                            echo CJSON::encode(array('status'=>'fail'));
                            Yii::app()->end();
                        }
                    }
                    if($model->exception!=null)
                    {
                        $exception=CJSON::decode($model->exception);
                        $temp=array();
                        foreach($exception['accept'] as $key=>$cid)
                            if($cid!=$contact[0])
                                $temp[]=$cid;
                        $exception['accept']=$temp;
                        if($exception['accept']==array())
                        {
                            if(!$model->delete())
                            {
                                echo CJSON::encode(array('status'=>'fail'));
                                Yii::app()->end();
                            }
                        }
                        else
                        {
                            $model->exception=CJSON::encode($exception);
                            if(!$model->save())
                            {
                                echo CJSON::encode(array('status'=>'fail'));
                                Yii::app()->end();
                            }
                        }
                    }
                }
            }
            echo CJSON::encode(array('status'=>'success'));
        }
    }

    public function actionSelectWLSearchResult()
    {
        if(isset($_POST['contacts_info']))
        {
            $contacts=CJSON::decode($_POST['contacts_info']['ids']);
            $smsID=$_POST['contacts_info']['sid'];
            foreach($contacts as $contact)
            {
                if($contact[1]=='ignore')
                {
                    $model=SmsRecipientsFromSystemWL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$smsID,':cat_id'=>$contact[2],':from'=>'contacts'));
                    if($model==null)
                    {
                        echo CJSON::encode(array('status'=>'fail'));
                        Yii::app()->end();
                    }
                    if($model->exception!=null)
                    {
                        $exception=CJSON::decode($model->exception);
                        $temp=array();
                        foreach($exception['ignore'] as $key=>$cid)
                            if($cid!=$contact[0])
                                $temp[]=$cid;
                        $exception['ignore']=$temp;
                        if($exception['ignore']==array())
                            $model->exception=null;
                        else
                            $model->exception=CJSON::encode($exception);
                        if(!$model->save())
                        {
                            echo CJSON::encode(array('status'=>'fail'));
                            Yii::app()->end();
                        }
                    }
                }
                elseif($contact[1]=='accept')
                {
                    $model=SmsRecipientsFromSystemWL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$smsID,':cat_id'=>$contact[2],':from'=>'contacts'));
                    if($model==null)
                    {
                        $model=new SmsRecipientsFromSystemWL();
                        $model->sms_id=$smsID;
                        $model->cat_id=$contact[2];
                        $model->from='contacts';
                        if(!$model->save())
                        {
                            echo CJSON::encode(array('status'=>'fail'));
                            Yii::app()->end();
                        }
                    }
                    if($model->exception==null)
                    {
                        $exception=array('accept'=>array($contact[0]));
                        $model->exception=CJSON::encode($exception);
                        if(!$model->save())
                        {
                            echo CJSON::encode(array('status'=>'fail'));
                            Yii::app()->end();
                        }
                    }
                    else
                    {
                        $exception=CJSON::decode($model->exception);
                        if(!in_array($contact[0],$exception['accept']))
                            $exception['accept'][]=$contact[0];
                        $model->exception=CJSON::encode($exception);
                        if(!$model->save())
                        {
                            echo CJSON::encode(array('status'=>'fail'));
                            Yii::app()->end();
                        }
                    }
                }
            }
            echo CJSON::encode(array('status'=>'success'));
        }
    }

    public function actionGetRecipientsException()
    {
        if(isset($_POST['recipients_info']))
        {
            if($_POST['recipients_info']['dest']=='wl')
                $model=SmsRecipientsFromSystemWL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$_POST['recipients_info']['sid'],':cat_id'=>$_POST['recipients_info']['gid'],':from'=>'contacts'));
            else
                $model=SmsRecipientsFromSystemBL::model()->find('sms_id=:sms_id AND cat_id=:cat_id AND `from`=:from', array(':sms_id'=>$_POST['recipients_info']['sid'],':cat_id'=>$_POST['recipients_info']['gid'],':from'=>'contacts'));
            if($model!=null)
                echo CJSON::encode(array('status'=>'success','exception'=>CJSON::decode($model->exception)));
            else
                echo CJSON::encode(array('status'=>'empty'));
        }
    }

    public function actionSearchWhiteList()
    {
        if(isset($_GET['ajax']) and $_GET['ajax']=='contacts-search-list')
        {
            if($_GET['search']['in']=='inContacts')
            {
                $criteria=new CDbCriteria();
                $criteria->addSearchCondition('mobile', $_GET['search']['value'],true,'OR');
                $criteria->addSearchCondition('first_name', $_GET['search']['value'],true,'OR');
                $criteria->addSearchCondition('last_name', $_GET['search']['value'],true,'OR');
                $criteria->addSearchCondition('email', $_GET['search']['value'],true,'OR');
                $criteria->addSearchCondition('category.user_id', Yii::app()->user->userID);
                $criteria->with='category';

                // Get this sms recipients
                Yii::app()->getModule('contacts');
                $selectedCriteria=new CDbCriteria();
                $selectedCriteria->select='cat_id,exception';
                $selectedCriteria->condition='sms_id=:sms_id AND `from`=:from';
                $selectedCriteria->params=array(':sms_id'=>$_GET['search']['sid'],':from'=>'contacts');
                $recipientsRule=SmsRecipientsFromSystemWL::model()->findAll($selectedCriteria);
                if(!empty($recipientsRule))
                {
                    foreach($recipientsRule as $rule)
                    {
                        $exception=CJSON::decode($rule->exception);
                        $this->selectedCategories[]=$rule->cat_id;
                        if(isset($exception['ignore']))
                        {
                            if(!in_array($rule->cat_id,$this->ignoreCats))$this->ignoreCats[]=$rule->cat_id;
                            foreach($exception['ignore'] as $key=>$value)
                                $this->ignoreContacts[$rule->cat_id][]=$value;
                        }
                        elseif(isset($exception['accept']))
                        {
                            if(!in_array($rule->cat_id,$this->acceptCats))$this->acceptCats[]=$rule->cat_id;
                            foreach($exception['accept'] as $key=>$value)
                                $this->acceptContacts[$rule->cat_id][]=$value;
                        }
                        elseif(is_null($exception))
                        {
                            if(!in_array($rule->cat_id,$this->ignoreCats))$this->ignoreCats[]=$rule->cat_id;
                            $this->acceptContacts[$rule->cat_id]='all';
                        }
                    }
                }
                else
                    $this->hasRule=false;

                $this->renderPartial('sms/__recipients_contacts_search_list',array(
                    'dataProvider'=>new CActiveDataProvider('Contacts',array(
                        'criteria'=>$criteria
                    ))
                ));
            }
            elseif($_GET['search']['in']=='thisContacts')
            {
                // Create new Criteria for search in contacts
                $criteria=new CDbCriteria();
                $criteria->addSearchCondition('mobile', $_GET['search']['value'],true,'OR');
                $criteria->addSearchCondition('first_name', $_GET['search']['value'],true,'OR');
                $criteria->addSearchCondition('last_name', $_GET['search']['value'],true,'OR');
                $criteria->addSearchCondition('email', $_GET['search']['value'],true,'OR');
                $criteria->addCondition('category.user_id=:user_id');
                $criteria->params[':user_id']=Yii::app()->user->userID;
                $criteria->with='category';
                $criteria->alias='contacts';

                // Get this sms recipients
                Yii::app()->getModule('contacts');
                $selectedCriteria=new CDbCriteria();
                $selectedCriteria->select='cat_id,exception';
                $selectedCriteria->condition='sms_id=:sms_id AND `from`=:from';
                $selectedCriteria->params=array(':sms_id'=>$_GET['search']['sid'],':from'=>'contacts');
                $recipientsRule=SmsRecipientsFromSystemWL::model()->findAll($selectedCriteria);
                if(!empty($recipientsRule))
                {
                    foreach($recipientsRule as $rule)
                    {
                        $exception=CJSON::decode($rule->exception);
                        $this->selectedCategories[]=$rule->cat_id;
                        if(isset($exception['ignore']))
                        {
                            if(!in_array($rule->cat_id,$this->ignoreCats))$this->ignoreCats[]=$rule->cat_id;
                            foreach($exception['ignore'] as $key=>$value)
                                $this->ignoreContacts[$rule->cat_id][]=$value;
                            $contacts=Contacts::model()->findAll('cat_id=:cat_id AND id NOT IN (:ids)', array(':cat_id'=>$rule->cat_id, ':ids'=>implode(',', $this->ignoreContacts[$rule->cat_id])));
                            foreach($contacts as $contact)
                                $this->acceptContacts[$rule->cat_id][]=$contact->id;
                        }
                        elseif(isset($exception['accept']))
                        {
                            if(!in_array($rule->cat_id,$this->acceptCats))$this->acceptCats[]=$rule->cat_id;
                            foreach($exception['accept'] as $key=>$value)
                                $this->acceptContacts[$rule->cat_id][]=$value;
                        }
                        elseif(is_null($exception))
                        {
                            if(!in_array($rule->cat_id,$this->ignoreCats))$this->ignoreCats[]=$rule->cat_id;
                            $this->acceptContacts[$rule->cat_id]='all';
                        }
                    }
                }
                else
                    $this->hasRule=false;

                // Add IDs that are most be found to condition
                if(!empty($this->acceptContacts))
                {
                    $inIDsArray=array();
                    foreach($this->acceptContacts as $key=>$value)
                    {
                        if($value=='all')
                        {
                            $records=Contacts::model()->findAll('cat_id=:cat_id',array(':cat_id'=>$key));
                            foreach($records as $record)
                                $inIDsArray[]=$record->id;
                        }
                        else
                            $inIDsArray=array_merge($inIDsArray,$this->acceptContacts[$key]);
                    }
                    $criteria->addInCondition('contacts.id',$inIDsArray);
                }

                // Add IDs that are must not be found to condition
                if(!empty($this->ignoreContacts))
                {
                    $notInIDsArray=array();
                    foreach($this->ignoreContacts as $key=>$value)
                        $notInIDsArray=array_merge($notInIDsArray,$this->ignoreContacts[$key]);
                    $criteria->addNotInCondition('contacts.id',$notInIDsArray);
                }

                if($this->hasRule)
                    $dataProvider=new CActiveDataProvider('Contacts',array(
                        'criteria'=>$criteria
                    ));
                else
                    $dataProvider=new CArrayDataProvider(array());

                $this->renderPartial('sms/__recipients_contacts_search_list',array(
                    'dataProvider'=>$dataProvider,
                ));
            }
        }
    }

    public function selectSearchListCheckbox($contactID, $contactCatID)
    {
        if(!$this->hasRule)
            return false;

        if(isset($this->acceptContacts[$contactCatID]) and $this->acceptContacts[$contactCatID]=='all')
            return true;

        if(!empty($this->ignoreContacts) and isset($this->ignoreContacts[$contactCatID]))
        {
            if(in_array($contactID, $this->ignoreContacts[$contactCatID]))
                return false;
            elseif(!in_array($contactID, $this->ignoreContacts) and in_array($contactCatID,$this->selectedCategories))
                return true;
        }

        if(!empty($this->acceptContacts) and isset($this->acceptContacts[$contactCatID]))
        {
            if(in_array($contactID, $this->acceptContacts[$contactCatID]))
                return true;
            elseif(!in_array($contactID, $this->acceptContacts[$contactCatID]))
                return false;
        }
    }

    public function getContactScenario($contactCatID)
    {
        if(!$this->hasRule)
            return 'accept';

        if(in_array($contactCatID, $this->acceptCats))
            return 'accept';
        elseif(in_array($contactCatID, $this->ignoreCats))
            return 'ignore';
        else
            return 'accept';
    }

    public function actionGetSmsStatus($sendtype)
    {
        if(isset($_POST['smsID']))
        {
            $result = array();
            $smsID = $_POST['smsID'];
            $requestResult = Yii::app()->iWebSms->getSmsStatus($smsID);
            switch($requestResult['status'])
            {
                case 'fault':
                    $result['hasError'] = true;
                    $result['sendingRequest'] = array(
                        'status' => 'fault',
                        'message' => 'درخواست برای مخابرات ارسال نشد'
                    );
                    break;

                case 'error':
                    $result['hasError'] = true;
                    $result['sendingRequest'] = array(
                        'status' => 'error',
                        'message' => 'درخواست برای مخابرات ارسال نشد'
                    );
                    break;
                case 'success':
                    $xml = $requestResult['result']['XmsRequestResult'];
                    $webServiceResponse = Yii::app()->iWebSms->analysisResponse($xml, 'message');
                    $result['hasError'] = false;
                    $result['sendingRequest'] = array(
                        'status' => 'success',
                        'message' => 'درخواست برای مخابرات ارسال شد',
                        'WSResponse' => Yii::app()->iWebSms->getStatusMessage($webServiceResponse[0]->attributes->status),
                        'smsID' => $webServiceResponse[0]->attributes->id,
                    );
                    break;
            }
            echo json_encode($result);
        }
    }

    public function getSMSPagesCount($SMSContext)
    {
        $pagesCount = 0;
        $contextLength = mb_strlen($SMSContext, 'utf8');

        if($contextLength <= 70)
            $pagesCount = 1;
        else
        {
            $contextLength -= 70;
            $pagesCount = ceil($contextLength / 66) + 1;
        }
        return $pagesCount;
    }

    public function getSMSContextType($SMSContext)
    {
        $farsiChars = array('ا', 'ب', 'پ', 'ت', 'ث', 'ج', 'چ', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'ژ', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ک', 'گ', 'ل', 'م', 'ن', 'و', 'ه', 'ی', 'آ', 'ة', 'ي', 'ؤ', 'إ', 'أ', 'ء', 'ئ', 'َ', 'ُ', 'ِ', 'ّ', 'ۀ', 'ً', 'ٌ', 'ٍ');
        $neutralChars = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '+', '-', '*', '/', '.', ',', '\\', '`', '=', '[', ']', '\'', ';', '<', '>', '?', ':', '"', '|', '{', '}', '~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '×', ' ');
        $farsiCharsCount = 0;
        $latinCharsCount = 0;

        for($i = 0; $i < mb_strlen($SMSContext, 'utf8'); $i++)
        {
            if(in_array($SMSContext[$i], $neutralChars))
                continue;

            if(in_array($SMSContext[$i], $farsiChars))
                $farsiCharsCount++;
            else
                $latinCharsCount++;
        }

        if($latinCharsCount == $farsiCharsCount)
            return 'Farsi';
        else if($latinCharsCount > $farsiCharsCount)
            return 'Latin';
        else if($latinCharsCount < $farsiCharsCount)
            return 'Farsi';
    }

    public function getRecipientOperator($recipient)
    {
        if(preg_match('/^093/', $recipient))
            return 'irancell';
        elseif(preg_match('/^091/', $recipient))
            return 'mci';
        else
            return 'other';
    }

    protected function upload($file)
    {
        $filePath = 'upload/tempRecipients/';
        $uploadDirectory = Yii::getPathOfAlias('webroot').'/'.$filePath;
        $fileInfo = pathinfo($file['name']);
        $fileName = time().".".$fileInfo['extension'];

        if(array_key_exists('userFile',$_FILES) && $_FILES['userFile']['error'] == 0 ){
            iWebHelper::makePath($uploadDirectory);
            if(move_uploaded_file($file['tmp_name'], $uploadDirectory.$fileName))
                return array(
                    'status'=>true,
                    'fileName'=>$fileName,
                    'filePath'=>$uploadDirectory.$fileName
                );
            else
                return false;
        }
    }
}