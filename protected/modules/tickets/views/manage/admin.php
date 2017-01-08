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

<h1>پشتیبانی</h1>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'tickets-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        array(
            'name'=>'title',
            'type'=>'raw',
            'value'=>'CHtml::link($data->title, Yii::app()->createUrl("//tickets/manage/view/?id=".$data->id), array("class"=>"table-link"))'
        ),
        array(
            'name'=>'user_id',
            'value'=>'$data->user->email'
        ),
        array(
            'name'=>'cat_id',
            'value'=>'$data->cat->title',
            'filter'=> CHtml::listData(TicketsCategories::model()->findAll(), 'id', 'title'),
        ),
        array(
            'name'=> 'priority',
            'value'=> 'Tickets::showPriority($data->priority)',
            'filter'=> Tickets::priorityList()
        ),
        array(
            'name'=> 'status',
            'type'=>'raw',
            'value'=> 'Tickets::showStatusLabel($data->status)',
        ),
        array(
            'header'=>'آخرین پیام',
            'value'=> 'JalaliDate::date("Y-m-d H:i", Tickets::lastTalkTime($data->id))',
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{view}{delete}'
        ),
    ),
)); ?>


