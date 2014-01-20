function httprequest(className, methodName, paramArray, callback){
  $.ajax({
    type: "POST",
    url: "ajax.php",
    data: { "class": className, "method": methodName, "args": paramArray }
  })
    .done(function( msg ) {
      callback( msg );
    });
}
