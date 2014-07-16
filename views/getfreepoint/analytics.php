<?php

  if(!isset($_COOKIE) || !isset($_COOKIE["FreePointSecureDashboard"])){
    header("Location: sync-login");
  }

?>


  

<div id="server-process" style="display: none; color: #fff; background-color: rgba(0,0,0,0.8); padding: 5px;">Trying To Establish Connection</div>

<div class="container">

  <!-- Col Width 8 -->
  <span class="col-xs-6">
    <div class="row" style="padding: 15px;">
      <label for="selCompany">Select Company:</label>
      <select id="selCompany" style="width: 100%; padding: 7.5px; opacity: 0.6;">
      </select>
    </div>

    <div class="row" style="padding: 15px;">
      <label for="selCell">Select a Cell:</label>
      <select id="selCell" style="width: 100%; padding: 7.5px; opacity: 0.6;">
      </select>
    </div>

    <div class="row" style="padding: 15px;">
      <label for="selMach">Select a Machine:</label>
      <select id="selMach" style="width: 100%; padding: 7.5px; opacity: 0.6;">
      </select>
    </div>

    <div class="row" style="padding: 15px;">
      <label for="selFlist">Select a File:</label><br />
      <select id="selFlist" style="width: 100%; padding: 7.5px; opacity: 0.6;">
      </select>
      <!--<button id="btnForceSync" style="width: 12%; float: right; padding: 7.5px; margin-right: 7.5px;" disabled="disabled" class="btn btn-primary">Force Sync</button>-->
    </div>   
  </span>
  <!-- End Col Width 8 -->

  <span class="col-xs-6" style="margin-top: 15px;">
    <div style="width: 100%; background: rgba(100,100,100,1);" class="text-center">
      <p style="padding: 5px; font-weight: 900; color: #fff; margin: 0;">Overview</p>
    </div>
    <div style="border: 1px solid #aaa; height: 100%; width: 100%; padding: 20px;">
      <table style="width: 100%;">
        <tr><td style="width: 33%; min-width: 33%;">Company:</td><td id="table-overview-companyname"></td></tr>
        <tr><td style="width: 33%; min-width: 33%;">Last Update:</td><td id="table-overview-lastupdate"></td></tr>
        <tr><td style="width: 33%; min-width: 33%;">Total Uploads:</td><td id="table-overview-totalmb"></td></tr>
      </table>
      <div class="text-center" style="border-top: 1px solid #aaa; padding-top: 20px; margin-top: 20px;">
        <a id="aDownloadLauncher" href="#">
          <button id="btnDownload" style="width: 100%;" class="btn btn-primary">Download File</button>
        </a>
        <!--<form method="POST" action="sync-report" style="margin: 0; margin-top: 7.5px; padding: 0; width: 100%;">
          <input id="formfpath" type="hidden" name="path" />
          <button type="submit" id="btnReport" class="btn btn-default" style=" width: 100%;">Generate Report</button>
        </form>
        <button id="btnForceSync" style="width: 100%; margin-top: 7.5px;" disabled="disabled" class="btn btn-primary">Force Sync</button>-->
        <a href="sync-logout">
          <button class="btn btn-default" style="width: 100%; margin-top: 7.5px;">Logout</button>
        </a>
      </div>
    </div>
  </span>

</div>

<script type="text/javascript">

// Application Functions //
Array.prototype.contains = function ( needle ) {
   for (i in this) {
       if (this[i] == needle) return true;
   }
   return false;
}

/* Application data */
var User = {

  Select: {
      Company: "", 
      Cell: "",
      Machine: ""
  }

};

var Server = {

  Companies: {},
  Cells: {},
  Machines: {}

}

/* Disable buttons on start */

DisableFileButtons();

function DisableFileButtons(){

  $("#btnDownload").attr("disabled", "disabled");
  $("#btnReport").attr("disabled", "disabled");

}


/* company list */
function displayCompanyList(){
  $.ajax({
    type: "POST",
    url: "http://54.213.13.56/sync-api",
    data: {'lib': "company", 'software_key': '<?php echo $_COOKIE["FreePointSecureDashboard"]; ?>' },
    dataType: "json",
    success: function(resp){
      Server.Companies = resp;
      displayList(resp);
    },
    error: function(e){
    }
  });
}
function displayList(list){
  $("#selCell").html("");
  $("#selMach").html("");
  $("#selFlist").html("");
  // Clear celllist, machlist...

  for (var i = 0; i < list.length; i++){
    var self = $("#selCompany");

    // Add the update
    self.html(self.html() + "<option data-key='"+list[i].software_key+"' data-update='"+list[i].last_updated+"'>"+list[i].name+"</option>");
  }
}

$("#selCompany").change(function(){
  User.Select.Company = $(this).val();
  User.Select.SoftwareKey = $(this).find(":selected").data("key");

  getCellList(User.Select.SoftwareKey);

  // Add overview information.
  $("#table-overview-companyname").html($(this).val());
  $("#table-overview-lastupdate").html($(this).find(":selected").data("update"));

  // Load the Force Sync button.
  Force.Enable($(this).find(":selected").html(), $(this).find(":selected").data("key"));

}).click(function(){
  User.Select.Company = $(this).val();
  User.Select.SoftwareKey = $(this).find(":selected").data("key");

  getCellList(User.Select.SoftwareKey);

  // Add overview information.
  $("#table-overview-companyname").html($(this).val());
  $("#table-overview-lastupdate").html($(this).find(":selected").data("update"));

  // Load the Force Sync button.
  Force.Enable($(this).find(":selected").html(), $(this).find(":selected").data("key"));
});

displayCompanyList();

/* cell list */
function getCellList( SoftwareKey ){
  $.ajax({
    type: "GET",
    url: "http://54.213.13.56/sync-api",
    data: {key: SoftwareKey},
    dataType: "json",
    success: function(resp){
      Server.Cells = resp;
      displayCellList(Server.Cells);
    }
  });
}

function displayCellList( Cells ){
  
  $("#selCell").html("");
  $("#selMach").html("");
  $("#selFlist").html("");
  // Clear celllist, machlist...

  var cellList = [];
  for(var i = 0; i < Cells.length; i++){
    if( typeof(Cells[i].cell) != "undefined" ){
      var locInArray = $.inArray(Cells[i].cell, cellList);
      if (locInArray < 0){
        cellList.push(Cells[i].cell);
      }
    }
  }
  // We get a list of unique Cell names.

  for (var i = 0; i < cellList.length; i++){
    var a = cellList[i];
    $("#selCell").html(
      $("#selCell").html() + "<option>" + a + "</option>"
    );
  }
  // We display them in the GUI.
}
function selCell_Change(){
  User.Select.Cell = $(this).val();
  displayMachineList(Server.Cells);
}

$("#selCell").on("click", selCell_Change).on("change", selCell_Change);

/* Machines */
function displayMachineList(Cells){
  var selMach = $("#selMach");
  var machList = [];

  selMach.html("");
  // Clear selMach.

  for (var i = 0; i < Cells.length; i ++){

    // Row contains a server sync-file record.
    var row = Cells[i];

    // Filter: by correct cell and machine that isn't undefined.
    if (row.cell == User.Select.Cell && typeof(row.machine) != "undefined"){
      
        // Assemble array of only unique machines.
        if (!machList.contains(row.machine)){
          machList.push(row.machine);
        }
        
    }
    
  }

  // Display machList in UI
  for(var i = 0; i < machList.length; i ++){
    selMach.html( selMach.html() + "<option>" + machList[i] + "</option>" );
  }
}

$("#selMach").on("click", selMach_Change).on("change", selMach_Change);

// Set the User.Selected var
function selMach_Change(){
  User.Select.Machine = $(this).val();
  getFileList();
}

/* File List */
function getFileList(){

  var selFlist = $("#selFlist");
  selFlist.html("");

  for(var i = 0; i < Server.Cells.length; i++){

      // Store a row.
      var row = Server.Cells[i];

      // Trim the row (Correction made for Precision MFG - cell with trailing spaces)
      if (row.machine !== undefined) row.machine = row.machine.trim();

      // Display the files.
      if (row.cell == User.Select.Cell && row.machine == User.Select.Machine){
          
          if (row.file_pointer != null){
            selFlist.html(selFlist.html() + "<option data-pointer='" + row.file_pointer + "'>" + row.name + "</option>");
          } else {
            selFlist.html(selFlist.html() + "<option style='color: red;' data-pointer='" + row.file_pointer + "'>" + row.name + "</option>");
          }
      }
  }
}

$("#selFlist").on("click", selFlist_Change).on("change", selFlist_Change);

function selFlist_Change(){

    // Pick the file.
    var $selectedFile = $("#selFlist").find(":selected");

    // Check if it's valid.
    if (typeof($selectedFile) == "undefined"){
      return false;
    }

    // Choose the button.
    var btn = $("#aDownloadLauncher");

    // Only make the download link if it's not null.
    if($selectedFile.data("pointer") == null){
      btn.attr("disabled", "disabled");
      $("#btnDownload").attr("disabled", "disabled");
      $("#btnReport").attr("disabled", "disabled");
    } else {
      btn.removeAttr("disabled");
      $("#btnReport").removeAttr("disabled");
      $("#btnDownload").removeAttr("disabled");
    }


    // Get pertinent info for download link.
    var filePointer = "http://54.213.13.56/uploads/" + $selectedFile.data("pointer");
    var fileName = $selectedFile.val();

    // Place the info on the anchor.
    btn.attr("href", filePointer);
    btn.attr("download", fileName);

    // Place the info on the hidden form input.
    $("#formfpath").attr("value", filePointer);
   
}

/* Button: Download */
$("#btnDownload").on("click", function(){

  //console.log($("#selFlist").find(":selected").val());

});

/* Button: Force Sync */
$("#btnForceSync").on("click", function(){

  Force.Request($("#selCompany").find(":selected").html(), $("#selCompany").find(":selected").data("key"));

});

var Force = {

  CompanyName: "",

  SoftwareKey: "",

  Busy: false,

  pollInterval: "",

  init: function(company, key){
    Force.firstRequest = true;
    Force.CompanyName = company;
    Force.SoftwareKey = key;
  },

  Enable: function(company, key){
    if (Force.Busy === false){
      $("#btnForceSync").removeAttr("disabled").attr("data-key", key);
    } else {
      $("#btnForceSync").attr("disabled", "disabled");
    }
  },

  Request: function(company, key){
    if (Force.Busy === false){
      Force.Busy = true;
      Force.init(company, key);
    } else {
      return false;
    }

    var data = 
    { 
      'software-key': Force.SoftwareKey,
      'json': 'true'
    };

    httprequest('SyncCore', 'ForceUpdate', data, function(resp){
      if(resp === 0 || resp === "0" || resp === "" ){
        alert("Couldn't force request.");
      } else if (resp === 1 || resp === "1"){
        Force.ShowProcessing();
      }
    });
  },

  Stop: function(){

    Force.Busy = false;
    clearInterval(Force.pollInterval);

  },

  ShowProcessing: function(){
    $("#server-process").css("display", "block");
    var i = 001, firstRequest = true;

    Force.pollInterval = setInterval(function(){
      // Display the proper message.
      $("#server-process").css("color", "#fff").html("Trying to Establish a Connection with " + Force.CompanyName + " - " + i + " seconds");

      // Send a request to the server -- asking if the ping has been answered.
      var data = 
      { 
        'software-key': Force.SoftwareKey
      };

      httprequest('SyncCore', 'HasPingRequest', data, function(resp){
        if(resp === 0 || resp === "0"){
          if(Force.firstRequest===false){        
            $("#server-process").css("color", "green").html("Connection Established with " + Force.CompanyName + ". Now syncing.");
            $("#table-overview-lastupdate").html("Now");
            Force.Stop();
          } else {
            $("#server-process").css("color", "red").html("The server has experienced an error while establishing connection with " + Force.CompanyName);
            Force.Stop();
          }
        }
      });

      // Check if the ping attempt has timed out.
      if ( i >= 60 ){
        $("#server-process").css("color", "red").html("Connection could not be established with " + Force.CompanyName);
        Force.Stop();
      }
    
      // Increment i
      i=i+01;

      // This is no longer the first request
      Force.firstRequest = false;
    }, 1000);
  }

}

function GetGenericData(){
    httprequest('SyncProperties', 'getTotalServerFileLoad', {}, function(resp){
      var mb = (resp/1048576);
      $("#table-overview-totalmb").html((Math.round(mb * 100) / 100) + "MB");
    });
} GetGenericData();

</script>
