var iWebFunctions = {
    splitNumber: function(amount){
        var pattern = /[^0-9]+/g;
        amount = amount.replace(pattern, "");
        amount.replace(new RegExp("(,)", 'ig'),",");
        var str = new Array();
        while (amount !== "")
        {
            str.push(amount.substring(amount.length - 3, amount.length));
            amount = amount.substring(0, amount.length - 3);
        }
        var j = 0;
        var newstr = new Array();
        for (i = str.length - 1; i >= 0; i--)
        {
            newstr[j] = str[i];
            j++;
        }
        return newstr;
    },
    filterPersian: function(amount){
        var pattern = /[^\u0600-\u06FF\s]/g;
        amount = amount.replace(pattern, "");
        return amount;
    }
}

// general events
$(function(){

    $(document).on('keydown','.just-number',function(e){

        // Allow: backspace, delete, tab, escape and enter
        var allowed = [46, 8, 9, 27, 13, 110];

        // for allow dot(for decimal numbers) input must has dot-allowed class
        if($(this).hasClass('dot-allowed'))
            allowed.push(190);

        // check allowed variable values
        if ($.inArray(e.keyCode, allowed) !== -1 ||
                // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
                // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $(document).on('keydown','.just-letter',function(e){

        // Allow: backspace, space, delete, tab, escape and enter
        var allowed = [46, 8, 9, 27, 13, 110, 32];

        // for allow dot(for decimal numbers) input must has dot-allowed class
        if($(this).hasClass('dot-allowed'))
            allowed.push(190);

        // check allowed variable values
        if ($.inArray(e.keyCode, allowed) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        var allowedChars=[219,220,221,222,59,192,188];
        if ((e.shiftKey || (e.keyCode < 65 || e.keyCode > 90)) && $.inArray(e.keyCode, allowedChars) === -1) {
            e.preventDefault();
        }
    });
});