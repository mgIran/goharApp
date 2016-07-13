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

    <h1>پیش نویس ها</h1>

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'messages-texts-drafts-grid',
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'id',
        array(
            'name' => 'body',
            'value' => 'iWebHelper::truncate($data->body, 140,"...",true,true)',
        ),
        array(
            'class'=>'CButtonColumn',
            'template'=>'{update}{delete}{send}',
            'buttons' => array(
                'delete' => array(
                    'click'=>'js:CGridViewDeleteConfirm',
                ),
                'send' => array
                (
                    'label'=>'',
                    'options'=> array(
                        'class' => 'fa fa-paper-plane',
                        'style' => 'padding:0 3px;color:#7e569f;',
                        'title' => 'ارسال پیش نویس'
                    ),
                    //'imageUrl'=>Yii::app()->request->baseUrl.'/images/email.png',
                    'url'=>'Yii::app()->createUrl("messages/texts_send/sms/?draft=".$data->id)',
                ),
            )
        ),
    ),
)); ?>