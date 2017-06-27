<?php

///
// Tegelizr - hmd/style-configuration.inc.php
// ----------------------------------------------------------------------------------
// styling voor HMD tegelgenerator
// ----------------------------------------------------------------------------------
// @author  Paul van Buuren
// @license GPL-2.0+
// @version 7.6.2
// @desc.   Minify HTML; extra teksten uit vertaling; minify JS.
// @link    https://github.com/paulvanbuuren/tegelizr-source
///



$thispath                   = dirname(__FILE__)."/";
define('STYLEFOLDER', $thispath);

$rand = rand ( 1 , 2 );

if ( $rand > 1 ) {

  // wit: 
  define('TXTCOLOR_R', 255 );
  define('TXTCOLOR_G', 255 );
  define('TXTCOLOR_B', 255 );
  define('BASEIMAGE', "hmd-base.png");
  
}
else {

    // HMD rood: 
  define('TXTCOLOR_R', 112 );
  define('TXTCOLOR_G', 23 );
  define('TXTCOLOR_B', 36 );
  define('BASEIMAGE', "hmd-base-grijs.png");
  
}

define('STYLING_BLURSTRENGTH', 0 );
define('STYLING_STYLESHEET', 'includes/style/hmd/hmd.css' );

define('FONTFILE', $thispath . "BebasNeue-webfont.ttf");


define('TEGELIZR_TITLE',            'HMD plaatjes maken');
define('TEGELIZR_FORM',             'Een handige plek om een plaatje te maken voor Hoe Mannen Denken');
define('TEGELIZR_BACK',             'Maak een plaatje');
define('TEGELIZR_SUBMIT',           'Maak mijn plaatje');
define('TEGELIZR_TXT_LENGTH',       120);
define('TEGELIZR_SELECTOR',         'plaatje');
define('TEGELIZR_SUMMARY',          'Een handige plek om een plaatje te maken voor Hoe Mannen Denken. Een geintje van Paul van Buuren, van WBVB Rotterdam.');
define('TEGELIZR_METADESC',         'Een handige plek om een plaatje te maken voor Hoe Mannen Denken.');
define('TEGELIZR_ZOEK_LABEL',       'Zoek plaatje');
define('TEGELLABEL_PLURAL',         'plaatjes');


define('IMG_FAVICONICO', '/includes/style/hmd/favicon.ico' );
define('IMG_FAVICONAPPLE', '/includes/style/hmd/hmd-beeldmerk.png' );

define('MAIL_PREFIX', 'HMD-plaatjes' );

