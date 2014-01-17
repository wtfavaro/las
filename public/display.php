<?php require "header.php"; ?>
<?php require "navigation.php"; ?>
  <div class="row">
    <div class="container">
      <div id="title" class="col-md-12 text-center"><h2><span class="glyphicon glyphicon-upload"></span> Cell Monitor</h2></div>


      <!-- Login Form -->
      <div id="section-login" class="col-md-offset-4 col-md-4">
        <form class="form-horizontal" role="form" style="padding: 25px 0px;">
          <div class="form-group">
            <label for="inputProductkey" class="col-sm-4 control-label text-left">Product Key:</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="inputProductkey" placeholder="********">
            </div>
            <p class="help-block text-center" style="padding-top: 50px;">Your <strong>product key</strong> is located on your local machine.</p>
          </div>
        </form>
        <div class="text-center">
          <span class="btn btn-primary btn-lg" id="btnLogin"><span class="glyphicon glyphicon-user"></span> Login</span>
        </div>
      </div>

      <script type="text/javascript">
        document.getElementById('btnLogin').addEventListener(
          'click',
          function(){
            document.getElementById('section-login').style.display='none';
            document.getElementById('section-liveData').style.display='block';
          },
          false
        );
      </script>


      <!-- Live Data Display -->
      <div id="section-liveData" style="display: none;">
        <div id="liveData" class="col-md-12 text-center" style="padding: 25px 0px;">
          <!-- output table -->
        </div>
        <div class="col-md-12 text-center">
          <div id="btnSync" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-cloud"></span> Sync</div>
          <div id="" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-align-center"></span> Use our API</div>
          <div id="btnCSV" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-upload"></span> Open .LOG file</div>
        </div>
      </div>

      <script type="text/javascript">
        // Gateway Class

        function Gateway(admin, password)
        {

          // Declare self for use in methods.
          var self = this;
          var auth = false;
          this.input = Array();

          // Function to authorize the user password.
          function setAuth(admin, password)
          {
            if (admin && password)
            {
              auth = true;
            }
          }

          // Creates the sync and controls the display.
          function sync()
          {
            getInputs();

            setInterval(
              function()
              {
                // Change the input values.
                for(var i in self.input)
                {
                  document.getElementById(self.input[i].getAttribute('data-value-id')).innerHTML = Math.round(Math.random());
                }

                // Change the timestamp.
                //self.packet.geo = document.getElementById('timestamp').getAttribute('data-value-id').innerHTML = new Date('Y-m-d H:i:s');

              }, 1000);
          }

          // Get inputs from the database.
          function getInputs()
          {
            for(var i=1; i<5; i++)
            {
              // Add paragraph element for label.
              self.input[i] = document.createElement('p');
              self.input[i].innerHTML = 'Input'+i+': '; 
              self.input[i].setAttribute('data-value-id', 'val-input-'+i);
              document.getElementById('liveData').appendChild(self.input[i]);

              // Create the value span
              var span = document.createElement('span');
              span.id = 'val-input-'+i;
              self.input[i].appendChild(span);
            }
          }
  
          // Authorize the session.
          setAuth(admin, password);

          // Sync
          if (auth)
          {
            sync();
          }
        }

        // Create the Gateway
        document.getElementById('btnSync').addEventListener(
          'click',
          function()
          {
            var gateway = new Gateway('admin', 'password')
          },
          false
        );

      </script>
      <!-- End Live Data Display -->


      <!-- Data Logger -->
      <div id="section-logger">
        <div class="col-md-12" style="padding-top: 50px;">
          <table id="table-csv" style="width: 100%;">
            <!-- table content -->
          </table>
        </div>
      </div>

      <script type="text/javascript">
        function Log()
        {
          // Declare self.
          var self = this;
          this.data = false;

          this.config = function(cols, dimensions)
          {
            for(var index in dimensions)
            {
              //dimensions[index];
            }
          }

          this.readLine = function(callback)
          {
            var lines = self.data.split('\n');           
            
            for (var index in lines)
            {
              callback(lines[index], index);
            }
          }

          // Takes a row and splits it into columns.
          this.readCol = function(row, callback)
          {
            var cols = row.split(',');
            for(var ind in cols)
            {
              callback(cols[ind]);
            }
          }

          // Open a Log File.
          this.open = function(csv, callback)
          {
            $.ajax({
                type: "GET",
                url: csv,
                dataType: "text",
                success: function(data) {
                  self.data = data;
                  callback(self.data);
                }
             });            
          }

          function show()
          {

          }
        }

        document.getElementById('btnCSV').addEventListener(
          'click',
          function()
          {
            // Create the CSV Log stream.
            var stream = new Log();

            // Open the stream and read it
            // line by line.
            stream.config(8, [20,10,10,17,11,11,11,10]);
            stream.open('logs/sample-log.csv',
              function()
              {
                stream.readLine(function(row, index)
                {
                  var html_tr = document.createElement('tr');
                  var html_td = '';  

                  stream.readCol(row, function(item)
                  {
                    if(index == 0)
                    {
                      html_td += '<th>'+item+'</th>';
                    }
                    else
                    {
                      html_td += '<td>'+item+'</td>';
                    }
                  });

                  // Place all TDs within TR
                  html_tr.innerHTML = html_td;

                  // Append the TR to the table.
                  document.getElementById('table-csv').appendChild(html_tr);
                });
              }
            );
          },
          false
        );

      </script>
      <!-- End Data Logger -->

    </div>
  </div>
<?php require "footer.php"; ?>
