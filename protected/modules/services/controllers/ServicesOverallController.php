<?php
if(!@class_exists('ServicesController'))
    Yii::import('application.modules.services.components.ServicesController');
class ServicesOverallController extends ServicesController {
    protected $_type = 4;
    public static $actionsArray =
        array(
            'title' => 'نوبت دهی',
            'menu' => TRUE,
            'menu_name' => 'services_overall',
            'module' => 'services',
            'type' => 'user',
            'index' => array(
                'title' => 'لیست نوبت دهی ها',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'services_overall',
                'menu_name' => 'services_overall_index',
                'url' => 'services/overall',
            ),
            'create' => array(
                'title' => 'ایجاد نوبت دهی',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'services_overall',
                'menu_name' => 'services_overall_create',
                'url' => 'services/overall/create',
                'otherActions'=> 'delete,view',
            )
        );
}
