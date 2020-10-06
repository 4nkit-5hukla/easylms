<script>
(function ($) {
  $(document).ready (function () {
    let player = videojs("player");
		$("#play-video").on("shown.bs.modal", function(e){
      player.src({ type: "video/webm", src: "//webrtc.inovmercury.com/video/<?php echo $recording_file; ?>" });
      player.play();
    });
    $("#play-video").on("hide.bs.modal", function(e){
      player.reset();
    });
	});
})(jQuery);
</script>