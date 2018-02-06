$(function()
{
	$(window).scroll(function() {
		var windowTop=$(window).scrollTop();
		if(windowTop>56){
			var leftBox = $('.cartIcon');
			leftBox.css({
				position: 'fixed',
				top: '6px',
			});
		}else{
			var leftBox = $('.cartIcon');
			leftBox.css({
				position: 'relative',
				top: '0px',
			});
		}
	});
	$(".menuHead").click(function()
	{
		$(".menuHead").toggleClass("menuOpen")
		$(".menu").toggleClass("menuMainOpen")
	})

	$(".menuHeadRight").click(function()
	{
		$(".menuHeadRight").toggleClass("menuOpen")
		$(".userDropBox").toggleClass("openUserDropBox")
	})

	$(document).on("click",".orderLink",function()
	{
		$(this).parent().toggleClass("activeOrder")
		var value = $(this).data('goodid');
		$.ajax({
			url:'/cart/add',
			type:"post",
			cache:false,
			data:{"product":value, "count":1},
			success:function(data){
				getMobileBasket();
			}
		});
		return false
	})

	$(document).on("click",".plus",function()
	{
		var value = $(this).data('goodid');
		$.ajax({
			url:'/cart/add',
			type:"post",
			cache:false,
			data:{"product":value, "count":1},
			success:function(data){
				getMobileBasket();
			}
		});
		$('#num_' + value).text(Number($('#num_' + value).text()) + 1);
		return false
	})


	$(document).on("click",".minus",function()
	{
		var value = $(this).data('goodid');
		$.ajax({
			url:'/cart/minusProduct',
			type:"post",
			cache:false,
			data:{"product":value, "count":1},
			success:function(data){
				getMobileBasket();
			}
		});
		if(Number($('#num_' + value).text()) == 1) {
			$(this).parent().parent().toggleClass("activeOrder");
			$.ajax({url:'/cart/deleteProduct', type:"post", cache:false, data:{"product":value},});
		} else {
			$('#num_' + value).text(Number($('#num_' + value).text()) - 1);
		}
		return false;
	})

	$(".shortLIcon").click(function()
	{
		$(".shortLIcon").toggleClass("activeTopnav")
		$(".popShort").openPop();
		return false
	})

	$(".searchIcon").click(function()
	{
		$(".searchIcon").toggleClass("activeTopnav")
		$(".popSearch").openPop();
		return false
	})

	$("body").on("click",".bgFon",function()
	{
		$(".activeTopnav").removeClass("activeTopnav")
	})





	$(document).on("click",".likeBox label",function(){

		$(".likeBox label").removeClass("activeRadio");
		$(this).addClass("activeRadio");

	})
})