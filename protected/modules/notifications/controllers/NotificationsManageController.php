<?php

class NotificationsManageController extends Controller
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
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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
                'actions' => array('view', 'admin', 'delete', 'create', 'update', 'upload', 'deleteUpload'),
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
                'attribute' => 'poster',
                'rename' => 'random',
                'validateOptions' => array(
                    'acceptedTypes' => array('jpg', 'jpeg', 'png', 'gif')
                )
            ),
            'deleteUpload' => array(
                'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
                'modelName' => 'Notifications',
                'attribute' => 'poster',
                'uploadDir' => '/uploads/notifications',
                'storedMode' => 'field'
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->pageTitle ='مشاهده اطلاعیه';

        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->pageTitle ='ارسال اطلاعیه';

        $model = new Notifications;

        $this->performAjaxValidation($model);

        $tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        if (!is_dir($tmpDIR))
            mkdir($tmpDIR);
        $tmpUrl = Yii::app()->baseUrl . '/uploads/temp/';
        $posterDIR = Yii::getPathOfAlias("webroot") . "/uploads/notifications/";
        if (!is_dir($posterDIR))
            mkdir($posterDIR);

        $poster = array();

        if (isset($_POST['Notifications'])) {
            $model->attributes = $_POST['Notifications'];

            if (isset($_POST['Notifications']['poster'])) {
                $file = $_POST['Notifications']['poster'];
                $poster = array(
                    'name' => $file,
                    'src' => $tmpUrl . '/' . $file,
                    'size' => filesize($tmpDIR . $file),
                    'serverName' => $file,
                );
            }

            if ($model->save()) {
                if ($model->poster and file_exists($tmpDIR . $model->poster))
                    rename($tmpDIR . $model->poster, $posterDIR . $model->poster);

                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ذخیره شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است!');
        }

        $this->render('create', array(
            'model' => $model,
            'poster' => $poster,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $this->pageTitle ='ویرایش اطلاعیه';

        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        $tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        if (!is_dir($tmpDIR))
            mkdir($tmpDIR);
        $tmpUrl = Yii::app()->baseUrl . '/uploads/temp/';
        $posterDIR = Yii::getPathOfAlias("webroot") . "/uploads/notifications/";
        if (!is_dir($posterDIR))
            mkdir($posterDIR);
        $posterUrl = Yii::app()->baseUrl . '/uploads/notifications/';

        $poster = array();
        if ($model->poster and file_exists($posterDIR . $model->poster)) {
            $file = $model->poster;
            $poster = array(
                'name' => $file,
                'src' => $posterUrl . '/' . $file,
                'size' => filesize($posterDIR . $file),
                'serverName' => $file,
            );
        }

        if (isset($_POST['Notifications'])) {
            $model->attributes = $_POST['Notifications'];

            if (isset($_POST['Notifications']['poster']) and file_exists($tmpDIR . $_POST['Notifications']['poster'])) {
                $file = $_POST['Notifications']['poster'];
                $poster = array(
                    'name' => $file,
                    'src' => $tmpUrl . '/' . $file,
                    'size' => filesize($tmpDIR . $file),
                    'serverName' => $file,
                );
            }

            if ($model->save()) {
                if ($model->poster and file_exists($tmpDIR . $model->poster))
                    rename($tmpDIR . $model->poster, $posterDIR . $model->poster);

                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ذخیره شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است!');
        }

        $this->render('update', array(
            'model' => $model,
            'poster' => $poster,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model=$this->loadModel($id);

        $posterDIR = Yii::getPathOfAlias("webroot") . "/uploads/notifications/";
        @unlink($posterDIR.$model->poster);

        $model->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $this->pageTitle='لیست اطلاعیه ها';

        $model = new Notifications('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Notifications']))
            $model->attributes = $_GET['Notifications'];

        $this->render('admin', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Notifications the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Notifications::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Notifications $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'notifications-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}