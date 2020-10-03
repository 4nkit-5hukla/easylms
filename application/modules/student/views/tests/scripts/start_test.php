<script src="<?php echo base_url(THEME_PATH . 'assets/js/vendor/moment.min.js'); ?>"></script>
<script>
(function ($) {
	let mediaRecorder, recStartTime;
	/*Multi Select questions checkboxes toggle*/
	function mcmc_deselect (blankid, qid) {
		if ( blankid.checked == true) {
			/*leave blank selected*/
			for ( var i=1; i <= 6; i++) {
				itemid = document.getElementById ('mc_'+qid+'_'+i);
				if ( itemid.checked == true ) {
					itemid.checked = false;
				}
			}
		}
	}
	
	/*Multi Select questions checkboxes toggle*/
	function mcmc_select (qid, blankid) {
		itemid = document.getElementById (blankid);
		if ( itemid.checked == true) {
			/*leave blank selected*/
			itemid.checked = false;
		}
	}
	function match_deselect (blankid, qid) {
		if ( blankid.checked == true ) {
			for ( var i=1; i <=6; i++) {
				document.getElementById('ans_'+qid+'_'+i).value = "0";
			}
		}
	
	}
	function match_select (blankid) {
		item = document.getElementById(blankid);
		if ( item.checked == true ) {
			item.checked = false;
		}
	}
	function display_question(id) {
		var next = parseInt (id) + 1;
		var prev = parseInt (id) - 1 ;
		var first = 1;
		var last = <?php echo $total_questions; ?>;
		var confirm_div = <?php echo $confirm_div; ?>;
		$('.pages').hide();
		$('#page'+id).show();
		$('#num_question').val(id);
		// console.log(id, next, prev, first, last, confirm_div);
		if(id > 1){
			$(".previous").removeClass('d-none');
			// $(".next").addClass('d-none');
			if(id >= 2 && id <= last){
				$(".next").removeClass('d-none');
			}
		}else{
			$(".previous").addClass('d-none');
			$(".next").removeClass('d-none');
		}
	}
	function show_last () {	
		var last = <?php echo $total_questions; ?>;
		$('.pages').hide();
		$('#page'+last).show();
	}
	function show_first () {	
		var first = 1;
		$('.pages').hide();
		$('#page'+first).show();
		$('#num_question').val (first);	
	}
	const handleFail = function (error) {
		console.error(error);
	};
	const handleSuccess = function (stream) {
		const options = {
			mimeType: 'video/webm'
		};
		mediaRecorder = new MediaRecorder(stream, options);
		recStartTime = moment();
		mediaRecorder . start(10000);
		mediaRecorder.addEventListener('dataavailable', function (e) {
			if (e.data.size > 0) {
				fetch(`<?php echo site_url("student/tests_actions/save_chunks/$coaching_id/$member_id/$course_id/$test_id"); ?>/${recStartTime.format('D, MMM YYYY')} Time ${recStartTime.format('hh-mm-ss')}`, {
						method: 'POST',
						headers: {
							'Content-Type': 'application/octet-stream',
						},
						body: e.data
					})
					.then((res) => {
						return res.json();
					})
					.then((data) => {
						if (data.status) {
							if (mediaRecorder.state == 'inactive') {
								$('#submit-test').val('Submitting...')
								.prop('disabled', true)
								.parents("#test_form")
								.trigger('submit');
							}
						}
					}).catch((error) => console.error(error));
			}
		});
	};
	$(document).ready (function () {
		navigator.mediaDevices
			.getUserMedia({
				audio: {
					echoCancellation: true,
					googEchoCancellation: true,
					googAutoGainControl: true,
					googNoiseSuppression: true,
					googHighpassFilter: true,
					googNoiseReduction: true
				},
				video: true
			})
			.then(handleSuccess)
			.catch(handleFail);
		/*Hide All Questions on First Load and show only section 1*/
		$("body").addClass('menu-sub-hidden main-hidden sub-hidden test-mode');
		/*$("main").css({
			'margin-top': `calc(${$("main").css('margin-top')} - ${$('nav').outerHeight()}px)`,
			'margin-bottom': `calc(${$("main").css('margin-top')} + ${$('.card.fixed-bottom').outerHeight()}px)`
		});*/
		$(".pages,nav,.main-menu,.page-footer").hide();
		$("#page1").show ();
		const finishTime = Date.now() + (<?php echo $test_duration; ?> * 1000);
		const fiveMinutesBefore = ((<?php echo $test_duration; ?> - 300) * 1000);
		const oneMinutesBefore =  ((<?php echo $test_duration; ?> - 60) * 1000);
		var x = setInterval(()=>{
			// Get current unix timestamp time
			const now = Date.now();
			// Find the timeRemain between now and the time finishTime
			const timeRemain = finishTime - now;
	
			if (timeRemain < 0) {
					clearInterval(x);
				$("#submit-test").trigger('click').prop('disabled', true);
				toastr.success('Time is up. Submitting test now.');
					return false;
			}
			const hoursRemain = Math.floor((timeRemain % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			const minutesRemain = Math.floor((timeRemain % (1000 * 60 * 60)) / (1000 * 60));
			const secondsRemain = Math.floor((timeRemain % (1000 * 60)) / 1000);
			$("#hours").text((hoursRemain<10)?`0${hoursRemain}`:hoursRemain);
			$("#minutes").text((minutesRemain<10)?`0${minutesRemain}`:minutesRemain);
			$("#seconds").text((secondsRemain<10)?`0${secondsRemain}`:secondsRemain);
		}, 1000);
	
		if(fiveMinutesBefore > 0){
			setTimeout(() => {
				toastr.warning('Only 5 minutes remaining');
			}, fiveMinutesBefore);
		}
		setTimeout(() => {
			toastr.error('Hurry up, 1 minute remaining');
		}, oneMinutesBefore);
	
		$(".next").click(function() {
			
			/*Hide/Show question blocks*/
			/*$(".pages:visible").hide().next(".pages").andSelf().last().show();*/
			var id = $('#num_question').val();
			var next = parseInt (id) + 1;
			var last = <?php echo $total_questions; ?>;
			var confirm_div = <?php echo $confirm_div; ?>;
			if( next > 1 ){
				$(".previous").removeClass('d-none');
			}
			if ( next == confirm_div ){
				$(".next").addClass('d-none');
			}
			$(".pages").hide();
			$("#page"+next).show();
			$('#num_question').val (next);
	
			/*Change color of progress buttons and increment values for - Answered, Not-answered and For-review*/
			if (document.getElementById("leaveblank_"+id).checked == true ) {
				/*Change color*/
				document.getElementById("btn_"+id).className="btn btn-sm btn-warning";
			} else {
				/*Change color*/
				document.getElementById("btn_"+id).className="btn btn-sm btn-success";
			}
	
			if (document.getElementById("visitlater_"+id).checked == true ) {
				/*Change color*/
				document.getElementById("btn_"+id).className="btn btn-sm btn-danger";
			}	
		});
	
		$(".previous").click(function() {
			
			/*Hide/Show question blocks*/
			/*$(".pages:visible").hide().next(".pages").andSelf().last().show();*/
			var id = $('#num_question').val();
			var prev = parseInt (id) - 1 ;
			var first = 1;
			var confirm_div = <?php echo $confirm_div; ?>;
			if( first == prev ){
				$(".previous").addClass('d-none');
			}
			if ( ( prev + 1 ) == confirm_div ){
				$(".next").removeClass('d-none');
			}
			if (prev < first) {
				$(".pages").hide();
				$("#page"+first).show();
				$('#num_question').val (first);
			} else {
				$(".pages").hide();
				$("#page"+prev).show();
				$('#num_question').val (prev);
			}
	
			/*Change color of progress buttons and increment values for - Answered, Not-answered and For-review*/
			if (document.getElementById("leaveblank_"+id).checked == true ) {
				/*Change color*/
				document.getElementById("btn_"+id).className="btn btn-sm btn-warning";
			} else {
				/*Change color*/
				document.getElementById("btn_"+id).className="btn btn-sm btn-success";
			}
	
			if (document.getElementById("visitlater_"+id).checked == true ) {
				/*Change color*/
				document.getElementById("btn_"+id).className="btn btn-sm btn-danger";
			}	
		});	
	
		$('.visitlater').click (function() { 
			var id = $(this).attr ('data-id');
			if ($(this).is(':checked')) {
				$("#btn_"+id).addClass ("btn btn-sm btn-danger");		
			} else {
				$("#btn_"+id).removeClass ("btn-danger");		
			}		
		});
	
		$('.answer_choices').click (function() {
			var id = $(this).attr ('data-id');
			if ($(this).is(':checked')) {
				$("#btn_"+id).addClass ("btn btn-sm btn-success");		
			} else {
				$("#btn_"+id).removeClass ("btn-success");		
			}
		});
	
		$('.leaveblank').click (function() {
			var id = $(this).attr ('data-id');
			if ($(this).is(':checked')) {
				$("#btn_"+id).removeClass ("btn-success");
				$("#btn_"+id).addClass ("btn-warning");
			} else {
				$("#btn_"+id).removeClass ("btn-warning");		
			}		
		});
	
		/*Enable/Disable Timer*/
		$('#disable-timer-d, #disable-timer-m').on ('click', function (e) {		
			var x = confirm ('This will disable automatic test submission on time complete. Though the timer will keep running.');
			if (x) {
				/*$(this).checked = true;*/
			} else {
				e.preventDefault ();
			}
		});
	
		$('#submit-test').on ('click', function (e) {
			e.preventDefault ();
			mediaRecorder.stop();
		});
		$('input:radio').change(function(){
			var total_questions = <?php echo $total_questions; ?>;
			var num_answered = $('.answer:checked').length;
			var num_not_answered = total_questions - num_answered;
			$('#review-answered').text(num_answered);
			$('#review-not-answered').text(num_not_answered);
		});
		$('input:checkbox').change(function(){
					var num_visitlater = $('.visitlater:checked').length;
					$('#review-for-review').text(num_visitlater);
			});
	});
})(jQuery);
</script>