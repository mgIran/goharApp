<? Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->getBaseUrl(TRUE).'/iWebAjaxForm/jquery.form.js',CClientScript::POS_END);?>




<span class="loading"></span>



<div class="col-md-8 col-md-offset-2">
    <h1><?= $model->title ?></h1>
    <?php $this->widget('zii.widgets.CListView', array(
        'id'=>'list-view',
        'dataProvider'=>$dataProvider,
        'itemView'=>'/content/_view',
        'template'=>'{items}'
    )); ?>
    <hr style="border-color: #999"/>
    <div class="clearfix"></div>
    <div class="form-container">
        <? $this->renderPartial('/content/_reply', array(
            'model' => $newTicketContent,
            'currentTicket'=> $model->id
        )); ?>
    </div>
</div>





