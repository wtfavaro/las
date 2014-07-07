<style>

body {
  font: 10px sans-serif;
}

.chartDowntime {
  fill: red;
  opacity: 1;
}

#chart-downtime-table {
  width: 100%;
}

#chart-downtime-table td {
  text-align: center;
  font-size: 1.3em;
  padding-top: 10px;
  color: #555;
}

#chart-downtime-table th {
  text-align: center;
  font-size: 2em;
}

.chart-downtime-tr-header {
  padding: 10px;
  border: 1px solid #ccc;
}

.chart-downtime-tr-header th {
  padding: 10px;
  border: 1px solid #ccc;
}

</style>

<div class="container">
  <span class="row col-xs-10">
    <h1 class="row col-xs-12" style="font-size: 3em; margin-bottom: 0px; padding: 0px;"><strong>ARTOS CUTTER</strong></h1>
    <p class="row col-xs-12" style="padding: 0px;"><small style="font-size: 0.9em; font-weight: 900; padding: 0px;">Shift: 7:00AM to 3:00PM <br />Date: June 6th, 2014</small></p>
  </span> <!-- 
  <span class="row col-xs-2">
        <div id="chart-uptime" style="opacity: 0;"></div>
  </span>-->
</div> 

<div class="container" style="margin-top: 25px;">
  <div class="row">
    
    <span class="col-xs-8">
      <div class="row col-xs-12" style="padding: 0px;"><p><small style="font-size: 1em; font-weight: 100;">OVERVIEW</small></p></div>
      <div class="row col-xs-12" style="border-bottom: 1px solid #555;"></div>
      <div id="chart"></div>
    </span>

    <span class="col-xs-4">

      <!-- First Part -->
      <div class="col-xs-12" style="padding: 0px;">
        <p><small style="font-size: 1em; font-weight: 100;">First Part</small></p>
      </div>
      <div class="col-xs-12" style="border-bottom: 1px solid #555;"></div>
      <div class="col-xs-12" style="padding: 0px;">
        <h1 style="font-size: 3em; display: inline-block; margin-top: 5px;"><b>7:09:56</b></h1>
      </div>

      <!-- Last Part -->
      <div class="col-xs-12" style="padding: 0px;">
        <p><small style="font-size: 1em; font-weight: 100;">Last Part</small></p>
      </div>
      <div class="col-xs-12" style="border-bottom: 1px solid #555;"></div>
      <div class="col-xs-12" style="padding: 0px;">
        <h1 style="font-size: 3em; display: inline-block; margin-top: 5px;"><b>15:30:40</b></h1>
      </div>

      <!-- Shift Time -->
      <div class="col-xs-12" style="padding: 0px;">
        <p><small style="font-size: 1em; font-weight: 100;">Shift Duration</small></p>
      </div>
      <div class="col-xs-12" style="border-bottom: 1px solid #555;"></div>
      <div class="col-xs-12" style="padding: 0px;">
        <h1 style="font-size: 3em; display: inline-block; margin-top: 5px;"><b>08:00:00</b></h1>
      </div>

      <!-- Cycles -->
      <div class="col-xs-12" style="padding: 0px;">
        <p><small style="font-size: 1em; font-weight: 100;">CYCLES</small></p>
      </div>
      <div class="col-xs-12" style="border-bottom: 1px solid #555;"></div>
      <div class="col-xs-12" style="padding: 0px;">
        <h1 style="font-size: 3em; display: inline-block; margin-top: 5px;"><b>1031</b></h1>
        <h1 style="font-weight: 900; color: rgba(200, 25, 35, 1); font-size: 1.6em; display: inline-block; margin-top: 5px;"><b>(-2.4%)</b></h1>
      </div>

      <!-- M/C Ready -->
      <div class="col-xs-12" style="padding: 0px;">
        <p><small style="font-size: 1em; font-weight: 100;">M/C READY</small></p>
      </div>
      <div class="col-xs-12" style="border-bottom: 1px solid #555;"></div>
      <div class="col-xs-12" style="padding: 0px;">
        <h1 style="font-size: 3em; display: inline-block; margin-top: 5px;"><b>32</b></h1>
        <h1 style="font-weight: 900; color: rgba(30, 120, 20, 1); font-size: 1.6em; display: inline-block; margin-top: 5px;"><b>(+0.3%)</b></h1>
      </div>

      <!-- Guard Up Event -->
      <div class="col-xs-12" style="padding: 0px;">
        <p><small style="font-size: 1em; font-weight: 100;">GUARD UP</small></p>
      </div>
      <div class="col-xs-12" style="border-bottom: 1px solid #555;"></div>
      <div class="col-xs-12" style="padding: 0px;">
        <h1 style="font-size: 3em; display: inline-block; margin-top: 5px;"><b>19</b></h1>
        <h1 style="font-weight: 900; color: rgba(30, 120, 20, 1); font-size: 1.6em; display: inline-block; margin-top: 5px;"><b>(+33.3%)</b></h1>
      </div>
    </span>

  </div>

  <div class="row col-xs-12">

    <span class="col-xs-8">
      <div class="row col-xs-12" style="padding: 0px; margin-top: 25px;"><p><small style="font-size: 1em; font-weight: 100;">IDENTIFY DOWNTIME</small></p></div>
      <div class="row col-xs-12" style="border-bottom: 1px solid #555;"></div>
      <div id="chart-downtime"></div>
    </span>

    <span class="col-xs-4">
      <!-- Downtime List -->
      <div class="col-xs-12" style="padding: 0px; margin-top: 25px; opacity: 0;">
        <p><small style="font-size: 1em; font-weight: 100;">PERIODS OF LOW ACTIVITY</small></p>
        <div class="col-xs-12" style="border-bottom: 1px solid #555;"></div>
      </div>
      <div class="col-xs-12" style="padding:0px;">
        <table id="chart-downtime-table">
          <tr class="chart-downtime-tr-header"><th>From</th><th>Until</th></tr>  
          <tr><td>7:00:00AM</td><td>7:18:27AM</td></tr> 
          <tr><td>7:55:17AM</th><td>8:22:18AM</td></tr>
          <tr><td>10:55:09AM</th><td>11:44:51AM</td></tr> 
          <tr><td>12:55:14PM</th><td>1:32:50PM</td></tr> 
          <tr><td>2:00:08PM</th><td>2:45:21PM</td></tr>
          <tr><td colspan="2"></td></tr>
          <tr class="chart-downtime-tr-header"><th colspan="2">Total Low Activity: <span style="color:red;">03:17:53</span></th></tr>       
        </table>
      </div>
    </span>

  </div>

  <div class="row col-xs-12 text-center" style="margin-top: 75px;">

    <span class="col-xs-2">    
      <div id="chart-uptime"></div>
    </span>
  </div>
  <!--
  <p class="row text-center"><small style="font-size: 0.7em; font-weight: 900;">
    <span style="background: #333; color: #fff; border-radius: 5px; padding: 7.5px;">SHIFT REPORT</span>
  </small></p>
  -->
</div>

<script type="text/javascript">
var chart = c3.generate({
    bindto: '#chart',
    data: {
        x : 'x',
        columns: [
            ['x', '7-8AM', '8-9AM', '9-10AM', '10-11AM', '11-12PM', '12-1PM', '1PM-2PM', '2PM-3PM'],
            ['Cycles', 156, 201, 221, 75, 48, 191, 124, 143],
            ['M/C Ready', 7, 3, 1, 3, 4, 6, 4, 4],
            ['Guard Up', 3, 4, 2, 3, 2, 2, 1, 2],
        ],
        groups: [
            ['download', 'loading']
        ],
        type: 'bar'
    },
    axis: {
        x: {
            type: 'category' // this needed to load string x value
        }
    }
});

var chart = c3.generate({
    bindto: '#chart-uptime',
    data: {
        x : 'x',
        columns: [
            ['x', '7-8AM', '8-9AM', '9-10AM', '10-11AM', '11-12PM', '12-1PM', '1PM-2PM', '2PM-3PM'],
            ['Uptime', 41.65],
            ['Setup & Downtime', 58.35]
        ],
        type: 'pie'
    },
    axis: {
        x: {
            type: 'category' // this needed to load string x value
        }
    }
});

var chart = c3.generate({
    bindto: '#chart-downtime',
    data: {
        x : 'x',
        columns: [
            ['x', '7:00', '7:05', '7:10', '7:15',
                  '7:20', '7:25', '7:30', '7:35',
                  '7:40', '7:45', '7:50', '7:55',
                  '8:00', '8:05', '8:10', '8:15',
                  '8:20', '8:25', '8:30', '8:35',
                  '8:40', '8:45', '8:50', '8:55',
                  '9:00', '9:05', '9:10', '9:15',
                  '9:20', '9:25', '9:30', '9:35',
                  '9:40', '9:45', '9:50', '9:55',
                  '10:00', '10:05', '10:10', '10:15',
                  '10:20', '10:25', '10:30', '10:35',
                  '10:40', '10:45', '10:50', '10:55',
                  '11:00', '11:05', '11:10', '11:15',
                  '11:20', '11:25', '11:30', '11:35',
                  '11:40', '11:45', '11:50', '11:55',
                  '12:00', '12:05', '12:10', '12:15',
                  '12:20', '12:25', '12:30', '12:35',
                  '12:40', '12:45', '12:50', '12:55',
                  '1:00', '1:05', '1:10', '1:15',
                  '1:20', '1:25', '1:30', '1:35',
                  '1:40', '1:45', '1:50', '1:55',
                  '2:00', '2:05', '2:10', '2:15',
                  '2:20', '2:25', '2:30', '2:35',
                  '2:40', '2:45', '2:50', '2:55'],
            ['Cycles', 
                       0, 0, 3, 11, 
                       12, 19, 21, 15,
                       14, 23, 24, 2,
                       0, 0, 0, 0, 
                       5, 15, 22, 24,
                       21, 20, 26, 21,
                       18, 15, 18, 20, 
                       18, 25, 26, 15,
                       14, 3, 0, 14,
                       20, 21, 14, 19, 
                       17, 15, 14, 13,
                       5, 0, 0, 0,
                       0, 0, 0, 0, 
                       0, 0, 0, 0,
                       0, 3, 15, 21,
                       15, 4, 3, 11, 
                       12, 0, 6, 15,
                       14, 13, 20, 4,
                       0, 0, 3, 0, 
                       0, 5, 0, 0,
                       12, 14, 11, 15,
                       7, 0, 3, 1, 
                       7, 1, 2, 3,
                       0, 0, 11, 4]
        ],
        groups: [
            ['download', 'loading']
        ],
        type: 'area'
    },
    axis: {
        x: {
            type: 'category', // this needed to load string x value
            show: true,
            tick: {
              culling: {
                max: 9              
              }
            }
        },
    },
    zoom: {
            enabled: true
          },
    regions: [
      {axis: 'x', start: 0, end: 3, class:'chartDowntime'},
      {axis: 'x', start: 11, end: 16, class:'chartDowntime'},
      {axis: 'x', start: 44, end: 57, class:'chartDowntime'},
      {axis: 'x', start: 71, end: 79, class:'chartDowntime'},
      {axis: 'x', start: 84, end: 93, class:'chartDowntime'}
    ],
    point: {
      show: false
    }
});
</script>
