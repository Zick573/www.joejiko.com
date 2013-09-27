<!doctype html>
<title>Deploy</title>
<link rel="stylesheet" type="text/css" href="deploy.css">
<section class="main">
  <h1 class="deploy-title">Deploy?</h1>
  <button id="forward" class="btn-style3 deploy-forward" type="submit" data-action="deploy">Okay</button>
  <div class="btn-style2-indent">
    <button id="rewind" class="btn-style2 deploy-rewind" type="submit" data-action="revert">Oh Sh*t</button>
  </div>
</section>

<div class="deploy-confirm-modal">
  <div class="dcm-positioning">
    <div class="dcm-close"><i class="modernpics" data-icon="x"></i></div>
    <div class="dcm-content">
      <label for="passwd">Enter secret code</label>
      <input type="password" name="passwd" id="passwd">
    </div>
  </div>
  <div class="dcm-mask"></div>
</div>
<script src="/assets/js/admin/deploy.js" async="true"></script>