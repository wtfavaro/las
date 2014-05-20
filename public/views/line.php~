		<canvas id="canvas" height="450" width="600"></canvas>

    <span id="machNameLabel"><p style="font-weight: 900; font-size: 0.7em; opacity: 0.6; padding: 10px 0px 0px 10px;"><? echo $_POST["machine"]["name"]; ?></p></span>

	<script>

    // Get all the data from today.
    var data = {
      "get":      "packets",
      "softkey":  "<? echo $_POST['machine']['softkey']; ?>",
      "address":  "<? echo $_POST['machine']['addr']; ?>",
      "time":
      {
        "start": new Date(new Date().getTime() - (24 * 60 * 60 * 1000)),
        "end": new Date(new Date().getTime())
      }
    };

    $.ajax({
      type: "POST",
      url: "stream.api",
      data: data,
      success: function(response)
        {
          if( !response[0] )
          {
            // No result.
          }
          else
          {
            // Result found.
            console.log(response);
          }
       }//,
      //dataType: "json"
    });    


    // Add data to the canvas.
		var lineChartData = {
			labels : [],
			datasets : [
				{
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,0.8)",
					pointColor : "rgba(151,187,205,0)",
					pointStrokeColor : "rgba(151,187,205,0)",
					data : []
				},
				/*{
					fillColor : "rgba(151,187,205,0.5)",
					strokeColor : "rgba(151,187,205,1)",
					pointColor : "rgba(151,187,205,1)",
					pointStrokeColor : "#fff",
					data : [0,0,0,0,0,0,0,0,0,0,0,0]
				}*/
			]
		}

    // Add enough labels.
    lineChartData.labels = [];
    lineChartData.datasets[0].data = [];

    for (var i=0; i < 500; i++)
    {
      lineChartData.labels.push("");
      var randInputState = Math.round((Math.floor(Math.random() * 60) + 1)/100);
      lineChartData.datasets[0].data.push(randInputState);
    }

    // Change the canvas dimensions to fit the screen.
    $("#canvas").height($(window).height() - 110).width(($(window).width() + 8000)).css({
      "position": "fixed",
      "left": "-55px",
      "bottom": "110px"
    }).attr("height", $(window).height() - 110).attr("width", (($(window).width() + 8000)));

    // Add the chart to the canvas.
    var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Line(lineChartData);

    // Animate the chart.
    $("#canvas").animate({
      "left": "-=8000"
    }, 180000);
	
	</script>

<div id="appLiveTrendNavigation" class="appViewNavigation">
  <span style="position: absolute; display: inline-block; left: 120px; padding: 15px;">
      <?php foreach($_POST["machine"]["inputs"] as $input): ?>
      <p style="padding: 0; margin: 0; font-size: 0.8em; color: #FFF;"><input type="checkbox" style="margin-top: 2px;" value="" /> <span style="padding: 0;"><?php echo $input; ?></span></p>
      <?php endforeach; ?>
  </span>
</div>
