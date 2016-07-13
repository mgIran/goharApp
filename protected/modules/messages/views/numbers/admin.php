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

    <h1>مدیریت خطوط</h1>

<?
$this->menu=array(
    array('label'=>'افزودن خط', 'url'=>array('create')),
);
?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'contacts-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'user_id',
        'number',
        array(
            'name' => 'status',
            'value' => '$data::$statusList[$data->status]'
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