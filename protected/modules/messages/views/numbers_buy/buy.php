<div id="factor-refresh">
    <div class="col-md-12">
        <div class="plans-labels<?=((isset($_GET['num']))?' active':'')?>" id="factor-details">
            <div class="col-md-6 pull-right">
                <?$this->renderPartial('_factor',array(
//                        'model' => $model,
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
                            'factorFields' => $factorFields,
                            'specialModel' => $specialModel,
                            'buy' => $buy,
                        )
                    )?>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="clearfix"></div>
    <br>
    <div class="col-md-12">
        <div class="plans-labels">
            <?$this->renderPartial('//report/_recent',array(
                    'title' => 'خط اختصاصی',
                    'dataProvider' => $messagesTextsNumbersBuyDataProvider
                )
            )?>
        </div>
    </div>
</div>