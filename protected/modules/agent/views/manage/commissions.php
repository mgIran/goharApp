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

<h1>پورسانت ها</h1>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-2">
            <?php echo CHtml::checkBox('effective',TRUE,array('class'=>'pull-right css-checkbox','style'=>'margin-left:6px;')); ?>
            <?php echo CHtml::label('پورسانت موثر','effective',array('class'=>'pull-right css-label')); ?>
        </div>
        <div class="col-md-2">
            <?php echo CHtml::checkBox('non-effective',TRUE,array('class'=>'pull-right css-checkbox','style'=>'margin-left:6px;')); ?>
            <?php echo CHtml::label('پورسانت غیر موثر','non-effective',array('class'=>'pull-right css-label')); ?>
        </div>
    </div>
</div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'buys-grid',
    'summaryText'=>'',
    'dataProvider'=>$model->commissions(),
    'rowCssClassExpression'=>'(isset($data->credit->effective) AND $data->credit->effective)?"green":"red"',
    'columns'=> array(
        array(
            'name' => 'full_name',
            'value' => '$data->user->first_name." ".$data->user->last_name'
        ),
        array(
            'name' => 'registerDate',
            'value' => '(!is_null($data->user->registerDate))?Yii::app()->jdate->date(\'Y/m/d\',$data->user->registerDate->time):NULL',
            'htmlOptions' => array(
                'style' => 'text-align:center'
            )
        ),
        'subset_level',
        array(
            'name' => 'currentPlan',
            'value' => '$data->getCurrentPlanAttributes(Yii::app()->user->userID,"name")'
        ),
        array(
            'name' => 'date',
            'value' => 'Yii::app()->jdate->date(\'Y/m/d\',$data->date)',
            'htmlOptions' => array(
                'style' => 'text-align:center'
            )
        ),
        'tracking_no',
        array(
            'name' => 'type',
            'value' => 'Buys::$typeList[$data->type]',
            'filter' => Buys::$typeList
        ),
        array(
            'name' => 'sum_price',
            'value' => 'number_format($data->sum_price)',
        ),
        array(
            'header' => 'سرمایه نمایندگی (مبلغی-تومان)',
            'value' => 'ceil($data->getAgentAttr("investment"))',
        ),
        array(
            'header' => 'سرمایه نمایندگی (درصدی)',
            'value' => '(($data->getAgentAttr("investment") * 100) / $data->sum_price)."%"',
        ),

        array(
            'header' => 'کل سود (تومان)',
            'value' => 'number_format(($data->sum_price - ceil($data->getAgentAttr("investment"))))',
        ),
        array(
            'name' => 'commission_percent',
            'value' => 'ceil((isset($data->credit->price))?(($data->credit->price * 100) / $data->sum_price):0)."%"',
        ),
        array(
            'name' => 'commission_price',
            'value' => '((isset($data->credit->price))?number_format($data->credit->price):0)'
        ),

    ),
));
?>

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
        $.fn.yiiGridView.update('buys-grid', {
            type:'GET',
            data:{effectiveType:typeOfUsers},
        })
    });
");
?>