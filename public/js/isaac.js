$( document ).ready(function() {

	welcome();
    registerClicks();
 	
});


function welcome() {
	/* Nav Develop on click */
	handleMenu();
    /* First push */
    console.log("Isaac");
}

function handleMenu() {
	// BEWARE: This is totally dependant of the CSS, any modifications would break it 
	$( '#nav_trigger' ).click(function() {
		$("#menu").toggleClass('rightAngle');

		// Slide it from/to inital position 
		if($(menu).hasClass("rightAngle"))
		{
			var w = $("#title").width();

			$("#title").css("margin-left", 0);
			$("#title").css("width", w);

			$("#nav_trigger").css("left", w);

			$("#usrpnl").css("left", "0");
		}
		else
		{
			$("#title").css("margin-left", "60px");
			$("#title").css("width", "calc(100% - 60px)");
		 
			$("#nav_trigger").css("left", 0);

			$("#usrpnl").css("left", "-100%");
		}
	});

	$('.pnli').hover(function () {
		$(this).children().css("color: white");
	});
}

function registerClicks(){

    $('input[type="text"].autocomplete').keyup(function(){
        var $field = $(this);
        var $hidden = $(this).parent().find('input[name="ids"]');
        var ids = [];

        if($field.val().length > 1){
            ids = JSON.parse($hidden.val());
            var search = $field.val();
            $.getJSON( "/ajax/users/" + search, function( data ) {
                $field.parent().find('.autocomplete-box').remove();
                var div = $('<ul class="autocomplete-box"></ul>');
                $.each( data, function( key, val ){
                    var result = $.grep(ids, function(e){ return e.id == key; });
                    if(result.length == 0)
                        div.append('<li class="autocomplete-element" data-id="' + key + '">' + val + '</li>');
                });
                div.insertAfter($field);
            });
        }
        else
        {
            $field.parent().find('.autocomplete-box').remove();
        }
    });

    $('form.accept-autocomplete').on('click', 'li.autocomplete-element', function(){
        var $field = $(this).closest('form.accept-autocomplete').find('input[type="text"].autocomplete');
        var $text = $(this).closest('form.accept-autocomplete').find('.' + $field.data('target'));
        var $hidden = $(this).closest('form.accept-autocomplete').find('input[name="ids"]');
        var type = $field.data('type');
        var ids = [];
        switch(type){
            case 'list':
                ids = JSON.parse($hidden.val());
                ids.push({id:$(this).data('id'), text:$(this).html()});
                $hidden.val(JSON.stringify(ids));
                $text.empty();
                $.each(ids, function(index, e){
                    $text.append('<span data-id="' + e.id + '" class="deleleable">' + e.text + '</span>')
                });

                $field.val("").focus();
                break;

            default :
                $field.val($(this).html()).focus();
                $hidden.val($(this).data('id'));
                break;
        }
        $(this).parent().remove();
    });

    $('form.accept-autocomplete').on('click', '.deleleable', function(){
        var toDelete = $(this).data('id');
        var $field = $(this).closest('form.accept-autocomplete').find('input[type="text"].autocomplete');
        var $hidden = $(this).closest('form.accept-autocomplete').find('input[name="ids"]');
        var $text = $(this).closest('form.accept-autocomplete').find('.' + $field.data('target'));
        ids = JSON.parse($hidden.val());
        ids = ids.filter(function(e){
            return e.id != toDelete;
        });
        $hidden.val(JSON.stringify(ids));
        $text.empty();
        $.each(ids, function(index, e){
            $text.append('<span data-id="' + e.id + '" class="deleleable">' + e.text + '</span>')
        });
    })

    $('li.default a').click();
}
