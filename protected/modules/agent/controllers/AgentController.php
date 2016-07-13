<?php
class AgentController extends Controller {
    public static $actionsArray =
        array(
            'title' => 'نمایندگی',
            'menu' => true,
            'menu_name' => 'agent',
            'type' => 'user',
            'report' => array(
                'title' => 'زیرمجموعه ها',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'agent',
                'menu_name' => 'agent_report',
                'url' => 'agent/manage/report'
            ),
            'commissions' => array(
                'title' => 'پورسانت های من',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'agent',
                'menu_name' => 'agent_commissions',
                'url' => 'agent/manage/commissions'
            ),
            'banners' => array(
                'title' => 'بنرهای آماده',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'agent',
                'menu_name' => 'agent_banners',
                'url' => 'agent/manage/banners'
            ),
            'intro' => array(
                'title' => 'معرفی اولیه',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'agent',
                'menu_name' => 'agent_intro',
                'url' => 'agent/manage/intro'
            ),
            'payed' => array(
                'title' => 'پورسانت های پرداخت شده',
                'type' => 'user',
                'menu' => true,
                'menu_parent' => 'agent',
                'menu_name' => 'agent_payed',
                'url' => 'agent/manage/payed'
            ),

        );

	public function filters(){
		return array(
			'accessControl',
		);
	}

	public function actionReport(){
        $firstSubsets = Users::model()->find(array(
            'select' => 'GROUP_CONCAT(id) AS id',
            'condition' => 'agent_id = :agentId',
            'params' => array(':agentId'=>Yii::app()->user->userID)
        ));
        $firstSubsets = $firstSubsets->id;

        if(!is_null($firstSubsets)){
            $model = new Users('search');
            $model->unsetAttributes();
            $model->agent_id = Yii::app()->user->userID;
            $model->pageSize = 6;

            $secondAgentModel = new Users('search');
            $secondAgentModel->unsetAttributes();
            $secondAgentModel->searchCondition = "agent_id IN($firstSubsets)";
            $secondAgentModel->pageSize = 6;
        }
        else{
            $model = NULL;
            $secondAgentModel = NULL;
        }


        $secondSubsets = Users::model()->find(array(
            'select' => 'GROUP_CONCAT(id) AS id',
            'condition' => 'agent_id IN(:agentId)',
            'params' => array(':agentId'=>$firstSubsets)
        ));
        $secondSubsets = $secondSubsets->id;

        if(!is_null($secondSubsets)){
            $thirdAgentModel = new Users('search');
            $thirdAgentModel->unsetAttributes();
            $thirdAgentModel->searchCondition = "agent_id IN($secondSubsets)";
            $thirdAgentModel->pageSize = 6;
        }
        else
            $thirdAgentModel = NULL;


        if(isset($_GET['Users']))
            $model->attributes=$_GET['Users'];
        elseif(isset($_GET['UsersSecond']))
            $secondAgentModel->attributes=$_GET['UsersSecond'];
        elseif(isset($_GET['UsersThird']))
            $thirdAgentModel->attributes=$_GET['UsersThird'];

        $this->render('report',array(
            'model'=>$model,
            'secondAgentModel' => $secondAgentModel,
            'thirdAgentModel' => $thirdAgentModel
        ));
	}

    public function actionCommissions(){
        $model = new Buys('commissions');
        $model->unsetAttributes();

        if(isset($_GET['Buys']))
            $model->attributes=$_GET['Buys'];

        $this->render('commissions',array(
            'model'=>$model,
        ));
    }

    public function actionBanners(){
        $model=Pages::model()->findByAttributes(array('name'=>'agent_banners'));
        $this->render('//pages/view',array(
            'model' => $model
        ));
    }

    public function actionIntro(){
        $model=Pages::model()->findByAttributes(array('name'=>'agent_intro'));
        $this->render('//pages/view',array(
            'model' => $model
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

}