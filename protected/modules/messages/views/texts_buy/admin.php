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

<h1>تراکنش های مربوط به خرید شارژ پیامک</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'messages-texts-buy-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'user_id',
        array(
            'name' => 'user_name',
            'value' => '$data->user->first_name." ".$data->user->last_name',
        ),
        array(
            'name' => 'qty',
            'value' => 'number_format($data->qty)',
            'htmlOptions'=>array(
                'class' => 'direct-ltr',
                'style' => 'text-align:center',
            )
        ),
        array(
            'name' => 'status',
            'value' => 'MessagesTextsBuy::$statusList[$data->status]',
            'filter' => MessagesTextsBuy::$statusList
        ),
        array(
            'name' => 'sum_price',
            'value' => 'number_format($data->sum_price)',
        ),
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
