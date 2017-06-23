<?php

///
// Tegelizr - hdk/style-configuration.inc.php
// ----------------------------------------------------------------------------------
// styling voor HKD tegelgenerator
// ----------------------------------------------------------------------------------
// @author  Paul van Buuren
// @license GPL-2.0+
// @version 7.5.1
// @desc.   Styling in een apart bestand, zodat meerdere websites eigen stijl kunnen krijgen
// @link    https://github.com/paulvanbuuren/tegelizr-source
///



$thispath                   = dirname(__FILE__)."/";
define('STYLEFOLDER', $thispath);

// wit: 
define('TXTCOLOR_R', 255 );
define('TXTCOLOR_G', 255 );
define('TXTCOLOR_B', 255 );

define('BASEIMAGE', "hvd-beeldmerk-plaatje-base.png");

define('STYLING_BLURSTRENGTH', 0 );
define('STYLING_STYLESHEET', 'includes/style/hvd/hvd.css' );

define('FONTFILE', $thispath . "hvd-titel-webfont.ttf");


define('TEGELIZR_TITLE',            'HVD plaatjes maken');
define('TEGELIZR_FORM',             'Een handige plek om een plaatje te maken voor Hoe Vrouwen Denken');
define('TEGELIZR_BACK',             'Maak een plaatje');
define('TEGELIZR_SUBMIT',           'Maak mijn plaatje');
define('TEGELIZR_TXT_LENGTH',       120);
define('TEGELIZR_SELECTOR',         'plaatje');
define('TEGELIZR_SUMMARY',          'Een handige plek om een plaatje te maken voor Hoe Vrouwen Denken. Een geintje van Paul van Buuren, van WBVB Rotterdam.');
define('TEGELIZR_METADESC',         'Een handige plek om een plaatje te maken voor Hoe Vrouwen Denken.');
define('TEGELIZR_ZOEK_LABEL',       'Zoek plaatje');
define('TEGELLABEL_PLURAL',         'plaatjes');


define('IMG_FAVICONICO', '/includes/style/hvd/hvd-beeldmerk-plaatjefavicon.png' );
define('IMG_FAVICONAPPLE', '/includes/style/hvd/hvd-beeldmerk-base.png' );

