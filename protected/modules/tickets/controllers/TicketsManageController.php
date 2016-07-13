<?php

class TicketsManageController extends Controller
{
    public static $actionsArray =
        array(
            'title' => 'پشتیبانی',
            'menu'=>true,
            'menu_name'=>'manage_tickets',
            'admin' => array(
                'title'=>'مدیریت تیکت ها',
                'menu'=>true,
                'menu_parent'=>'manage_tickets',
                'menu_name'=>'manage_tickets_admin',
                'url' => 'tickets/manage/admin',
                'type'=>'admin',
                'otherActions'=>array('view','delete','deleteSelected')
            ),
            'userTickets' => array(
                'title'=>'صندوق پاسخ تیکت ها',
                'menu'=>true,
                'menu_parent'=>'manage_tickets',
                'menu_name'=>'manage_tickets_user',
                'url' => 'tickets/manage/userTickets',
                'type'=>'user',
                'otherActions'=>array('view','delete','deleteSelected')
            ),
            'create'=>array(
                'title'=>'ارسال تیکت',
                'menu'=>true,
                'menu_parent'=>'manage_tickets',
                'menu_name'=>'manage_tickets_create',
                'url' => 'tickets/manage/create',
                'type'=>'user'
            ),
            'help' => array(
                'title' => 'آموزش و راهنما',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'manage_tickets',
                'menu_name' => 'manage_tickets_help',
                'url' => 'tickets/manage/help',
            ),
            'contact' => array(
                'title' => 'پل های ارتباطی',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'manage_tickets',
                'menu_name' => 'manage_tickets_contact',
                'url' => 'tickets/manage/contact',
            )
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
        $newTicketContent = new TicketsContent;
        $criteria=new CDbCriteria;
        $criteria->condition='ticket_id='.$id;
        $criteria->order='id DESC';
        $dataProvider=new CActiveDataProvider('TicketsContent', array(
            'criteria'=>$criteria
        ));

		$this->render('view',array(
			'model'=>$this->loadModel($id),
            'newTicketContent' => $newTicketContent,
            'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Tickets;
		$this->performAjaxValidation($model);

        if(Yii::app()->user->type == 'admin')
            $model->scenario = 'admin_insert';
		if(isset($_POST['Tickets']))
		{
			$model->attributes=$_POST['Tickets'];
            $model->status= Tickets::STATUS_NO_REPLY;
			if($model->save())
            {
                $ticketsContentModel = new TicketsContent;
                $ticketsContentModel->attributes = $_POST['Tickets'];
                $ticketsContentModel->ticket_id = $model->id;
                $ticketsContentModel->text= $model->text;
                $ticketsContentModel->file= $model->file;
                $ticketsContentModel->save();
                Yii::app()->user->setFlash('success','درخواست پشتیبانی شما با موفقیت ارسال شد.');
				$this->redirect(array('userTickets'));
            }
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
		// $this->performAjaxValidation($model);

		if(isset($_POST['Tickets']))
		{
			$model->attributes=$_POST['Tickets'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
	}

    public function actionDeleteSelected()
    {
        foreach ($_POST['selectedItems'] as $modelId)
            $this->actionDelete($modelId);
    }

	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Tickets');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	public function actionAdmin()
	{
		$model=new Tickets('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Tickets']))
			$model->attributes=$_GET['Tickets'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

    public function actionUserTickets()
    {
        $model=new Tickets('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Tickets']))
            $model->attributes=$_GET['Tickets'];
        $model->user_id = Yii::app()->user->userID;

        $this->render('user_tickets',array(
            'model'=>$model,
        ));
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Tickets the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model = Tickets::model()->findByPk($id);
		if($model===null OR (Yii::app()->user->type == 'user' AND $model->user_id != Yii::app()->user->userID))
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Tickets $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tickets-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    public function actionHelp(){
    $model=Pages::model()->findByAttributes(array('name'=>'tickets_help'));
        $this->render('//pages/view',array(
            'model' => $model
        ));
    }

    public function actionContact(){
        $model=Pages::model()->findByAttributes(array('name'=>'tickets_contact'));
        $this->render('//pages/view',array(
            'model' => $model
        ));
    }
}
