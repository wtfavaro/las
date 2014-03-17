<div class="container">
  <div class="row">
    <span class="col-md-offset-3 col-md-6">
      <div class="limit-to-80">
        <div class="form-group">
            <input type="email" class="form-control input-modern" id="inputProductkey" placeholder="me@example.com">
        </div>

        <div class="form-group">
            <input type="password" class="form-control input-modern" id="inputProductkey" placeholder="**********">
        </div>

        <div class="text-center">
          <span class="btn btn-primary btn-lg" id="btnLogin">Create Account</span>
        </div>
      </div>
    </span>

  </div>

</div>

<!-- Loader -->
<div id="circle-load-container" class="text-center">
  <span style="overflow: none;">
    <div id="load-circle-1" class="circle"></div>
    <div id="load-circle-2" class="circle"></div>
    <div id="load-circle-3" class="circle"></div>
    <div id="load-circle-4" class="circle"></div>
    <div id="load-circle-5" class="circle last"></div>
  </span>
</div>

<script type="text/javascript">
  $('body').css({
    "background-color": "#222222"
  });

  $('#btnLogin').click(function(){

      function Loader(){

            var self = this;
            var i = 1;
            var elapsed = false;

            this.clearAll = function(){
              for(var int = 1; int < 6; int++){
                $('#load-circle-'+int).css({
                  "background-color": "#888888"
                });                 
              }
            }

            this.start = function(){
              setInterval(function(){

                // If we're on the first after an elapse,
                // we can clear and move to the next.
                if (elapsed && i==1){
                  self.clearAll();
                }


                $('#load-circle-'+i).css({
                  "background-color": "#428bca"
                });

                // Increment the counter and make sure
                // it is within bounds.
                i++;

                if (i > 5){
                  i = 1;
                  elapsed = true;
                }
              }, 1000);
            }
      }

    var Load = new Loader();
    Load.start();
  });
</script>
