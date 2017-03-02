
$('#showArchived').on("click", function() {
    if ($('#showArchived').is(':checked')) {
        $('tr.archived').css('display', 'table-row');
    } else {
        $('tr.archived').css('display', 'none');
    }
});

$('#simpleError').click(function () {
    if($('#simpleError').is(':checked')){
        $('.errorLvl0').css('display','list-item');
    } else {
        $('.errorLvl0').css('display','none');
    }
});

$('#seriousError').click(function () {
    if($('#seriousError').is(':checked')){
        $('.errorLvl1').css('display','list-item');
    } else {
        $('.errorLvl1').css('display','none');
    }
});

$('#warningError').click(function () {
    if($('#warningError').is(':checked')){
        $('.errorLvl2').css('display','list-item');
    } else {
        $('.errorLvl2').css('display','none');
    }
});
