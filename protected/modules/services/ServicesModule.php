<?php

class ServicesModule extends CWebModule
{
    public $path = null;
    public function init()
    {
        $this->setImport(array(
            'services.models.*',
            'services.components.*',
        ));

    }

    public $controllerMap = array(
        'polls' => 'services.controllers.ServicesPollsController',
        'competitions' => 'services.controllers.ServicesCompetitionsController',
        'scoring' => 'services.controllers.ServicesScoringController',
        'joining' => 'services.controllers.ServicesJoiningController',
        'overall' => 'services.controllers.ServicesOverallController',
    );

    public function beforeControllerAction($controller, $action)
    {
//        if(isset(Yii::app()->user->type) AND Yii::app()->user->type == 'admin')
//            $controller->menu = array(
//                array('label'=>'ارسال ایمیل', 'url'=>array('/messages/emails_send/email')),
//        );
        if(parent::beforeControllerAction($controller, $action))
        {
            return true;
        }
        else
            return false;
    }
}

