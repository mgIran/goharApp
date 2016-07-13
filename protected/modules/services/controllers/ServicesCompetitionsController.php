<?php

if(!@class_exists('ServicesController'))
    Yii::import('application.modules.services.components.ServicesController');
class ServicesCompetitionsController extends ServicesController {
    protected $_type = 2;
    public static $actionsArray =
        array(
            'title' => 'مسابقه',
            'menu' => TRUE,
            'menu_name' => 'services_competitions',
            'module' => 'services',
            'type' => 'user',
            'index' => array(
                'title' => 'لیست مسابقات',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'services_competitions',
                'menu_name' => 'services_competitions_index',
                'url' => 'services/competitions',
            ),
            'create' => array(
                'title' => 'ایجاد مسابقه',
                'type' => 'user',
                'menu' => TRUE,
                'menu_parent' => 'services_competitions',
                'menu_name' => 'services_competitions_create',
                'url' => 'services/competitions/create',
                'otherActions'=> 'delete,view',
            )
        );
}
