		<canvas id="canvas" height="450" width="600"></canvas>

    <span id="machNameLabel"><p style="font-weight: 900; font-size: 0.7em; opacity: 0.6; padding: 10px 0px 0px 10px;"><? echo $_POST["machine"]["name"]; ?></p></span>

	<script>


function DataStruct()
{

  this.get      = "packets";
  this.softkey  = "<? echo $_POST['machine']['softkey']; ?>";
  this.address  = "<? echo $_POST['machine']['addr']; ?>";
  this.time     = {};

}

function QueryLibrary()
{

  this.Stream = function( a )
  {
    $.ajax({
      type:     "POST",
      url:      "stream.api",
      data:     a.data,
      success:  function(response)
                {

                  if ( typeof(a.onSuccess) == "function" ){ a.onSuccess( response ) };

                  if( !response[0] && typeof(a.onNoResults) == "function" )
                  {
                    a.onNoResults( response );
                  }
                  else if( response[0] && typeof(a.onResults) == "function" ) 
                  {
                    a.onResults( response );
                  }
                },
       error:   function()
                {
                  if ( typeof(a.onError) == "function" ) { a.onError() };
                }
    });
  }

}

function APILibrary()
{

  // The API depends on the Query Library
  var Query = new QueryLibrary();

  // And needs a global data variable where the machine data is stored
  var Data = new DataStruct();

  // A function that gets the first and last packet of the past 24 hours for comparison
  this.LastTwentyFour = function( callbackFromMainFirst, callbackFromMainLast )
  {
    // An object used to store the results -- the first and the last twenty-four hour packet
    var results = { first: "", last: "" };

    // Set the time from which to search and send a stream query to retrieve the first twenty-four hour packet
    Data.time.start   = new Date(new Date().getTime() - (24 * 60 * 60 * 1000));
    Data.time.end     = new Date(new Date().getTime());
    Data.limit        = "1";
    Data.reverse      = "true";

    Query.Stream(
    {
      data: Data,
      onSuccess: function(response){
        callbackFromMainFirst(response);
      },
      onError: function(response){
        callbackFromMainFirst(false);
      },
      onNoResults: function(response){
        callbackFromMainFirst(false);
      }
    });

    // Now do the same for the last twenty-four hour packet
    Data.limit        = "1";
    Data.reverse      = "false";

    Query.Stream (
    {
      data: Data,
      onSuccess: function(response){
        callbackFromMainLast(response);
      },
      onError: function(response){
        callbackFromMainLast(false);
      },
      onNoResults: function(response){
        callbackFromMainLast(false);
      }
    });

  }

}

// Declare the API
var API = new APILibrary();

// Get the first and last packet of the last twenty-four hours.
API.LastTwentyFour(

  function(firstResponse){
    console.log(firstResponse);
  },
  
  function(lastResponse){
    console.log(lastResponse);
  }

);

/*
    // Get all the data from today.
    var data = {
      "get":      "packets",
      "softkey":  "<? echo $_POST['machine']['softkey']; ?>",
      "address":  "<? echo $_POST['machine']['addr']; ?>",
      "time":
      {
        "start": "",
        "end": ""
      },
      "lastTimeEnd": ""
    };


    // Start the interval.
    var newMapAnimation = setInterval(function()
    {

      // Set the current time to be searched.
      data.time.start = data.lastTimeEnd || new Date(new Date().getTime()),
      data.time.end = new Date(new Date().getTime())

      // Store the lastTimeEnd to be used on the next search.
      data.lastTimeEnd =  data.time.end;

      // Perform the search.
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
              // displayLiveGraphUpdates(response);
            }
         }
      }); 
    }, 500);   

    function displayLiveGraphUpdates(response)
    {
      $('body').html( $('body').html() + response );
    }
*/
/*
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
				}
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
*/
	</script>

<div id="appLiveTrendNavigation" class="appViewNavigation">
  <span style="position: absolute; display: inline-block; left: 120px; padding: 15px;">
      <?php foreach($_POST["machine"]["inputs"] as $input): ?>
      <p style="padding: 0; margin: 0; font-size: 0.8em; color: #FFF;"><input type="checkbox" style="margin-top: 2px;" value="" /> <span style="padding: 0;"><?php echo $input; ?></span></p>
      <?php endforeach; ?>
  </span>
</div>
