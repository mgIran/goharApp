<?php
if(!@class_exists('ServicesController'))
    Yii::import('application.modules.services.components.ServicesController');
class ServicesJoiningController extends ServicesController {
    protected $_type = 5;
    public static $actionsArray =
        array(
            'title' => 'عضو گیری',
            //'module' => 'contacts',
            'type' => 'user',
            'index' => array(
                'title' => 'لیست عضوگیری',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'contacts',
                'menu_name' => 'contacts_joining_index',
                'url' => 'services/joining',
            ),
            'create' => array(
                'title' => 'ایجاد عضوگیری',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'contacts',
                'menu_name' => 'contacts_joining_create',
                'url' => 'services/joining/create',
                'otherActions'=> 'delete,view',
            )
        );
}
