<?php

class AgentModule extends CWebModule
{
    public $path = null;
    public function init()
    {
        $this->setImport(array(
            'users.models.*',
            'agent.models.*',
        ));

    }

    public $controllerMap = array(
        'manage' => 'agent.controllers.AgentController',
        'admin' => 'agent.controllers.AgentAdminController',
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

