<div id="factor-refresh">
    <?
    $this->menu=array(
        array('label'=>'لیست', 'url'=>array('admin')),
    );

    $factorFields = json_decode($buyModel->buy->details,true);
    $model = (object)array();
    ?>
    <div class="col-md-12">
        <div class="plans-labels<?=((isset($_GET['num']))?' active':'')?>" id="factor-details">
            <div class="col-md-6 pull-right">
                <?$this->renderPartial('_factor',array(
                        'model' => $model,
                        'factorFields' => $factorFields,
                        'lineNumber' => ((isset($buyModel->prefix->number))?$buyModel->prefix->number." ":"").$buyModel->number
                    )
                )?>
            </div>
            <div class="col-md-6 pull-right">
                <?$this->renderPartial('//report/_desc')?>
                <div class="factor-sections factor-box col-md-12">
                    <?$this->renderPartial('_form', array(
                            'buyModel' => $buyModel,
                            'buy' => $buyModel->buy,
                            'factorFields' => $factorFields,
                            'specialModel' => $specialModel
                        )
                    )?>
                </div>
            </div>
        </div>
    </div>
</div>