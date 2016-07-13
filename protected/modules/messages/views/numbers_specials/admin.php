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

<?php
$this->breadcrumbs=array('مدیران', 'مدیریت');
$this->menu=array(
    array('label'=>'افزودن خط اختصاصی', 'url'=>array('create')),
);
?>

<h1>مدیریت خطوط اختصاصی</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'messages-texts-numbers-specials-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'prefix',
            'value' => '(isset($data->prefix->number))?$data->prefix->number:"بدون پیش شماره"',
        ),
        'number',
        array(
            'name' => 'view',
            'value' => '(!is_null($data->view) AND !empty($data->view))?$data->view:((isset($data->prefix->number))?$data->prefix->number." ":"").$data->number',
            'htmlOptions' => array(
                'style' => 'direction:ltr;text-align:center'
            )
        ),
        array(
            'name' => 'status',
            'value' => 'MessagesTextsNumbersSpecials::$statusList[$data->status]',
        ),

        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'buttons' => array(
                'delete' => array(
                    'click'=>'js:CGridViewDeleteConfirm',
                )
            )
        ),
    ),
)); ?>
