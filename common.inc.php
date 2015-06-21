<?php 

// Report all PHP errors
error_reporting(-1);

// Same as error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);

// ===================================================================================================================

define('PVB_DEBUG', false);


define('TEGELIZR_TITLE',        'Online tegeltjes bakken');
define('TEGELIZR_FORM',         'Wat is jouw tegeltjeswijsheid? Voer hier je tekst in. Een dag geen tegeltjes gemaakt is een dag niet geleefd!');
define('TEGELIZR_BACK',         '<span>nog een tegeltje</span>');
define('TEGELIZR_SUBMIT',       'bak mijn tegeltje');
define('TEGELIZR_TXT_LENGTH',   90);
define('TEGELIZR_THUMB_WIDTH',  220);
define('TEGELIZR_BLUR',         2);
define('TEGELIZR_TXT_VALUE',    '');
define('TEGELIZR_SELECTOR',     'tegeltje');
define('TEGELIZR_PROTOCOL',     'http://');
define('TEGELIZR_SUMMARY',      'Maak hier je eigen tegeltje. Een geintje van Paul van Buuren, van WBVB Rotterdam.');
define('TEGELIZR_THUMBS',       'thumbs');
define('TEGELIZR_VIEWS',        'views');
define('TEGELIZR_TEGELFOLDER',  'tegeltjes');
define('TEGELIZR_ALLES',        'alle-tegeltjes');
define('TEGELIZR_REDACTIE',     'redactie');
define('TEGELIZR_DEFAULT_IMAGE','http://wbvb.nl/images/kiezen-is-een-keuze.jpg');


$path               = dirname(__FILE__)."/";

$sourcefolder       = $path."img/";
$fontpath           = $path."fonts/";
$outpath            = $path. TEGELIZR_TEGELFOLDER . "/";
$outpath_thumbs     = $path. TEGELIZR_THUMBS . "/";
$baseimgpath        = $sourcefolder."base.png";

// ===================================================================================================================
function maakoverzichtspagina() {

    global $outpath_thumbs;
    global $outpath;
    global $path;
    $list = '';
    
    $index_html     = $path . TEGELIZR_ALLES . "/index.html";
    $index_txt      = $path . TEGELIZR_ALLES . "/index.txt";

    if (is_dir($outpath_thumbs)) {

        $images = glob($outpath_thumbs . "*.png");
        
        rsort($images);
        
        foreach($images as $image) {
            
            // door alle thumbs heen lopen
            $stack          = explode('/', $image);
            $thumb_filename = array_pop($stack);
            $info           = explode('_', $thumb_filename );
            $time           = explode('-', $info[0] );

            $groot_image    = $outpath . $info[1] . '.png';
            $groot_txt      = $outpath . $info[1] . '.txt';
            
            // als de grote plaat ook bestaat
            if ( file_exists( $groot_image ) ) {
                $boom[$thumb_filename]  = getviews($groot_txt, false);

                $list .= '<li><a href="/'  . TEGELIZR_SELECTOR . '/' . $info[1] . '" title="' . filtertext($boom[$thumb_filename]['txt_tegeltekst']) . ' - ' . $boom[$thumb_filename][TEGELIZR_VIEWS] . ' keer bekeken"><img src="/' . TEGELIZR_THUMBS . '/' . $thumb_filename . '" height="' . TEGELIZR_THUMB_WIDTH . '" width="' . TEGELIZR_THUMB_WIDTH . '" alt="' . filtertext($boom[$thumb_filename]['txt_tegeltekst']) . '" /></a></li>'; 

            }
        }    

        if ( file_exists( $index_html ) ) {
    
            $desturl        = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ALLES . '/';
            $stringData     = spitoutheader() . '<meta property="og:title" content="' . filtertext($boom[$thumb_filename]['txt_tegeltekst']) . '" /><meta property="og:description" content="' . TEGELIZR_SUMMARY . '" /><meta property="og:url" content="' . $desturl . '" /><meta property="article:tag" content="' . TEGELIZR_ALLES . '" /><meta property="og:image" content="' . TEGELIZR_DEFAULT_IMAGE . '" /><title>' . TEGELIZR_TITLE . ' - alle tegeltjes</title>' .  htmlheader() . '<article class="resultaat"><h1><a href="/" title="Maak zelf ook een tegeltje">' . returnlogo() . 'Alle tegeltjes</a></h1><p>Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>' .  wbvb_d2e_socialbuttons($desturl, $txt_tegeltekst, TEGELIZR_SUMMARY) . '<section id="andere"><h2>Wat anderen maakten:</h2><ul class="thumbs">' . $list . '</ul></section><p id="home"> <a href="/">' .  TEGELIZR_BACK . '</a> </p></article>' . spitoutfooter();
            $fh             = fopen($index_html, 'w') or die("can't open file: " . $index_html );
            fwrite($fh, $stringData);
            fclose($fh);
        }
        else {
            die('file does not exist : ' . $index_html);
        }

        $fh             = fopen($index_txt, 'w') or die("can't open file: " . $index_txt);
        $stringData     = json_encode($boom);
        fwrite($fh, $stringData);
        fclose($fh);
    }    
}    

// ===================================================================================================================

function filtertext($text = '') {

    $text                = preg_replace("/script>/", "poep>", trim($text));
    $text                = preg_replace("/[^a-zA-Z0-9-_\.\, \?\!\@\(\)\=\-\:\;\'\"üëïöäéèêç]+/", "", trim($text));
    $text                = substr($text,0,TEGELIZR_TXT_LENGTH);
    $text                = preg_replace("/Geert Wilders/i", "zaadslurf", trim($text));
    $text                = preg_replace("/Wilders/", "zaadslurf", trim($text));
    $text                = preg_replace("/PVV/", "NSB", trim($text));
    $text                = preg_replace("/moslima/i", "Tante Truus", trim($text));
    $text                = preg_replace("/Tante Truus's/i", "Tante Truusjes", trim($text));
    $text                = preg_replace("/moslims/i", "Ajax-supporters en wielrenfans", trim($text));
    $text                = preg_replace("/moslim/i", "Alfred Jodocus Kwak", trim($text));
    $text                = preg_replace("/islam/i", "Kabouter Plop", trim($text));
    $text                = preg_replace("/username/i", " *zucht* ", trim($text));
    $text                = preg_replace("/password/i", " *gaap* ", trim($text));
    $text                = preg_replace("/;DROP /i", " *snurk* ", trim($text));
    $text                = preg_replace("/select /i", " *fart* ", trim($text));
    $text                = preg_replace("/ table /i", " *pfffffrt* ", trim($text));
    $text                = preg_replace("/facebook/i", " het satanische Facebook", $text);
    
    return $text;

}

// ===================================================================================================================

function showthumbs($aantal = '10', $hide = '') {
    global $outpath_thumbs;
    global $outpath;

    echo '<section id="andere"><h2>Wat anderen maakten:</h2>';
    echo '<ul class="thumbs">';

    $counter = 0;

    if (is_dir($outpath_thumbs)) {

        $images = glob($outpath_thumbs . "*.png");
        
        rsort($images);
        
        foreach($images as $image) {

            
            if ( ( $counter >= $aantal ) && ( $aantal > 0 ) ) {
                break;
            }

            $stack       = explode('/', $image);
            $filename    = array_pop($stack);
            $info        = explode('_', $filename );
            $views       = getviews($outpath.$info[1] . '.txt',false);

            if ( $hide == $info[1] ) {
//                break;
            }
            else {
                
                if ( $aantal > 0 ) {
                    $counter++;
                }

                $fruit = '<a href="/'  . TEGELIZR_SELECTOR . '/' . $info[1] . '" title="' . filtertext($views['txt_tegeltekst']) . ' - ' . $views[TEGELIZR_VIEWS] . ' keer bekeken"><img src="/' . TEGELIZR_THUMBS . '/' . $filename . '" height="' . TEGELIZR_THUMB_WIDTH . '" width="' . TEGELIZR_THUMB_WIDTH . '" alt="' . filtertext($views['txt_tegeltekst']) . '" /></a>';
                echo '<li>' . $fruit . "</li>";
            }

            
        }    
    }    
    echo '</ul></section>';
    
}

// ===================================================================================================================

function writedebug($text) {
    if ( PVB_DEBUG ) {
        @ini_set('display_errors',1);
        echo $text . "<br />";
    }
    else {
        @ini_set('display_errors',0);
    }    
}

// ===================================================================================================================

function wbvb_d2e_socialbuttons($thelink = 'thelink', $thetitle = 'thetitle', $summary = 'summmary') {

    $sitetitle  = urlencode($thetitle);
    $popup      = ' onclick="javascript:window.open(this.href, \'\', \'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600\');return false;"';
    
    if ( $thelink ) {
        return '<ul class="social-media">
            <li><a href="https://twitter.com/share?url=' . $thelink . '&via=paulvanbuuren&text=' . $thetitle . '" class="twitter" data-url="' . $thelink . '" data-text="' . $thetitle . '" data-via="@paulvanbuuren"' . $popup . '><span>Tweet</span></a></li><li><a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=' . $thelink . '&t=' . $thetitle . '"' . $popup . '><span>Facebook</span></a></li><li><a class="linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=' . $thelink . '&title=' . $thetitle . '&summary=' . $summary . '&source=' . $sitetitle . '"' . $popup . '><span>LinkedIn</span></a></li></ul>';    
    }
}


// ===================================================================================================================
function htmlheader() {
    return '<link href="//wbvb.nl/wp-content/themes/wbvb/style.css" rel="stylesheet" type="text/css"><link href="css/style.css" rel="stylesheet" type="text/css"></head><body>';
  
}

// ===================================================================================================================
function returnlogo() {
    return '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="90px" height="63px" viewBox="0 0 869 490" version="1.1"><title>wbvb-logo</title><defs/><g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage"><g id="wbvb-logo" sketch:type="MSArtboardGroup"><g id="WBVB-logo" sketch:type="MSLayerGroup" transform="translate(16.000000, 7.000000)"><path d="M7.18988211 68.4 L0.158783513 75.5 L7.22404114 82.5 C8.33451468 83.7 9 84.3 9.7 85.1 C11.7375822 87.3 14.1 90 16.6 92.9 C23.728477 101.5 30.9 111.3 37.6 122 C56.5807529 152.2 67.9 183.8 67.9 214.8 C67.8932004 245.9 56.6 277.6 37.6 307.9 C30.9071551 318.6 23.7 328.4 16.5 337 C14.037922 340 11.7 342.7 9.7 344.9 C8.94577626 345.7 8.3 346.3 7.8 346.9 C7.48130958 347.2 0.2 354.5 0.2 354.5 L7.18988211 361.6 L57.5861278 412.4 L63.4459054 418.3 L70.3163067 413.6 C72.0624874 412.4 73 411.8 74.1 411.1 C77.3408623 409 81 406.7 85.2 404.2 C97.0894259 397.1 110.2 390 124 383.4 C151.611088 370.2 178.7 360.6 203.8 356.1 C213.863822 354.3 223.5 353.4 232.6 353.4 C245.010119 353.4 258.7 355.5 273.4 359.5 C295.918989 365.7 320.1 376.1 344.9 389.5 C359.715966 397.5 373.8 406.1 386.7 414.7 C391.178123 417.7 395.2 420.4 398.7 422.9 C399.913377 423.8 401 424.6 401.9 425.2 C402.392514 425.6 409.3 430.8 409.3 430.8 L415.405779 425.6 C417.078774 424.2 418 423.5 419.1 422.6 C422.160919 420.1 425.7 417.4 429.7 414.4 C441.243072 405.8 453.9 397.3 467.4 389.3 C497.120291 371.8 526.3 359.7 553.2 355.2 C560.513534 354 567.6 353.4 574.3 353.4 C587.613462 353.4 602.3 355.3 618.2 359 C640.692414 364.2 664.8 372.7 689.6 383.5 C704.704237 390.2 719.2 397.3 732.4 404.4 C737.018348 406.9 741.2 409.2 744.8 411.3 C746.001643 412 747.1 412.7 748 413.2 C748.554996 413.5 755.8 418 755.8 418 L761.385679 412.3 L811.545964 361.6 L818.537617 354.5 L811.501738 347.5 C810.386383 346.3 809.8 345.7 809 344.9 C806.971509 342.6 804.7 340 802.1 337 C794.941281 328.4 787.7 318.6 781 307.9 C761.984982 277.5 750.6 245.9 750.6 214.8 C750.637363 183.8 762 152.2 781 122 C787.721696 111.3 794.9 101.5 802.1 93 C804.63672 90 807 87.3 809 85.1 C809.742344 84.3 810.4 83.7 810.9 83.1 C811.210313 82.8 818.6 75.5 818.6 75.5 L811.545964 68.4 L761.385679 17.7 L756.330799 12.5 L749.873442 15.7 C747.836723 16.7 746.7 17.2 745.5 17.8 C741.798514 19.5 737.6 21.4 732.9 23.5 C719.463954 29.3 704.8 35.2 689.6 40.6 C666.276523 49 643.6 55.6 622.4 60 C604.79023 63.5 588.7 65.4 574.3 65.4 C565.718768 65.4 556.6 64.6 547.1 62.9 C522.065645 58.4 495 48.4 467.5 34.6 C453.853503 27.8 441 20.5 429.3 13.2 C425.253073 10.6 421.6 8.3 418.5 6.1 C417.390537 5.4 416.4 4.8 415.6 4.2 C415.176992 3.9 409.2 -0.4 409.2 -0.4 L403.532624 3.3 C401.586066 4.5 400.5 5.1 399.3 5.9 C395.776551 8 391.7 10.4 387.2 13 C374.221582 20.3 360.1 27.6 345.2 34.5 C321.057841 45.6 297.5 54.4 275.5 59.7 C259.962304 63.5 245.6 65.4 232.6 65.4 C222.743752 65.4 212.2 64.5 201.2 62.8 C176.733922 59 150.4 51.2 123.6 40.7 C109.701171 35.3 96.5 29.5 84.5 23.6 C80.3633718 21.6 76.6 19.7 73.4 18 C72.2780493 17.4 71.3 16.9 70.5 16.5 C69.9951805 16.2 62.9 12.3 62.9 12.3 L57.5861278 17.6 L7.18988211 68.4 Z M14.2856373 75.4 C14.2856373 75.4 77.9 138.9 77.9 214.8 C77.8932004 290.8 14.3 354.6 14.3 354.6 L64.681883 405.3 C64.681883 405.3 155.4 343.4 232.6 343.4 C309.782993 343.4 408.9 418 408.9 418 C408.913126 418 496.3 343.4 574.3 343.4 C652.390531 343.4 754.3 405.3 754.3 405.3 L804.433695 354.6 C804.433695 354.6 740.6 290.8 740.6 214.8 C740.637363 138.9 804.4 75.4 804.4 75.4 L754.27341 24.7 C754.27341 24.7 650.7 75.4 574.3 75.4 C497.977282 75.4 408.9 11.7 408.9 11.7 C408.913141 11.7 309.1 75.4 232.6 75.4 C156.167586 75.4 64.7 24.7 64.7 24.7 L14.2856373 75.4 Z" id="outside" fill="#9D1626" sketch:type="MSShapeGroup"/><path d="M14.2856373 354.3 L64.6724398 405 C64.6724398 405 155.4 343.2 232.6 343.2 C309.727623 343.2 408.8 417.7 408.8 417.7 C408.839181 417.7 496.2 343.2 574.2 343.2 C652.270964 343.2 754.1 405 754.1 405 L804.285637 354.3 C804.285637 354.3 740.5 290.6 740.5 214.7 C740.50126 138.8 804.3 75.4 804.3 75.4 L754.134751 24.7 C754.134751 24.7 650.6 75.4 574.2 75.4 C497.886648 75.4 408.8 11.7 408.8 11.7 C408.839196 11.7 309 75.4 232.6 75.4 C156.141 75.4 64.7 24.7 64.7 24.7 L14.2856373 75.4 C14.2856373 75.4 77.9 138.8 77.9 214.7 C77.8812817 290.6 14.3 354.3 14.3 354.3 Z" id="Path-2" stroke="#FFFFFF" stroke-width="10" fill="#9D1626" sketch:type="MSShapeGroup"/><g id="tekst" fill="#FFFFFF" sketch:type="MSShapeGroup"><path d="M141.2 228.3 L125.4 251.7 L116.3 245 L133.6 222.8 L106 214.7 L109.6 203.8 L136.5 213.8 L135.6 184.9 L147 184.9 L146.1 213.8 L172.7 203.8 L176.4 214.7 L148.8 222.8 L166 245 L157.1 251.7 L141.2 228.3 Z" id="ster-links"/><path d="M677.8 229.6 L662.1 252.9 L653 246.2 L670.2 224.1 L642.7 216 L646.3 205.1 L673.1 215 L672.2 186.2 L683.6 186.2 L682.7 215 L709.4 205.1 L713 216 L685.5 224.1 L702.7 246.2 L693.8 252.9 L677.8 229.6 Z" id="ster-rechts"/><path d="M259.2 151.6 L259.8 151.6 L301.7 236.3 L302.2 236.3 L322.2 154.3 L337.4 154.3 L307.1 278 L306.6 278 L259.8 183.2 L259.2 183.2 L212.5 278 L211.9 278 L181.6 154.3 L196.9 154.3 L216.8 236.3 L217.4 236.3 L259.2 151.6 ZM404.1 212 C406.7 213.1 409 214.6 411.1 216.6 C413.2 218.6 415.1 220.9 416.6 223.6 C418.1 226.3 419.3 229.1 420.2 232 C421.1 235 421.6 238 421.6 241.2 C421.6 245.9 420.6 250.3 418.8 254.4 C417 258.5 414.6 262.1 411.5 265.2 C408.4 268.3 404.7 270.7 400.5 272.6 C396.3 274.4 391.8 275.3 387.1 275.3 L351.6 275.3 L351.6 154.3 L381.3 154.3 C386 154.3 390.4 155.2 394.4 156.9 C398.5 158.7 402.1 161.1 405.1 164.1 C408.2 167.1 410.6 170.7 412.4 174.7 C414.1 178.8 415 183.1 415 187.7 C415 192.4 414 197 411.9 201.5 C409.9 205.9 407.3 209.3 404.1 211.4 L404.1 212 ZM380.4 207.1 C386.3 207.1 391.1 205.2 394.6 201.6 C398.2 197.9 400 193.3 400 187.9 C400 185.2 399.5 182.7 398.4 180.3 C397.4 178 396 175.9 394.3 174.2 C392.5 172.4 390.5 171 388.1 170 C385.7 169 383.2 168.5 380.4 168.5 L366.2 168.5 L366.2 207.1 L380.4 207.1 ZM366.2 220.9 L366.2 261.1 L386.2 261.1 C389 261.1 391.6 260.6 394.1 259.5 C396.6 258.4 398.7 257 400.5 255.1 C402.3 253.3 403.8 251.2 404.9 248.8 C406 246.4 406.5 243.8 406.5 241 C406.5 238.2 406 235.6 404.9 233.2 C403.8 230.8 402.3 228.7 400.5 226.9 C398.7 225 396.6 223.6 394.1 222.5 C391.6 221.4 389 220.9 386.2 220.9 L366.2 220.9 ZM486.5 278 L485.9 278 L429.2 154.3 L445.1 154.3 L485.9 243.4 L486.5 243.4 L527.5 154.3 L543.4 154.3 L486.5 278 ZM610.2 212 C612.7 213.1 615 214.6 617.1 216.6 C619.3 218.6 621.1 220.9 622.6 223.6 C624.1 226.3 625.3 229.1 626.2 232 C627.1 235 627.6 238 627.6 241.2 C627.6 245.9 626.7 250.3 624.8 254.4 C623 258.5 620.6 262.1 617.5 265.2 C614.4 268.3 610.8 270.7 606.5 272.6 C602.3 274.4 597.8 275.3 593.1 275.3 L557.6 275.3 L557.6 154.3 L587.3 154.3 C592 154.3 596.4 155.2 600.5 156.9 C604.5 158.7 608.1 161.1 611.2 164.1 C614.2 167.1 616.7 170.7 618.4 174.7 C620.2 178.8 621 183.1 621 187.7 C621 192.4 620 197 618 201.5 C615.9 205.9 613.3 209.3 610.2 211.4 L610.2 212 ZM586.4 207.1 C592.3 207.1 597.1 205.2 600.6 201.6 C604.2 197.9 606 193.3 606 187.9 C606 185.2 605.5 182.7 604.4 180.3 C603.4 178 602 175.9 600.3 174.2 C598.5 172.4 596.5 171 594.1 170 C591.7 169 589.2 168.5 586.4 168.5 L572.3 168.5 L572.3 207.1 L586.4 207.1 ZM572.3 220.9 L572.3 261.1 L592.2 261.1 C595 261.1 597.6 260.6 600.1 259.5 C602.6 258.4 604.7 257 606.5 255.1 C608.3 253.3 609.8 251.2 610.9 248.8 C612 246.4 612.5 243.8 612.5 241 C612.5 238.2 612 235.6 610.9 233.2 C609.8 230.8 608.3 228.7 606.5 226.9 C604.7 225 602.6 223.6 600.1 222.5 C597.6 221.4 595 220.9 592.2 220.9 L572.3 220.9 Z" id="WBVB"/></g></g></g></g></svg>';
}

// ===================================================================================================================


function spitoutheader() {
    return '<!DOCTYPE html><html lang="nl"><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="shortcut icon" href="http://wbvb.nl/images/favicon.ico" type="image/x-icon" /><link rel="publisher" href="https://plus.google.com/u/0/+PaulvanBuuren"/><meta name="twitter:card" content="summary"/><meta name="twitter:site" content="@paulvanbuuren"/><meta name="twitter:domain" content="WBVB"/><meta name="twitter:creator" content="@paulvanbuuren"/><meta property="og:locale" content="nl_NL" /><meta property="og:type" content="article" /><meta property="og:site_name" content="Webbureau Van Buuren Rotterdam" /><meta property="article:publisher" content="https://www.facebook.com/webbureauvanbuuren" />';
    
}
// ===================================================================================================================

function getviews($filename, $update = false) {

    $json_data      = file_get_contents($filename);
    $archieftekst   = json_decode($json_data, true);

    if ( isset( $archieftekst[TEGELIZR_VIEWS] ) ) {
        $archieftekst[TEGELIZR_VIEWS] = intval( $archieftekst[TEGELIZR_VIEWS] );
    }
    else {
        $archieftekst[TEGELIZR_VIEWS] = 1;
    }

    if ( $update ) {
        $archieftekst[TEGELIZR_VIEWS] = ( intval( $archieftekst[TEGELIZR_VIEWS] ) + $update);
        $newJsonString = json_encode($archieftekst);
        file_put_contents($filename, $newJsonString);
    }

    if ( isset( $archieftekst[TEGELIZR_VIEWS] ) ) {
        return $archieftekst;
    }
    else {
        $dinges = array();
        $dinges[TEGELIZR_VIEWS] = 1;
        return $dinges;
    }
    
}

// ===================================================================================================================

function spitoutfooter() {
    
    return '
<footer><div id="footer-contact"><h3>Contact</h3><ul><li><a href="mailto:paul@wbvb.nl">mail</a></li><li><a href="https://twitter.com/paulvanbuuren">twitter</a></li><li><a href="https://wbvb.nl/">wbvb.nl</a></li></ul></div><div id="footer-about"><h3>Over de site</h3><ul><li><a href="' . TEGELIZR_PROTOCOL . $_SERVER["HTTP_HOST"] . '/' . TEGELIZR_REDACTIE . '/">redactie</a></li><li><a href="' . TEGELIZR_PROTOCOL . $_SERVER["HTTP_HOST"] . '/' . TEGELIZR_ALLES . '/">alle tegeltjes</a></li><li><a href="http://wbvb.nl/tegeltjes-maken-is-een-keuze/">waarom tegeltjes</a></li><li><a href="https://github.com/paulvanbuuren/tegelizr-source">broncode</a></li></ul></div></footer><scri' . "pt>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', 'UA-1780046-36', 'auto');ga('send', 'pageview');</scr" . 'ipt></body></html>';

}

?>