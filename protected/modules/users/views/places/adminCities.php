<?php
/* @var $this PlacesController */
/* @var $model Places */

$this->breadcrumbs=array(
	'UsersPlaces',
	'Manage',
);

$this->menu=array(
	array('label'=>'افزودن شهر', 'url'=>array('createCity')),
    array('label'=>'مدیریت استان ها', 'url'=>array('adminTowns')),
);
?>

<h1>مدیریت شهر ها</h1>
<?php echo CHtml::button('نمایش',array('class'=>'pull-left btn btn-info','style'=>'margin-right:10px;','id'=>'change-page-size'));?>
<?php echo CHtml::textField('page-size','',array('class'=>'pull-left just-number','placeholder'=>'تعداد رکورد'));?>
<?php Yii::app()->clientScript->registerScript('change-page-size',"
    $('#page-size').on('keydown', function(e){
        if(e.keyCode===13)
            $('#change-page-size').trigger('click');
    });
    $('#change-page-size').click(function(){
        var ps=$('#page-size').val();
        if(ps!='')
            $.fn.yiiGridView.update('users-places-grid',{
                data:{page_size:ps}
            });
    });
");?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'users-places-grid',
	'dataProvider'=>$model->searchCities(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'title',
        array(
            'name'=>'parent_id',
            'value'=>'$data->parent->title'
        ),
		array(
			'class'=>'CButtonColumn',
            'template'=>'{update}{delete}',
            'buttons'=>array(
                'update'=>array(
                    'url'=>'$this->grid->controller->createUrl("/users/places/updateCity", array("id"=>$data->id))',
                )
            )
		),
	),
)); ?>
