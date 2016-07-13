<?php
class MessagesMobilesBankCategoriesController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'دسته بندی بانک شماره تلفن',
            'menu' => true,
            'menu_parent' => 'mobiles_bank',
            'menu_name' => 'mobiles_bank_categories',
            'url' => 'messages/mobiles_categories/admin' ,
            'admin' => array(
                'title' => 'مدیریت',
                'type' => 'admin'
            ),
            'create' => array(
                'title' => 'افزودن',
                'type' => 'admin'
            ),
            'update' => array(
                'title' => 'ویرایش',
                'type' => 'admin'
            ),
            'delete' => array(
                'title' => 'حذف',
                'type' => 'admin'
            ),
        );

	public function filters()
	{
		return array(
			'accessControl',
			'postOnly + delete',
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
        $model = new MessagesMobilesBankCategories;

        $this->performAjaxValidation($model);

        if(isset($_POST['MessagesMobilesBankCategories']))
        {
            if($_POST['MessagesMobilesBankCategories']['parent_id']=='' OR $_POST['MessagesMobilesBankCategories']['parent_id']==0)
                $_POST['MessagesMobilesBankCategories']['parent_id'] = NULL;

            $model->attributes=$_POST['MessagesMobilesBankCategories'];
            if($model->save())
                $this->redirect(array('admin'));
        }

        $categories = MessagesMobilesBankCategories::model()->findAll();

        $temp = array(
            0 => 'بدون والد'
        );
        foreach($categories as $category){
            $temp[$category->id] = $category->getFullName();
        }
        $categories = $temp;

        $this->render('create',array(
            'model'=>$model,
            'categories' => $categories
        ));
    }

	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

        $this->performAjaxValidation($model);

		if(isset($_POST['MessagesMobilesBankCategories']))
		{
			$model->attributes=$_POST['MessagesMobilesBankCategories'];
			if($model->save())
				$this->redirect(array('admin'));
		}

        $categories = MessagesMobilesBankCategories::model()->findAll();
        $temp = array(
            0 => 'بدون والد'
        );
        foreach($categories as $category){
            $temp[$category->id] = $category->getFullName();
        }
        $categories = $temp;

		$this->render('update',array(
			'model'=>$model,
            'categories' => $categories
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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('MessagesMobilesBankCategories');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        $model = new MessagesMobilesBankCategories('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesMobilesBankCategories']))
            $model->attributes=$_GET['MessagesMobilesBankCategories'];

        $this->render('admin',array(
            'model'=>$model,
        ));
	}

	public function loadModel($id)
	{
		$model=MessagesMobilesBankCategories::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='messages-mobiles-bank-categories-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
