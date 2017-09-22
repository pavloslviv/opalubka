jQuery(document).ready(function($) {
	var myOptions = {
		defaultColor: '#000',
		//change: function(event, ui){ },
		//clear: function(){ },
		hide: true,
		palettes: true
	};

	$('input#callphoner_background_color').wpColorPicker(myOptions);
	$('input#callphoner_icon_color').wpColorPicker(myOptions);

	$('div.callphoner_work_checkbox input, div.callphoner_break_checkbox input').each(function() {tryCheckedSelects($(this))});
	$('div.callphoner_work_checkbox input, div.callphoner_break_checkbox input').on('change', function() {tryCheckedSelects($(this))});

	$('div.callphoner_row_day_checkbox input').each(function() {tryCheckedDays($(this))});
	$('div.callphoner_row_day_checkbox input').on('change', function() {tryCheckedDays($(this))});

	function tryCheckedSelects(_this) {
		var interval = _this.data('interval');
		var day = _this.data('day');

		if (_this.is(':checked')) {
			$('select#callphoner_'+interval+'_'+day+'_from_select, select#callphoner_'+interval+'_'+day+'_to_select').attr('disabled', true);
		} else {
			$('select#callphoner_'+interval+'_'+day+'_from_select, select#callphoner_'+interval+'_'+day+'_to_select').removeAttr('disabled');
		}
	}

	function tryCheckedDays(_this) {
		var _work = _this.parent().next('div');
		var _break = _work.next('div');

		var _work_checkbox = _work.find('input');
		var _break_checkbox = _break.find('input');

		if (_this.is(':checked') == false) {
			_work.find('select, input').attr('disabled', true);
			_break.find('select, input').attr('disabled', true);
		} else {
			_work.find('select, input').removeAttr('disabled');
			_break.find('select, input').removeAttr('disabled');
			tryCheckedSelects(_work_checkbox);
			tryCheckedSelects(_break_checkbox);
		}
	}
});