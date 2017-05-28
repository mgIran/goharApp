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
                'actions' => array('update', 'upload', 'deleteUpload', 'uploadPoster', 'deleteUploadPoster'),
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
            'uploadPoster' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxUploadAction',
                'attribute' => 'weekly_unity_poster',
                'rename' => 'random',
                'validateOptions' => array(
                    'acceptedTypes' => array('jpg','png','gif')
                )
            ),
            'deleteUploadPoster' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'SiteOptions',
                'attribute' => 'value',
                'uploadDir' => '/uploads/unity',
                'storedMode' => 'field'
            ),
        );
    }

    public function actionUpdate()
    {
        $settings = SiteOptions::model()->findAll();

        $errors=array();

        $showEventMessage =
        $showEvent =
        $showEventMoreThanDefaultPrice =
        $showEventMoreThanDefault =
        $eventMaxLongDays =
        $showEventArrivedDeadline =
        $submitGeneralEvents =
        $goharYabProgram =
        $baseLine =
        $filterFreeCount =
        $additionalFilterCost =
        $appVersion =
        $eventTaxEnabled =
        $signupStatus =
        $adminGroupsPrice =
        $generalFiltersPrice =
        $favoriteFiltersPrice =
        $weeklyUnityRecord =
        $adminGroupsTaxEnabled =
        $generalFiltersTaxEnabled=
        $favoriteFiltersTaxEnabled= null;

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
            elseif ($setting->name == 'filter_free_count')
                $filterFreeCount = $setting;
            elseif ($setting->name == 'additional_filter_cost')
                $additionalFilterCost = $setting;
            elseif ($setting->name == 'app_version')
                $appVersion = $setting;
            elseif ($setting->name == 'event_tax_enabled')
                $eventTaxEnabled = $setting;
            elseif ($setting->name == 'signup_status')
                $signupStatus = $setting;
            elseif ($setting->name == 'admin_groups_price')
                $adminGroupsPrice = $setting;
            elseif ($setting->name == 'general_filters_price')
                $generalFiltersPrice = $setting;
            elseif ($setting->name == 'favorite_filters_price')
                $favoriteFiltersPrice = $setting;
            elseif ($setting->name == 'weekly_unity_image')
                $weeklyUnityRecord = $setting;
            elseif ($setting->name == 'admin_groups_tax_enabled')
                $adminGroupsTaxEnabled = $setting;
            elseif ($setting->name == 'general_filters_tax_enabled')
                $generalFiltersTaxEnabled = $setting;
            elseif ($setting->name == 'favorite_filters_tax_enabled')
                $favoriteFiltersTaxEnabled = $setting;
        }

        $tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        if (!is_dir($tmpDIR))
            mkdir($tmpDIR);
        $tmpUrl = Yii::app()->baseUrl . '/uploads/temp/';
        $programDIR = Yii::getPathOfAlias("webroot") . "/uploads/app/";
        if (!is_dir($programDIR))
            mkdir($programDIR);
        $programUrl = Yii::app()->baseUrl . '/uploads/app/';
        $weeklyUnityPosterDIR = Yii::getPathOfAlias("webroot") . "/uploads/unity/";
        if (!is_dir($weeklyUnityPosterDIR))
            mkdir($weeklyUnityPosterDIR);
        $weeklyUnityPosterUrl = Yii::app()->baseUrl . '/uploads/unity/';

        $program = $weeklyUnityPoster = array();

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

            if (isset($_POST['filterFreeCount'])) {
                $filterFreeCount->value = $_POST['filterFreeCount'];

                if (!$filterFreeCount->save())
                    foreach ($filterFreeCount->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['additionalFilterCost'])) {
                $additionalFilterCost->value = $_POST['additionalFilterCost'];

                if (!$additionalFilterCost->save())
                    foreach ($additionalFilterCost->errors as $error)
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

            if (isset($_POST['eventTaxEnabled'])) {
                $eventTaxEnabled->value = $_POST['eventTaxEnabled'];

                if (!$eventTaxEnabled->save())
                    foreach ($eventTaxEnabled->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['signupStatus'])) {
                $signupStatus->value = $_POST['signupStatus'];

                if (!$signupStatus->save())
                    foreach ($signupStatus->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['adminGroupsPrice'])) {
                $adminGroupsPrice->value = $_POST['adminGroupsPrice'];

                if (!$adminGroupsPrice->save())
                    foreach ($adminGroupsPrice->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['generalFiltersPrice'])) {
                $generalFiltersPrice->value = $_POST['generalFiltersPrice'];

                if (!$generalFiltersPrice->save())
                    foreach ($generalFiltersPrice->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['favoriteFiltersPrice'])) {
                $favoriteFiltersPrice->value = $_POST['favoriteFiltersPrice'];

                if (!$favoriteFiltersPrice->save())
                    foreach ($favoriteFiltersPrice->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['adminGroupsTaxEnabled'])) {
                $adminGroupsTaxEnabled->value = $_POST['adminGroupsTaxEnabled'];

                if (!$adminGroupsTaxEnabled->save())
                    foreach ($adminGroupsTaxEnabled->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['generalFiltersTaxEnabled'])) {
                $generalFiltersTaxEnabled->value = $_POST['generalFiltersTaxEnabled'];

                if (!$generalFiltersTaxEnabled->save())
                    foreach ($generalFiltersTaxEnabled->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
            }

            if (isset($_POST['favoriteFiltersTaxEnabled'])) {
                $favoriteFiltersTaxEnabled->value = $_POST['favoriteFiltersTaxEnabled'];

                if (!$favoriteFiltersTaxEnabled->save())
                    foreach ($favoriteFiltersTaxEnabled->errors as $error)
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

            if (isset($_POST['weekly_unity_poster'])) {
                $weeklyUnityRecord->value = $_POST['weekly_unity_poster'];

                if (isset($_POST['weekly_unity_poster']) and file_exists($tmpDIR . $_POST['weekly_unity_poster'])) {
                    $file = $_POST['weekly_unity_poster'];
                    $weeklyUnityPoster = array(
                        'name' => $file,
                        'src' => $tmpUrl . '/' . $file,
                        'size' => filesize($tmpDIR . $file),
                        'serverName' => $file,
                    );
                }

                if ($weeklyUnityRecord->save()) {
                    if ($weeklyUnityRecord->value and file_exists($tmpDIR . $_POST['weekly_unity_poster']))
                        rename($tmpDIR . $_POST['weekly_unity_poster'], $weeklyUnityPosterDIR . $weeklyUnityRecord->value);
                } else {
                    foreach ($weeklyUnityRecord->errors as $error)
                        foreach ($error as $item)
                            $errors[] = $item;
                }
            }

            if(empty($errors))
                Yii::app()->user->setFlash("success", "اطلاعات با موفقیت ثبت شد.");
            else
                Yii::app()->user->setFlash("failed", "در ثبت اطلاعات خطایی رخ داده است!");
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

        if ($weeklyUnityRecord->value and file_exists($weeklyUnityPosterDIR . $weeklyUnityRecord->value)) {
            $file = $weeklyUnityRecord->value;
            $weeklyUnityPoster = array(
                'name' => $file,
                'src' => $weeklyUnityPosterUrl . '/' . $file,
                'size' => filesize($weeklyUnityPosterDIR . $file),
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
            'filterFreeCount' => $filterFreeCount,
            'additionalFilterCost' => $additionalFilterCost,
            'appVersion' => $appVersion,
            'eventTaxEnabled' => $eventTaxEnabled,
            'signupStatus' => $signupStatus,
            'adminGroupsPrice' => $adminGroupsPrice,
            'generalFiltersPrice' => $generalFiltersPrice,
            'favoriteFiltersPrice' => $favoriteFiltersPrice,
            'weeklyUnityPoster' => $weeklyUnityPoster,
            'adminGroupsTaxEnabled' => $adminGroupsTaxEnabled,
            'generalFiltersTaxEnabled' => $generalFiltersTaxEnabled,
            'favoriteFiltersTaxEnabled' => $favoriteFiltersTaxEnabled,
        ));
    }
}