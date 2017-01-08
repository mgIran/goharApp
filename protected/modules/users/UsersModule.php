<?php
class UsersModule extends CWebModule
{
	public
        $path = null,
        $verification = NULL,
        $deletePermanently = FALSE,
        $enablePlan = FALSE,
        $enableLegalDocuments = FALSE,
        $enableBankDetails = FALSE;

    public $controllerMap = array(
        'login'=>'users.controllers.UsersLoginController',
        'manage'=>'users.controllers.UsersManageController',
        'roles'=>'users.controllers.UsersRolesController',
        'account'=>'users.controllers.UsersAccountController',
        'places' => 'users.controllers.UsersPlacesController',
    );
	public function init(){
        if($this->enableLegalDocuments){
            $this->controllerMap['documents'] = 'users.controllers.UsersLegalDocumentsController';
            $this->controllerMap['places'] = 'users.controllers.UsersPlacesController';
        }

        if($this->enableBankDetails){
            $this->controllerMap['bank'] = 'users.controllers.UsersBankDetailsController';
        }

		$this->setImport(array(
			'users.models.*',
            'users.components.*',
		));

        Yii::app()->user->loginUrl = Yii::app()->baseUrl.'/users/account/index';
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			return true;
		}
		else
			return false;
	}
}
