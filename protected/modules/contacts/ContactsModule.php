<?php

class ContactsModule extends CWebModule
{
    public $path = null;
    public function init()
    {
        $this->setImport(array(
            'contacts.models.*',
            'contacts.components.*',
        ));

    }

    public $controllerMap = array(
        'manage' => 'contacts.controllers.ContactsController',
        'categories' => 'contacts.controllers.ContactsCategoriesController',
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

