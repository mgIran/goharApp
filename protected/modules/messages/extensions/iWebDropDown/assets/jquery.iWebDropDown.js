$.fn.iWebDropDown = function(options) {
    var that = this;
    var parent = this.parent();
    var defaultOptions = {
        afterSelect:function(){}
    };

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

    /*
     * Hide popups on body click
     */
    $('body').click(function (e) {
        childs = getChilds($('.popup'));
        childs = $.merge(childs, getChilds($('.popup-trigger')));
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

    parent.delegate('.category-select-head', 'click', function() {
        if ($(this).parent().find('.category-select-content').is(':hidden'))
            $(this).next('.category-select-content').slideDown(function () {
                $(this).getNiceScroll();
            });
        else {
            $(this).parent().find('.category-select-content').stop().slideUp(function () {
                $(this).getNiceScroll().hide();
            });
        }
    });

    parent.delegate('.category-select-content ul li', 'click', function() {
        // set option id in input
        $(this).parents('.category-select').next('input').val($(this).data('value')).change();

        $(this).parents('.category-select').find('.category-input').val($(this).text());
        if ($(this).text() == 'همه')$('.category-input').val('all');
        $(this).parents('.category-select').find('.category-select-head').html(
            '<div class="flash"><div class="glyphicon glyphicon-chevron-down"></div></div>' +
                $(this).text()
        );
        $(this).parents('.category-select').find('.category-select-content').stop().slideUp();

        defaultOptions.afterSelect();
    });

};

function getChilds(selector) {
    childs = new Array();
    selector.children().each(function (index, element) {
        childs[childs.length] = $(this);
        if ($(this).children().length != 0)
            childs = $.merge(childs, getChilds($(this)));
    });
    childs[childs.length] = selector;
    return childs;
}