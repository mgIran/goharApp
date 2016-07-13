<?php
class FinancialCostsController extends Controller
{
	public static $actionsArray = array(
		'title' => 'هزینه ها و سرمایه سایت',
		'type' => 'admin',
		'menu' => TRUE,
		'menu_name' => 'financial_costs',
		'admin' => array(
			'title' => 'مدیریت هزینه های جاری سایت و سرمایه سایت',
			'type' => 'admin',
			'menu' => TRUE,
			'menu_name' => 'financial_costs_admin',
			'menu_parent' => 'financial_costs',
			'url' => 'financial/costs/admin',
			'otherActions' => 'update,delete,activate,custom',
		),
		'create' => array(
			'title' => 'ایجاد هزینه جاری',
			'type' => 'admin',
			'menu' => TRUE,
			'menu_name' => 'financial_costs_create',
			'menu_parent' => 'financial_costs',
			'url' => 'financial/costs/create',
		),
	);

	public $layout='//layouts/column2';

	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete,DeleteSelected', // we only allow deletion via POST request
		);
	}

	public function actionCreate()
	{
		$model=new Costs;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Costs']))
		{
			$model->attributes=$_POST['Costs'];
			$model->start_date = iWebHelper::jalaliToTime($model->start_date);
			$model->price = str_replace(',','',$model->price);
			if($model->save()) {
				Yii::app()->user->setFlash('success','هزینه جدید اضافه گردید!');
				$this->refresh();
			}
			else
				Yii::app()->user->setFlash('failed','خطا در هنگام ثبت!');
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);

		if(isset($_POST['Costs']))
		{
			$model->qty=$_POST['Costs']['qty'];

			if($model->save()) {
				Yii::app()->user->setFlash('success','هزینه با موفقیت ویرایش شد');
				$this->refresh();
			}
			else
				Yii::app()->user->setFlash('failed','خطا در هنگام ویرایش!');
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

	public function actionActivate($id)
	{
		$model = $this->loadModel($id);
		$model->status = Costs::STATUS_ENABLE;
		$model->save();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	public function actionCustom($value){
		$value = intval(str_replace(',','',$value));
		$model = SiteOptions::model()->findByAttributes(array('name'=>'site_investment'));
		$model->value += $value;
		if($model->save()){
			$this->renderPartial('_custom');
		}

	}


	public function actionAdmin()
	{
		$model=new Costs('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Costs']))
			$model->attributes=$_GET['Costs'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Costs::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Costs $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='costs-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
