
  <canvas id="canvas" height="600" width="600" style="padding: 10%;"></canvas>
  <span id="tallyReadoutDisplay" style="color: #000; font-size: 4em;">0.41</span>

  <div id="appLiveTrendNavigation" class="appViewNavigation">
    <div style="width: 20%; height: 100%;"></div>
  </div>

	<script type="text/javascript">

		var doughnutData = [
				{
					value: 143,
					color:"#F7464A"
				},
				{
					value : 37,
					color : "#46BFBD"
				},
			];

    var options = {
        animationSteps: 100,
        animationEasing: "easeOutQuint"
      };

	// Set the proper height/width for the canvas.
  $("#canvas").attr("width", $(window).width()).attr("height", $(this).width());

  // Display the canvas.
  var myDoughnut = new Chart(document.getElementById("canvas").getContext("2d")).Doughnut(doughnutData, options);

  // Pre-position the canvas.
  $("#canvas").css({
      "position": "absolute",
      "top": ((($(window).height() - 125)/2) - ($("#canvas").height()/2)) * 0.80 + "px"
    });

  // Change BG colour.
  $("body, html, window").css({
    "background-color": "#FFFFFF"
  });

  // Put the text midway up.
  $("#tallyReadoutDisplay").css({
    "z-index": "900",
    "position": "absolute",
    "padding": "0%",
    "margin": "0",
    "font-weight": "900",
    "top": ($("#canvas").position().top) + "px"
  }).animate({
    "left": "15%"
  }, 1000);
	</script>
