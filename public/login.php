<div class="container">
  <div class="row page-middle">
    <span id="form-container" class="col-md-offset-3 col-md-6">
      <div class="limit-to-80">
        <div class="form-group">
            <label for="inp-password">Choose a Username</label>
            <input type="text" class="form-control input-modern" id="inp-email" placeholder="username">
        </div>

        <div class="form-group">
            <label for="inp-password">Enter Password</label>
            <input type="password" class="form-control input-modern" id="inp-password" placeholder="**********">
        </div>

        <div class="form-group">
            <label for="inp-password">Retype Password</label>
            <input type="password" class="form-control input-modern" id="inp-retype-password" placeholder="**********">
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

  var form = { 

    'email': false,
    'password': false,
    checkValid: function(){
      if (form.email===true && form.password===true && $('#inp-password').val() === $('#inp-retype-password').val()){
        $('#btnLogin').removeAttr('disabled');
      } else {
        $('#btnLogin').attr('disabled', 'disabled');
      }
    },
    init: function(){
      form.checkValid();
    }

  };

  // Place the form on page middle, to align it correctly
  // on all devices.
  function setMiddle(Elem){
    var newTopMargin = (window.innerHeight - Elem.height()) / 2;

    Elem.css({
      'margin-top': newTopMargin+'px'
    });
  } setMiddle($('#form-container'));

  // Focus on the email input.
  $('#inp-email').focus();

  // Make certain that we check if this email already has an entry.
  $('#inp-email').on('keyup', function(){

    var data = {'email': document.getElementById('inp-email').value};

    httprequest('User', 'email_exists', data, function(resp){
      if (resp != 0){
        $('#inp-email').addClass("alert");
        form.email = false;
        form.checkValid();
      } else {
        $('#inp-email').removeClass("alert");
        form.email = true;
        form.checkValid();
      }
    });

  });

// Make certain that the password is okay.
  $('#inp-password').on('keyup', function(){
    if (this.value){
      form.password = true;
    } else {
      form.password = false;
    }

    form.checkValid();
  });

// Check if passwords match.
  $('#inp-retype-password').on('keyup', function(){
    form.checkValid();
  });


  $('#btnLogin').click(function(){

      if(!form.email || !form.password){
        return false;
      }

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

              // Turn the first circle on to make the click
              // feel more immediate.
              self.turnOnCircle(i);
              i++;

              setInterval(function(){

                // If we're on the first after an elapse,
                // we can clear and move to the next.
                if (elapsed && i==1){
                  self.clearAll();
                }

                // Illuminate a circle
                self.turnOnCircle(i);

                // Increment the counter and make sure
                // it is within bounds.
                i++;

                if (i > 5){
                  i = 1;
                  elapsed = true;
                }
              }, 1000);
            }

          this.turnOnCircle = function(num){
              $('#load-circle-'+num).css({
                "background-color": "#428bca"
              });
          }
      }

    var Load = new Loader();
    Load.start();

    // Make an httprequest.
    var data = { 'email': document.getElementById('inp-email').value, 'password': document.getElementById('inp-password').value };

    httprequest('User', 'make', data, function(resp){
      if(resp = 0){
        alert("Your account couldn't be created.");
      } else {
        window.location.href = "machines";
      }
    });

  });

  // Initilize this form.
  form.init();
</script>
