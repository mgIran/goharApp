$(function(){
    setInterval(function(){
        $(".alert").fadeOut(2000);
    }, 20000);
    $('.nav-tabs > li').click(function(event){
        if ($(this).hasClass('disabled')) {
            return false;
        }
    });

    $('body').on('click', '.add-multipliable-input', function(){
        var input=document.createElement('input');
        input.type='text';
        input.name='Books[permissions]['+$('.multipliable-input').length+']';
        input.placeholder='دسترسی';
        input.className='form-control multipliable-input';
        var container=document.getElementsByClassName('multipliable-input-container');
        $(container).append(input);
        return false;
    });

    $('body').on('click', '.remove-multipliable-input', function(){
        if($('.multipliable-input').length>1)
            $('.multipliable-input-container .multipliable-input:last').remove();
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