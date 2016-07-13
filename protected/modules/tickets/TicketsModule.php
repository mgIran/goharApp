<?php

class TicketsModule extends CWebModule
{
	public function init()
	{
		$this->setImport(array(
			'tickets.models.*',
			'tickets.components.*',
            'admins.models.*'
		));
	}

    public $controllerMap = array(
        'categories'=>'tickets.controllers.TicketsCategoriesController',
        'manage'=>'tickets.controllers.TicketsManageController',
        'content'=>'tickets.controllers.TicketsContentController',
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
