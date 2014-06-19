<div class="container">
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
    <label for="selCompany">Select a Machine:</label>
    <select id="selCompany" style="width: 100%; padding: 7.5px; opacity: 0.6;">
    </select>
  </div>

</div>


<script type="text/javascript">

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

/* company list */
function displayCompanyList(){
  $.ajax({
    type: "GET",
    url: "http://54.213.13.56/sync-api",
    data: {lib: "company"},
    dataType: "json",
    success: function(resp){
      Server.Companies = resp;
      displayList(resp);
    }
  });
}
function _getFileList(){

}
function displayList(list){
  for (var i = 0; i < list.length; i++){
    var self = $("#selCompany");
    self.html(self.html() + "<option data-key='"+list[i].software_key+"'>"+list[i].name+"</option>");
  }
}

$("#selCompany").change(function(){
  User.Select.Company = $(this).val();
  User.Select.SoftwareKey = $(this).find(":selected").data("key");

  getCellList(User.Select.SoftwareKey);
}).click(function(){
  User.Select.Company = $(this).val();
  User.Select.SoftwareKey = $(this).find(":selected").data("key");

  getCellList(User.Select.SoftwareKey);
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
  for(var i = 0; i < Cells.length; i++){

    if (typeof(Cells[i].cell) != "undefined"){
        var self = $("#selCell");
        self.html(self.html() + "<option>"+Cells[i].cell+"</option>");
    }

  }
}

</script>
