<div class="container">
  <div class="row">
    <label for="selCompany">Select Company:</label>
    <select id="selCompany" style="width: 100%;">
      <option value="Config Files">Config Files</option>
    </select>
  </div>
</div>


<script type="text/javascript">
$.ajax({
  type: "GET",
  url: "http://54.213.13.56/sync-api",
  data: {lib: "company"},
  dataType: "json",
  success: function(resp){
    console.log(resp);
  }
});
</script>
