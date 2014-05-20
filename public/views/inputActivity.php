<div class="container" style="overflow-x: hidden;">

  <!--
    HEADERS
  -->

  <div class="row" style="padding: 20px 0px; color: #777; font-size: 0.7em;">
    <div class="inputNameDiv col-xs-6">
      INPUT NAME
    </div>
    <div class="col-xs-3 text-right">
      COUNTER
    </div>
    <div class="col-xs-3 text-right">
      TIMER
    </div>
  </div>

  <!--
    INPUTS
  -->

  <?php foreach($_POST["machine"]["inputs"] as $input): ?>
  <div class="inputNavButton row" style="background: #fff; padding: 20px 0px; border-bottom: 1px solid #ccc; z-index: 200;">
    <div class="inputNameDiv col-xs-6">
      <?php echo $input; ?>
    </div>
    <div class="col-xs-3 text-right">
      <span class="inputCounterSpan">0</span>
    </div>
    <div class="col-xs-3 text-right">
      <span class="inputTimerSpan">0</span>
    </div>
  </div>
  <?php endforeach; ?>

</div>

<script type="text/javascript">
getRecentTimerAndCounter();

function getRecentTimerAndCounter()
{

  var updateData = setInterval(function(){
    var data = {
      "get":      "packets",
      "softkey":  "<? echo $_POST['machine']['softkey']; ?>",
      "address":  "<? echo $_POST['machine']['addr']; ?>",
      "limit":    "1",
      "reverse":  "true"
    };

    $.ajax({
      type: "POST",
      url: "stream.api",
      data: data,
      success: function(response)
        {
          if( !response[0] )
          {
            //There is no recent data.
            alert("This machine isn't ready to log data.");
          }
          else
          {
            //There is recent data.
            for (var input in response[0].data){

              var inputSchema = response[0].data[input];

              $(".inputNavButton").each(function()
              {
                if ($(this).find(".inputNameDiv").text().trim() == input)
                {
                  $(this).find(".inputTimerSpan").text( inputSchema.timer );
                  $(this).find(".inputCounterSpan").text( inputSchema.counter );
                }
              });
            }
          }
        },
      dataType: "json"
    })
  }, 1000);

}
</script>
