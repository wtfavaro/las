<?php

if (isset($_GET["machine"]))
{

  $packets = Package::MostRecent("SELECT * FROM packets ORDER BY date_added DESC LIMIT 2");

  $expPacket = explode("-", $packets[0]["data"]);

  $rnPacket["num_of_inputs"] = $expPacket[0];
  $rnPackets["state"] = $expPacket[1];
  $rnPackets["input1_counter_hibyte"] = $expPacket[2];
  $rnPackets["input1_counter_lobyte"] = $expPacket[3];
  $rnPackets["input1_timer_hibyte"] = $expPacket[4];
  $rnPackets["input1_timer_lobyte"] = $expPacket[5];
  $rnPackets["input2_counter_hibyte"] = $expPacket[6];
  $rnPackets["input2_counter_lobyte"] = $expPacket[7];
  $rnPackets["input2_timer_hibyte"] = $expPacket[8];
  $rnPackets["input2_timer_lobyte"] = $expPacket[9];
  $rnPackets["input3_counter_hibyte"] = $expPacket[10];
  $rnPackets["input3_counter_lobyte"] = $expPacket[11];
  $rnPackets["input3_timer_hibyte"] = $expPacket[12];
  $rnPackets["input3_timer_lobyte"] = $expPacket[13];
  $rnPackets["input4_counter_hibyte"] = $expPacket[14];
  $rnPackets["input4_counter_lobyte"] = $expPacket[15];
  $rnPackets["input4_timer_hibyte"] = $expPacket[16];
  $rnPackets["input4_timer_lobyte"] = $expPacket[17];

  var_dump($rnPackets);

//  Package::Each($packets, function($pack){
//    echo json_encode(array($pack));
//  });
}

// If not a valid api call, die
// Else run the api controller
if( !$cloud->api->validHttpRequest() ){
  die();
} else {
  $cloud->api->controller();
}


?>
