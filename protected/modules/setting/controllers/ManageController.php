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
                'actions' => array('update', 'upload', 'deleteUpload'),
                'users' => array('admin'),
            ),
            array('deny',  // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actions()
    {
        return array(
            'upload' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'attribute' => 'gohar_yab_program',
                'rename' => 'none',
                'validateOptions' => array(
                    'acceptedTypes' => array('apk')
                )
            ),
            'deleteUpload' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'SiteOptions',
                'attribute' => 'value',
                'uploadDir' => '/uploads/app',
                'storedMode' => 'field'
            ),
        );
    }

    public function actionUpdate()
    {
        $settings = SiteOptions::model()->findAll();

        $errors=array();

        $showEventMessage = null;
        $showEvent = null;
        $showEventMoreThanDefaultPrice = null;
        $showEventMoreThanDefault = null;
        $eventMaxLongDays = null;
        $showEventArrivedDeadline = null;
        $submitGeneralEvents = null;
        $goharYabProgram = null;
        $baseLine = null;
        $appVersion = null;

        foreach ($settings as $setting) {
            /* @var $setting SiteOptions */
            if ($setting->name == 'show_event_message')
                $showEventMessage = $setting;
            elseif ($setting->name == 'show_event')
                $showEvent = $setting;
            elseif ($setting->name == 'show_event_more_than_default_price')
                $showEventMoreThanDefaultPrice = $setting;
            elseif ($setting->name == 'show_event_more_than_default')
                $showEventMoreThanDefault = $setting;
            elseif ($setting->name == 'event_max_long_days')
                $eventMaxLongDays = $setting;
            elseif ($setting->name == 'show_event_arrived_deadline')
                $showEventArrivedDeadline = $setting;
            elseif ($setting->name == 'submit_general_events')
                $submitGeneralEvents = $setting;
            elseif ($setting->name == 'gohar_yab_program')
                $goharYabProgram = $setting;
            elseif ($setting->name == 'base_line')
                $baseLine = $setting;
            elseif ($setting->name == 'app_version')
                $appVersion = $setting;
        }

        $tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        if (!is_dir($tmpDIR))
            mkdir($tmpDIR);
        $tmpUrl = Yii::app()->baseUrl . '/uploads/temp/';
        $programDIR = Yii::getPathOfAlias("webroot") . "/uploads/app/";
        if (!is_dir($programDIR))
            mkdir($programDIR);
        $programUrl = Yii::app()->baseUrl . '/uploads/app/';

        $program = array();

        if (isset($_POST['submit'])) {
            if (isset($_POST['showEventMessage'])) {
                $showEventMessage->value = CJSON::encode($_POST['showEventMessage']);

                if (!$showEventMessage->save())
                    foreach ($showEventMessage->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['showEvent'])) {
                $showEvent->value = CJSON::encode($_POST['showEvent']);

                if (!$showEvent->save())
                    foreach ($showEvent->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['showEventMoreThanDefaultPrice'])) {
                $showEventMoreThanDefaultPrice->value = $_POST['showEventMoreThanDefaultPrice'];

                if (!$showEventMoreThanDefaultPrice->save())
                    foreach ($showEventMoreThanDefaultPrice->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['eventMaxLongDays'])) {
                $eventMaxLongDays->value = $_POST['eventMaxLongDays'];

                if (!$eventMaxLongDays->save())
                    foreach ($eventMaxLongDays->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['showEventArrivedDeadline'])) {
                $showEventArrivedDeadline->value = $_POST['showEventArrivedDeadline'];

                if (!$showEventArrivedDeadline->save())
                    foreach ($showEventArrivedDeadline->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['submitGeneralEvents'])) {
                $submitGeneralEvents->value = $_POST['submitGeneralEvents'];

                if (!$submitGeneralEvents->save())
                    foreach ($submitGeneralEvents->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['baseLine'])) {
                $baseLine->value = $_POST['baseLine'];

                if (!$baseLine->save())
                    foreach ($baseLine->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['appVersion'])) {
                $appVersion->value = $_POST['appVersion'];

                if (!$appVersion->save())
                    foreach ($appVersion->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['gohar_yab_program'])) {
                $goharYabProgram->value = 'gohar-v'.$appVersion->value.'.apk';

                if (isset($_POST['gohar_yab_program']) and file_exists($tmpDIR . $_POST['gohar_yab_program'])) {
                    $file = $_POST['gohar_yab_program'];
                    $program = array(
                        'name' => $file,
                        'src' => $tmpUrl . '/' . $file,
                        'size' => filesize($tmpDIR . $file),
                        'serverName' => $file,
                    );
                }

                if ($goharYabProgram->save()) {
                    if ($goharYabProgram->value and file_exists($tmpDIR . $_POST['gohar_yab_program']))
                        rename($tmpDIR . $_POST['gohar_yab_program'], $programDIR . $goharYabProgram->value);
                } else {
                    foreach ($goharYabProgram->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
                }
            }
        }

        if ($goharYabProgram->value and file_exists($programDIR . $goharYabProgram->value)) {
            $file = $goharYabProgram->value;
            $program = array(
                'name' => $file,
                'src' => $programUrl . '/' . $file,
                'size' => filesize($programDIR . $file),
                'serverName' => $file,
            );
        }

        $this->render('update', array(
            'showEventMessage' => $showEventMessage,
            'showEvent' => $showEvent,
            'showEventMoreThanDefaultPrice' => $showEventMoreThanDefaultPrice,
            'showEventMoreThanDefault' => $showEventMoreThanDefault,
            'eventMaxLongDays' => $eventMaxLongDays,
            'showEventArrivedDeadline' => $showEventArrivedDeadline,
            'submitGeneralEvents' => $submitGeneralEvents,
            'program' => $program,
            'baseLine' => $baseLine,
            'appVersion' => $appVersion,
        ));
    }
}