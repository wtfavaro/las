<section id="login">
  <div style="width: 25%; margin-left: 37.5%;">

    <div class="row">
      <p><strong>My Dashboard</strong></p>
    </div>
    
    <div class="row">
        <input type="text" id="inputUsername" placeholder="username" style="width: 100%" />
    </div>

    <div class="row" style="margin-top: 3px;">
        <input type="password" id="inputPassword" placeholder="password" style="width: 100%" />
    </div>

    <br />
    
    <div class="row text-center">
        <button id="btnLogin" class="btn btn-primary btn-flatui" style="padding: 5px 25px;">Login</button>
    </div>

  </div>
</section>

<script type="text/javascript">
    var form = {
        username: $("#inputUsername"),
        password: $("#inputPassword")
    };

    $("#btnLogin").on("click", function(){
        if(form.username.val() && form.password.val()){

            var data = { 'email': form.username.val(), 'password': form.password.val(), 'callback': 'true' };
 
            httprequest('FPSecure', 'VerifyUser', data, function(resp){           
              if (resp !== "0"){
                window.location.href = "analytics";
              } else {
                alert("Your username/password combination is incorrect.");
              }
            });

        }
    });
</script>
