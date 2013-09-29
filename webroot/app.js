var bottomBar = function(){
    // This will actually happen, not bound to an event.
    if(document.body.clientHeight > ("innerHeight" in window ? window.innerHeight : document.documentElement.offsetHeight)){// - $('.footer').height();
        $('.bottom').css('position', 'relative').css('bottom', '0');
    } else {
        $('.bottom').css('position', 'absolute').css('bottom', '0');
    }
};
bottomBar();

$('.box-maker').bind('click', function(event){
    event.preventDefault();
    $(this).after('<input type="text" name="'+$(this).attr('data-for')+'[]">');
    bottomBar();
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
            bottomBar()
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
            bottomBar();
        }
    );
});