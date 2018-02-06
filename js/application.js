$(function(){

	 setTimeout('openAd()', 30000);
	

var offsetY = 200;
var userScrolledY = $(window).scrollTop() + offsetY;

	findUnderElements($(window).scrollTop() + $(window).height(), offsetY);

	$(window).scroll(function() {
		if($(window).scrollTop() > userScrolledY) {

			userScrolledY = $(window).scrollTop() + offsetY;

			findUnderElements(userScrolledY + $(window).height(), offsetY);

		 }
	});
});




$('body').on('click', '.close-add, .morePopAd', function(event) {
	closeAd();
	hideAdPop();
})

$('body').on('click', '.closePop', function(event) {
	closeVoiceImage();
})


$('body').on('click', '.submit-search-image', function(event) {
	event.preventDefault();
	var name = $(this).siblings('.good-name').val();
	var id = $('#orig-update-image').val();

	$.ajax({
		url: '/restorany/getImages',
		data: {name: name, id: id},
	})
	.done(function(data) {
		$('.openVoiceImage').html(data);
	})
	.fail(function() {
		console.log("error");
	})
});

$('body').on('click', '.save-image', function(event) {
	event.preventDefault();

	if(confirm("Сохранить картинку?")){
		var current = $(this);
		var id = $(this).attr('data-id');
		var origId = $('#orig-update-image').val();

		$.ajax({
			url: '/restorany/updateGoodImage',
			data: {id: id, origId: origId},
			beforeSend: function(){
				current.addClass('updateLoader');
			}
		})
		.done(function(data) {
			current.removeClass('updateLoader');
			$('.save-image').removeClass('red');
			current.addClass('red');
			$('.updateImgSuccess'+origId).attr('src',data);
			// $('.openVoiceImage').html(data);
		})
		.fail(function() {
			console.log("error");
		})
	}

});


$('body').on('click', '.editor-image', function(event) {
	event.preventDefault();
	var name = $(this).attr('data-name');
	var id = $(this).attr('data-id');

	openVoiceImage();

	$.ajax({
		url: '/restorany/getImages',
		data: {name: name, id: id},
	})
	.done(function(data) {
		$('.openVoiceImage').html(data);
	})
	.fail(function() {
		console.log("error");
	})
});

function openVoiceImage(){
	$('#parent_popup').show();

	$('.openVoiceImage').css({'top': $(window).scrollTop() + 'px'})
	$('.openVoiceImage').show();
}

function closeVoiceImage(){
	$('#parent_popup').hide();
	$('.openVoiceImage').hide();
}

function findUnderElements(coordY, offset){
	$('.blok_order:not(.scrolled)').each(function(index, el) {
		var top = $(this).offset().top;
		if(top<coordY + offset){
			$(this).addClass('scrolled');
			$(this).find('.updateImg').each(function(index, el) {
				$(this).attr('src', $(this).attr('data-id'));
			});
		}
	});
}


function openAd(){
	if($('.openAd').length && $('#pop-up-bascet').length == 0){

		$('#parent_popup').show();
		if($('.popmodile').length != 0){
			$('.openAd').css({'top': $(window).scrollTop()+ 100 + 'px', 'width': $(window).width()-50+'px', 'margin-left':-Math.round(($(window).width()-50)/2)+'px'})
		}else{
			$('.openAd').css({'top': $(window).scrollTop()+ 100 + 'px'})
		}
		$('.openAd').show();
	}
}

function closeAd(){
	$('#parent_popup').hide();
	$('.openAd').hide();
}

function hideAdPop(){
	$.ajax({
		type: "GET",
		url: '/site/hidePop/',
		success: function(list){
		}
	});
}