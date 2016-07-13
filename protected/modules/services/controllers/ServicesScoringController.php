<?php
if(!@class_exists('ServicesController'))
    Yii::import('application.modules.services.components.ServicesController');
class ServicesScoringController extends ServicesController {
    protected $_type = 3;
    public static $actionsArray =
        array(
            'title' => 'امتیازدهی',
            'menu' => TRUE,
            'menu_name' => 'services_scoring',
            'module' => 'services',
            'type' => 'user',
            'index' => array(
                'title' => 'لیست امتیازدهی ها',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'services_scoring',
                'menu_name' => 'services_scoring_index',
                'url' => 'services/scoring',
            ),
            'create' => array(
                'title' => 'ایجاد امتیازدهی',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'services_scoring',
                'menu_name' => 'services_scoring_create',
                'url' => 'services/scoring/create',
                'otherActions'=> 'delete,view',
            )
        );
}
