<script>
(function($) {
  $(document).ready(function() {
    $(window).resize(function(event) {
      $('iframe').each(function() {
        $(this).width(
          Math.floor(
            $(this).parent().width()
          )
        );
        $(this).height(
          Math.floor(
            $(this).parent().width() * 9 / 16
          )
        );
      });
    });
    $('iframe').on("load", function() {
      $(this).width(
        Math.floor($(this).parent().width())
      );
      $(this).height(
        Math.floor($(this).parent().width() * 9 / 16)
      );
    });
    $("#play-video").on("shown.bs.modal", function(e) {
      $('#attempt-recording').attr('src',
        '<?php echo "https://webrtc.inovmercury.com/embed/$recording_file?autoplay=1"; ?>');
    });
    $("#play-video").on("hide.bs.modal", function(e) {
      $('#attempt-recording').removeAttr('src');
    });
  });
})(jQuery);
</script>