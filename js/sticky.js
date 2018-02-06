$.fn.stickyfloat = function(options, lockBottom) {
	var $obj = this;
	$obj.css({ position: 'absolute' });
	
	$(window).on("scroll", function () {
		var obj_y= $obj.offset().top+100;
		
		var footer_top=$('#footer').offset().top;
		
		var win_h=$(window).height();
		var win_h2=$(window).scrollTop();
		win_h=win_h+win_h2-100;
		
		if(win_h>=footer_top-100)
		{
			$(".bascet").css("position","absolute")
		}else
		{
			$(".bascet").css("position","fixed")
		}
	});
	
	$(window).trigger("scroll");
};