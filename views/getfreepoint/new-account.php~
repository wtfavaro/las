<section id="login">
  <div style="width: 25%; margin-left: 37.5%;">

    <div class="row">
      <p><strong>Create Account</strong></p>
    </div>
    
    <div class="row">
        <input type="text" id="inputEmail" placeholder="email" style="width: 100%" />
    </div>

    <div class="row" style="margin-top: 3px;">
        <input type="password" id="inputPassword" placeholder="password" style="width: 100%" />
    </div>

    <div class="row" style="margin-top: 3px;">
        <input type="password" id="inputRPassword" placeholder="retype password" style="width: 100%" />
    </div>

    <div class="row">
        <input type="text" id="inputFirstname" placeholder="first name" style="width: 100%" />
    </div>

    <div class="row" style="margin-top: 3px;">
        <input type="text" id="inputLastname" placeholder="last name" style="width: 100%" />
    </div>

    <div class="row">
        <input type="text" id="inputSoftwareKey" placeholder="specify software key" style="width: 100%" />
    </div>

    <br />
    
    <div class="row text-center">
        <button id="btnNewAccount" class="btn btn-primary btn-flatui" style="padding: 5px 25px;">Create Account</button>
    </div>

  </div>
</section>

<script type="text/javascript">

    var form = {
      'email': document.getElementById('inputEmail'),
      'password': document.getElementById('inputPassword'),
      'repassword': document.getElementById('inputRPassword'),
      'firstname': document.getElementById('inputFirstname'),
      'lastname': document.getElementById('inputLastname'),
      'software_key': document.getElementById('inputSoftwareKey')
    };

    $("#btnNewAccount").on("click", function(){
        if(form.password.value == form.repassword.value){

            var data = { 
              'email': document.getElementById('inputEmail').value,
              'password': document.getElementById('inputPassword').value,
              'firstname': document.getElementById('inputFirstname').value,
              'lastname': document.getElementById('inputLastname').value,
              'software_key': document.getElementById('inputSoftwareKey').value,
              'callback': 'true' };

              httprequest('FPSecure', 'CreateAccount', data, function(resp){
                if (resp !== "1"){
                  alert(resp);
                } else {
                  alert("The account has been saved.");
                  for(var f in form){
                    form[f].value = "";
                  }
                }
              });

        }
    });
</script>
