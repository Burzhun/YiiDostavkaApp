  	$(function(){
	

  		$.fn.openPop = function(){

  			obj =  $(this);

  
			if($(".bgFon").length) {		 
			}else{			 
				$("body").append("<div class='bgFon'></div>");
			}
			if(obj.find(".closePop").length) {		 
			}else{			 
				obj.prepend("<div class='closePop'>закрыть X</div>");
			}

			obj.addClass("popBoxTrue");	  		 

	  		// настройки
			var  widthPop = 440,
			 	 heightPop = "auto",
			 	 bgFonOpacity = 0.4,
			 	 duration = 100,
			 	 zIndex = 100;
			 	 bgFonColor = "#000";
			//

			var bgFon = $(".bgFon"),
			    topPop = $(window).scrollTop()+$(window).height()/2;
			    

			   bgFon.on("click",function(){
			   		$(".bgFon").fadeOut(100);
			    	 
			    	obj.fadeOut(100).removeClass("popBoxTrue");		    	 

			    })
			   $(document).keydown(function(e) { 
				    if( e.keyCode === 27 ) { 
				    	$(".bgFon").fadeOut(100);			    	 
			    	obj.fadeOut(100).removeClass("popBoxTrue");		    	
				    	 
				    } 
				});

			 
					bgFon.css({
						left:0,
						right:0,
						top:0,
						bottom:0,
						position:"fixed",
						opacity:bgFonOpacity,
						background:bgFonColor,
						zIndex:zIndex,
						 display:"none"
					}) 
			obj.css({
				top:topPop,
				left:50+"%",
				marginLeft:-widthPop/2,
				width:widthPop,
				height:heightPop,				 
				position:"absolute",				 
				zIndex:zIndex+1,
				display:"none",
			})

			obj.css({
			marginTop:-obj.height()/2,

			})





			 	 
			 bgFon.fadeIn(duration);			 
			 obj.fadeIn(duration);

			 if(obj.offset().top < 0){
			  	obj.css({
				marginTop:0,
				top:20,
				})
			  }

			   if(obj.offset().left < 0){
			  	obj.css({
				marginLeft:0,
				left:10,
				})
			  }

			  function closePop(close){
			close.parent().fadeOut(100).removeClass("popBoxTrue");
			if($(".popBoxTrue").length) {		 
			}else{
				$(".bgFon").fadeOut(100);
			}
			  }


			$("body").on("click",".closePop", function(){
				closePop($(this))

			})
		}


			$.fn.closePop = function(){			  
				$(this).fadeOut(100).removeClass("popBoxTrue");
				if($(".popBoxTrue").length) {		 
				}else{
					$(".bgFon").fadeOut(100);
				}


			}



})

