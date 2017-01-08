$.fn.iWebDropDown = function(options) {
    var that = this;
    var parent = this.parent();
    var defaultOptions = {
        fixedContent : false,
        afterSelect:function(value,select){
            // run after value change
        }
    };
    
    var functions = {
        getChilds : function(selector){
            childs = new Array();
            selector.children().each(function (index, element) {
                childs[childs.length] = $(this);
                if ($(this).children().length != 0)
                    childs = $.merge(childs, functions.getChilds($(this)));
            });
            childs[childs.length] = selector;
            return childs;
        }
    }

    defaultOptions = $.extend(defaultOptions, options );

    parent.delegate('.popup-trigger', 'click', function(e) {
        var test = setTimeout(function () {
            $('.detail-scroll-area').getNiceScroll().resize();
            clearTimeout(test);
        }, 100);
        if (!$(this).parents().hasClass('popup')) {
            thisPopup = $(this).parent().find('.popup');
            $('.popup').each(function (index, element) {
                if (!$(this).is(thisPopup))
                    $(this).hide();
            });
            $('.popup, .popup-trigger').removeClass('active');
            $('.popup').parent().removeClass('active');
        }
    });

    // content size
    if(defaultOptions.fixedContent){
        var $categorySelectContent = parent.find('.category-select-content');
        $categorySelectContent.css('position','fixed');
        $categorySelectContent.width(parent.find('.category-select').width());
    }

    /*
     * Hide popups on body click
     */
    $('body').click(function (e) {
        childs = functions.getChilds($('.popup'));
        childs = $.merge(childs, functions.getChilds($('.popup-trigger')));
        closeWindow = true;
        for (i = 0; i < childs.length; i++) {
            if ($(e.target).is(childs[i]))
                closeWindow = false;
        }
        if (closeWindow) {
            $('.popup').hide();
            $('.popup, .popup-trigger').removeClass('active');
            $('.popup').parent().removeClass('active');
        }
    });

    parent.delegate('.category-select','keydown', function(e){
        var currentValue = $(this).next("input").val();

        if(e.which==40)         //down
        {
            if(currentValue=='')
                $(this).find('.filter-change:first-of-type').trigger('click');
            else
                $(this).find('.filter-change[data-value="'+currentValue+'"]').next().trigger('click');
            e.preventDefault();
        }
        else if(e.which==38)    //up
        {
            $(this).find('.filter-change[data-value="'+currentValue+'"]').prev().trigger('click');
            e.preventDefault();
        }
    });

    parent.delegate('.category-select-head', 'click', function() {
        $(this).focus();
        if ($(this).parent().find('.category-select-content').is(':hidden'))
            $(this).parent().find('.category-select-content').stop().slideDown(function () {
                $(this).niceScroll({railoffset: {top: 0, left: -($(this).width() - 6)}, autohidemode: false, cursorcolor: '#aaa', cursorborder: '0', zindex: 9999});
            });
        else {
            $(this).parent().find('.category-select-content').getNiceScroll().remove();
            $(this).parent().find('.category-select-content').delay(100).stop().slideUp();
        }
    });

    parent.delegate('.category-select-content ul li', 'click', function() {
        // set option id in input
        $(this).parents('.category-select').next('input').val($(this).data('value')).change();

        $(this).parents('.category-select').find('.category-input').val($(this).text());
        if ($(this).text() == 'همه')$('.category-input').val('all');
        $(this).parents('.category-select').find('.category-select-text').html(
            $(this).text()
        );
        $(this).parents('.category-select').find('.category-select-content').getNiceScroll().remove();
        $(this).parents('.category-select').find('.category-select-content').delay(100).stop().slideUp();

        $(this).parents('.category-select').focus();

        defaultOptions.afterSelect($(this).data('value'),$(this).text());
    });

};