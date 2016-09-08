<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 * @property SearchForm $searchModel
 */
class Controller extends CController
{
    /**
     * @var string the default layout for the controller views. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '//layouts/column1';

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public $town = null;
    public $place = null;
    public $description;
    public $keywords;

    public $siteName;
    public $pageTitle;

    public $searchModel;
    public $sideRender = null;
    public $message = null;
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&views=FileName
            'page'=>array(
                'class'=>'CViewAction',
            ),
        );
    }

    public function init(){
        Yii::app()->clientScript->registerScript('js-requirement','
            var baseUrl = "'.Yii::app()->getBaseUrl(true).'";
        ',CClientScript::POS_HEAD);
        // set default meta tag values
        parent::init();
    }

    /**
     * @param $model
     * @return string
     */
    public static function implodeErrors($model)
    {
        $errors = '';
        foreach($model->getErrors() as $err){
            $errors .= implode('<br>' ,$err) . '<br>';
        }
        return $errors;
    }

    public static function generateRandomString($length = 20)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for($i = 0;$i < $length;$i++){
            $randomString .= $characters[rand(0 ,$charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Converts latin numbers to farsi script
     */
    public static function parseNumbers($matches)
    {
        $farsi_array = array('Û°' ,'Û±' ,'Û²' ,'Û³' ,'Û´' ,'Ûµ' ,'Û¶' ,'Û·' ,'Û¸' ,'Û¹');
        $english_array = array('0' ,'1' ,'2' ,'3' ,'4' ,'5' ,'6' ,'7' ,'8' ,'9');

        return str_replace($english_array ,$farsi_array ,$matches);
    }

    public static function fileSize($file){
        $size = filesize($file);
        if($size < 1024)
            return $size.' Byte';
        elseif($size < 1024*1024){
            $size = (float)$size/1024;
            return number_format($size,1). ' KB';
        }
        elseif($size < 1024*1024*1024){
            $size = (float)$size/(1024*1024);
            return number_format($size,1). ' MB';
        }else
        {
            $size = (float)$size/(1024*1024*1024);
            return number_format($size,1). ' GB';
        }
    }

    // for rest api

    protected function _sendResponse($status = 200, $body = '', $content_type = 'text/html')
    {
        // set the status
        $status_header = 'HTTP/1.1 '.$status.' '.$this->_getStatusCodeMessage($status);
        header($status_header);
        // and the content type
        header('Content-type: '.$content_type);

        // pages with body are easy
        if($body != '') {
            // send the body
            echo $body;
        } // we need to create the body if none is passed
        else {
            // create some body messages
            $message = '';

            // this is purely optional, but makes the pages a little nicer to read
            // for your users.  Since you won't likely send a lot of different status codes,
            // this also shouldn't be too ponderous to maintain
            switch($status) {
                case 401:
                    $message = 'You must be authorized to view this page.';
                    break;
                case 404:
                    $message = 'The requested URL '.$_SERVER['REQUEST_URI'].' was not found.';
                    break;
                case 500:
                    $message = 'The server encountered an error processing your request.';
                    break;
                case 501:
                    $message = 'The requested method is not implemented.';
                    break;
            }

            // servers don't always have a signature turned on
            // (this is an apache directive "ServerSignature On")
            $signature = ($_SERVER['SERVER_SIGNATURE'] == '') ? $_SERVER['SERVER_SOFTWARE'].' Server at '.$_SERVER['SERVER_NAME'].' Port '.$_SERVER['SERVER_PORT'] : $_SERVER['SERVER_SIGNATURE'];

            // this should be templated in a real-world solution
            $body = '
				<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
				<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
					<title>'.$status.' '.$this->_getStatusCodeMessage($status).'</title>
				</head>
				<body>
					<h1>'.$this->_getStatusCodeMessage($status).'</h1>
					<p>'.$message.'</p>
					<hr />
					<address>'.$signature.'</address>
				</body>
				</html>';

            echo $body;
        }
        Yii::app()->end();
    }

    protected function _getStatusCodeMessage($status)
    {
        // these could be stored in a .ini file and loaded
        // via parse_ini_file()... however, this will suffice
        // for an example
        $codes = Array(
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }

    /**
     * @param string $userType
     * return bool
     */
    protected function _checkAuth($userType = 'admin')
    {
        // Check if we have the USERNAME and PASSWORD HTTP headers set?
        if(!(isset($_SERVER['HTTP_X_USERNAME']) and isset($_SERVER['HTTP_X_PASSWORD'])) and !(isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW']))) {
            // Error: Unauthorized
            $this->_sendResponse(401);
        }

        if(isset($_SERVER['HTTP_X_USERNAME']) and isset($_SERVER['HTTP_X_PASSWORD'])) {
            $username = $_SERVER['HTTP_X_USERNAME'];
            $password = $_SERVER['HTTP_X_PASSWORD'];
        } elseif(isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW'])) {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
        }
        // Find the user
        if(!Yii::app()->user || Yii::app()->user->isGuest) {
            if(isset($username) && isset($password)) {
                if($userType == 'admin') {
                    Yii::app()->getModule('admins');
                    $model = new AdminLoginForm;
                } elseif($userType == 'user') {
                    Yii::app()->getModule('users');
                    $model = new UserLoginForm;
                }
                $model->username = $username;
                $model->password = $password;
                if(!($model->validate() && $model->login())) {
                    // Error: Unauthorized
                    $this->_sendResponse(401, 'Error: User Name or Password is invalid');
                }
            } else
                $this->_sendResponse(401);
        }
        return true;
    }

    /**
     * The filter method for 'restAccessControl' filter.
     * This filter throws an exception (CHttpException with code 400) if the applied action is receiving a non-AJAX request.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     * @throws CHttpException if the current request is not an AJAX request.
     */
    public function filterRestAccessControl($filterChain)
    {
        if($this->_checkAuth('user'))
            $filterChain->run();
        else
            throw new CHttpException(400,Yii::t('yii','Your request is invalid.'));
    }

    /**
     * The filter method for 'restAccessControl' filter.
     * This filter throws an exception (CHttpException with code 400) if the applied action is receiving a non-AJAX request.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     * @throws CHttpException if the current request is not an AJAX request.
     */
    public function filterRestAdminAccessControl($filterChain)
    {
        if($this->_checkAuth('admin'))
            $filterChain->run();
        else
            throw new CHttpException(400,Yii::t('yii','Your request is invalid.'));
    }
}