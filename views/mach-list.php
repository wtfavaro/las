<div class="container">
  <div class="row">
    <span id="form-container" class="col-md-offset-3 col-md-6">
      <div class="mobile-nav">
        <span class="glyphicon glyphicon-home"></span>
      </div>

      <div id="option-overview">
        <!-- Machine List -->
        <div class="machine-item">
          <h4>Schleuniger</h4>
          <span class="status">
            <span class="ok">OK</span>
          </span>
        </div>

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
      </div>


    </span>

  </div>

</div>

<script type="text/javascript">
 $(".machine-item").on('click', function(){
    window.location.href = "data";
 });
</script>
