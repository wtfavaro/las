<div class="container">
  <div class="row">
    <span id="form-container" class="col-md-offset-3 col-md-6">
      <div class="mobile-nav">
        <span class="glyphicon glyphicon-home"></span>
        <select id="selViewOption" style="float:right; padding: 0px 5px;">
          <option value="overview" selected="selected">Overview</option>
          <option value="ppm">Parts Per Minute</option>
        </select>
      </div>
      
      <!-- Input List -->
      <div id="option-overview">
        <!-- Input Item #1 -->
        <div class="input-item" data-id="1">
          <h4>COVER UP</h4>
          <span class="console">
            <span class="decal">
              <span style="font-size: 6pt;">TIMER</span>
              <span class="timer">17810</span>
            </span>
            <span class="decal">
              <span style="font-size: 6pt;">COUNTER</span>
              <span class="counter">41</span>
            </span>
          </span>
        </div>

        <!-- Input Item #2 -->
        <div class="input-item" data-id="2">
          <h4>PIECES MADE</h4>
          <span class="console">
            <span class="decal">
              <span style="font-size: 6pt;">TIMER</span>
              <span class="timer">12050</span>
            </span>
            <span class="decal">
              <span style="font-size: 6pt;">COUNTER</span>
              <span class="counter">112</span>
            </span>
          </span>
        </div>

        <!-- Input Item #3 -->
        <div class="input-item">
          <h4>UPTIME</h4>
          <span class="console">
            <span class="decal">
              <span style="font-size: 6pt;">TIMER</span>
              <span class="timer">450</span>
            </span>
            <span class="decal">
              <span style="font-size: 6pt;">COUNTER</span>
              <span class="counter">15</span>
            </span>
          </span>
        </div>

        <!-- Input Item #4 -->
        <!-- <div class="input-item">
          <h4>_</h4>
          <span class="console">
            <span class="decal">
              <span style="font-size: 6pt;">TIMER</span>
              <span class="timer">0</span>
            </span>
            <span class="decal">
              <span style="font-size: 6pt;">COUNTER</span>
              <span class="timer">0</span>
            </span>
            <span class="decal">
              <span style="font-size: 6pt;">STATE</span>
              <span class="timer">0</span>
            </span>
          </span>
        </div> -->

      </div>


      <!--<div id="option-ppm">
        <h1 style="font-size: 4em;">31</h1>
      </div>-->

    </span>

  </div>

</div>

<script type="text/javascript">

  // Manage the Select Input field.
  $('#selViewOption').change(function(){
    if($(this).val() == "overview"){
      $('#option-overview').css({ "display": "block" });
    } else {
      $('#option-overview').css({ "display": "none" });
      //$('#option-overview').css({ "display": "none" });
    }
  });

  // Random number class
  function RndGen (intLow, intHigh){

    return Math.floor((Math.random()*intHigh)+intLow);

  }

  // Update the counter
  function UpdCounter ($elem){
    var intCount = parseInt($elem.find('.counter').html()) + 1;
    $elem.find('.counter').html(intCount);
  }


  // Every two seconds.
  var tmrPiecesMade = setInterval(function(){
    if ($elem = $("div").find("[data-id='2']")) {

      UpdCounter($elem);

    }

    $("div").find("[data-id='2']").css({
      "background-color": "green"
    });

    var tmrBlip = setTimeout(function(){
       $("div").find("[data-id='2']").css({
        "background-color": "#fff"
      });     
    }, RndGen(100, 1000));
  }, 2200);

  // Every one second update the timer...
  var tmrAdvanceTimer = setInterval(function(){
    $('.input-item').each(function(){
      var lblTimer = $(this).find('.timer');
      var intCurTimer = lblTimer.html();
      var newCurTimer = parseInt(intCurTimer) + 1;
      lblTimer.html(newCurTimer);
    });
  }, 1000);
</script>
