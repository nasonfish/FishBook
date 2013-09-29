$('.box-maker').bind('click', function(event){
    event.preventDefault();
    $(this).after('<input type="text" name="'+$(this).attr('data-for')+'[]">');
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
            'category':$('.category').valArray(),
            type:$('.type').val(),
            glue:$('.glue').val()
        },
        function(data){
            $('.result').html('<pre>' + data + '</pre>');
        }
    );
});