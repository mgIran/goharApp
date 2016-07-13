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

<h1>تراکنش های مربوط به خرید پلن</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'plans-buys-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        array(
            'name' => 'user_id',
            'value' => '$data->buy->user_id',
        ),
        array(
            'name' => 'user_name',
            'value' => '$data->buy->user->first_name." ".$data->buy->user->last_name',
        ),
        array(
            'name' => 'plan_name',
            'value' => '$data->plan->name',
        ),
        array(
            'name' => 'status',
            'value' => 'Buys::$statusList[$data->buy->status]',
            'filter' => Buys::$statusList
        ),
        array(
            'name' => 'sum_price',
            'value' => 'number_format($data->buy->sum_price)',
        ),
        //'date',
        array(
            'class'=>'CButtonColumn',
            'template'=>'{view}{delete}',
            'buttons' => array(
                'delete' => array(
                    'click'=>'js:CGridViewDeleteConfirm',
                )
            )
        ),
    ),
)); ?>
