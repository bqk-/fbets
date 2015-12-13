/* Admin JS file */

$(function() {
    $('.refreshlink').click(function(e){
        e.preventDefault();
        $.ajax({
            type: "GET",
             url: '{{ URL::to('/admin/refresh/bets') }}/'+$(this).data('id'),
            context: $(this),
             success: function(data) {
                $(this).parent().parent().find('.ajax-content').html(data);
             }
        }

        );
    });

    $('.refreshTime').click(function(e){
        e.preventDefault();
        $.ajax({
            type: "GET",
             url: '{{ URL::to('/admin/refresh/time') }}/'+$(this).data('id'),
            context: $(this),
             success: function(data) {
                $(this).parent().parent().find('.ajax-content').html(data);
             }
        }

        );
    });

    $('.active').click(function(e){
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: '{{ URL::to('/admin/active/champ') }}/'+$(this).data('id'),
            context: $(this),
            success: function(data) {
                if($(this).parent().find('.active').hasClass('glyphicon-ok-circle')){
                    $(this).parent().find('.active').removeClass('glyphicon-ok-circle').addClass('glyphicon-remove-circle');
                    $(this).parent().find('.active').removeClass('text-success').addClass('text-danger');
                }
                else{
                    $(this).parent().find('.active').removeClass('glyphicon-remove-circle').addClass('glyphicon-ok-circle');
                    $(this).parent().find('.active').removeClass('text-danger').addClass('text-success');
                }
            }
        });
    });

    $('.team1').keyup(function(){
        var s = $(this).val();
        $.ajax({
            type: 'GET',
            url: '{{ URL::to('/ajax/autocomplete/logo') }}/'+s,
            context: $(this),
            success: function(data){
                $('.logo1').html(data);
                if($('.logo1 img').length==1){
                    $('input[name="hlogo1"]').val($('.logo1 img').data('id'));
                    $('.logo1 img').addClass('clicked');
                }
            }
        });
    });

    $('.team2').keyup(function(){
        var s = $(this).val();
        $.ajax({
            type: 'GET',
            url: '{{ URL::to('/ajax/autocomplete/logo') }}/'+s,
            context: $(this),
            success: function(data){
                $('.logo2').html(data);
                if($('.logo2 img').length==1){
                    $('input[name="hlogo2"]').val($('.logo2 img').data('id'));
                    $('.logo2 img').addClass('clicked');
                }
            }
        });
    });

    $('#processBets').click(function(e){
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: '{{ URL::to('/admin/processbets') }}/',
            context: $(this),
            success: function(data){
                $('.ajax-modal').html(data);
                $('.modal').show();
            }
        });
    });

    $('[data-dismiss="modal"]').click(function(){
         $('.modal').hide();
    });

    $('.team1').trigger('keyup');
    $('.team2').trigger('keyup');

    $('#suggok').click(function() {
        var c = $('#suggselect').val();
        var id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '{{ URL::to('/ajax/suggestions/ok') }}/'+id+'/'+c,
            context: $(this),
            success: function(data){
                if(data != '')
                    $(this).parent().parent().find('.state').html('<span class="text-success">Approved</span>');
                else
                    $(this).parent().parent().find('.state').html('<span class="text-danger">Erreur...</span>');
            }
        })
    });

    $('#suggno').click(function() {
        var id = $(this).data('id');
        $.ajax({
            type: 'GET',
            url: '{{ URL::to('/ajax/suggestions/no') }}/'+id,
            context: $(this),
            success: function(data){
                if(data != '')
                    $(this).parent().parent().find('.state').html('<span class="text-danger">Rejected</span>');
                else
                    $(this).parent().parent().find('.state').html('<span class="text-danger">Erreur...</span>');
            }
        })
    });

});