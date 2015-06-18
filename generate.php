<?

include("common.inc.php"); 
include("includes/blur.php"); 


// ===================================================================================================================
// gelijk doorlussen als er geen waarde is
if (empty($_GET['txt_tegeltekst']))
    header('Location: ' . $desturl);    


// ===================================================================================================================

// opschonen
$text                = substr(preg_replace("/[^a-zA-Z0-9-_\.\, \?\!\@\(\)\=\-\:\;\'\"üëïöäéèêç]+/", "", trim($_GET['txt_tegeltekst'])),0,TEGELIZR_TXT_LENGTH);

// zorgen dat er per unieke tekst maar 1 uniek plaatje aangemaakt wordt
$hashname           = seoUrl( $text );
$filename           = $hashname . ".png";
$filetxt            = $hashname . ".txt";
$filename_klein     = $hashname . "_thumb.png";

// output path voor grote tegel
$destimagepath      = $outpath.$filename;

// en dat tekstbestand erbij
$filetxt            = $outpath.$hashname . ".txt";

// output path voor kleine thumbnail tegel
$destimagepath_klein= $outpath_thumbs.date("Y") . "-" . date("m") . "-" . date("d") . "-" . date("H") . "-" . date("i") . "-" . date("s") . "_" . $filename_klein;

$desturl            = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $hashname;
$imagesource        = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . "/" . TEGELIZR_TEGELFOLDER . "/".$filename;


// ===================================================================================================================

// als het bestand niet bestaat
if (!file_exists($outpath.$filename)) {

    // base image importeren
    $img            = imagecreatefrompng($baseimgpath);

    // we mikken op een maximum van 4 zinnen en ideaal is 3
    $aantal_zinnen  = 3;

    // Get image Width and Height
    $image_width    = imagesx($img);  
    $image_height   = imagesy($img);
    $aantal_tekens  = strlen($text);
    $delimiter      = "<br />";
    
    // wat een fijne functie, dit wordwrap. Dank u, PHP.
    $newtext        = wordwrap($text, round( ( $aantal_tekens / $aantal_zinnen  ), 3 ), $delimiter);

    $zinnen         = explode($delimiter, $newtext);
    $aantal_zinnen  = count($zinnen);

    //     tellen van maximum aantal karakters per zin
    $maximale_text_lengte    = 0;
    $maximale_text            = $text;

    for($i = 0, $size = $aantal_zinnen; $i < $size; ++$i) {
        if ( strlen($zinnen[$i]) > $maximale_text_lengte ) {
            $maximale_text_lengte   = strlen($zinnen[$i]);
            $maximale_text          = $zinnen[$i];
        }
    }
    
    // white
    $textcolor        = imagecolorallocate($img, 56, 98, 170);

    $standaard_karaktersperregel = 10;
    
    $fontsize        = 100;
    $angle           = 0;
    $font            = $fontpath."tegeltje.otf";
    $regelafstand    = 10;

    // beetje tweaken en tunen met font-grootte
    if ( $maximale_text_lengte > $standaard_karaktersperregel ) {
        $fontsize        = round( ( $fontsize * ( $standaard_karaktersperregel / $maximale_text_lengte  ) ), 0);
    }

    if ( $aantal_zinnen > 2 )  {
    
        $coordinaten    = calculatetekstdimensie($fontsize,$font,$maximale_text,$image_width,$image_height);
        $regelhoogte     = $coordinaten['hoogte'];
        $verschuiving    = ( $image_height / 2 ) - ( ( ( $regelhoogte + $regelafstand ) * ( $aantal_zinnen  ) ) / 2);
    
        for($i = 0, $size = $aantal_zinnen; $i < $size; ++$i) {
            
            // per zin bepalen waar deze op de image geplaatst moet worden
            $text = $zinnen[$i];
        
            $coordinaten            = calculatetekstdimensie($fontsize,$font,$text,$image_width,$image_height);
            $coordinaten['hoogte']    = $regelhoogte; // $regelhoogte;
            teken_tekst($img, $fontsize, $coordinaten, $textcolor, $font, $text, TEGELIZR_BLUR, $i, $verschuiving);

            $verschuiving            = ( $verschuiving  + $regelhoogte + $regelafstand );    
        
        }
    
    }
    else {
        
        // er zijn maar 2 zinnen of minder

        // voor grote teksten wil ik een grotere blur
        $blur = TEGELIZR_BLUR;

        $text = $zinnen[0];
        
        if ( $maximale_text_lengte < 8 ) {

            $blur = 4;

            // per max. regellengte bepalen welke font-grootte gebruikt moet worden
            switch ($maximale_text_lengte) {
                case 1:
                    $blur = 8;
                    $fontsize        = 350;
                    break;
                case 2:
                    $blur = 8;
                    $fontsize        = 230;
                    break;
                case 3:
                    $blur = 6;
                    $fontsize        = 180;
                    break;
                case 4:
                    $fontsize        = 160;
                    $blur = 6;
                    break;
                case 5:
                    $fontsize        = 125;
                    break;
                case 6:
                    $fontsize        = 105;
                    break;
                default:
                    $fontsize        = 90;
            }
            
        }
        
    
        if ( $aantal_zinnen == 2 ) {

            $coordinaten    = calculatetekstdimensie($fontsize,$font,$maximale_text,$image_width,$image_height);
            $regelhoogte     = $coordinaten['hoogte'];
            $verschuiving    = ( $image_height / 2 ) - ( ( ( $regelhoogte + $regelafstand ) * ( $aantal_zinnen  ) ) / 2);

            $text = $zinnen[0];
        
            $coordinaten            = calculatetekstdimensie($fontsize,$font,$text,$image_width,$image_height);
            $coordinaten['hoogte']    = $regelhoogte; // $regelhoogte;
            teken_tekst($img, $fontsize, $coordinaten, $textcolor, $font, $text, TEGELIZR_BLUR, $i, $verschuiving);

            $verschuiving            = ( $verschuiving  + $regelhoogte + $regelafstand );    

            $text = $zinnen[1];
        
            $coordinaten            = calculatetekstdimensie($fontsize,$font,$text,$image_width,$image_height);
            $coordinaten['hoogte']    = $regelhoogte; // $regelhoogte;
            teken_tekst($img, $fontsize, $coordinaten, $textcolor, $font, $text, TEGELIZR_BLUR, $i, $verschuiving);


        }
        else {
            
            $text = $zinnen[0];

            $coordinaten    = calculatetekstdimensie($fontsize,$font,$maximale_text,$image_width,$image_height);
            $regelhoogte     = $coordinaten['hoogte'];
            $verschuiving    = ( $image_height / 2 ) - ( ( ( $regelhoogte + $regelafstand ) * ( $aantal_zinnen  ) ) / 2);

            // voor tegeltjes met 1 zin
            $coordinaten            = calculatetekstdimensie($fontsize,$font,$text,$image_width,$image_height);
            $coordinaten['hoogte']    = $regelhoogte; // $regelhoogte;
            teken_tekst($img, $fontsize, $coordinaten, $textcolor, $font, $text, TEGELIZR_BLUR, $i, $verschuiving);

        }
        
    }


    // tijd om op te ruimen
    // tekst bestand vullen. Deze hebben we nodig om de tekst te lezen. 
    // Sleutel hiervoor is de bestandsnaam
    $fh         = fopen($filetxt, 'w') or die("can't open file: " . $filetxt);
    $stringData    = json_encode($_GET);
    fwrite($fh, $stringData);
    fclose($fh);

    
    // save merged image
    imagepng($img, $destimagepath);
    imagedestroy($img);

    resize(TEGELIZR_THUMB_WIDTH,$destimagepath_klein,$destimagepath);
    
    // doorsturen naar pagina met het aangemaakte image
    header('Location: ' . $desturl);    

}
else {

    // doorsturen naar pagina met het bestaande image
    header('Location: ' . $desturl);    

}



function calculatetekstdimensie($fontsize,$font,$text,$image_width,$image_height) {
    
    $coordinaten  = array();

    // Get Bounding Box Size
    $text_box        = imagettfbbox($fontsize,0,$font,$text);

    $coordinaten['hoogte']        = ( $text_box[1] - $text_box[5] );
    $coordinaten['breedte']        = ( $text_box[2] - $text_box[0] );
    $coordinaten['xbottomr']    = $coordinaten['breedte'];
    $coordinaten['ybottomr']    = 0;
    $coordinaten['xtopl']        = ($image_width / 2) - ($coordinaten['breedte']/2);
    $coordinaten['ytopl']        = $coordinaten['hoogte'];


    return $coordinaten;



}

// ===================================================================================================================
// function to resize an image to a new width
// ===================================================================================================================
function teken_tekst($canvas, $fontsize, $coordinaten, $textcolor, $font, $text, $blur, $counter, $verschuiving_y) {

    $angle = 0;

    switch ($counter) {
        case 1:
            $color = imagecolorallocate($canvas, 132, 135, 28);
            break;
        case 2:
            $color = imagecolorallocate($canvas, 255, 105, 180);
            break;
        case 3:
            $color = imagecolorallocate($canvas, 255, 0, 0);
            break;
        default:
            $color = imagecolorallocate($canvas, 255, 0, 255);
            break;
    }

//    $ypos = ( ( -1 * ( $coordinaten['hoogte'] + $coordinaten['ybottomr'] ) ) + $verschuiving_y );
    $ypos = ( $coordinaten['hoogte'] + $verschuiving_y );

//    $eindy = ( $coordinaten['hoogte'] + $verschuiving_y );

    // Een rechthoek om de omtrek te zien
//    imagerectangle($canvas, $coordinaten['xtopl'], $verschuiving_y, $coordinaten['breedte'], $eindy, $color);

//    imagettftextblur($canvas, $fontsize, $angle, $coordinaten['xtopl'], $ypos, $color, $font, $text, $blur);
    imagettftextblur($canvas, $fontsize, $angle, $coordinaten['xtopl'], $ypos, $textcolor, $font, $text, $blur);
//    imagettftextblur($canvas, $fontsize, $angle, $coordinaten['xtopl'], ( $coordinaten['ytopl'] + $verschuiving_y ), $textcolor, $font, $text, $blur);

}


// ===================================================================================================================
// function to resize an image to a new width
// ===================================================================================================================
function resize($newWidth, $targetFile, $originalFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
            case 'image/jpeg':
                    $image_create_func = 'imagecreatefromjpeg';
                    $image_save_func = 'imagejpeg';
                    $new_image_ext = 'jpg';
                    break;

            case 'image/png':
                    $image_create_func = 'imagecreatefrompng';
                    $image_save_func = 'imagepng';
                    $new_image_ext = 'png';
                    break;

            case 'image/gif':
                    $image_create_func = 'imagecreatefromgif';
                    $image_save_func = 'imagegif';
                    $new_image_ext = 'gif';
                    break;

            default: 
                    throw Exception('Unknown image type.');
    }

    $img = $image_create_func($originalFile);
    list($width, $height) = getimagesize($originalFile);

    $newHeight = ($height / $width) * $newWidth;
    $tmp = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
        unlink($targetFile);
    }

    // save merged image
    imagepng($tmp, $targetFile);

    
}

// ===================================================================================================================
// function to strip out unwanted text characters
// ===================================================================================================================
function seoUrl($string) {
    //Lower case everything
    $string = strtolower($string);
    //Make alphanumeric (removes all other characters)
    $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
    //Clean up multiple dashes or whitespaces
    $string = preg_replace("/[\s-]+/", " ", $string);
    //Convert whitespaces and underscore to dash
    $string = preg_replace("/[\s_]/", "-", $string);
    return $string;
}

// ===================================================================================================================

?>


