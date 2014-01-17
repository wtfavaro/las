function httprequest(className, methodName, callback){
  $.ajax({
    type: "POST",
    url: "ajax.php",
    data: { "class": className, "method": methodName }
  })
    .done(function( msg ) {
      callback( msg );
    });
}
