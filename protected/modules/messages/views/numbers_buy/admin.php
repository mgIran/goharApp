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

<h1>تراکنش های مربوط به خرید خط</h1>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'messages-texts-numbers-buy-grid',
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
            'name' => 'user_number',
            'value' => '((isset($data->prefix->number))?$data->prefix->number." ":"").$data->number',
            'htmlOptions'=>array(
                'class' => 'direct-ltr',
                'style' => 'text-align:center',
            )
        ),
        array(
            'name' => 'special',
            'value' => 'MessagesTextsNumbersBuy::$specialList[$data->special]',
            'filter' => MessagesTextsNumbersBuy::$specialList
        ),
        array(
            'name' => 'status',
            'value' => 'Buys::$statusList[$data->buy->status]',
            'filter' => Buys::$statusList
        ),
        array(
            'name' => 'sum_price',
            'value' => 'number_format($data->buy->sum_price)',
            'htmlOptions'=>array(
                'class' => 'direct-ltr',
                'style' => 'text-align:center',
            ),
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
));
Yii::app()->clientScript->registerCss("gridview","
input[name=\"MessagesTextsNumbersBuy[sum_price]\"]{
    direction:ltr;
}
");

?>
