<?php
class MessagesEmailsBankCategoriesController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'دسته بندی بانک ایمیل',
            'menu' => true,
            'menu_parent' => 'emails_bank',
            'menu_name' => 'emails_bank_categories',
            'url' => 'messages/emails_categories/admin' ,
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
        $model = new MessagesEmailsBankCategories;

        $this->performAjaxValidation($model);

        if(isset($_POST['MessagesEmailsBankCategories']))
        {
            if($_POST['MessagesEmailsBankCategories']['parent_id']=='' OR $_POST['MessagesEmailsBankCategories']['parent_id']==0)
                $_POST['MessagesEmailsBankCategories']['parent_id'] = NULL;

            $model->attributes=$_POST['MessagesEmailsBankCategories'];
            if($model->save())
                $this->redirect(array('admin'));
        }

        $categories = MessagesEmailsBankCategories::model()->findAll();

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

		if(isset($_POST['MessagesEmailsBankCategories']))
		{
			$model->attributes=$_POST['MessagesEmailsBankCategories'];
			if($model->save())
				$this->redirect(array('admin'));
		}

        $categories = MessagesEmailsBankCategories::model()->findAll();
        $temp = array(
            0 => 'بدون والد'
        );
        foreach($categories as $categories){
            $temp[$categories->id] = $categories->getFullName();
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
		$dataProvider=new CActiveDataProvider('MessagesEmailsBankCategories');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
        $model = new MessagesEmailsBankCategories('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['MessagesEmailsBankCategories']))
            $model->attributes=$_GET['MessagesEmailsBankCategories'];

        $this->render('admin',array(
            'model'=>$model,
        ));
	}

	public function loadModel($id)
	{
		$model=MessagesEmailsBankCategories::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='messages-emails-bank-categories-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
