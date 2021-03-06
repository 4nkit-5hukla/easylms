<div class="card mb-3">
  <div class="card-body">
    <div class="form-group mb-0 row">
      <div class="col-md-6">
        <strong>Attempts</strong>
        <div class="input-group">
          <div class="form-control p-0 border-0">
            <select title="Select Attempt" class="form-control select2-single" id="attempts">
              <?php foreach ($attempts as $attempt) {?>
              <option value="<?php echo $attempt['id']; ?>" <?php if ($attempt_id == $attempt['id']) {
    echo 'selected="selected"';
}
    ?>><?php echo date('d F, Y h:i A', $attempt['loggedon']); ?></option>
              <?php }?>
            </select>
          </div>
          <div class="input-group-append">
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#play-video"><i
                class="fas fa-play"></i></button>
            <a href="#" class="btn btn-sm btn-danger" title="Delete Attempt"
              onclick="show_confirm ('Delete this attempt and all reports?', '<?php echo site_url('coaching/tests_actions/delete_attempt/' . $coaching_id . '/' . $attempt_id . '/' . $member_id . '/' . $test_id); ?>')"><i
                class="fas fa-trash"></i></a>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <strong>Report Type</strong>
        <select title="Report Type" class="form-control select2-single" id="report-type">
          <?php foreach ($reports as $t => $report) {?>
          <option value="<?php echo $t; ?>" <?php if ($type == $t) {
    echo 'selected="selected"';
}
    ?>><?php echo $report['title']; ?></option>
          <?php }?>
        </select>
      </div>
    </div>
  </div>
</div>
<div class="modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" id="play-video">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header p-3">
        <h5 class="modal-title">Attempt Recording</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <div class="d-flex position-relative">
          <iframe id="attempt-recording" allowfullscreen frameborder="0" style="width:100%;height:0"></iframe>
        </div>
        <span
          class="d-none"><?php echo site_url("coaching/report_actions/get_recording/$coaching_id/$member_id/$course_id/$test_id/$attempt_id"); ?></span>
      </div>
    </div>
  </div>
</div>
<?php
if (isset($attempt_id) && $attempt_id > 0) {
    $this->load->view('reports/' . $reports[$type]['report_file']);
}
?>