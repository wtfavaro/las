<div class="container" style="overflow-x: hidden; overflow-y: scroll; margin-right: -18px;">

  <!--
  
  Parallax top-layer that allows the user to scroll "over" the machine.

  -->

  <div style="position: fixed; top: 0; left: 0; background: #eee; padding: 20px; width: 100%; z-index: 100;">
      <h1><? echo $_POST["machine"]["name"]; ?> <small>recipe</small></h1>
  </div>

  <div class="clearfix"></div>


<div id="analyticsNav" style="position: relative; top: 100px; z-index: 200;">

  <!--
  
  A row that includes all up-to-date analytics such as: Last Acitivty & Uptime.

  -->

  <div class="row tagsBG" style="background: #fff; padding: 20px 0px; border-bottom: 1px solid #ccc; z-index: 200; overflow: none; text-align: center;">
    <? foreach ($_POST["machine"]["inputs"] as $input) : ?>
      <span class="inputTag"><span><? echo $input; ?></span></span>
    <? endforeach; ?>
  </div>

  <div class="clearfix"></div>


  <!--
    
  Parts Per Minute

  -->

  <div class="row" style="background: #fff; padding: 20px 0px; border-bottom: 1px solid #ccc; z-index: 200;">
    <div class="col-xs-6" style="line-height: 34px;">
      Parts Per Minute:
    </div>
    <div class="col-xs-6" style="text-align: center;">
      <div class="btn-group">
        <button type="button" id="btnAddRecipe" class="btn btn-default" style="padding: 6px 25px;">Add Recipe</button>
      </div>
    </div>
  </div>

  <!--
  
  Live Trending

  -->

  <div class="row" style="background: #fff; padding: 20px 0px; margin-top: 25px; border-bottom: 1px solid #ccc; z-index: 200;">
    <div class="col-xs-12">
      <div id="placeholder" style="width: 100%; height: 200px; border: 1px solid #ccc;"></div>    
    </div>
  </div>

  <div class="clearfix"></div>

</div>

</div>

<script type="text/javascript">
  $(".inputTag").on("click", function()
    {
      $( this ).animate({
        "opacity": "1.0",
        'boxShadowX': '10px',
        'boxShadowY':'10px',
        'boxShadowBlur': '20px',
        'boxShadowColor': '#FFFFFF'
      }, 500, function()
              {
                $( this ).animate({
                  "opacity": "0.1" 
                }, 500);        
              });
    });
</script>
