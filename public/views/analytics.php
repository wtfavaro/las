<div class="container" style="overflow-x: hidden; overflow-y: scroll; margin-right: -18px;">

  <!--
  
  Parallax top-layer that allows the user to scroll "over" the machine.

  -->

  <div style="position: fixed; top: 0; left: 0; background: #eee; padding: 20px; width: 100%; z-index: 100;">
      <h1><? echo $_POST["machine"]["name"]; ?></h1>
  </div>

  <div class="clearfix"></div>

<div id="analyticsNav" style="position: relative; top: 100px; z-index: 200;">

  <!--
  
  A row that includes all up-to-date analytics such as: Last Acitivty & Uptime.

  -->

  <div class="row" style="background: #fff; padding: 20px 0px; border-bottom: 1px solid #ccc; z-index: 200;">
    <div class="col-xs-6">
      Last Activity:
    </div>
    <div class="col-xs-6" style="text-align: center;">
      <strong><span id="lblRecentActivity" style="opacity: 0;"></span></strong>
    </div>
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
/*

Ajax request to pull an API stream from the database.

*/
updateRecentActivity();

function updateRecentActivity()
{
  var data = {
    "get":      "packets",
    "softkey":  "<? echo $_POST['machine']['softkey']; ?>",
    "address":  "<? echo $_POST['machine']['addr']; ?>",
    "limit":    "1",
    "reverse":  "true"
  },
  lblRecentActivity = document.getElementById("lblRecentActivity");

  $.ajax({
    type: "POST",
    url: "stream.api",
    data: data,
    success: function(response)
      {
        if( !response[0] )
        {
          lblRecentActivity.innerHTML = "Never";
        }
        else
        {

          var packetDate = new Date(response[0].date_added);
          var nowDate = new Date();

          // Calculate the gap between the packet and now.
          var gap = packetDate.msToTime(nowDate - packetDate);

          // Display the gap on the Recent Activity label.
          if (gap.days > 1)
          {
            lblRecentActivity.innerHTML = gap.days + " days ago";          
          }
          else if (gap.days = 1)
          {
            lblRecentActivity.innerHTML = gap.days + " yesterday";
          }
          else if (gap.days = 0 && gap.hours > 1)
          {
            lblRecentActivity.innerHTML = gap.hours + " hours ago";
          }
          else if (gap.days = 0 && gap.hours <= 1 && gap.minutes > 2)
          {
            lblRecentActivity.innerHTML = gap.minutes + " minutes ago";
          }
          else if (gap.days = 0 && gap.hours <= 1 && gap.minutes <= 1)
          {
            lblRecentActivity.innerHTML = gap.seconds + " seconds ago";
          }
        }

        // The changes have been made. No show the label.
        $('#lblRecentActivity').css({
          "margin-right": 25
        }).animate({
          "opacity": "1",
          "marginRight": "0px"
        }, 1000);
      },
    dataType: "json"
  });
}
</script>

<script type="text/javascript">

/*

  View Controller for analyticsView.

*/
$("#btnAddRecipe").on("click", function()
{
  new View("recipe", { machine: <? echo json_encode($_POST['machine']) ?> });
});

</script>

<script type="text/javascript">

// Change the body background live.
  $('body, html, window').css({
    "background": "#EEEEEE",
    "overflow": "hidden"
  });

// Change the dimensions of the appViewContainer
$( "#appViewContainer" ).height( $( "window" ).height() ).width( $( "window" ).width() );


// Animate a row when it is clicked.
  $(".row").on("click", function()
  {
    $(this).animate({
      // Offset the nav row.
      marginLeft:   "+=25",
      marginRight:  "-=25"
    }, function(){
      var containerWidth = $('#analyticsNav').width();

      // Put the analytics container back in place.
      $(this).animate({
        marginLeft:   "-=25",
        marginRight:  "+=25"
      });
    })
  });
</script>
