<?php
if(!@class_exists('ServicesController'))
    Yii::import('application.modules.services.components.ServicesController');
class ServicesPollsController extends ServicesController {
    protected $_type = 1;
    public static $actionsArray =
        array(
            'title' => 'نظرسنجی',
            'menu' => TRUE,
            'menu_name' => 'services_polls',
            'module' => 'services',
            'type' => 'user',
            'index' => array(
                'title' => 'لیست نظرسنجی ها',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'services_polls',
                'menu_name' => 'services_polls_index',
                'url' => 'services/polls',
            ),
            'create' => array(
                'title' => 'ایجاد نظرسنجی',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'services_polls',
                'menu_name' => 'services_polls_create',
                'url' => 'services/polls/create',
                'otherActions'=> 'delete,view',
            )
        );
}
