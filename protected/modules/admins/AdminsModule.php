<?php
class AdminsModule extends CWebModule
{
	public $path = null;
	public function init()
	{
		$this->setImport(array(
			'admins.models.*',
            'admins.components.*',
		));
//        Yii::app()->defaultController = 'index';
//        Yii::app()->user->loginUrl = '../login/index';
        Yii::app()->user->loginUrl = Yii::app()->baseUrl.'/admins/login/dashboard';

    }

    public $controllerMap = array(
        'login'=>'admins.controllers.AdminsLoginController',
        'manage'=>'admins.controllers.AdminsManageController',
        'roles'=>'admins.controllers.AdminsRolesController',
    );

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
