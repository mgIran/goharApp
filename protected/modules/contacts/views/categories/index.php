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
    array('label'=>'افزودن دسته', 'url'=>array('create')),
);
?>

<h1>مدریریت گروه مخاطبین</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'contacts-categories-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'title',
        array(
            'header' => 'تعداد مخاطبین',
            'value' => 'count($data->contacts)',
            'htmlOptions' => array(
                'style' => 'text-align:center;'
            )
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

