<?php

///
// Tegelizr - hdk/style-configuration.inc.php
// ----------------------------------------------------------------------------------
// styling voor HKD tegelgenerator
// ----------------------------------------------------------------------------------
// @author  Paul van Buuren
// @license GPL-2.0+
// @version 7.6.4
// @desc.   Meer vertaalopties. Wordwrap optioneel. Paginering intelligenter.
// @link    https://github.com/paulvanbuuren/tegelizr-source
///



$thispath                   = dirname(__FILE__)."/";
define('STYLEFOLDER', $thispath);

// wit: 
define('TXTCOLOR_R', 0 );
define('TXTCOLOR_G', 48 );
define('TXTCOLOR_B', 61 );

$images = array(
	'poppetje-1.png',
	'poppetje-2.png',
	'poppetje-3.png',
	'poppetje-4.png',
	'poppetje-5.png',
	'poppetje-6.png',
	'poppetje-7.png',
	'poppetje-8.png',
	'poppetje-9.png',
	'poppetje-10.png',
	'poppetje-11.png',
	'poppetje-12.png',
	'poppetje-13.png',
	'poppetje-14.png',
	'poppetje-15.png' );

define('BASEIMAGE', $images[ array_rand( $images ) ] );

define('STYLING_BLURSTRENGTH', 0 );
define('STYLING_STYLESHEET', 'includes/style/gebruikercentraal/gc-style.css' );

define('FONTFILE', $thispath . "Montserrat-SemiBold.ttf");


define('TEGELIZR_TITLE',            'Noteer een GC-quote');
define('TEGELIZR_FORM',             "Noteer een GC-quote en leg 'm vast voor later.");
define('TEGELIZR_BACK',             'Noteer een GC-quote');
define('TEGELIZR_SUBMIT',           'Citaat invoeren');
define('TEGELIZR_SELECTOR',         'plaatje');
define('TEGELIZR_SUMMARY',          "Noteer een GC-quote en leg 'm vast voor later.");
define('TEGELIZR_METADESC',         "Noteer een GC-quote en leg 'm vast voor later.");
define('TEGELIZR_ZOEK_LABEL',       'Zoek plaatje');
define('TEGELLABEL_PLURAL',         'plaatjes');


define('IMG_FAVICONICO', '/404.png' );
define('IMG_FAVICONAPPLE', '/404.png' );

define('MAIL_PREFIX', '[gc-plaatje]' );

define('DO_WORDWRAP', true ); // moeten de woorden op een nieuwe regel gezet worden?
define('DO_SEARCH', false ); // geen zoekformuliertje


define('TEGELIZR_TXT_LENGTH', 200 ); // moeten de woorden op een nieuwe regel gezet worden?

define('TXT_RECENT_ITEMS', 'Suggestie' );



