<? if (($flashMessage = Yii::app()->user->getFlash('success')) !== null): ?>    <div class="alert alert-success">
    <i class="fa fa-check-square-o fa-lg"></i>
    <?= $flashMessage; ?>    </div>
<? endif; ?><? if (($flashMessage = Yii::app()->user->getFlash('info')) !== null): ?>    <div class="alert alert-info">
    <i class="fa fa-info-circle fa-lg"></i>
    <?= $flashMessage; ?>    </div>
<? endif; ?><? if (($flashMessage = Yii::app()->user->getFlash('failed')) !== null): ?>    <div class="alert alert-failed">
    <i class="fa fa-frown-o fa-lg"></i>
    <?= $flashMessage; ?>    </div>
<? endif; ?>
<div class="col-md-12">
    <h1>گزارشات نمایندگی</h1>
</div>
<div class="clearfix"></div>
<br/>
<div class="col-md-8">
    <div class="row">
        <div class="col-md-2 pull-right">
            <? echo CHtml::label('لینک نمایندگی','agent-link',array('style'=>'padding-top:6px'))?>

        </div>
        <div class="col-md-10 pull-right">
            <? echo CHtml::textField('agent-link',Yii::app()->createAbsoluteUrl("users/account/register?agentId=".base64_encode(Yii::app()->user->userID)),array(
                'class'=>'form-control direct-ltr',
                'readonly'=>'readonly',
                'style' => 'cursor:text;'
            ));?>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<br/>
<?
$fakeActiveDataProvider = new CActiveDataProvider('Users',array('criteria'=>array('condition'=>'id = \'sdfsdfsdf\'')));

$columns = array(
    array(
        'name' => 'full_name',
        'value' => '$data->first_name." ".$data->last_name'
    ),
    array(
        'name' => 'activePlan',
        'value' => '(!is_null($data->activePlan))?$data->activePlan->plansBuys->plan->name:NULL'
    ),
    array(
        'name' => 'registerDate',
        'value' => '(!is_null($data->registerDate))?Yii::app()->jdate->date(\'Y/m/d H:i:s\',$data->registerDate->time):NULL',
        'htmlOptions' => array(
            'style' => 'text-align:center'
        )
    ),
);?>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2">
            <?php echo CHtml::checkBox('effective',TRUE,array('class'=>'pull-right css-checkbox','style'=>'margin-left:6px;')); ?>
            <?php echo CHtml::label('زیر مجموعه موثر','effective',array('class'=>'pull-right css-label')); ?>
        </div>
        <div class="col-md-2">
            <?php echo CHtml::checkBox('non-effective',TRUE,array('class'=>'pull-right css-checkbox','style'=>'margin-left:6px;')); ?>
            <?php echo CHtml::label('زیر مجموعه غیر موثر','non-effective',array('class'=>'pull-right css-label')); ?>
        </div>
    </div>
</div>

<div class="col-md-4 pull-right">
    <h6>زیر مجموعه های سطح اول</h6>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'users-grid',
        'summaryText'=>'',
        'ajaxUpdate' => 'users-second-grid,users-third-grid',
        'dataProvider'=>(!is_null($model))?$model->search():$fakeActiveDataProvider,
        'rowCssClassExpression'=>'($data->userInfoStatus())?"green":"red"',
        //'filter'=>$model,
        'columns'=> $columns,
    )); ?>
</div>
<div class="col-md-4 pull-right">
    <h6>زیر مجموعه های سطح دوم</h6>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'users-second-grid',
        'summaryText'=>'',
        'dataProvider'=>(!is_null($secondAgentModel))?$secondAgentModel->search():$fakeActiveDataProvider,
        'rowCssClassExpression'=>'($data->userInfoStatus())?"green":"red"',
        //'filter'=>$secondAgentModel,
        'columns'=> $columns,
    )); ?>
</div>
<div class="col-md-4 pull-right">
    <h6>زیر مجموعه های سطح سوم</h6>
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id'=>'users-third-grid',
        'summaryText'=>'',
        'dataProvider'=>(!is_null($thirdAgentModel))?$thirdAgentModel->search():$fakeActiveDataProvider,
        'rowCssClassExpression'=>'($data->userInfoStatus())?"green":"red"',
        //'filter'=>$thirdAgentModel,
        'columns'=> $columns,
    )); ?>
</div>
<?
Yii::app()->clientScript->registerScript("","
    $(document).on('click','#non-effective,#effective',function(){
        var typeOfUsers = 0;
        if($('#non-effective').prop('checked') && $('#effective').prop('checked')){
            typeOfUsers = 3;
        }
        else if($('#non-effective').prop('checked')){
            typeOfUsers = 2;
        }
        else if($('#effective').prop('checked')){
            typeOfUsers = 1;
        }
        $.fn.yiiGridView.update('users-grid', {
            type:'GET',
            data:{effectiveType:typeOfUsers},
        })
    });
");
?>