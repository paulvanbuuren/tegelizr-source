<?php

include("common.inc.php"); 
include("includes/filecheck.inc.php"); 

$response = array();


$response[TEGELIZR_JS_START_KEY] = TEGELIZR_JS_SCRIPTERROR;

if ( verbeteralletegelmetadata( '' ) ) {
  $response[TEGELIZR_JS_START_KEY] = TEGELIZR_JS_START_MSG;
}



$newJsonString = json_encode( $response );

echo $newJsonString;


?>


