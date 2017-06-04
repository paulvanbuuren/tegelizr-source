<?php

include("../common.inc.php"); 
include("filecheck.inc.php"); 

$response = array();

$response[TEGELIZR_JS_START_KEY] = TEGELIZR_JS_SCRIPTERROR;

$response1 = verbeteralletegelmetadata( '' );

if ( $response1 ) {
  $response[TEGELIZR_JS_START_KEY] = TEGELIZR_JS_START_MSG;
}

$newJsonString = json_encode( $response );

echo $newJsonString;


?>


