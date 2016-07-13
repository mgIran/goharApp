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
<?
    echo CHtml::link("خروجی اکسل","#",array('submit'=>array('commissions','export'=>'true'),'class'=>'btn btn-info'));
?>
<h1>پورسانت ها</h1>
<?php

$this->confirmMessage = 'آیا از پرداخت این پورسانت اطمینان دارید؟';

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'users-grid',
    'summaryText'=>'',
    'dataProvider'=>$model->agents(),
    //'filter'=>$model,
    'columns'=> array(
        array(
            'name' => 'full_name',
            'value' => '$data->first_name." ".$data->last_name'
        ),
        array(
            'name' => 'registerDate',
            'value' => '(!is_null($data->registerDate))?Yii::app()->jdate->date(\'Y/m/d\',$data->registerDate->time):NULL',
            'htmlOptions' => array(
                'style' => 'text-align:center'
            )
        ),
        array(
            'name' => 'sumPrice',
            'value' => '$data->getSumPrice()'
        ),
        array(
            'class' => 'CButtonColumn',
            'template'=>'{pay}',
            'buttons' => array(
                'pay' => array(
                    'label'=>'<span style="margin-right: -10px;color: #fff;font-size: 9px;" class="fa fa-check"></span>',
                    'options'=> array(
                        'class' => 'delete fa fa-user',
                        'style' => 'padding:0 3px;color:#7e569f;font-size:15px;',
                        'title' => 'پرداخت پورسانت'
                    ),
                    'url'=>'Yii::app()->createUrl("agent/admin/pay/?userId=".$data->id)',
                    'click'=>'js:CGridViewDeleteConfirm',
                ),
            )
        )

    ),
));

