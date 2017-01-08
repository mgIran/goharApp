<?php

class UsersPlacesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
    public static $actionsArray =
        array(
            'title' => 'مکان ها',
            'menu' => true,
            'menu_name' => 'users_places',
            'adminTowns' => array(
                'title' => 'مدیریت استان ها',
                'type' => 'admin',
                'menu' => true,
                'url' => 'users/places/adminTowns',
                'menu_parent'=>'users_places',
                'menu_name' => 'users_towns_admin',
            ),
            'adminCities' => array(
                'title' => 'مدیریت شهر ها',
                'type' => 'admin',
                'menu' => true,
                'url' => 'users/places/adminCities',
                'menu_parent'=>'users_places',
                'menu_name' => 'users_cities_admin',
            ),
            'updateTown' => array(
                'title' => 'اضافه / ویرایش',
                'otherActions' => array('updateCity','createCity','createTown'),
                'type' => 'admin'
            ),
            'delete' => array(
                'title' => 'حذف',
                'otherActions' => array('deleteSelected'),
                'type' => 'admin'
            ),
            'getCities'=>array(
                'title'=>'شهرهای استان',
                'type'=>'all'
            ),
        );

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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new UsersPlaces;

		$this->performAjaxValidation($model);

		if(isset($_POST['UsersPlaces']))
		{
			$model->attributes=$_POST['UsersPlaces'];
			if($model->save())
				$this->refresh();
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

    public function actionCreateTown()
    {
        $model=new UsersPlaces;

        $this->performAjaxValidation($model);

        if(isset($_POST['UsersPlaces']))
        {
            $model->attributes=$_POST['UsersPlaces'];
            if($model->save())
                $this->refresh();
        }

        $this->render('createTown',array(
            'model'=>$model,
        ));
    }

    public function actionCreateCity()
    {
        $model=new UsersPlaces;

        $this->performAjaxValidation($model);

        if(isset($_POST['UsersPlaces']))
        {
            $model->attributes=$_POST['UsersPlaces'];
            if($model->save())
                $this->refresh();
        }

        $this->render('createCity',array(
            'model'=>$model,
        ));
    }

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		$this->performAjaxValidation($model);

		if(isset($_POST['UsersPlaces']))
		{
			$model->attributes=$_POST['UsersPlaces'];
			if($model->save())
                $this->refresh();
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

    public function actionUpdateTown($id)
    {
        $model=$this->loadModel($id);

        $this->performAjaxValidation($model);

        if(isset($_POST['UsersPlaces']))
        {
            $model->attributes=$_POST['UsersPlaces'];
            if($model->save())
                $this->refresh();
        }

        $this->render('updateTown',array(
            'model'=>$model,
        ));
    }

    public function actionUpdateCity($id)
    {
        $model=$this->loadModel($id);

        $this->performAjaxValidation($model);

        if(isset($_POST['UsersPlaces']))
        {
            $model->attributes=$_POST['UsersPlaces'];
            if($model->save())
                $this->refresh();
        }

        $this->render('updateCity',array(
            'model'=>$model,
        ));
    }

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('UsersPlaces');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

    public function actionAdmin()
    {
        $this->render('admin');
    }

	public function actionAdminTowns(){
		$model=new UsersPlaces('searchTowns');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UsersPlaces']))
			$model->attributes=$_GET['UsersPlaces'];

		$this->render('adminTowns',array(
			'model'=>$model,
		));
	}

    public function actionAdminCities()
    {
        $model=new UsersPlaces('searchCities');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['UsersPlaces']))
            $model->attributes=$_GET['UsersPlaces'];

        $this->render('adminCities',array(
            'model'=>$model,
        ));
    }

	public function loadModel($id)
	{
		$model=UsersPlaces::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-places-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionGetCities($id){
        $models = UsersPlaces::model()->findAll(
            'parent_id=:town_id',
            array(':town_id'=>$id)
        );
        $data = CHtml::listData($models, 'id', 'title');
        echo CJSON::encode($data);
    }
}
