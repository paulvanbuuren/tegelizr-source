<?php

///
// Tegelizr - style-configuration.inc.php
// ----------------------------------------------------------------------------------
// default styling settings
// ----------------------------------------------------------------------------------
// @author  Paul van Buuren
// @license GPL-2.0+
// @version 7.6.2
// @desc.   Minify HTML; extra teksten uit vertaling; minify JS.
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

define('TXT_WATHEBJETOCH',       '<aside>(maar Paul, <a href="//wbvb.nl/tegeltjes-maken-is-een-keuze/">wat heb je toch met die tegeltjes</a>?)</aside>');


define('TEGELIZR_ALLES_TXT', 'alle tegeltjes' );
define('TEGELIZR_WAAROM', '<li><a href="//wbvb.nl/tegeltjes-maken-is-een-keuze/">waarom tegeltjes</a></li>' );
define('TEGELIZR_ABOUT_THIS_SITE', 'Over deze site' );
define('TEGELIZR_REDACTIE_TXT', 'Redactie' );
define('DO_SOCMED', false );
define('DO_RATING', true );

define('TXT_DOBETTER', '<p id="leuk">Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>' );

define('MAIL_PREFIX', 'Tegelizr' );



