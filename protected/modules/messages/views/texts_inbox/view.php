<h1>نمایش پیام</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
        'sender',
        'to',
		'body',
	),
));
echo "<br>";
echo CHtml::link('حذف پیام','#',array('class'=>'btn btn-danger','submit'=>array('delete','id'=>$model->id),'confirm'=>'آیا از حذف این آیتم اطمینان دارید؟'));
//array('label'=>'حذف Pages', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
?>
