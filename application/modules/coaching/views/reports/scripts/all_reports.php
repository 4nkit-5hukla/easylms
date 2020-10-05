<script src="<?php echo base_url(THEME_PATH . 'assets/js/vendor/plyr.min.js'); ?>"></script>
<script>
(function ($) {
  // let player = $("#player").get(0);
  $("#player").css("--plyr-color-main", themeColor1);
  let player = new Plyr($("#player").get(0), {
    clickToPlay: true,
    resetOnEnd: true,
    tooltips: { controls: true, seek: true },
    controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'download', 'fullscreen'],
    // urls: {download: 'https://webrtc.inovmercury.com/download-file/<?php echo md5("$coaching_id.$member_id.$course_id.$test_id.$attempt_id.") ?>'},
  });
	$(document).ready (function () {
		$("#play-video").on("shown.bs.modal", function(e){
      player.play();
    });
    $("#play-video").on("hidden.bs.modal", function(e){
      player.stop();
    });
	});
})(jQuery);
</script>