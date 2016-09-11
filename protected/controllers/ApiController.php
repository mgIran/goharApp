<?php
Yii::import('admins.models.*');
class ApiController extends ApiBaseController
{
	/**
	 * Key which has to be in HTTP USERNAME and PASSWORD headers
	 */
	Const APPLICATION_ID = 'ASCCPE';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'RestAccessControl + getLastVer,downloadApp,getBaseLine,checkNumber',
			'RestUserAccessControl + test',
//			'RestAdminAccessControl +'
		);
	}

	public function actionGetList()
	{
		if(isset($_POST['entity']) && $entity = $_POST['entity'])
		{
			switch (trim($entity)) {
				case 'places':
					$list = UsersPlaces::model()->findAll();
					break;
				case 'ceremonies':
					$list = Events::model()->findAll();
					break;
				default:
					$list = array();
					break;
			}
			if($list) {
				$this->_sendResponse(200, CJSON::encode(['status' => 'success', 'list' => $list]), 'application/json');
			}else
				$this->_sendResponse(400, CJSON::encode(['status' => 'failed', 'message' => 'اطلاعاتی برای دریافت موجود نیست.']), 'application/json');
		}
	}

	public function actionGetLastVer()
	{
		if(isset($_POST['version']) && isset($_POST['sim'])) {
			$lastVer = SiteOptions::model()->findByAttributes(['name' => 'app_version']);
			if($_POST['version'] == $lastVer->value)
				$this->_sendResponse(200, CJSON::encode(['status' => 'success', 'message' => 'نسخه نرم افزار به روز می باشد.']), 'application/json');
			else {
				$fileName = 'gohar-v'.$lastVer->value.'.apk';
				$downloadToken = DownloadTokens::model()->findByAttributes(['app_version' => $lastVer->value, 'sim' => $_POST['sim']]);
				if(!$downloadToken) {
					$downloadToken = new DownloadTokens();
					$downloadToken->app_version = $lastVer->value;
					$downloadToken->sim = $_POST['sim'];
					$downloadToken->request_time = time();
					$downloadToken->token = sha1(md5($downloadToken->app_version.$downloadToken->sim.time()));
					$downloadToken->save();
					$copyFileName = 'gohar-v'.$lastVer->value.'-'.$downloadToken->request_time.'.apk';
					if(file_exists(Yii::getPathOfAlias('webroot').'/uploads/app/'.$fileName))
						@copy(Yii::getPathOfAlias('webroot').'/uploads/app/'.$fileName, Yii::getPathOfAlias('webroot').'/temp/'.$copyFileName);
				} elseif($downloadToken && $downloadToken->request_time < (time() - (24 * 60 * 60))) {
					$copyFileName = 'gohar-v'.$lastVer->value.'-'.$downloadToken->request_time.'.apk';
					@unlink(Yii::getPathOfAlias('webroot').'/temp/'.$copyFileName);
					$downloadToken->delete();
					$downloadToken = new DownloadTokens();
					$downloadToken->app_version = $lastVer->value;
					$downloadToken->sim = $_POST['sim'];
					$downloadToken->request_time = time();
					$downloadToken->token = sha1(md5($downloadToken->app_version.$downloadToken->sim.time()));
					$downloadToken->save();
					$copyFileName = 'gohar-v'.$lastVer->value.'-'.$downloadToken->request_time.'.apk';
					if(file_exists(Yii::getPathOfAlias('webroot').'/uploads/app/'.$fileName))
						@copy(Yii::getPathOfAlias('webroot').'/uploads/app/'.$fileName, Yii::getPathOfAlias('webroot').'/temp/'.$copyFileName);
				}
				$fileLink = Yii::app()->createAbsoluteUrl('/api/downloadApp/'.$downloadToken->token);
				$this->_sendResponse(200, CJSON::encode(['status' => 'failed', 'newVersionLink' => $fileLink]), 'application/json');
			}
		} elseif(!isset($_POST['version']))
			$this->_sendResponse(400,CJSON::encode(['status' => 'failed','message' => 'مقدار نسخه فعلی ارسال نشده است.']), 'application/json');
		elseif(!isset($_POST['sim']))
			$this->_sendResponse(400,CJSON::encode(['status' => 'failed','message'=> 'شماره سیم کارت ارسال نشده است.']), 'application/json');
	}

	public function actionDownloadApp($token)
	{
		$downloadToken = DownloadTokens::model()->findByAttributes(['token' => $token]);
		if($downloadToken && $downloadToken->request_time > (time() - (26 * 60 * 60))) {
			$copyFileName = 'gohar-v'.$downloadToken->app_version.'-'.$downloadToken->request_time.'.apk';
			if(!file_exists(Yii::getPathOfAlias('webroot').'/temp/'.$copyFileName)) {
				$downloadToken->delete();
				$this->_sendResponse(200, CJSON::encode(['getBaseLine'=>[['status' => 'failed', 'message' => 'نسخه جدید برنامه در سرور موجود نیست.لطفا مجددا درخواست کنید.']]]), 'application/json');
			}
			$fileLink = Yii::app()->createAbsoluteUrl('/temp/'.$copyFileName);
			$this->_sendResponse(200, CJSON::encode(['status' => 'success', 'directLink' => $fileLink]), 'application/json');
		}
		if($downloadToken)
			$downloadToken->delete();
		$this->_sendResponse(200, CJSON::encode(['status' => 'failed', 'message' => 'لینک منقضی شده است.']), 'application/json');
	}

	public function actionGetBaseLine()
	{
		$baseLine = SiteOptions::model()->findByAttributes(['name' => 'base_line']);
		if($baseLine)
			$this->_sendResponse(200, CJSON::encode(['status' => 'success', 'baseLine' => $baseLine->value]), 'application/json');
		else
			$this->_sendResponse(500, CJSON::encode(['status' => 'failed', 'message' => 'خط ارسال پیام در سیستم ثبت نشده است.']), 'application/json');
	}

	public function actionCheckNumber()
	{
		if(isset($_POST['sim']) && isset($_POST['activateCode'])) {
			Yii::import('users.models.*');
			$_POST['sim'] = strpos($_POST['sim'], '0') === 0 ? $_POST['sim'] : '0'.$_POST['sim'];
			$sim = strpos($_POST['sim'], '0') === 0 ? substr($_POST['sim'], 1) : $_POST['sim'];
			// Delete Old Activate messages From this Mobile number
			$criteria = new CDbCriteria();
			$criteria->compare('text', 'GoharActivate', true);
			$criteria->compare('sender', $sim);
			$criteria->addCondition('date <= :date');
			$criteria->params[':date'] = time() - 10 * 60;
			$criteria->order = 'date DESC';
			TextMessagesReceive::model()->deleteAll($criteria);
			//
			$criteria = new CDbCriteria();
			$criteria->compare('text', 'GoharActivate', true);
			$criteria->compare('sender', $sim);
			$criteria->addCondition('date >= :date');
			$criteria->params[':date'] = time() - 10 * 60;
			$criteria->order = 'date DESC';
			$messages = TextMessagesReceive::model()->findAll($criteria);
			$flag = false;
			if($messages) {
				foreach($messages as $message)
					if($message->text === $_POST['activateCode'])
						$flag = true;
			}
			if(!$flag)
				$this->_sendResponse(200, CJSON::encode(['status' => 'failed', 'sms_send' => false, 'message' => 'پیامک کد فعالسازی ارسال نشده است.']), 'application/json');
			$model = Users::model()->findByAttributes(array('mobile' => $_POST['sim']));
			if($model) {
				$model->password = null;
				$this->_sendResponse(200, CJSON::encode(['status' => 'success', 'isUser' => 1,'newUser' => 0, 'user' => $model]), 'application/json');
			} else {
				$signUpStatus = SiteOptions::model()->findByAttributes(['name' => 'signup_status']);
				if($signUpStatus->value == 1) {
					$model = new Users('app_insert');
					$model->mobile = $_POST['sim'];
					if($model->save())
						$this->_sendResponse(200, CJSON::encode(['status' => 'success', 'isUser' => 1, 'newUser' => 1, 'user' => $model]), 'application/json');
					else
						$this->_sendResponse(200, CJSON::encode(['status' => 'failed', 'isUser' => 0, 'message' => 'در ثبت نام مشکلی ایجاد شده است، لطفا مجددا تلاش کنید.', 'errors' => $model->errors]), 'application/json');
				}
				else
					$this->_sendResponse(200, CJSON::encode(['status' => 'success', 'isUser' => 0, 'signup_status' => 0, 'message' => 'متاسفانه در حال حاضر امکان عضویت جدید وجود ندارد، لطفا بعدا اقدام فرمایید.']), 'application/json');
			}
		} else
			$this->_sendResponse(400, CJSON::encode(['status' => 'failed', 'message' => 'شماره سیم کارت  یا کد فعالسازی ارسال نشده است.']), 'application/json');
	}

	public function actionCreateCeremony()
	{
		if(isset($_POST['Ceremony'])) {
			list($eventsController) = Yii::app()->createController('events');
			$eventsController->actionCreate(true);
		}else
			$this->_sendResponse(400, CJSON::encode(['status' => 'failed', 'message' => 'اطلاعات ثبت مراسم ارسال نشده است.']), 'application/json');
	}

	public function actionTest()
	{
		var_dump(1);exit;
	}
}
?>