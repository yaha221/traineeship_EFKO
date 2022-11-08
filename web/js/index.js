    
    
    
    
    
    $('#calculated-form').on('beforeSubmit', function(){
        var form = $(this);
        var data = form.serialize();
        $.ajax({
            url: '/home/feedback',
            type: 'POST',
            data: data,
            success: function(data){
                $('#feedback').html(data.message);
                form.children('.has-success').removeClass('has-success');
                form[0].reset();
            },
            error: function(){
                alert('Произошла ошибка при отправке');
            }
        });
        return false;
    })