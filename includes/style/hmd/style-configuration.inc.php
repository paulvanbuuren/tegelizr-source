<?php

///
// Tegelizr - hmd/style-configuration.inc.php
// ----------------------------------------------------------------------------------
// styling voor HMD tegelgenerator
// ----------------------------------------------------------------------------------
// @author  Paul van Buuren
// @license GPL-2.0+
// @version 7.5.1
// @desc.   Styling in een apart bestand, zodat meerdere websites eigen stijl kunnen krijgen
// @link    https://github.com/paulvanbuuren/tegelizr-source
///


define('FONTFILE', "BebasNeue-webfont.ttf");
define('BASEIMAGE', "hmd.png");

$thispath                   = dirname(__FILE__)."/";
define('STYLEFOLDER', $thispath);

define('TXTCOLOR_R', 112 );
define('TXTCOLOR_G', 23 );
define('TXTCOLOR_B', 36 );

define('STYLING_BLURSTRENGTH', 0 );
define('STYLING_STYLESHEET', 'includes/style/hmd/hmd.css' );

