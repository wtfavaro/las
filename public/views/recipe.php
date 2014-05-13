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
    <? $i = 0; ?>
    <? foreach ( $_POST["machine"]["inputs"] as $input ) : ?>
      <? $i++; ?>
      <span class="inputTag" data-inputid="<? echo $i; ?>"><span><? echo $input; ?></span></span>
    <? endforeach; ?>
    <? unset($i); ?>
  </div>

  <div class="clearfix"></div>


  <!--
    
  Parts Per Minute

  -->

  <div class="tagRow row" style="background: #fff; padding: 20px 0px; border-bottom: 1px solid #ccc; z-index: 200;">
    <div class="tagDefault col-xs-12" style="line-height: 34px; border-top: 1px solid #eee; border-bottom: 1px solid #ccc;">
      There is no <b>recipe</b> for this machine.
    </div>
  </div>

  <!--
  
  Live Trending

  -->

  <div class="row" style="background: #fff; padding: 20px 0px; border-bottom: 1px solid #ccc; z-index: 200;">
    <div class="col-xs-12 text-center">
      <button class="btn btn-default">Save Recipe</button>  
    </div>
  </div>

  <div class="clearfix"></div>

</div>

</div>

<script type="text/javascript">
  
// Add the recipe variable.
fpt.recipe = new Array();

// When an input tag is clicked.
$(".inputTag").on("click", function()
  {
    // Add this input.
    fpt.recipe.push($(this).data("inputid"));
    
    // Display it on the view.
    var $curRow = $(".tagRow").find(".tagDefault")
    $curRow.text("Here's the recipe to make a part:");
    $curRow.clone().appendTo($(".tagRow")).removeClass("tagDefault").addClass("tagDisplay").text($(this).text());

    // Style the click event.
    $( this ).css({
      "boxShadow": "0px 0px 10px -1px #FFFFFF"
    });

    $( this ).animate({
      "opacity": "1.0"
    }, 500, function()
            {
              $( this ).animate({
                "opacity": "0.2" 
              }, 500);        
            });
  });
</script>