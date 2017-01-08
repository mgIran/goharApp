$(function() {
    setInterval(function () {
        $(".alert").fadeOut(2000);
    }, 20000);
    $('.nav-tabs > li').click(function (event) {
        if ($(this).hasClass('disabled')) {
            return false;
        }
    });

    $('body').on('click', '.add-dynamic-field', function () {
        var parent = $(this).parents('.dynamic-field-container'),
            input = document.createElement('input');
        if (parent.find('.dynamic-field').length < parseInt(parent.data('max'))) {
            input.type = 'text';
            input.name = parent.data('name') + '[' + parent.find('.dynamic-field').length + ']';
            input.placeholder = parent.find('.dynamic-field').attr('placeholder');
            input.className = parent.find('.dynamic-field').attr('class');
            $(parent).find('.input-container').append(input);
        }
        return false;
    });

    $('body').on('click', '.remove-dynamic-field', function () {
        var parent = $(this).parents('.dynamic-field-container');
        if (parent.find('.dynamic-field').length > 1)
            parent.find('.dynamic-field:last').remove();
        return false;
    });
});


function submitAjaxForm(form ,url ,loading ,callback) {
    loading = typeof loading !== 'undefined' ? loading : null;
    callback = typeof callback !== 'undefined' ? callback : null;
    $.ajax({
        type: "POST",
        url: url,
        data: form.serialize(),
        dataType: "json",
        beforeSend: function () {
            if(loading)
                loading.show();
        },
        success: function (html) {
            if(loading)
                loading.hide();
            if (typeof html === "object" && (typeof html.status === 'undefined' || typeof html.state === 'undefined')) {
                $.each(html, function (key, value) {
                    $("#" + key + "_em_").show().html(value.toString());
                });
            }else
                eval(callback);
        }
    });
}