<?php

class TicketsCategoriesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */


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
    public static $actionsArray =
        array(
            'title' => 'بخش های تیکت',
            'admin' => array(
                'title'=>'مدیریت بخش های تیکت',
                'type'=>'admin',
                'menu'=>true,
                'menu_parent'=>'manage_tickets',
                'menu_name'=>'manage_tickets_categories',
                'url' => 'tickets/categories/admin',
                'otherActions'=>array('update','delete')
            ),
            'create'=>array(
                'title'=>'افزودن',
                'type'=>'admin',
            ),
            'update'=>array(
                'title'=>'ویرایش',
                'type'=>'admin'
            ),
            'delete'=>array(
                'title'=>'حذف',
                'type'=>'admin'
            )
        );

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new TicketsCategories;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['TicketsCategories']))
		{
			$model->attributes=$_POST['TicketsCategories'];
			if($model->save())
				$this->redirect(array('admin'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['TicketsCategories']))
		{
			$model->attributes=$_POST['TicketsCategories'];
			if($model->save())
                $this->redirect(array('admin'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TicketsCategories('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TicketsCategories']))
			$model->attributes=$_GET['TicketsCategories'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TicketsCategories the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TicketsCategories::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TicketsCategories $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tickets-categories-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
