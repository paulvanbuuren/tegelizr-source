<?php

///
// Tegelizr - style-configuration.inc.php
// ----------------------------------------------------------------------------------
// default styling settings
// ----------------------------------------------------------------------------------
// @author  Paul van Buuren
// @license GPL-2.0+
// @version 7.5.1
// @desc.   Styling in een apart bestand, zodat meerdere websites eigen stijl kunnen krijgen
// @link    https://github.com/paulvanbuuren/tegelizr-source
///

  
define('BASEIMAGE', "base.png");

$thispath                   = dirname(__FILE__)."/";
define('STYLEFOLDER', $thispath);

define('TXTCOLOR_R', 56 );
define('TXTCOLOR_G', 98 );
define('TXTCOLOR_B', 170 );

define('STYLING_BLURSTRENGTH', 4 );

define('STYLING_STYLESHEET', 'includes/style/default/default.css' );
define('FONTFILE', $thispath . "tegeltje.otf");
