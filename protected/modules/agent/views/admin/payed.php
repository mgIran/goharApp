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

<h1>پورسانت های پرداخت شده</h1>
<?php

$this->confirmMessage = 'آیا از پرداخت این پورسانت اطمینان دارید؟';

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'agents-commissions-grid',
    'summaryText'=>'',
    'dataProvider'=>$model->search(),
    //'filter'=>$model,
    'columns'=> array(
        array(
            'name' => 'full_name',
            'value' => '$data->user->first_name." ".$data->user->last_name'
        ),
        array(
            'name' => 'date',
            'value' => '(!is_null($data->date))?Yii::app()->jdate->date(\'Y/m/d\',$data->date):NULL',
            'htmlOptions' => array(
                'style' => 'text-align:center'
            )
        ),
        array(
            'name' => 'price',
            'value' => 'number_format($data->price)'
        )

    ),
)); ?>
