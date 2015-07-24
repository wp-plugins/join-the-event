jQuery(document).ready(function($) {
	var $jteForm = $('#join-the-event');
	$jteForm.find('input').each(function() {
		var $this = $(this)
		
		// Only allow numbers for input-type: number
		$this.keydown(function(e) {
			if ($this.prop('type') == "number") {
				if ((e.keyCode < 48 || e.keyCode > 57) && (e.keyCode < 96 || e.keyCode > 105) && $.inArray(e.keyCode, [8,13,27,38,40,46]) == -1) {
					e.preventDefault();
				} 
			}
			if ($this.hasClass('error')) {
				$this.removeClass('error');
			}
		});
	});
		
	$('#submit-jte-form').click(function(e) {
		e.preventDefault();
		var sendForm = true,
		firstname = $('#firstname').val(),
		surname = $('#surname').val(),
		mail = $('#mail').val(),
		plus = $('#plus').val(),
		_wpnonce = $('#_wpnonce').val()
		$jteForm.find('input').each(function() {
			var $this = $(this);
			
			// Check if any required field is missing
			if ($this.data('required') == true) {
				if ($this.val() == "") {
					$this.addClass('error');
					sendForm = false;
				}
			}
			
			// Check if Mail Adress is a correct format
			if ($this.prop('type') == 'email') {
				if ($this.val().indexOf("@") == -1 || $this.val().indexOf(".") == -1) {
					$this.addClass('error');
					sendForm = false;
				}
			}
			
			// Check if the number is below the maximum
			if ($this.prop('max') !== "") {
				var val = +$this.val(),
					maximum = +$this.prop('max');
				if (val > maximum) {
					$this.addClass('error');
					sendForm = false;
				}
			}
		});
		if (sendForm == false) {
			return false;
		} else {
			$.ajax({
				type: "POST",
				dataType: "HTML",
				data: {
					action: 'jte_post_guest',
					firstname: firstname,
					surname: surname,
					mail: mail,
					plus: plus,
					_wpnonce: _wpnonce
				},
				url: jteAjax.ajaxurl,
				beforeSend: function() {
					$jteForm.addClass('sending');
				},
				success: function(data, textStatus, XMLHttpRequest) {
					success(data, $jteForm);
				},
				error: function(a, b, c) {
					alert(a + b + c);
				}
			});
		}
	});
});

function success(data, $jteForm) {
	$jteForm.removeClass('sending');
	$jteForm.css('min-height', $jteForm.height());
	$jteForm.find('#jte-form').fadeOut(300, function() {
		$jteForm.append(data).hide().fadeIn(100);
	});
	$jteForm.find('#jte-form').remove();
}
