$(function(){
    // CALL CALENDAR
    $('.datepicker').persianDatepicker({
        altField: '.date',
        altFormat: 'X',
        observer: true,
        format: 'DD MMMM YYYY',
        persianDigit: false
    });

    $('.timepicker').persianDatepicker({
        altField: '.time',
        altFormat: 'X',
        format: 'HH:mm a',
        onlyTimePicker: true
    });

    // fade out alert messages
    setInterval(function(){
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 5000);


    //
    $('.nav-tabs > li').click(function(event){
        if ($(this).hasClass('disabled')) {
            return false;
        }
    });

    $('body').on('click', '.add-multipliable-input', function(){
        var input=document.createElement('input');
        input.type='text';
        input.name='Apps[permissions]['+$('.multipliable-input').length+']';
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
            if (typeof html === "object" && typeof html.state === 'undefined') {
                $.each(html, function (key, value) {
                    $("#" + key + "_em_").show().html(value.toString()).parent().removeClass('success').addClass('error');
                });
            }else
                eval(callback);
        }
    });
}