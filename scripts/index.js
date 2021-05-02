// external js file
$(document).ready(function () {

	// job submission
	const $jobForm = $('form[name="form_job"]');
	const $outputScreen = $('#output-screen');
	
	$jobForm.on('submit', function (event) {
		event.preventDefault();
		event.stopPropagation();
		
		var formData = new FormData(this);
		const URL = $(this).attr('action');
		/* for (var value of formData.values()) { console.log(value); } */
	
		$outputScreen.removeClass('hidden').slideDown()
			.html('<p class="loading-message"><span><img src="images/loading.gif"> Job running...please wait</span></p>')
			.removeClass('hidden');
		$jobForm.find('.form-content').addClass('hidden').slideUp();
		
		$.ajax({
			url: URL,
			type: 'POST',
			contentType: false,
			processData: false,
			data: formData,
			success: function (response) {
				$outputScreen.removeClass('hidden').slideDown().html(response);
				$jobForm.find('.form-content').addClass('hidden').slideUp();
			}
		});
		
	});
	
	$(document).on('click', '.output-screen-toggle', function (ev) {
		
		$outputScreen.slideToggle();
		$jobForm.find('.form-content').slideToggle();
	});
});