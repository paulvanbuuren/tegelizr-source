<?php

///
// Tegelizr - hdk/style-configuration.inc.php
// ----------------------------------------------------------------------------------
// styling voor HKD tegelgenerator
// ----------------------------------------------------------------------------------
// @author  Paul van Buuren
// @license GPL-2.0+
// @version 7.6.2
// @desc.   Minify HTML; extra teksten uit vertaling; minify JS.
// @link    https://github.com/paulvanbuuren/tegelizr-source
///



$thispath                   = dirname(__FILE__)."/";
define('STYLEFOLDER', $thispath);

// wit: 
define('TXTCOLOR_R', 240 );
define('TXTCOLOR_G', 240 );
define('TXTCOLOR_B', 240 );

define('BASEIMAGE', "boaty-source.png");

define('STYLING_BLURSTRENGTH', 0 );
define('STYLING_STYLESHEET', 'includes/style/boaty/boaty.css' );

define('FONTFILE', $thispath . "boaty2.ttf");


define('TEGELIZR_TITLE',            'Geef dat ding een nieuwe naam');
define('TEGELIZR_FORM',             'Omdat Paul een nerd is, wil hij een nieuwe naam voor z\'n auto. Maar welke dan?');
define('TEGELIZR_BACK',             'Verzin een naam');
define('TEGELIZR_SUBMIT',           'Naam invoeren');
define('TEGELIZR_SELECTOR',         'plaatje');
define('TEGELIZR_SUMMARY',          'Omdat Paul een nerd is, wil hij een nieuwe naam voor z\'n auto. Maar welke dan?');
define('TEGELIZR_METADESC',         'Omdat Paul een nerd is, wil hij een nieuwe naam voor z\'n auto. Maar welke dan?');
define('TEGELIZR_ZOEK_LABEL',       'Zoek plaatje');
define('TEGELLABEL_PLURAL',         'plaatjes');


define('IMG_FAVICONICO', '/404.png' );
define('IMG_FAVICONAPPLE', '/404.png' );

define('MAIL_PREFIX', 'Name That Barrel' );

define('DO_WORDWRAP', false ); // moeten de woorden op een nieuwe regel gezet worden?
define('DO_SEARCH', false ); // geen zoekformuliertje


define('TEGELIZR_TXT_LENGTH', 30 ); // moeten de woorden op een nieuwe regel gezet worden?

define('TXT_RECENT_ITEMS', 'Suggestie' );



