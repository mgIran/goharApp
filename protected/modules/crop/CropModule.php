<?php
class CropModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
            'crop.components.*',
		));
	}

    public $controllerMap = array(
        'default'=>'crop.controllers.CropDefaultController',
    );

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
			return true;
		else
			return false;
	}
}
