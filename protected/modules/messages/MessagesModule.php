<?php

class MessagesModule extends CWebModule
{
    public $path = null;
    public function init()
    {
        $this->setImport(array(
            'messages.models.*',
            'messages.components.*',
        ));

    }

    public $controllerMap = array(
        'emails_send' => 'messages.controllers.MessagesEmailsSendController',
        'emails' => 'messages.controllers.MessagesEmailsBankController',
        'emails_categories' => 'messages.controllers.MessagesEmailsBankCategoriesController',
        'emails_drafts' => 'messages.controllers.MessagesEmailsDraftsController',
        'emails_templates' => 'messages.controllers.MessagesEmailsTemplatesController',

        'mobiles' => 'messages.controllers.MessagesMobilesBankController',
        'mobiles_categories' => 'messages.controllers.MessagesMobilesBankCategoriesController',
        'texts_send' => 'messages.controllers.MessagesTextsSendController',
        'texts_drafts' => 'messages.controllers.MessagesTextsDraftsController',
        'texts_inbox' => 'messages.controllers.MessagesTextsInboxController',
        'texts_buy' => 'messages.controllers.MessagesTextsBuyController',
        'numbers_prefix' => 'messages.controllers.MessagesTextsNumbersPrefixController',
        'numbers_specials' => 'messages.controllers.MessagesTextsNumbersSpecialsController',
        'numbers_buy' => 'messages.controllers.MessagesTextsNumbersBuyController',
        'numbers_price' => 'messages.controllers.MessagesTextsNumbersBasePricesController',
        'numbers' => 'messages.controllers.MessagesTextsUsersNumbersController',
        /*'login'=>'users.controllers.UsersLoginController',
        'manage'=>'users.controllers.UsersManageController',
        'roles'=>'users.controllers.UsersRolesController',
        'account'=>'users.controllers.UsersAccountController',*/
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

