<?php
/**
 * call the debug anywhere
 */
function mrsl_debug($data,$name = ""){
  echo "<br>$name<br>";
  var_dump($data);
  echo "<br><br>";
}
/**
 * Call the api
 */
function mrsl_call_api($method, $url, $data = false) {
  $result = array();
  $opts = array('http' =>
              array(
                  'method'  => $method,
                  'header'  => "Content-Length: " . mb_strlen(serialize($dataraw), '8bit') . "\r\n" ."Content-type: application/json",
                  'content' => $data
              )
          );
  $context = stream_context_create($opts);
  //mrsl_debug($context,"context in mrsl_call_api");
  $result  = json_decode(@file_get_contents($url, false, $context));

  return $result;
}

