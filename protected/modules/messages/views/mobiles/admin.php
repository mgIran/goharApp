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
    array('label'=>'افزودن شماره موبایل', 'url'=>array('create')),
    array('label'=>'مدیریت دسته ها', 'url'=>array('mobiles_categories/admin')),
);
?>

    <h1>بانک شماره موبایل</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'messages-mobiles-bank-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'mobile',
        array(
            'name' => 'category',
            'value' => '$data->category->getFullName()'
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

    <a  data-toggle="modal" data-backdrop="static" data-target="#upload-txt" href="#" class="btn btn-primary" style="margin-left: 10px" title="ثبت شماره موبایل به بانک از طریق آپلود فایل" href="">ثبت شماره موبایل به بانک از طریق آپلود فایل</a>

<? $this->renderPartial('_from_file')?>