<script>
(function ($) {
	$(document).ready(function(){
		$('#start_test').click(function(e){
			e.preventDefault();
			const destination = $(this).attr('href');
			navigator.mediaDevices
				.getUserMedia({
					audio: true,
					video: true
				})
				.then(function (stream) {
					window.location.href = destination;
				})
				.catch(function (error) {
					$('#start_test').attr({
						'aria-disabled': true,
						'title': error.message,
					})
					.addClass('disabled')
					.after(`<span class="text-danger my-auto ml-3">Media ${error.message}.</span>`);;
				});
		});
	});
})(jQuery);
</script>