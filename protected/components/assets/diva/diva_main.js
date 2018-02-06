
window.diva = new Object();

window.diva.nice_popup = function(url, name, ww, wh){
		
	if (!ww) ww = '80%';
	if (!wh) wh = '80%';
	if (!name) name = '_blank';

	// define main window
	var win = window;
	while (win.opener) {
		win = win.opener;
	}

	// define main width and height
	var w = jQuery(win).width();
	var h = jQuery(win).height();

	if (ww.substr(-1) == '%'){
		wp = ww.substr(0, ww.length-1);
		//alert(ww);
		//alert(wp);
		var new_w = parseInt(w*wp/100);
	} else {
		var new_w = ww;
	}

	if (wh.substr(-1) == '%'){
		hp = wh.substr(0, wh.length-1);
		var new_h = parseInt(h*hp/100);
	} else {
		var new_h = wh;
	}

	var l = parseInt((w - new_w) / 2);
	var t = parseInt((h - new_h) / 2);


	return window.open(url, name, "width="+new_w+",height="+new_h+",left="+l+",top="+t+",scrollbars=yes");

};
	
	
	
window.diva.autocomplete_prepare_object_id = function(_this, input_name){
	
	// создаем (или берем имеющийся) инпут для id
	var id_input = $(_this).prev('.autocomplete-input-id');
	if (!id_input.length){
		id_input = $('<input type="hidden" class="autocomplete-input-id" name="'+input_name+'" />')
		id_input.insertBefore(_this);
	} 
	
	// берем вычленяем айди из значения инпута автокомплита
	var reg_exp = /^#([0-9]+) /;
	var arr = reg_exp.exec($(_this).val());
	
	if (arr[1]){
		id_input.val(arr[1]);
	} else {
		id_input.val('');
	}
	
}