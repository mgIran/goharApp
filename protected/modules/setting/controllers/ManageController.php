<?php

class ManageController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('update'),
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionUpdate()
    {
        $settings=SiteOptions::model()->findAll();

        $showEventMessage=null;
        foreach($settings as $setting) {
            /* @var $setting SiteOptions */
            if ($setting->name == 'show_event_message')
                $showEventMessage = $setting;
        }

        $this->render('update', array(
            'showEventMessage'=>$showEventMessage
        ));
    }
}