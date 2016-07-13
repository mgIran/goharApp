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

<h1>پیامک های ارسال شده</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'messages-texts-send-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        array(
            'name' => 'sender_id',
            'value' => '$data->sender->number',
        ),
        array(
            'name' => 'body',
            'value' => 'iWebHelper::truncate($data->body, 100,"...",true,true)',
        ),
        //'date',
        array(
            'class'=>'CButtonColumn',
            'template'=>'{delete}',
            'buttons' => array(
                'delete' => array(
                    'click'=>'js:CGridViewDeleteConfirm',
                )
            )
        ),
    ),
)); ?>

<? MessagesTextsSend::model()  ?>
