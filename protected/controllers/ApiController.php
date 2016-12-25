<?php
Yii::import('admins.models.*');
class ApiController extends Controller
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
			'RestAccessControl + getLastVer,downloadApp,checkNumber',
			'RestUserAccessControl + test, getList, create',
//			'RestAdminAccessControl +'
		);
	}

	public function actionGetList()
	{
		if(isset($_POST['entity']) && $entity = $_POST['entity'])
		{
			$criteria = new CDbCriteria();
			if(isset($_POST['lastId']) && !empty($_POST['lastId']) && $lastId = (int)$_POST['lastId'])
			{
				$criteria->addCondition('t.id > :last_id');
				$criteria->params[':last_id'] = $lastId;
			}
			switch (trim($entity)) {
				case 'Place':
					$list = UsersPlaces::model()->findAll($criteria);
					break;
				case 'Ceremony':
					$list = Events::model()->findAll($criteria);
					break;
				case 'Ticket':
					Yii::app()->getModule('tickets');
					$criteria->addCondition('user_id = :user_id');
					$criteria->params[':user_id'] = $this->loginArray['userID'];
					$list = Tickets::model()->with('ticketsContents')->findAll($criteria);
					break;
				case 'Notification':
					Yii::app()->getModule('notifications');
					$criteria->addCondition('send_date < :time AND  expire_date > :time');
					$criteria->params[':time'] = time();
					$list = Notifications::model()->findAll($criteria);
					break;
				default:
					$list = array();
					break;
			}
			if($list) {
				$this->_sendResponse(200, CJSON::encode(['status' => true, 'list' => $list]), 'application/json');
			}else
				$this->_sendResponse(400, CJSON::encode(['status' => false, 'message' => 'اطلاعاتی برای دریافت موجود نیست.']), 'application/json');
		}
	}

	/**
	 * Create Model from Entity
	 */
	public function actionCreate()
	{
		if (isset($_POST['entity']) && $entity = ucfirst(trim($_POST['entity']))) {
			if (isset($_POST[$entity])) {
				switch ($entity) {
					case 'Ceremony':
						$model = new Events;
						$model->attributes = $_POST[$entity];
						$model->creator_type = $this->loginArray['type'];
						$model->creator_id = $this->loginArray['userID'];
						if ($model->save())
							$this->_sendResponse(200, CJSON::encode(['status' => true, 'message' => 'مراسم با موفقیت ثبت شد.']), 'application/json');
						break;
					case 'Ticket':
						Yii::app()->getModule('tickets');
						$model = new Tickets();
						if ($this->loginArray['type'] == 'admin')
							$model->scenario = 'admin_insert';
						$model->attributes = $_POST[$entity];
						$model->status = Tickets::STATUS_NO_REPLY;
						$model->user_id = $this->loginArray['userID'];
						if ($model->save()) {
							$ticketsContentModel = new TicketsContent;
							$ticketsContentModel->attributes = $_POST[$entity];
							$ticketsContentModel->ticket_id = $model->id;
							$ticketsContentModel->text = $model->text;
							$ticketsContentModel->file = $model->file;
							$ticketsContentModel->save();
							$this->_sendResponse(200, CJSON::encode(['status' => true, 'message' => 'تیکت با موفقیت ارسال شد.']), 'application/json');
						}
						break;
					default:
						$this->_sendResponse(400, CJSON::encode(['status' => false, 'message' => 'موجودیت مورد نظر وجود ندارد.']), 'application/json');
						break;
				}
				$this->_sendResponse(400, CJSON::encode(['status' => false, 'message' => 'متاسفانه در ثبت اطلاعات خطایی رخ داده است.', 'errors' => $this->implodeErrors($model)]), 'application/json');
			}
			$this->_sendResponse(400, CJSON::encode(['status' => false, 'message' => 'اطلاعات ثبت ارسال نشده است.']), 'application/json');
		}
		$this->_sendResponse(400, CJSON::encode(['status' => false, 'message' => 'مقدار entity نمی تواند خالی باشد.']), 'application/json');
	}
	/**************************************************** Base Actions ***********************************************************/
	public function actionGetLastVer()
	{
		if(isset($_POST['version']) && isset($_POST['sim'])) {
			$baseLine = SiteOptions::model()->findByAttributes(['name' => 'base_line']);
			$lastVer = SiteOptions::model()->findByAttributes(['name' => 'app_version']);
			if($_POST['version'] == $lastVer->value)
				$this->_sendResponse(200, CJSON::encode(['status' => true, 'message' => 'نسخه نرم افزار به روز می باشد.',
					'serverTime' => time(),
					'baseLine' => $baseLine?$baseLine->value:false]), 'application/json');
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
				$this->_sendResponse(200, CJSON::encode(['status' => false, 'newVersionLink' => $fileLink,'serverTime' => time(), 'baseLine' => $baseLine?$baseLine->value:false]), 'application/json');
			}
		} elseif(!isset($_POST['version']))
			$this->_sendResponse(400,CJSON::encode(['status' => false,'message' => 'مقدار نسخه فعلی ارسال نشده است.']), 'application/json');
		elseif(!isset($_POST['sim']))
			$this->_sendResponse(400,CJSON::encode(['status' => false,'message'=> 'شماره سیم کارت ارسال نشده است.']), 'application/json');
	}

	public function actionDownloadApp($token)
	{
		$downloadToken = DownloadTokens::model()->findByAttributes(['token' => $token]);
		if($downloadToken && $downloadToken->request_time > (time() - (26 * 60 * 60))) {
			$copyFileName = 'gohar-v'.$downloadToken->app_version.'-'.$downloadToken->request_time.'.apk';
			if(!file_exists(Yii::getPathOfAlias('webroot').'/temp/'.$copyFileName)) {
				$downloadToken->delete();
				$this->_sendResponse(200, CJSON::encode(['getBaseLine'=>[['status' => false, 'message' => 'نسخه جدید برنامه در سرور موجود نیست.لطفا مجددا درخواست کنید.']]]), 'application/json');
			}
			$fileLink = Yii::app()->createAbsoluteUrl('/temp/'.$copyFileName);
			$this->_sendResponse(200, CJSON::encode(['status' => true, 'directLink' => $fileLink]), 'application/json');
		}
		if($downloadToken)
			$downloadToken->delete();
		$this->_sendResponse(200, CJSON::encode(['status' => false, 'message' => 'لینک منقضی شده است.']), 'application/json');
	}

	public function actionCheckNumber()
	{
		if(isset($_POST['token']) && isset($_POST['sim']) && isset($_POST['activateCode'])) {
			Yii::import('users.models.*');
			$_POST['sim'] = strpos($_POST['sim'], '0') === 0 ? $_POST['sim'] : '0'.$_POST['sim'];
			$sim = strpos($_POST['sim'], '0') === 0 ? substr($_POST['sim'], 1) : $_POST['sim'];
			// Delete Old Activate messages
			$criteria = new CDbCriteria();
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
				$this->_sendResponse(200, CJSON::encode(['status' => false, 'smsSend' => false, 'message' => 'پیامک کد فعالسازی ارسال نشده است.']), 'application/json');
			$model = Users::model()->findByAttributes(array('mobile' => $_POST['sim']));
			if($model) {
				$model->password = null;
				if(!$model->app_token || empty($model->app_token))
				{
					$model->scenario = 'app_update';
					if($model->createAppToken()->save())
						$this->_sendResponse(200, CJSON::encode(['status' => true,
							'isUser' => 1, 'newUser' => 0, 'userToken' => $model->app_token,
							'user' => $model]), 'application/json');
				}else
					$this->_sendResponse(200, CJSON::encode(['status' => true,
						'isUser' => 1, 'newUser' => 0, 'userToken' => $model->app_token,
						'user' => $model]), 'application/json');
			} else {
				$signUpStatus = SiteOptions::model()->findByAttributes(['name' => 'signup_status']);
				if($signUpStatus->value == 1) {
					$model = new Users('app_insert');
					$model->mobile = $_POST['sim'];
					if($model->createAppToken()->save())
						$this->_sendResponse(200, CJSON::encode(['status' => true,
							'isUser' => 1, 'newUser' => 1, 'userToken' => $model->app_token,
							'user' => $model]), 'application/json');
					else
						$this->_sendResponse(200, CJSON::encode(['status' => false, 'isUser' => 0, 'message' => 'در ثبت نام مشکلی ایجاد شده است، لطفا مجددا تلاش کنید.', 'errors' => $model->errors]), 'application/json');
				}
				else
					$this->_sendResponse(200, CJSON::encode(['status' => true, 'isUser' => 0, 'signupStatus' => false, 'message' => 'متاسفانه در حال حاضر امکان عضویت جدید وجود ندارد، لطفا بعدا اقدام فرمایید.']), 'application/json');
			}
		} else
			$this->_sendResponse(400, CJSON::encode(['status' => false, 'message' => 'شماره سیم کارت  یا کد فعالسازی ارسال نشده است.']), 'application/json');
	}

	public function actionTest()
	{
		var_dump($this->loginArray);exit;
	}
}