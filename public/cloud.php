<?php
if (!isset($_GET["machine"]))
{
  die();
}

if ($_GET["machine"])
{
  echo json_encode(array(
    "input1"=>rand(0, 1),
    "input2"=>rand(0, 1),
    "input3"=>rand(0, 1),
    "input4"=>rand(0, 1),
    "strength"=>rand(50, 70),
    "timestamp"=>date("Y-m-d H:i:s")
  ));
} 


?>
