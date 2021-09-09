<?php

///
// Tegelizr - generate.php
// ----------------------------------------------------------------------------------
// maakt tegeltjes aan
// ----------------------------------------------------------------------------------
// @author  Paul van Buuren
// @license GPL-2.0+
// @version 7.6.4
// @desc.   Meer vertaalopties. Wordwrap optioneel. Paginering intelligenter.
// @link    https://github.com/paulvanbuuren/tegelizr-source
///


include( "../common.inc.php" );
include( "filecheck.inc.php" );
include( "blur.inc.php" );


// ===================================================================================================================
// gelijk doorlussen als er geen waarde is
if ( empty( $_GET['txt_tegeltekst'] ) ) {
	header( 'Location: ' . $desturl );
}

// ===================================================================================================================
// opschonen

function escapeescapers( $sentence = '' ) {

	if ( $sentence ) {
		$zinnen = explode( ' ', $sentence );

		if ( ! $zinnen ) {

			$needle = '-';
			if ( substr_count( $sentence, $needle ) > 1 ) {
				// meerdere streepjes in 1 woord
				$sentence = preg_replace( '|-|', '', $sentence );
			}
			$needle = '.';
			if ( substr_count( $sentence, $needle ) > 1 ) {
				// meerdere streepjes in 1 woord
				$sentence = preg_replace( '|\.|', '', $sentence );
			}

		} else {

			$nieuwezin   = array();
			$wordcounter = 0;
			$counter     = 0;

			foreach ( $zinnen as $word ) {

				$wordcounter ++;

//				echo $wordcounter . ' - "' . $word;

				if ( strlen( $word ) === 1 ) {

					if ( strlen( $zinnen[ $counter + 1 ] ) === 1 ) {
						$ookvolgendeiskort = true;
						$spatieteller      = $counter;

						while ( $ookvolgendeiskort ):

							$volgendwoord = $zinnen[ $spatieteller + 1 ];

							if ( strlen( $volgendwoord ) !== 1 ) {
								$ookvolgendeiskort = false;
								break;
							} else {

							}

							$word .= $volgendwoord;

							unset( $zinnen[ $spatieteller ] );
							unset( $zinnen[ $spatieteller + 1 ] );
							$spatieteller ++;
						endwhile;

					} else {
						// het eerste woord in de zin betaat uit 1 letter (bijv. een Engelse zin)
					}
				} else {


					$needle = '.';
					if ( substr_count( $word, $needle ) > 1 ) {
						// er komt meer dan 1x een punt in het woord voor
						$word = preg_replace( '|\.|', '', $word );
					}
					$needle = '-';
					if ( substr_count( $word, $needle ) > 1 ) {
						// er komt meer dan 1x een streepje in het woord voor
						$word = preg_replace( '|\-|', '', $word );
					}
					$replacer = '[lala-lala]';
					$word     = preg_replace( '/\bnouri\b/i', 'Henk de Vries', $word );
					$word     = preg_replace( '/\brifaap\b/i', $replacer, $word );
					$word     = preg_replace( '/\bnikker\b/i', $replacer, $word );
					$word     = preg_replace( '/\bneger\b/i', $replacer, $word );
					$word     = preg_replace( '/\bnazis\b/i', $replacer, $word );
					$word     = preg_replace( '/\bnazie\b/i', 'nazi', $word );
					$word     = preg_replace( '/\bnazi\b/i', $replacer, $word );
					$word     = preg_replace( '/\bverbalisant\b/i', $replacer, $word );
					$word     = preg_replace( '/\bglobalist\b/i', $replacer, $word );
					$word     = preg_replace( '/\b1488\b/', $replacer, $word );

				}

//				echo '" is nu: ' . $word . '<br>';

				$counter ++;
				if ( $word ) {
					array_push( $nieuwezin, $word );
				}


			}

			$sentence = implode( ' ', $nieuwezin );

		}

	}

//die('aargh');
	return $sentence;
}


// ===================================================================================================================

$esctext = escapeescapers( $_GET['txt_tegeltekst'] );
$text    = filtertext( $esctext );

// zorgen dat er per unieke tekst maar 1 uniek plaatje aangemaakt wordt
$hashname       = seoUrl( $text );
$filename       = $hashname . ".png";
$filename_klein = $hashname . "_thumb.png";

// output path voor grote tegel
$destimagepath = $sourcefiles_tegels . $filename;

// en dat tekstbestand erbij
$desttextpath = $sourcefiles_tegels . $hashname . ".txt";

// output path voor kleine thumbnail tegel
$thumbfile           = date( "Y" ) . "-" . date( "m" ) . "-" . date( "d" ) . "-" . date( "H" ) . "-" . date( "i" ) . "-" . date( "s" ) . "_" . $filename_klein;
$destimagepath_klein = $sourcefiles_thumbs . $thumbfile;

$desturl     = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $hashname;
$imagesource = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . "/" . TEGELIZR_TEGELFOLDER . "/" . $filename;

// ===================================================================================================================

// als het bestand niet bestaat
if ( ! file_exists( $destimagepath ) && ! file_exists( $desttextpath ) && ! file_exists( $destimagepath_klein ) ) {

	// base image importeren
	$img = imagecreatefrompng( $baseimgpath );

	// we mikken op een maximum van 4 zinnen en ideaal is 3
	$aantal_zinnen = 3;

	// Get image Width and Height
	$image_width   = imagesx( $img );
	$image_height  = imagesy( $img );
	$aantal_tekens = strlen( $text );
	$delimiter     = "<br />";

	// wat een fijne functie, dit wordwrap. Dank u, PHP.
	if ( DO_WORDWRAP ) {
		$newtext = wordwrap( $text, round( ( $aantal_tekens / $aantal_zinnen ), 3 ), $delimiter );
	} else {
		$newtext = $text;
	}

	$zinnen        = explode( $delimiter, $newtext );
	$aantal_zinnen = count( $zinnen );

	//     tellen van maximum aantal karakters per zin
	$maximale_text_lengte = 0;
	$maximale_text        = $text;

	for ( $i = 0, $size = $aantal_zinnen; $i < $size; ++ $i ) {
		if ( strlen( $zinnen[ $i ] ) > $maximale_text_lengte ) {
			$maximale_text_lengte = strlen( $zinnen[ $i ] );
			$maximale_text        = $zinnen[ $i ];
		}
	}

	$textcolor = imagecolorallocate( $img, TXTCOLOR_R, TXTCOLOR_G, TXTCOLOR_B );

	$standaard_karaktersperregel = 10;

	$fontsize = 100;
	$angle    = 0;
	$font     = FONTFILE;

	if ( ! file_exists( FONTFILE ) ) {
		die( 'font file not found: ' . FONTFILE );
	}


	$regelafstand = 10;

	// beetje tweaken en tunen met font-grootte
	if ( $maximale_text_lengte > $standaard_karaktersperregel ) {
		$fontsize = round( ( $fontsize * ( $standaard_karaktersperregel / $maximale_text_lengte ) ), 0 );
	}

	if ( $aantal_zinnen > 2 ) {

		$coordinaten  = calculatetekstdimensie( $fontsize, $font, $maximale_text, $image_width, $image_height );
		$regelhoogte  = $coordinaten['hoogte'];
		$verschuiving = ( $image_height / 2 ) - ( ( ( $regelhoogte + $regelafstand ) * ( $aantal_zinnen ) ) / 2 );

		for ( $i = 0, $size = $aantal_zinnen; $i < $size; ++ $i ) {

			// per zin bepalen waar deze op de image geplaatst moet worden
			$text = $zinnen[ $i ];

			$coordinaten           = calculatetekstdimensie( $fontsize, $font, $text, $image_width, $image_height );
			$coordinaten['hoogte'] = $regelhoogte; // $regelhoogte;
			teken_tekst( $img, $fontsize, $coordinaten, $textcolor, $font, $text, STYLING_BLURSTRENGTH, $i, $verschuiving );

			$verschuiving = ( $verschuiving + $regelhoogte + $regelafstand );

		}

	} else {
		// er zijn maar 2 zinnen of minder
		// voor grote teksten wil ik een grotere blur
		$blur = STYLING_BLURSTRENGTH;

		$text = $zinnen[0];

		if ( $maximale_text_lengte < 8 ) {
			// to do: dynamic blur
			// per max. regellengte bepalen welke font-grootte gebruikt moet worden
			switch ( $maximale_text_lengte ) {
				//------------------------------------------------------------------------------------------------------------
				case 1:
					//                    $blur = 8;
					$fontsize = 350;
					break;
				//------------------------------------------------------------------------------------------------------------
				case 2:
					//                    $blur = 8;
					$fontsize = 230;
					break;
				//------------------------------------------------------------------------------------------------------------
				case 3:
					//                    $blur = 6;
					$fontsize = 180;
					break;
				//------------------------------------------------------------------------------------------------------------
				case 4:
					$fontsize = 160;
					break;

				//------------------------------------------------------------------------------------------------------------
				case 5:
					$fontsize = 125;
					break;

				//------------------------------------------------------------------------------------------------------------
				case 6:
					$fontsize = 105;
					break;

				//------------------------------------------------------------------------------------------------------------
				default:
					$fontsize = 90;


				//------------------------------------------------------------------------------------------------------------
			}
		}

		if ( $aantal_zinnen == 2 ) {

			$coordinaten  = calculatetekstdimensie( $fontsize, $font, $maximale_text, $image_width, $image_height );
			$regelhoogte  = $coordinaten['hoogte'];
			$verschuiving = ( $image_height / 2 ) - ( ( ( $regelhoogte + $regelafstand ) * ( $aantal_zinnen ) ) / 2 );

			$text = $zinnen[0];

			$coordinaten           = calculatetekstdimensie( $fontsize, $font, $text, $image_width, $image_height );
			$coordinaten['hoogte'] = $regelhoogte; // $regelhoogte;
			teken_tekst( $img, $fontsize, $coordinaten, $textcolor, $font, $text, STYLING_BLURSTRENGTH, $i, $verschuiving );

			$verschuiving = ( $verschuiving + $regelhoogte + $regelafstand );

			$text = $zinnen[1];

			$coordinaten           = calculatetekstdimensie( $fontsize, $font, $text, $image_width, $image_height );
			$coordinaten['hoogte'] = $regelhoogte; // $regelhoogte;
			teken_tekst( $img, $fontsize, $coordinaten, $textcolor, $font, $text, STYLING_BLURSTRENGTH, $i, $verschuiving );

		} else {

			$text = $zinnen[0];

			$coordinaten  = calculatetekstdimensie( $fontsize, $font, $maximale_text, $image_width, $image_height );
			$regelhoogte  = $coordinaten['hoogte'];
			$verschuiving = ( $image_height / 2 ) - ( ( ( $regelhoogte + $regelafstand ) * ( $aantal_zinnen ) ) / 2 );

			// voor tegeltjes met 1 zin
			$coordinaten           = calculatetekstdimensie( $fontsize, $font, $text, $image_width, $image_height );
			$coordinaten['hoogte'] = $regelhoogte; // $regelhoogte;
			teken_tekst( $img, $fontsize, $coordinaten, $textcolor, $font, $text, STYLING_BLURSTRENGTH, $i, $verschuiving );

		}
	}

	$boom                   = array();
	$boom['txt_tegeltekst'] = filtertext( escapeescapers( $_GET['txt_tegeltekst'] ), true );
	$boom['file']           = $filename;
	$boom['secrit']         = uniqid();
	$boom['remoteip']       = get_user_ip();
	$boom['file_date']      = mktime( date( "H" ), date( "i" ), date( "s" ), date( "m" ), date( "d" ), date( "Y" ) );
	$boom['file_thumb']     = $destimagepath_klein;
	$boom[ TEGELIZR_VIEWS ] = 0;


	// tijd om op te ruimen
	// tekst bestand vullen. Deze hebben we nodig om de tekst te lezen.
	// Sleutel hiervoor is de bestandsnaam
	$fh = fopen( $desttextpath, 'w' ) or die( "can't open file: " . $desttextpath );
	$stringData = json_encode( $boom );
	fwrite( $fh, $stringData );
	fclose( $fh );

	// save merged image
	imagepng( $img, $destimagepath );
	imagedestroy( $img );

	resize( TEGELIZR_THUMB_WIDTH, $destimagepath_klein, $destimagepath );

// ===================================================================================================================

	$dosendemail = true;

	if ( defined( 'DO_SEND_MAIL' ) ) {
		// constante is alleen in speciale gevallen actief, niet op de live sites

		if ( DO_SEND_MAIL ) {
			// en we mogen ook in speciale gevallen mail sturen
		} else {
			// in alle andere gevallen: geen mail
			$dosendemail = false;
		}
	}

	if ( $dosendemail ) {

		$titel         = filtertext( $_GET['txt_tegeltekst'] );
		$secretkeyfile = $path . 'forbidden-tegeltjes.json';
		$file_contents = file_get_contents( $secretkeyfile );
		$secretkey     = json_decode( $file_contents );
		$data          = array(
			'tegel'  => $thumbfile,
			'action' => 'delete',
			'secrit' => $boom['secrit'],
			'sauce'  => $secretkey->sauce
		);
		$urldeleteme   = $desturl . '/?' . http_build_query( $data );
		$tralala       = "\n" . '<br><a href="' . $urldeleteme . '" style="display: inline-block; text-decoration: none; padding: .25rem .75rem; background: red; color: white;"><span style="font-size: 2rem">&#9888;</span> verwijder \'' . $titel . '\'</a>';

		$mailcontent = "Tekst: \n" . $_GET['txt_tegeltekst'] . "\n";
		$mailcontent .= "URL: \n";
		$mailcontent .= $desturl . "\n";
		$mailcontent .= "IP: \n";
		$mailcontent .= $boom['remoteip'] . "\n\n";
		$mailcontent .= "Verwijder tegeltje: \n";
		$mailcontent .= $urldeleteme . "\n";
//		$mailcontent .= $urlblockip . "\n\n";

		$data['remoteip'] = $boom['remoteip'];
		$data['action']   = 'block';
		$urlblockip       = $desturl . '/?' . http_build_query( $data );
		$tralala          = "\n" . '<br><a href="' . $urlblockip . '" style="display: inline-block; text-decoration: none; padding: .25rem .75rem; background: black; color: white;"><span style="font-size: 2rem">&#10013;</span> Block &amp; verwijder \'' . $titel . '\'</a>';

		$mailcontent .= "\n\nBlock IP en verwijder tegeltje: \n";
		$mailcontent .= $urlblockip . "\n";
//		$mailcontent .= $urldeleteme . "\n";

		mail( "vanbuuren@gmail.com", MAIL_PREFIX . ": " . $titel, $mailcontent, "From: tegelizr@globaldotcom.nl" );

	}

// ===================================================================================================================

	$destimagepath2       = linkbakker( $destimagepath, $path );
	$desttextpath2        = linkbakker( $desttextpath, $path );
	$destimagepath_klein2 = linkbakker( $destimagepath_klein, $path );
	$cookievalue          = $_GET['txt_tegeltekst'];

	if ( ! isset( $_COOKIE[ TEGELIZR_COOKIE_KEY ] ) ) {
		// een nieuwe gebruikert. hoi.
	} else {
		// een veteraan met meerdere tegels op z'n kerfstok
		$cookievalue = $_COOKIE[ TEGELIZR_COOKIE_KEY ] . COOKIESEPARATOR . $cookievalue;
	}

	$cookieexpire = strtotime( '+365 days' );
	$cookiepath   = '/';
	$cookiedomain = '/';
	$cookiesecure = '/';

	setcookie( TEGELIZR_COOKIE_KEY, $cookievalue, $cookieexpire, $cookiepath );

	if ( TEGELIZR_DEBUG_GENERATE && TEGELIZR_DEBUG ) {
		redirect_naar_verbetermetadatascript( $desturl );
	} else {
		redirect_naar_verbetermetadatascript( $desturl . '?' . TEGELIZR_TRIGGER_KEY . '=' . TEGELIZR_TRIGGER_VALUE );
	}
} else {

	redirect_naar_verbetermetadatascript( $desturl );

}

// ===================================================================================================================

function linkbakker( $textstring = '', $filter = '' ) {

	$with    = '/';
	$pattern = '|' . $filter . '|i';
	$url     = preg_replace( $pattern, $with, $textstring );

	$with       = '';
	$pattern    = '|' . $filter . '|i';
	$textstring = preg_replace( $pattern, $with, $textstring );


	$stroutput = '<a href="' . $url . '">' . $textstring . '</a>';

	return $stroutput;

}

// ===================================================================================================================

function calculatetekstdimensie( $fontsize, $font, $text, $image_width, $image_height ) {

	$coordinaten = array();

	// Get Bounding Box Size
	$text_box = imagettfbbox( $fontsize, 0, $font, $text );

	$coordinaten['hoogte']   = ( $text_box[1] - $text_box[5] );
	$coordinaten['breedte']  = ( $text_box[2] - $text_box[0] );
	$coordinaten['xbottomr'] = $coordinaten['breedte'];
	$coordinaten['ybottomr'] = 0;
	$coordinaten['xtopl']    = ( $image_width / 2 ) - ( $coordinaten['breedte'] / 2 );
	$coordinaten['ytopl']    = $coordinaten['hoogte'];


	return $coordinaten;


}

// ===================================================================================================================
// function to resize an image to a new width
// ===================================================================================================================
function teken_tekst( $canvas, $fontsize, $coordinaten, $textcolor, $font, $text, $blur, $counter, $verschuiving_y ) {

	$angle = 0;

	switch ( $counter ) {
		case 1:
			$color = imagecolorallocate( $canvas, 132, 135, 28 );
			break;
		case 2:
			$color = imagecolorallocate( $canvas, 255, 105, 180 );
			break;
		case 3:
			$color = imagecolorallocate( $canvas, 255, 0, 0 );
			break;
		default:
			$color = imagecolorallocate( $canvas, 255, 0, 255 );
			break;
	}

//    $ypos = ( ( -1 * ( $coordinaten['hoogte'] + $coordinaten['ybottomr'] ) ) + $verschuiving_y );
	$ypos = ( $coordinaten['hoogte'] + $verschuiving_y );

//    $eindy = ( $coordinaten['hoogte'] + $verschuiving_y );

	// Een rechthoek om de omtrek te zien
//    imagerectangle($canvas, $coordinaten['xtopl'], $verschuiving_y, $coordinaten['breedte'], $eindy, $color);

//    imagettftextblur($canvas, $fontsize, $angle, $coordinaten['xtopl'], $ypos, $color, $font, $text, $blur);
	imagettftextblur( $canvas, $fontsize, $angle, $coordinaten['xtopl'], $ypos, $textcolor, $font, $text, $blur );
//    imagettftextblur($canvas, $fontsize, $angle, $coordinaten['xtopl'], ( $coordinaten['ytopl'] + $verschuiving_y ), $textcolor, $font, $text, $blur);

}


// ===================================================================================================================
// function to resize an image to a new width
// ===================================================================================================================
function resize( $newWidth, $targetFile, $originalFile ) {

	$info = getimagesize( $originalFile );
	$mime = $info['mime'];

	switch ( $mime ) {
		case 'image/jpeg':
			$image_create_func = 'imagecreatefromjpeg';
			$image_save_func   = 'imagejpeg';
			$new_image_ext     = 'jpg';
			break;

		case 'image/png':
			$image_create_func = 'imagecreatefrompng';
			$image_save_func   = 'imagepng';
			$new_image_ext     = 'png';
			break;

		case 'image/gif':
			$image_create_func = 'imagecreatefromgif';
			$image_save_func   = 'imagegif';
			$new_image_ext     = 'gif';
			break;

		default:
			throw Exception( 'Unknown image type.' );
	}

	$img = $image_create_func( $originalFile );
	list( $width, $height ) = getimagesize( $originalFile );

	$newHeight = ( $height / $width ) * $newWidth;
	$tmp       = imagecreatetruecolor( $newWidth, $newHeight );
	imagecopyresampled( $tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height );

	if ( file_exists( $targetFile ) ) {
		unlink( $targetFile );
	}

	// save merged image
	imagepng( $tmp, $targetFile );


}


?>


