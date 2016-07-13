<?
$this->menu=array(
    array('label'=>'لیست', 'url'=>array('admin')),
);
$factorFields = json_decode($buyModel->details,true);
if(!is_null($factorFields)):?>
    <div class="col-md-12">
        <div class="plans-labels">
            <div class="col-md-6 pull-right">
                <?$this->renderPartial('_factor',array(
                        'model' => $model,
                        'factorFields' => $factorFields
                    )
                )?>
            </div>
            <div class="col-md-6 pull-right">
                <?$this->renderPartial('//report/_desc')?>
                <div class="factor-sections factor-box col-md-12">
                    <?$this->renderPartial('_form', array(
                            'buyModel' => $buyModel,
                            'factorFields' => $factorFields
                        )
                    )?>
                </div>
            </div>
        </div>
    </div>
<?endif;?>