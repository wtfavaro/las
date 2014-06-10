		<span id="machNameLabel"><p style="font-weight: 900; font-size: 0.7em; opacity: 0.6; padding: 10px 0px 0px 10px;"><? echo $_POST["machine"]["name"]; ?></p></span>

    <ul id="option-overview" class="window">

      <li class="machine-item">
        <h2 style="display: inline-block; font-size: 1.6em; margin-top: 10px;">Setup Time:</h2>
      </li>

      <li class="machine-item">

        <h2 style="display: inline-block; font-size: 1.6em; margin-top: 10px;">Calculate <b>PPM</b>:</h2>
        <select style="margin-left: 50px; font-size: 0.8em; padding: 5px 10px;">
          <?php foreach($_POST["machine"]["inputs"] as $input): ?>
            <option><?php echo $input; ?></option>
          <? endforeach; ?>
        </select>

        <!--
        <div style="display: inline-block; border-radius: 5px; border: 1px solid #ccc; margin-left: 50px; padding: 5px;">
          <select style="font-size: 0.8em; padding: 5px 10px;">
            <option>Last 24 Hours</option>
            <option>This Week</option>
            <option>This Month</option>
          </select>
        </div>
        -->

        <!-- 

            SELECT DATE COMPARISONS

        <div style="display: inline-block; border-radius: 5px; border: 1px solid #ccc; margin-left: 50px; padding: 5px;">
          <select id="selectPPMMonth" style="font-size: 0.8em; padding: 5px 10px;">
            <?php
              $months = Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
              foreach ($months as $month) :
            ?>

            <option><?php echo $month; ?></option>

            <?php endforeach; ?>
          </select>

          <select id="selectPPMDay" class="selectDateDisplay" style="font-size: 0.8em; padding: 5px 10px;">
            <?php for ($i=1; $i <= 31; $i++): ?>
            <option><?php echo $i; ?></option>
            <?php endfor; ?>
          </select>

          <select id="selectPPMDay" class="selectYearDisplay" style="font-size: 0.8em; padding: 5px 10px;">
            <?php $years = Array("2014");
            foreach ($years as $year) : ?>
            <option><?php echo $year; ?></option>
            <?php endforeach; ?>
          </select>

        </div>

        <span style="font-size: 0.7em; color: #aaa;"> to </span>

        <div style="display: inline-block; border-radius: 5px; border: 1px solid #ccc; padding: 5px;">
          <select id="selectPPMMonth" style="font-size: 0.8em; padding: 5px 10px;">
            <?php
              $months = Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
              foreach ($months as $month) :
            ?>

            <option><?php echo $month; ?></option>

            <?php endforeach; ?>
          </select>

          <select id="selectPPMDay" class="selectDateDisplay" style="font-size: 0.8em; padding: 5px 10px;">
            <?php for ($i=1; $i <= 31; $i++): ?>
            <option><?php echo $i; ?></option>
            <?php endfor; ?>
          </select>

          <select id="selectPPMDay" class="selectYearDisplay" style="font-size: 0.8em; padding: 5px 10px;">
            <?php $years = Array("2014");
            foreach ($years as $year) : ?>
            <option><?php echo $year; ?></option>
            <?php endforeach; ?>
          </select>

        </div> -->
      </li>

    </ul>

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

  this.Stream = function( a, b )
  {
    $.ajax({
      type:     "POST",
      url:      "stream.api",
      data:     a.data,
      success:  function(response)
                {
                  b();

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
                  b();
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
  this.LastTwentyFour = function( callBackToMain )
  {
    // An object used to store the results -- the first and the last twenty-four hour packet
    var results = { first: "", last: "", int: 0 };

    // Set the time from which to search and send a stream query to retrieve the first twenty-four hour packet
    Data.time.start   = new Date(new Date().getTime() - (72 * 60 * 60 * 1000));
    Data.time.end     = new Date(new Date().getTime());
    Data.limit        = "1";
    Data.reverse      = "true";

    Query.Stream(
    {
      data: Data,
      onSuccess: function(response){
        results.first = response;
      },
      onError: function(response){
        results.first = false;
      },
      onNoResults: function(response){
        results.first = false;
      }
    }, function(){ 
      results.int += 1;
      
      if (results.int === 2){
        callBackToMain(results);
      }
    });

    // Now do the same for the last twenty-four hour packet
    Data.limit        = "1";
    Data.reverse      = "false";

    Query.Stream (
    {
      data: Data,
      onSuccess: function(response){
        results.last = response;
      },
      onError: function(response){
        results.last = false;
      },
      onNoResults: function(response){
        results.last = false;
      }
    }, function(){ 
      results.int += 1;
      
      if (results.int === 2){
        callBackToMain(results);
      }
    });
  }
}

// Declare the API
var API = new APILibrary();

// Get the first and last packet of the last twenty-four hours.
API.LastTwentyFour(

  function(response){
    console.log(response);
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
