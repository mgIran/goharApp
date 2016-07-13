<?php
Yii::import('admins.models.*');
class ApiController extends Controller
{
	// Members
	/**
	 * Key which has to be in HTTP USERNAME and PASSWORD headers
	 */
	Const APPLICATION_ID = 'ASCCPE';

	/**
	 * Default response format
	 * either 'json' or 'xml'
	 */
	private $format = 'json';
	private $_token = '$2a$12$AK01s106Iqf7utPhANEf7uG5qup61kIPXoToAges5qo43Rm8mb28a';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array();
	}

	// Actions
	public function actionLogin()
	{
		$this->_checkAuth();
	}

	public function actionList()
	{
		// Get the respective model instance
		switch($_GET['model']) {
			case 'admins':
				Yii::import('admins.models.*');
				$models = Admins::model()->findAll();
				break;
			default:
				// Model not implemented error
				$this->_sendResponse(501, sprintf(
						'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
						$_GET['model']));
				Yii::app()->end();
		}
		// Did we get some results?
		if(empty($models)) {
			// No
			$this->_sendResponse(200,
					sprintf('No items where found for model <b>%s</b>', $_GET['model']));
		} else {
			// Prepare response
			$rows = array();
			foreach($models as $model)
				$rows[] = $model->attributes;
			// Send the response
			$this->_sendResponse(200, CJSON::encode($rows));
		}
	}

	public function actionView()
	{
		// Check if id was submitted via GET
		if(!isset($_GET['id']))
			$this->_sendResponse(500, 'Error: Parameter <b>id</b> is missing');

		switch($_GET['model']) {
			// Find respective model
			case 'admins':
				$model = Admins::model()->findByPk($_GET['id']);
				break;
			default:
				$this->_sendResponse(501, sprintf(
						'Mode <b>view</b> is not implemented for model <b>%s</b>',
						$_GET['model']));
				Yii::app()->end();
		}
		// Did we find the requested model? If not, raise an error
		if(is_null($model))
			$this->_sendResponse(404, 'No Item found with id '.$_GET['id']);
		else
			$this->_sendResponse(200, CJSON::encode($model));
	}

	public function actionCreate()
	{
		switch($_GET['model']) {
			// Get an instance of the respective model
			case 'admins':
				Yii::import('admins.models.*');
				$model = new Admins();
				break;
			default:
				$this->_sendResponse(501,
						sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
								$_GET['model']));
				Yii::app()->end();
		}
		// Try to assign POST values to attributes
		foreach($_POST as $var => $value) {
			// Does the model have this attribute? If not raise an error
			if($model->hasAttribute($var))
				$model->$var = $value;
			else
				$this->_sendResponse(500,
						sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
								$_GET['model']));
		}
		// Try to save the model
		if($model->save())
			$this->_sendResponse(200, CJSON::encode($model));
		else {
			// Errors occurred
			$msg = "<h1>Error</h1>";
			$msg .= sprintf("Couldn't create model <b>%s</b>", $_GET['model']);
			$msg .= "<ul>";
			foreach($model->errors as $attribute => $attr_errors) {
				$msg .= "<li>Attribute: $attribute</li>";
				$msg .= "<ul>";
				foreach($attr_errors as $attr_error)
					$msg .= "<li>$attr_error</li>";
				$msg .= "</ul>";
			}
			$msg .= "</ul>";
			$this->_sendResponse(500, $msg);
		}
	}

	public function actionUpdate()
	{
		// Parse the PUT parameters. This didn't work: parse_str(file_get_contents('php://input'), $put_vars);
		$json = file_get_contents('php://input'); //$GLOBALS['HTTP_RAW_POST_DATA'] is not preferred: http://www.php.net/manual/en/ini.core.php#ini.always-populate-raw-post-data
		$put_vars = CJSON::decode($json, true);  //true means use associative array

		switch($_GET['model']) {
			// Find respective model
			case 'admins':
				$model = Admins::model()->findByPk($_GET['id']);
				break;
			default:
				$this->_sendResponse(501,
						sprintf('Error: Mode <b>update</b> is not implemented for model <b>%s</b>',
								$_GET['model']));
				Yii::app()->end();
		}
		// Did we find the requested model? If not, raise an error
		if($model === null)
			$this->_sendResponse(400,
					sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
							$_GET['model'], $_GET['id']));

		// Try to assign PUT parameters to attributes
		foreach($put_vars as $var => $value) {
			// Does model have this attribute? If not, raise an error
			if($model->hasAttribute($var))
				$model->$var = $value;
			else {
				$this->_sendResponse(500,
						sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
								$var, $_GET['model']));
			}
		}
		// Try to save the model
		if($model->save())
			$this->_sendResponse(200, CJSON::encode($model));
		else
			$this->_sendResponse(500, $this->implodeErrors($model));
	}

	public function actionDelete()
	{
		switch($_GET['model']) {
			// Load the respective model
			case 'admins':
				$model = Admins::model()->findByPk($_GET['id']);
				break;
			default:
				$this->_sendResponse(501,
						sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>',
								$_GET['model']));
				Yii::app()->end();
		}
		// Was a model found? If not, raise an error
		if($model === null)
			$this->_sendResponse(400,
					sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
							$_GET['model'], $_GET['id']));

		// Delete the model
		$num = $model->delete();
		if($num > 0)
			$this->_sendResponse(200, $num);    //this is the only way to work with backbone
		else
			$this->_sendResponse(500,
					sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
							$_GET['model'], $_GET['id']));
	}


	public function actionGetLastVer(){
		if(isset($_POST['version']) && isset($_POST['sim'])) {
			$lastVer = SiteOptions::model()->findByAttributes(['name'=>'app_version']);
			if($_POST['version'] == $lastVer->value)
				$this->_sendResponse(200,CJSON::encode(['status' => 'success','message' => 'نسخه نرم افزار به روز می باشد.']),'application/json');
			else
            {
                $fileName = 'gohar-v'.$lastVer->value.'.apk';
                $downloadToken = DownloadTokens::model()->findByAttributes(['app_version'=>$lastVer->value,'sim'=>$_POST['sim']]);
                if(!$downloadToken) {
                    $downloadToken = new DownloadTokens();
                    $downloadToken->app_version = $lastVer->value;
                    $downloadToken->sim = $_POST['sim'];
                    $downloadToken->request_time = time();
                    $downloadToken->token = sha1(md5($downloadToken->app_version.$downloadToken->sim.time()));
                    $downloadToken->save();
                    $copyFileName = 'gohar-v'.$lastVer->value.'-'.$downloadToken->request_time.'.apk';
                    if(file_exists(Yii::getPathOfAlias('webroot').'/uploads/app/'.$fileName))
                        @copy(Yii::getPathOfAlias('webroot').'/uploads/app/'.$fileName,Yii::getPathOfAlias('webroot').'/temp/'.$copyFileName);
                }
                elseif($downloadToken && $downloadToken->request_time < (time()-(24*60*60))){
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
                        @copy(Yii::getPathOfAlias('webroot').'/uploads/app/'.$fileName,Yii::getPathOfAlias('webroot').'/temp/'.$copyFileName);
                }
                $fileLink = Yii::app()->createAbsoluteUrl('/api/downloadApp/'.$downloadToken->token);
                $this->_sendResponse(200,CJSON::encode(['status' => 'failed','newVersionLink' => $fileLink]),'application/json');
            }
		}
		elseif(!isset($_POST['version']))
			$this->_sendResponse(400,'مقدار نسخه فعلی ارسال نشده است.');
		elseif(!isset($_POST['sim']))
			$this->_sendResponse(400,'شماره سیم کارت ارسال نشده است.');
	}

    public function actionDownloadApp($token)
    {
        $downloadToken = DownloadTokens::model()->findByAttributes(['token' => $token]);
        if($downloadToken && $downloadToken->request_time > (time() - (26 * 60 * 60))) {
            $copyFileName = 'gohar-v'.$downloadToken->app_version.'-'.$downloadToken->request_time.'.apk';
            if(!file_exists(Yii::getPathOfAlias('webroot').'/temp/'.$copyFileName))
            {
                $downloadToken->delete();
                $this->_sendResponse(200, CJSON::encode(['status' => 'failed', 'message' => 'نسخه جدید برنامه در سرور موجود نیست.لطفا مجددا درخواست کنید.']), 'application/json');
            }
            $fileLink = Yii::app()->createAbsoluteUrl('/temp/'.$copyFileName);
            $this->_sendResponse(200, CJSON::encode(['status' => 'success', 'directLink' => $fileLink]), 'application/json');
        }
		if($downloadToken)
			$downloadToken->delete();
        $this->_sendResponse(200, CJSON::encode(['status' => 'failed', 'message' => 'لینک منقضی شده است.']), 'application/json');
    }

    public function actionGetBaseLine(){
        $baseLine = SiteOptions::model()->findByAttributes(['name'=>'base_line']);
        if($baseLine)
            $this->_sendResponse(200,CJSON::encode(['status' => 'success','baseLine' => $baseLine->value]),'application/json');
        else
            $this->_sendResponse(500,CJSON::encode(['status' => 'failed','message' => 'خط ارسال پیام در سیستم ثبت نشده است.']),'application/json');
	}

    public function actionCheckNumber()
	{
		if(isset($_POST['sim'])) {
			Yii::import('users.models.*');
			$_POST['sim'] = strpos($_POST['sim'],'0') === 0?$_POST['sim']:'0'.$_POST['sim'];
			$model = Users::model()->findByAttributes(array('mobile' => $_POST['sim']));
			if($model)
			{
				$model->password = null;
				$this->_sendResponse(200, CJSON::encode(['status' => 'success' ,'isUser'=> 1 , 'user' => $model]), 'application/json');
			}
			else {
				$signUpStatus = SiteOptions::model()->findByAttributes(['name' => 'signup_status']);
				if($signUpStatus->value == 1)
					$this->_sendResponse(200, CJSON::encode(['status' => 'success' ,'isUser'=> 0 , 'signup_status' => 1, 'message' => 'امکان عضویت وجود دارد.']), 'application/json');
				else
					$this->_sendResponse(200, CJSON::encode(['status' => 'success' ,'isUser'=> 0 , 'signup_status' => 0, 'message' => 'متاسفانه در حال حاضر امکان عضویت جدید وجود ندارد، لطفا بعدا اقدام فرمایید.']), 'application/json');
			}
		} else
			$this->_sendResponse(400, CJSON::encode(['status' => 'failed', 'message' => 'شماره سیم کارت ارسال نشده است.']), 'application/json');
	}

	public function actionRegisterUser(){
        if(isset($_POST['sim']) && isset($_POST['activateCode'])) {
			Yii::import('users.models.*');
			$model = Users::model()->findByAttributes(array('mobile' => $_POST['sim']));
			if($model)
				$this->_sendResponse(200, CJSON::encode(['status' => 'isUser', 'user_id' => $model->id]), 'application/json');
			else
			{
				$signUpStatus = SiteOptions::model()->findByAttributes(['name'=>'signup_status']);
				if($signUpStatus->value == 1)
				{
					$model = new Users();

				}
				else
					$this->_sendResponse(200, CJSON::encode(['status' => 'notUser', 'signup_status' => 0 , 'message' => 'متاسفانه در حال حاضر امکان عضویت جدید وجود ندارد، لطفا بعدا اقدام فرمایید.']), 'application/json');
			}
        }
        else
			$this->_sendResponse(400,CJSON::encode(['status' => 'failed','message' => 'شماره سیم کارت  یا کد فعالسازی ارسال نشده است.']),'application/json');
	}

    protected function beforeAction($action) {
//        if(!isset($_POST['token']))
//            $this->_sendResponse(400,'Token Not Found.');
//        $this->_checkToken($_POST['token']);
        return parent::beforeAction($action);
    }

    /**
     * @param string $token
     * @return bool
     */
    private function _checkToken($token)
    {
        // Check if we have the USERNAME and PASSWORD HTTP headers set?
        if(!$token || $token !== $this->_token) {
            $this->_sendResponse(500,'Token is not valid.');
        }
        return true;
    }
}