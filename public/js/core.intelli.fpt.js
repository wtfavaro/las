/*

Document onReady method list.

*/

// Declare the global fpt object.
var fpt = {};

// Create an onStartUp hook.
fpt.onStartUp = {};

// Call each method from the onStartUp object.
$( document ).ready(function()
{
  for(var fn in fpt.onStartUp)
  {
    fpt.onStartUp[fn]();
  }
});



/*

Convert a millisecond duration into a time object.

*/
Date.prototype.msToTime = function(duration)
{
  var gap = {
      milliseconds: parseInt((duration%1000)/100),
      seconds: parseInt((duration/1000)%60),
      minutes: parseInt((duration/(1000*60))%60),
      hours: parseInt((duration/(1000*60*60))%24),
      days: parseInt((duration/(1000*60*60*24))%365)
  }

  gap.days = (gap.days < 10) ? "0" + gap.days : gap.days;
  gap.hours = (gap.hours < 10) ? "0" + gap.hours : gap.hours;
  gap.minutes = (gap.minutes < 10) ? "0" + gap.minutes : gap.minutes;
  gap.seconds = (gap.seconds < 10) ? "0" + gap.seconds : gap.seconds;

  return gap;
}

/* 

Load a new view and dynamically include the page.

*/
function View( pageAddr, postData )
{
    // Animate the appViewContainer to transparent. Then load the next view.
    $( "#appViewContainer" ).animate(
    {
      "opacity": "0"
    }, 500, function()
              {
                  $( "#appViewContainer" ).load( "views/" + pageAddr + ".php", postData, function()
                  {
                    $( this ).animate(
                    {
                      "opacity": "1.0"
                    }, 500);
                  });
              }
    );
}
