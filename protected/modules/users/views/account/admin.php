<? if (($flashMessage = Yii::app()->user->getFlash('success')) !== null): ?>    <div class="alert alert-success">
        <i class="fa fa-check-square-o fa-lg"></i>
        <?= $flashMessage; ?>    </div>
<? endif; ?><? if (($flashMessage = Yii::app()->user->getFlash('info')) !== null): ?>    <div class="alert alert-info">
        <i class="fa fa-info-circle fa-lg"></i>
        <?= $flashMessage; ?>    </div>
<? endif; ?><? if (($flashMessage = Yii::app()->user->getFlash('danger')) !== null): ?>    <div class="alert alert-danger">
        <i class="fa fa-frown-o fa-lg"></i>
        <?= $flashMessage; ?>    </div>
<? endif; ?>

<?php $listView = $this->createWidget('zii.widgets.CListView', array(
    'id'=>'cms-list-view',
    'dataProvider'=>$dataProvider,
    'summaryText'=>'{count} نتیجه',
    'ajaxUpdate'=>'summaryWrapper',
    'itemView'=>'_view',
    'template' => '{items}{pager}',
    /*'pager' => array(
        'class' => 'ext.infiniteScroll.iWebIasPager',
    )*/
)); ?>

<div class="row">  
        
    <div class="right-float">

        <!-- search-form --> 
        <div class="search-form right-float">
            <? $this->renderPartial('_search'); ?>
        </div>
        
        <!-- list-view summary-->
        <div id="summaryWrapper">
            <? $listView->renderSummary();?>
        </div>

    </div>
    <div class="header-buttons-area">

        <!-- pager-size-area --> 
        <? $pageSize = Yii::app()->user->getState('pageSize',Yii::app()->params['defaultPageSize']); ?>
        <div class="pager-size-area left-margin-10">
            <h7>نمایش در صفحه</h7>
            <div class="select-group" style="width: 56px">                                
                <span class="search-dropdown" style="background-color: #e7e7e7;border-left: none;">
                    <i class="fa fa-angle-down fa-lg" id="search-dropdown-icon"></i>
                    <span id="section-caption"><?= $pageSize?></span>                                    
                </span>
                <ul id='pager-size' class="search-dropdown-ul" style="display: none;">
                    <li id="10">10</li>
                    <li id="25">25</li>
                    <li id="50">50</li>
                    <li id="100">100</li>
                </ul>
            </div>   
        </div>

        <!-- head-buttons --> 
        <a class="new-btn roles left-margin-10" title="مدیریت نقش ها" href="<?=Yii::app()->createAbsoluteUrl('users/roles/user')?>"></a>
        <a class="new-btn new-user left-margin-10" title="کاربر جدید" href="<?=Yii::app()->createAbsoluteUrl('users/manage/create')?>"></a>
        <a class="list-btn btn-back" href="<?=Yii::app()->createAbsoluteUrl('//')?>" title="بازگشت"></a>
    </div>

</div>


<div class="row items-header">
    <div class="checkbox-column">
        <input type="checkbox" class="check-all" />
    </div>
    <div class="avatar-column">       
    </div>
    <div class="width-20">
        <a class="sort-link">نام و نام خانوادگی</a>
    </div>
    <div class="width-20">
        <a class="sort-link">نام کاربری</a>
    </div>
    <div class="width-30">
        <a class="sort-link">پست الکترونیکی</a>
    </div>
    <div class="width-10">
        <a class="sort-link">نقش</a>
    </div>
    <div class="width-20"></div>
</div>

<div class="row">
    <div class="top-edge-container">
        <div class="list-upper-edge"></div>
    </div>
    <form id="list-view-form">
        <? $listView->run();?>
        <div class="bottom-edge-container">
            <div class="list-bottom-edge"></div>
        </div>
    </form> 
</div>

<div class="row">
    <a data-toggle="modal" data-backdrop="static" data-target="#confirm-delete-all" href='#' class="btn btn-danger del-selected hidden" data-href="<?=Yii::app()->createAbsoluteUrl('users/manage/deleteSelected/')?>>حذف موارد انتخابی</a>
</div>