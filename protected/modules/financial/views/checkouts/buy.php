<div id="factor-refresh">
    <div class="col-md-12">
        <div class="clearfix"></div>
        <div class="plans-labels<?=((isset($_GET['num']))?' active':'')?>" id="factor-details">
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
                            'factorFields' => $factorFields,
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
                    'title' => 'شماره اختصاصی',
                    'dataProvider' => $creditsTransactionsDataProvider
                )
            )?>
        </div>
    </div>
</div>