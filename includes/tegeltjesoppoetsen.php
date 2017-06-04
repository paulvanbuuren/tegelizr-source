<?php

///
// Tegelizr - tegeltjesoppoetsen.php
// ----------------------------------------------------------------------------------
// triggert consistent script en geeft responses terug
// ----------------------------------------------------------------------------------
// @author  Paul van Buuren
// @license GPL-2.0+
// @version 7.0.0
// @desc.   Filecheck aangepast. Navigatie retour. Wachtanimatie toegevoegd.
// @link    https://github.com/paulvanbuuren/tegelizr-source
///



include("../common.inc.php"); 
include("filecheck.inc.php"); 

$response = array();

$response[TEGELIZR_JS_START_KEY] = TEGELIZR_JS_SCRIPTERROR;

$response1 = verbeteralletegelmetadata( '' );

if ( $response1 ) {
  $response[TEGELIZR_JS_START_KEY]  = TEGELIZR_JS_START_MSG;
  $response[TEGELIZR_JS_NAV_NEXTKEY]   = $response1[TEGELIZR_JS_NAV_NEXTKEY];
  

}

$newJsonString = json_encode( $response );

echo $newJsonString;


?>


