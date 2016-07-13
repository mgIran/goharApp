<?php

class DashboardController extends Controller
{

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
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
            array('allow',  // allow all users to perform 'index' and 'views' actions
                'actions'=>array('index'),
                'roles' => array('admin'),
                //'roles'=>array('admin','validator'),
            ),
            array('deny',  // deny all users
                'actions'=>array('index'),
                'users'=>array('*'),
            ),
        );
    }

	public function actionIndex()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('status = "paid"');
        $transactionsPaid = new CActiveDataProvider('UserTransactions',array(
            'criteria' => $criteria
        ));

        $criteria = new CDbCriteria();
        $criteria->addCondition('status = "unpaid"');
        $transactionsUnPaid =new CActiveDataProvider('UserTransactions',array(
            'criteria' => $criteria
        ));
        $totalTransactionsPaidAmount =Yii::app()->db->createCommand()
            ->select('SUM(amount) AS sum')
            ->from('{{user_transactions}}')
            ->where('status="paid"')
            ->queryScalar();
		$this->render('index',array(
            'transactionsPaid' => $transactionsPaid,
            'transactionsUnPaid' => $transactionsUnPaid,
            'totalTransactionsPaidAmount' => $totalTransactionsPaidAmount
        ));
	}

}