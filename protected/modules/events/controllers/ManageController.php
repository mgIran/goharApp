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
				'actions' => array('admin', 'delete', 'create', 'update', 'view', 'upload', 'deleteUpload'),
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
				'attribute' => 'ceremony_poster',
				'rename' => 'random',
				'validateOptions' => array(
					'acceptedTypes' => array('jpg', 'jpeg', 'png', 'gif')
				)
			),
			'deleteUpload' => array(
				'class' => 'ext.dropZoneUploader.actions.AjaxDeleteUploadedAction',
				'modelName' => 'Events',
				'attribute' => 'ceremony_poster',
				'uploadDir' => '/uploads/events',
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
		$model = new Events;

		$this->performAjaxValidation($model);

		$tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
		if (!is_dir($tmpDIR))
			mkdir($tmpDIR);
		$tmpUrl = Yii::app()->baseUrl . '/uploads/temp/';
		$posterDIR = Yii::getPathOfAlias("webroot") . "/uploads/events/";
		if (!is_dir($posterDIR))
			mkdir($posterDIR);
		$poster = array();


		if (isset($_POST['Events'])) {
			$model->attributes = $_POST['Events'];

			$model->invitees = CJSON::encode($model->invitees);
			$model->type1 = $model->selectedCategories[0];
			$model->type2 = $model->selectedCategories[1];
			$model->dataSender = 'server';

			if (isset($_POST['Events']['ceremony_poster'])) {
				$file = $_POST['Events']['ceremony_poster'];
				$poster = array(
					'name' => $file,
					'src' => $tmpUrl . '/' . $file,
					'size' => filesize($tmpDIR . $file),
					'serverName' => $file,
				);
			}

			if ($model->save()) {
				if ($model->ceremony_poster and file_exists($tmpDIR . $model->ceremony_poster))
					rename($tmpDIR . $model->ceremony_poster, $posterDIR . $model->ceremony_poster);

				Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ذخیره شد.');
				$this->refresh();
			} else
				Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است!');
		}

		$states = CHtml::listData(UsersPlaces::model()->findAll('parent_id IS NULL'), 'id', 'title');
        $categories = array('مذهبی', 'فرهنگی', 'هنری', 'سیاسی', 'اجتماعی', 'اقتصادی', 'تجارت', 'ورزشی', 'تفریحی', 'سلامت', 'فناوری', 'علمی', 'راهپیمایی', 'آموزشی', 'خیرات', 'سایر موارد');

		$this->render('create', array(
			'model' => $model,
			'states' => $states,
			'categories' => $categories,
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
        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        $tmpDIR = Yii::getPathOfAlias("webroot") . '/uploads/temp/';
        if (!is_dir($tmpDIR))
            mkdir($tmpDIR);
        $tmpUrl = Yii::app()->baseUrl . '/uploads/temp/';
        $posterDIR = Yii::getPathOfAlias("webroot") . "/uploads/events/";
        if (!is_dir($posterDIR))
            mkdir($posterDIR);
        $posterUrl = Yii::app()->baseUrl . '/uploads/events/';

        $poster = array();
        if ($model->ceremony_poster and file_exists($posterDIR . $model->ceremony_poster)) {
            $file = $model->ceremony_poster;
            $poster = array(
                'name' => $file,
                'src' => $posterUrl . '/' . $file,
                'size' => filesize($posterDIR . $file),
                'serverName' => $file,
            );
        }

        if (isset($_POST['Events'])) {
            $model->attributes = $_POST['Events'];

            $model->invitees = CJSON::encode($model->invitees);
            if (isset($model->selectedCategories[0]))
                $model->type1 = $model->selectedCategories[0];
            if (isset($model->selectedCategories[1]))
                $model->type2 = $model->selectedCategories[1];
            $model->dataSender = 'server';

            if (isset($_POST['Events']['ceremony_poster']) and file_exists($tmpDIR . $_POST['Events']['ceremony_poster'])) {
                $file = $_POST['Events']['ceremony_poster'];
                $poster = array(
                    'name' => $file,
                    'src' => $tmpUrl . '/' . $file,
                    'size' => filesize($tmpDIR . $file),
                    'serverName' => $file,
                );
            }

            // delete selected categories from EventCategoryRel
            /* @var $category EventCategories */
            foreach ($model->categories as $category)
                EventCategoryRel::model()->deleteAll('category_id = :id', array(':id' => $category->id));

            if ($model->save()) {
                if ($model->ceremony_poster and file_exists($tmpDIR . $model->ceremony_poster))
                    rename($tmpDIR . $model->ceremony_poster, $posterDIR . $model->ceremony_poster);

                Yii::app()->user->setFlash('success', 'اطلاعات با موفقیت ذخیره شد.');
                $this->refresh();
            } else
                Yii::app()->user->setFlash('failed', 'در ثبت اطلاعات خطایی رخ داده است!');
        }

        $states = CHtml::listData(UsersPlaces::model()->findAll('parent_id IS NULL'), 'id', 'title');
        $categories = array('مذهبی', 'فرهنگی', 'هنری', 'سیاسی', 'اجتماعی', 'اقتصادی', 'تجارت', 'ورزشی', 'تفریحی', 'سلامت', 'فناوری', 'علمی', 'راهپیمایی', 'آموزشی', 'خیرات', 'سایر موارد');
        $model->selectedCategories = array($model->type1, $model->type2);

        $this->render('update', array(
            'model' => $model,
            'states' => $states,
            'categories' => $categories,
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
		$model = $this->loadModel($id);

		$posterDIR = Yii::getPathOfAlias("webroot") . "/uploads/events/";
		@unlink($posterDIR . $model->ceremony_poster);

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
		$model = new Events('search');
		$model->unsetAttributes();  // clear any default values
		if (isset($_GET['Events']))
			$model->attributes = $_GET['Events'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Events the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Events::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Events $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if (isset($_POST['ajax']) && $_POST['ajax'] === 'events-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}