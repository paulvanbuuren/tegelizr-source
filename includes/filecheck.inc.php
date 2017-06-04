<?php

  
function checkthreefiles( $type = '', $keyfilename = '',  $thumbfilename = '' ) {

  global $sourcefiles_thumbs;
  global $sourcefiles_tegels;
  global $deletedfiles_thumbs;
  global $deletedfiles_tegels;
  global $path;

  $groot_image    = $sourcefiles_tegels . $keyfilename . '.png';
  $groot_txt      = $sourcefiles_tegels . $keyfilename . '.txt';
  $thumb_image    = $sourcefiles_thumbs . $thumbfilename . '.png';

  if ( $thumbfilename ) {

    
  }
  else {


    $views            = getviews($groot_txt,true);
    $thumbfilename    = isset($views['file_thumb']) ? $views['file_thumb'] : '';


    $replace        = $sourcefiles_thumbs;
    $with           = '';
    $pattern        = '|' . $replace . '|i';
    $thumbfilename  = preg_replace($pattern, $with, $thumbfilename);

    $replace        = '/home/paulvanb/webapps/tegelizr/thumbs/';
    $with           = '';
    $pattern        = '|' . $replace . '|i';
    $thumbfilename  = preg_replace($pattern, $with, $thumbfilename);

    $with           = '';
    $pattern        = '|(\.png)|i';
    $thumbfilename  = preg_replace($pattern, $with, $thumbfilename);


    $thumb_image      = $sourcefiles_thumbs . $thumbfilename . '.png';

        
  }
  

  // als de grote plaat bestaat en het txt-bestand, niks doen
  if ( file_exists( $groot_image ) && file_exists( $groot_txt )  && file_exists( $thumb_image ) ) {
    return true;
  }
  else {

    dodebug('<p style="background: red; color: white;">Woeps (' . $type . ')</p><ul>');
    if (! file_exists( $groot_image ) ) {
      dodebug('<li>De grote plaat bestaat niet (' . $groot_image . ')</li>');
    }
    if (! file_exists( $groot_txt ) ) {
      dodebug('<li>Het txt-bestand bestaat niet (' . $groot_txt . ')</li>');
    }
    if (! file_exists( $thumb_image ) ) {
      dodebug('<li>De thumbnail bestaat niet (' . $thumb_image . ')</li>');
    }
    dodebug('</ul>');
      

    if ( file_exists( $groot_image ) ) {
      $newfile  = $deletedfiles_tegels . $keyfilename . '.png';
      rename( $groot_image, $newfile );
    }
    
    if ( file_exists( $groot_txt ) ) {
      $newfile  = $deletedfiles_tegels . $keyfilename . '.txt';
      rename( $groot_txt, $newfile );
    }

    if ( file_exists( $thumb_image ) ) {
      $newfile  = $deletedfiles_thumbs . $thumbfilename . '.png';
      rename( $thumb_image, $newfile );
    }

    
    return false;
  }

  return true;

}

  
function cleanfolder( $redirect = '' ) {
  
  global $sourcefiles_thumbs;
  global $sourcefiles_tegels;
  global $deletedfiles_thumbs;
  global $deletedfiles_tegels;
  global $path;


  $images         = '';
  $list           = '';
  $tegelcounter   = 0;
  $errorcounter   = 0;


  if ( is_dir($sourcefiles_thumbs) && is_dir($sourcefiles_tegels) && is_dir($deletedfiles_thumbs)  && is_dir($deletedfiles_tegels) ) {
    
    // eerst de thumbs opruimen
    $thumbs         = glob($sourcefiles_thumbs . "*.png");
    $replace        = $sourcefiles_thumbs;
    $with           = '';
    $pattern        = '|' . $replace . '|i';
    $thumbs         = preg_replace($pattern, $with, $thumbs);
    
    $with           = '';
    $pattern        = '|(\.png)|i';
    $thumbs         = preg_replace($pattern, $with, $thumbs);
    
    rsort($thumbs);
    
    foreach( $thumbs as $thethumb) {

      // door alle thumbs heen lopen
      $stack          = explode('/', $thethumb);
      $thumb_filename = array_pop($stack);
      $info           = explode('_', $thumb_filename );

      checkthreefiles( 'thumbs', $info[1], $thethumb );
      
    }

    // dan de tegeltjes opruimen
    $tegeltjes      = glob($sourcefiles_tegels . "*.png");
    $replace        = $sourcefiles_tegels;
    $with           = '';
    $pattern        = '|' . $replace . '|i';
    $tegeltjes      = preg_replace($pattern, $with, $tegeltjes);
    
    $with           = '';
    $pattern        = '|(\.png)|i';
    $tegeltjes      = preg_replace($pattern, $with, $tegeltjes);
    
    rsort($tegeltjes);

    
    foreach($tegeltjes as $tegeltje) {

      // door alle thumbs heen lopen
      $stack          = explode('/', $tegeltje);
      $thumb_filename = array_pop($stack);
      $info           = explode('_', $thumb_filename );

      checkthreefiles( 'echte tegeltjes', $tegeltje );

    }

  }


  if ( $redirect ) {
die('Redirect (' . $redirect . ')' );    
    // doorsturen naar pagina met het bestaande image
    header('Location: ' . $redirect);    
  }  

  
}
  