<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 *
 * @property [] $loginArray
 */
class ApiBaseController extends CController
{
    private $_token = '$2a$12$AK01s106Iqf7utPhANEf7uG5qup61kIPXoToAges5qo43Rm8mb28a';
    /**
     * @var array of user login detail
     */
    private $_loginArray = array();

    /**
     * @param string $token
     * @return bool
     */
    private function _checkToken()
    {
        if(!isset($_POST['token'])) {
            $this->_sendResponse(401);
        }
        $token = $_POST['token'];
        // Check if we have the USERNAME and PASSWORD HTTP headers set?
        if(!$token || $token !== $this->_token) {
            $this->_sendResponse(401, 'Error: Token is not valid.');
        }
        return true;
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
                    $message = 'You must send token for authorized to view this page.';
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
        if(!isset($_POST['token'])) {
            $this->_sendResponse(401);
        }
        $token = $_POST['token'];
        // Find the user
        $model = NULL;
        $identity = NULL;
        if($userType == 'admin') {
            Yii::app()->getModule('admins');
            $identity = new ModulesAdminIdentity($token);
            $identity->authMode = $identity::TOKEN;
            $identity->authenticate();
            $this->_loginArray = $identity->getAppLoginArray();
        } elseif($userType == 'user') {
            Yii::app()->getModule('users');
            $identity = new ModulesUserIdentity($token);
            $identity->authMode = $identity::TOKEN;
            $identity->authenticate();
            $this->_loginArray = $identity->getAppLoginArray();
        }
        if($identity && $identity->errorCode===$identity::ERROR_NONE && $this->getLoginArray())
            return true;
        elseif($identity && $identity->errorCode!==$identity::ERROR_NONE)
            $this->_sendResponse(401, 'Error: '.$identity->getErrorMessage());
        elseif(!$this->getLoginArray())
            $this->_sendResponse(401, 'Error: Not Authorized.');
        return true;
    }

    /**
     * The filter method for 'restAccessControl' filter.
     * This filter throws an exception (CHttpException with code 400) if the applied action is receiving a non-AJAX request.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     * @throws CHttpException if the current request is not an AJAX request.
     */
    public function filterRestUserAccessControl($filterChain)
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

    /**
     * The filter method for 'restAccessControl' filter.
     * This filter throws an exception (CHttpException with code 400) if the applied action is receiving a non-AJAX request.
     * @param CFilterChain $filterChain the filter chain that the filter is on.
     * @throws CHttpException if the current request is not an AJAX request.
     */
    public function filterRestAccessControl($filterChain)
    {
        if($this->_checkToken())
            $filterChain->run();
        else
            throw new CHttpException(400,Yii::t('yii','Your request is invalid.'));
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @return array get $_loginArray Property
     */
    public function getLoginArray()
    {
        return $this->_loginArray;
    }
}