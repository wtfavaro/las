<div id="appViewContainer" style="overflow: hidden;">
<div class="container">
  <div class="row">
    <span id="form-container" class="col-xs-12" style="padding: 0px;">

      <ul id="option-overview" class="window">

        <!-- Machine List -->
        <?php foreach($_SESSION["machines"] as $mach ) : ?>
        <li class="machine-item" data-content='<? echo json_encode($mach); ?>'>
          <h4><? echo $mach["name"]; ?></h4>
          <span class="status">
            <span class="chevron" style="color: #222;">
              <span class="glyphicon glyphicon-chevron-right"></span>
            </span>
          </span>
        </li>
        <? endforeach; ?>

<!--
        <div class="machine-item">
          <h4>KOMAX 0080</h4>
          <span class="status">
            <span class="ok">OK</span>
          </span>
        </div>

        <div class="machine-item">
          <h4>KOMAX 0084</h4>
          <span class="status">
            <span class="timeout">TIMEOUT</span>
          </span>
        </div>

        <div class="machine-item">
          <h4>KOMAX 0112</h4>
          <span class="status">
            <span class="ok">OK</span>
          </span>
        </div>
-->
      </ul>

    </span>

  </div>

</div>

<div id="appLiveTrendNavigation" class="appViewNavigation">
  <div style="width: 20%; height: 100%;"></div>
</div>

</div>

<script type="text/javascript">
  $('body').css(
  {
    "background": "#eee"
  });
  $(".machine-item").on('click', function()
  {
    new View("analytics", { machine: $(this).data("content") });
  });
</script>
