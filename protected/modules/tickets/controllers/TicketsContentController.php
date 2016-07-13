<?php

class TicketsContentController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
    public static $actionsArray =
        array(
            'title' => 'متن تیکت',
            'admin' => array(
                'title'=>'مدیریت',
                'type'=>'admin',
                'otherActions'=>array('update','delete')
            ),
            /*'create'=>array(
                'title'=>'افزودن',
                'type'=>'all',
            ),*/
            'reply'=>array(
                'title'=>'پاسخگویی',
                'type'=>'user'
            ),
            'adminReply'=>array(
                'title'=>'پاسخگویی',
                'type'=>'admin'
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
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

/*	public function actionCreate($id)
	{
        $ticketId=$id;
        $model=new TicketsContent;
        $this->performAjaxValidation($model);
		if(isset($_POST['TicketsContent']))
		{
			$model->attributes=$_POST['TicketsContent'];
            $model->ticket_id=$ticketId;
			if($model->save()){
                Tickets::model()->updateByPk(
                    $model->ticket_id,
                    array(
                        'status'=>Tickets::STATUS_NO_REPLY
                    )
                );
                Yii::app()->user->setFlash('success', 'پیام ارسال شد.');

            }
		}
        $this->render('update',array(
            'model'=>$model,
        ));
	}*/

    public function actionReply($id)
    {
        $ticketId=$id;
        $model=new TicketsContent;
        $this->performAjaxValidation($model);
        if(isset($_POST['TicketsContent']))
        {
            $model->attributes=$_POST['TicketsContent'];
            $model->ticket_id=$ticketId;
            if($model->save()){
                Tickets::model()->updateByPk(
                    $model->ticket_id,
                    array(
                        'status'=>(isset($model->admin_id))? Tickets::STATUS_ANSWERED: Tickets::STATUS_NO_REPLY
                    )
                );
                Yii::app()->user->setFlash('success', 'پیام ارسال شد.');
            }
        }
        if(isset($_POST['ajaxInsert']))
        {
            $this->renderPartial('_reply',array(
                'model'=>$model,
                'currentTicket'=>$model->ticket_id
            ));
        }
    }
    public function actionAdminReply($id){
        $this->actionReply($id);
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

		if(isset($_POST['TicketsContent']))
		{
			$model->attributes=$_POST['TicketsContent'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
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
        $model= $this->loadModel($id);
        $file=Yii::getPathOfAlias('webroot').'/upload/files/'. $model->file;
        $model->delete();

        if (!is_null($model->file) AND !empty($model->file)) {
            if(file_exists($file))
                unlink($file);
        }

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('TicketsContent');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new TicketsContent('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['TicketsContent']))
			$model->attributes=$_GET['TicketsContent'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return TicketsContent the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=TicketsContent::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param TicketsContent $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tickets-content-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
