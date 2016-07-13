<?php
class AgentAdminController extends Controller {
    public static $actionsArray =
        array(
            'title' => 'نمایندگی',
            'menu' => true,
            'menu_name' => 'admin_agent',
            'type' => 'admin',
            'commissions' => array(
                'title' => 'نمایش پورسانت ها',
                'type' => 'admin',
                'menu' => true,
                'menu_parent' => 'admin_agent',
                'menu_name' => 'admin_agent_commissions',
                'url' => 'agent/admin/commissions'
            ),
            'pay' => array(
                'title' => 'پرداخت پورسانت',
                'type' => 'admin',
            ),
            'payed' => array(
                'title' => 'پورسانت های پرداخت شده',
                'type' => 'admin',
                'menu' => true,
                'menu_parent' => 'admin_agent',
                'menu_name' => 'admin_agent_payed',
                'url' => 'agent/admin/payed'
            ),

        );

	public function filters(){
		return array(
			'accessControl',
		);
	}

	public function actionCommissions(){
        $model = new Users('agents');
        $model->unsetAttributes();

        if(isset($_GET['Users']))
            $model->attributes=$_GET['Users'];

        if(isset($_GET['export']) AND $_GET['export'] == 'true'){
            $this->exportCommissions($model);
        }

        $this->render('commissions',array(
            'model'=>$model,
        ));
    }

    public function actionPayed(){
        $model = new AgentsCommissions('search');
        $model->unsetAttributes();

        if(isset($_GET['AgentsCommissions']))
            $model->attributes=$_GET['AgentsCommissions'];

        $this->render('payed',array(
            'model'=>$model,
        ));
    }

    public function actionPay($userId){
        $user = Users::model()->findByPk($userId);

        if(!is_null($user)){
            $sumPrice = $user->getSumPrice(false);
            if($sumPrice){
                $model = new AgentsCommissions;
                $model->user_id = $user->id;
                $model->price = $sumPrice;
                $model->status = $model::STATUS_PAYED;
                $model->save();
            }
        }
        else
            throw new CHttpException(404,'The requested page does not exist.');
    }

    private function exportCommissions($model){
        $widget = $this->createWidget('ext.EExcelView.EExcelView', array(
            'dataProvider'=>$model->agents(),
            'title'=>'پورسانت ها',
            'autoWidth' => false,
            'grid_mode'=>'export',
            'filename'=> Yii::getPathOfAlias('webroot').'/protected/commissions.xlsx',
            'exportType'=>'Excel2007',
            'disablePaging'=>true,
            'stream'=>false,
            'columns'=> array(
                array(
                    'name' => 'full_name',
                    'value' => '$data->first_name." ".$data->last_name'
                ),
                array(
                    'name' => 'account_number',
                    'value' => '$data->bank->account_number'
                ),
                array(
                    'name' => 'iban',
                    'value' => '$data->bank->iban'
                ),
                array(
                    'name' => 'card_number',
                    'value' => '$data->bank->card_number'
                ),
                array(
                    'name' => 'bank_name',
                    'value' => '$data->bank->bank_name'
                ),
                array(
                    'name' => 'sumPrice',
                    'value' => '$data->getSumPrice()'
                )

            ),
        ));
        $widget->run();

        if (file_exists('protected/commissions.xlsx'))
            Yii::app()->getRequest()->sendFile(time(). '.xlsx', file_get_contents('protected/commissions.xlsx'));
        else
            Yii::app()->user->setFlash('danger', 'متاسفانه خطائی رخ داد، لطفا دوباره امتحان کنید.');
        exit;
    }


}