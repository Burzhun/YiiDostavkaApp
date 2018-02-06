$(document).ready(function() {
$('body').on('submit', '.ds-form form', function(){
var formid =  $(this).parents('.ds-form').attr("id");
if (!window.FormData) {
var dataform = $(this).serialize();
	dataform = dataform + '&formid=' + formid;
	$.ajax({
		type: "POST",
		url: "/ds-comf/ds-form/form.php",
		dataType:  "json",  
		cache: false,
		data: dataform,
		success: formpost
	});

} else {
	var formData = new FormData($(this).get(0));
	formData.append('formid', formid);
	$.ajax({
        url: "/ds-comf/ds-form/form.php",
        type: "POST",
        contentType: false,
        processData: false,
        data: formData,
        dataType: 'json', 
     	success: formpost
	});
}



	return false;
});

function formpost(data) {
			$.jGrowl.defaults.check= 300;
			$.jGrowl.defaults.closerTemplate = '<div>[ Закрыть все ]</div>';
			if($('#callme .buttonform').css('left')!='150px') {
				$('#callme .buttonform').animate({'left':'150px'}, 1200);
				return false;
			}
			formid = data['formid'];
			delete(data['formid']);
			if(data['error'] == 1) {
				 delete(data['error']);
				 if(formid!='callme') {
				 $('#'+formid + ' .error_form').empty();
				 var error_array = [];
				 $.each(data, function(index, val) {
				 	if($.inArray(val, error_array) == -1 && val!=1) error_array.push(val);
				 	$('#' + formid + ' *[name="'+ index +'"]').addClass('alert');
				 });
				 $('#' + formid + ' *[required]').each(function(){
				  	var pole = $(this);
				  	if(pole.hasClass('alert') && !data[pole.attr('name')]) {
				  		pole.removeClass('alert');
				  	}
				 });
				 var error_text = '<ul class="error-form">'+"\n";
				 $.each(error_array, function(index, val){
				 	error_text+='<li>'+val+'</li>'+"\n"; 
				 })
				 error_text+= '</ul>'+"\n";
				$('#'+formid + ' .error_form').append(error_text);
				//$('#'+formid).css('height','inherit');
				} else {
					$('#callme .youtelefon input').addClass('alert');
					$.jGrowl("Некорректный номер", {theme: 'callme-error', life:4000, speed:0});
				}

			} else if(data['error'] == 0 || data['error'] == 2) {
				if(formid!='callme') {
					$('#' + formid + ' form').remove();
					//$('#'+formid).css('height','inherit');
					$('#' + formid).append(data['error_text']);
				} else {
					if(data['error' ] == 0) $.jGrowl('Cообщение отправлено!', {theme: 'callme-report', life:4000, speed:0});
					if(data['error' ] == 2) $.jGrowl('Cообщение не отправлено!', {theme: 'callme-error', life:4000, speed:0});
					setTimeout(function() { 
						$('#callme .buttonform').animate({'left':0}, 1200);
						setTimeout(function() { 
							$('.youtelefon input').val('');
						}, 1200);
					}, 2000);
				}
			}
		}


$('body').on('keyup','form input,form textarea', function(){
	var pole = $(this);
	if(pole.attr('pattern') && !pole.val().match(pole.attr('pattern'))) {
		pole.addClass('alert');
	} else if(pole.attr('pattern') && pole.hasClass('alert')) {
		pole.removeClass('alert');
	}
	if(!pole.attr('pattern') && pole.hasClass('alert')) {
		pole.removeClass('alert');
	}
});
$('body').on('focusin', 'form input, form textarea, form select', function(){
		var formid = '#' + $(this).parents('form').attr("id");
		$('form input[type="text"], form textarea, form select').each(function(){
			$(this).removeClass('focusout');
		});
});
$('body').on('focusout','form input[type="text"],form textarea, form select', function(){
	$(this).addClass('focusout');
});
$('*[data-reveal-id]').each(function () {
	var modalLocation = $(this).attr('data-reveal-id');
	$('#' + modalLocation).addClass('reveal-modal');
});

$('.ds-form').each(function () {
		var formid = $(this).attr("id");
		addForm(formid);
});

$('body').on('click', '.repeatform', function(){
	var formid = $(this).parents('div.ds-form').attr('id');
	$('.error-report').remove();
	addForm(formid);
});

function addForm(formid) {
		$.ajax({
		url: "/ds-comf/ds-form/formtpl.php",
	    type: "POST",
		dataType: "text",
		data: "formid=" + formid, 
		cache: false,
		success: function(data) {
			if(data!="error") {
				$('#'+formid).append(data);
				 if(!$('#'+formid).hasClass('reveal-modal')) {
					$('#' + formid + ' *[autofocus]').focus();
				 }
				if (!window.FormData) {
				   $('#' + formid + ' *[type="file"]').css('display','none');
				}
			}
		}
		});
}

/*---------------------------
 Defaults for Reveal
----------------------------*/

/*---------------------------
 Listener for data-reveal-id attributes
----------------------------*/
	$('*[data-reveal-id]').on('click', function(e) {
		e.preventDefault();
		var modalLocation = $(this).attr('data-reveal-id');
		var withForm = $('#'+modalLocation).innerWidth();
		var heightForm = $('#'+modalLocation).innerHeight();
		var windowHeight = $(window).height();
		if ( windowHeight  <= (heightForm + 20)) {
			var topForm=20;
			var marginTopForm=0;
			var overflowDiv = 'scroll';
			var contentForm = $('#'+modalLocation).html();
			var paddingForm = heightForm - $('#'+modalLocation).height();
			$('#'+modalLocation).html('<div class="scrollform">' + "\n" + contentForm + '</div>');
			heightForm = windowHeight - 40 - paddingForm;
			$('.scrollform').css('height', heightForm - paddingForm);
			$('.scrollform').css('overflow-y' , 'scroll');
			$('#'+modalLocation).css('overflow' , 'hidden');
		} else {
			var topForm=Math.round($(window).innerHeight()/2);
			var marginTopForm=-Math.round(heightForm/2);
			var overflowDiv = 'visible';
			heightForm = $('#'+modalLocation).height();
		}

		$('#'+modalLocation).append('<div class="close-reveal-modal"></div>');
		setTimeout(function(){
			$('#'+ modalLocation + ' *[autofocus]').focus();
		}, 1000);
		
		$('#'+modalLocation).reveal($(this).data());

		$('#'+modalLocation).css({
			'top':topForm + 'px',
			'left':'50%',
			'margin-left': -Math.round(withForm/2) + 'px',
			'margin-top': marginTopForm + 'px',
			//'height' : heightForm,
		});

	});

/*---------------------------
 Extend and Execute
----------------------------*/

    $.fn.reveal = function(options) {
        
        
        var defaults = {  
	    	animation: 'fade', //fade, fadeAndPop, none
		    animationspeed: 300, //how fast animtions are
		    closeonbackgroundclick: true, //if you click background will modal close?
		    dismissmodalclass: 'close-reveal-modal' //the class of a button or element that will close an open modal
    	}; 
    	
        //Extend dem' options
        var options = $.extend({}, defaults, options); 
	
        return this.each(function() {
        
/*---------------------------
 Global Variables
----------------------------*/
        	var modal = $(this),
        		topMeasure  = parseInt(modal.css('top')),
				topOffset = modal.height() + topMeasure,
          		locked = false,
				modalBG = $('.reveal-modal-bg');

/*---------------------------
 Create Modal BG
----------------------------*/
			if(modalBG.length == 0) {
				modalBG = $('<div class="reveal-modal-bg" />').insertAfter(modal);
			}		    
     
/*---------------------------
 Open & Close Animations
----------------------------*/
			//Entrance Animations
			modal.bind('reveal:open', function () {
			  modalBG.unbind('click.modalEvent');
				$('.' + options.dismissmodalclass).unbind('click.modalEvent');
				if(!locked) {
					lockModal();
					if(options.animation == "fadeAndPop") {
						modal.css({'top': $(document).scrollTop()-topOffset, 'opacity' : 0, 'visibility' : 'visible'});
						modalBG.fadeIn(options.animationspeed/2);
						modal.delay(options.animationspeed/2).animate({
							"top": $(document).scrollTop()+topMeasure + 'px',
							"opacity" : 1
						}, options.animationspeed,unlockModal());					
					}
					if(options.animation == "fade") {
						modal.css({'opacity' : 0, 'visibility' : 'visible', 'top': $(document).scrollTop()+topMeasure});
						modalBG.fadeIn(options.animationspeed/2);
						modal.delay(options.animationspeed/2).animate({
							"opacity" : 1
						}, options.animationspeed,unlockModal());					
					} 
					if(options.animation == "none") {
						modal.css({'visibility' : 'visible', 'top':$(document).scrollTop()+topMeasure});
						modalBG.css({"display":"block"});	
						unlockModal()				
					}
				}
				modal.unbind('reveal:open');
			}); 	

			//Closing Animation
			modal.bind('reveal:close', function () {
			  if(!locked) {
					lockModal();
					if(options.animation == "fadeAndPop") {
						modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
						modal.animate({
							"top":  $(document).scrollTop()-topOffset + 'px',
							"opacity" : 0
						}, options.animationspeed/2, function() {
							modal.css({'top':topMeasure, 'opacity' : 1, 'visibility' : 'hidden'});
							unlockModal();
						});					
					}  	
					if(options.animation == "fade") {
						modalBG.delay(options.animationspeed).fadeOut(options.animationspeed);
						modal.animate({
							"opacity" : 0
						}, options.animationspeed, function() {
							modal.css({'opacity' : 1, 'visibility' : 'hidden', 'top' : topMeasure});
							unlockModal();
						});					
					}  	
					if(options.animation == "none") {
						modal.css({'visibility' : 'hidden', 'top' : topMeasure});
						modalBG.css({'display' : 'none'});	
					}		
				}
				modal.unbind('reveal:close');
			});     
   	
/*---------------------------
 Open and add Closing Listeners
----------------------------*/
        	//Open Modal Immediately
    	modal.trigger('reveal:open')
			
			//Close Modal Listeners
			var closeButton = $('.' + options.dismissmodalclass).bind('click.modalEvent', function () {
			  modal.trigger('reveal:close')
			});
			
			if(options.closeonbackgroundclick) {
				modalBG.css({"cursor":"pointer"})
				modalBG.bind('click.modalEvent', function () {
				  modal.trigger('reveal:close')
				});
			}
			$('body').keyup(function(e) {
        		if(e.which===27){ modal.trigger('reveal:close'); } // 27 is the keycode for the Escape key
			});
			
			
/*---------------------------
 Animations Locks
----------------------------*/
			function unlockModal() { 
				locked = false;
			}
			function lockModal() {
				locked = true;
			}	
			
        });//each call
    }//orbit plugin call

});