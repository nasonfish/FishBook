

$('.box-maker').bind('click', function(event){
    event.preventDefault();
    $(this).after('<input type="text" class="'+$(this).attr('data-for')+'" name="'+$(this).attr('data-for')+'[]">');
});

$('.matcher').bind('click', function(event){
    event.preventDefault();
    $AJAX(
        '/ajax_matcher.php',
        'POST',
        {
            'category':$('.category').valArray(),
            type:$('.type').val()
        },
        function(data){
            $('.data').html(data);
        }
    );
});

$('.builder').bind('click', function(event){
    event.preventDefault();
    $AJAX(
        '/ajax_builder.php',
        'POST',
        {
            'category': $('.category').valArray(),
            type: $('.type').val(),
            glue: $('.glue').val(),
            data: $('.data').val()
        },
        function(data){
            $('.result').html('<pre>' + data + '</pre>');
        }
    );
});

$('#all-search').bind('keyup', function(event){
    var inp = $(this);
    $('.search').each(function(obj){
        if($(obj).attr('data-search-for').toLowerCase().indexOf(inp.val().toLowerCase()) === -1){
            $(obj).hide();
        } else {
            $(obj).css('display', 'table-row');
        }
    });
});

$('.show-toggle').bind('click', function(event){
    var target = $('#' + $(this).attr('data-for'));
    if(target.hasClass('hidden')){
        target.show().removeClass('hidden');
    } else {
        target.hide().addClass('hidden');
    }
});
