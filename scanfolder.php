<?php

// ===================================================================================================================
// 
//    Tegelizr.nl
//    author:                     Paul van Buuren
//    contact:                    paul@wbvb.nl / wbvb.nl / twitter.com/paulvanbuuren
//    version:                    3.2.0

// ===================================================================================================================

include("common.inc.php"); 




//$rating = new scanfolder( $_GET['widget_id'] );
$rating = new scanfolder( '' );


class scanfolder {
    
    private $widget_id;
    private $data = array();
    
    // ==========================================================================    
    function __construct($wid) {
        global $outpath_thumbs;
        global $outpath;
        global $path;
        $list = '';
        $tegelcounter   = 0;
        $errorcounter   = 0;
        
        $index_html     = $path . TEGELIZR_ALLES . "/index.html";
        $index_txt      = $path . TEGELIZR_ALLES . "/index.txt";
    
        $images         = glob($outpath_thumbs . "*.png");
        $replace        = $outpath_thumbs;
        $with           = '';
        $pattern        = '|' . $replace . '|i';
        $images         = preg_replace($pattern, $with, $images);

        $with           = '';
        $pattern        = '|(\.png)|i';
        $images         = preg_replace($pattern, $with, $images);
        
        rsort($images);

        $this->data['nrdocs']   = count($images);
        $this->data['docs']     = $images;

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

                $tegelcounter++;

                
                $boom[$thumb_filename]                      = getviews($groot_txt, false);
                $boom[$thumb_filename]['file_thumb']        = $thumb_filename . '.png';
                $boom[$thumb_filename]['file_name']         = $info[1];

                if (isset( $boom[$thumb_filename]['txt_tegeltekst'] ) ) {
                    $alt = filtertext($boom[$thumb_filename]['txt_tegeltekst']);
                }
                else {
                    $errorcounter++;
                    $boom[$thumb_filename]['txt_tegeltekst']         = preg_replace("/[\-]/", " ", filtertext($info[1]));
        
                    $correct_txt    = $outpath . $info[1] . '_corrected.txt';

                    $fh             = fopen($groot_txt, 'w') or die("can't open file: " . $groot_txt);
                    $stringData     = json_encode($boom[$thumb_filename]);
            
                    fwrite($fh, $stringData);
                    fclose($fh);

                    $boom[$thumb_filename]['txt_tegeltekst']         = "ERROR " . $errorcounter . " - " . $boom[$thumb_filename]['txt_tegeltekst'];
                    $alt = $info[1];

                    
                }
                
//                $list .= '<li><a href="/'  . TEGELIZR_SELECTOR . '/' . $info[1] . '" title="' . $alt . ' - ' . $boom[$thumb_filename][TEGELIZR_VIEWS] . ' keer bekeken"><img src="/' . TEGELIZR_THUMBS . '/' . $thumb_filename . '" height="' . TEGELIZR_THUMB_WIDTH . '" width="' . TEGELIZR_THUMB_WIDTH . '" alt="' . $alt . '" /></a></li>'; 

//                $list .= '<li><a href="/'  . TEGELIZR_SELECTOR . '/' . $info[1] . '" title="' . $alt . ' - ' . $boom[$thumb_filename][TEGELIZR_VIEWS] . ' keer bekeken">' . $alt . '</a></li>'; 

                $list .= getSearchResultItem($boom[$thumb_filename], false);
                            

            }
        }    



        if ( file_exists( $index_html ) ) {
    
            $alt = count($images) . ' tegeltjes';
            $desturl        = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ALLES . '/';
            $stringData     = spitoutheader() . 
            '<meta property="og:title" content="' . $alt . '" />
            <meta property="og:description" content="' . TEGELIZR_SUMMARY . '" />
            <meta property="og:url" content="' . $desturl . '" />
            <meta property="article:tag" content="' . TEGELIZR_ALLES . '" />
            <meta property="og:image" content="' . TEGELIZR_DEFAULT_IMAGE . '" />
            <title>' . TEGELIZR_TITLE . ' - alle ' . $tegelcounter . ' tegeltjes</title>' .  htmlheader() . '
            <article class="resultaat">
            <h1 id="top"><a href="/" title="Maak zelf ook een tegeltje">' . returnlogo() . 'Alle ' . $tegelcounter . ' tegeltjes</a></h1>
            <p>Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>' .  wbvb_d2e_socialbuttons($desturl, $alt, TEGELIZR_SUMMARY) . '
            <section id="alle_tegeltjes"><h2>Wat anderen maakten:</h2><ul class="all">' . $list . '</ul></section>
            <p id="home"> <a href="/">' .  TEGELIZR_BACK . '</a> </p>
            </article>' . spitoutfooter();
            $fh             = fopen($index_html, 'w') or die("can't open file: " . $index_html );
            fwrite($fh, $stringData);
            fclose($fh);
        }
        else {
            die('file does not exist : ' . $index_html);
        }

        if ( file_exists( $index_txt ) ) {
            $fh             = fopen($index_txt, 'w') or die("can't open file: " . $index_txt);
            $stringData     = json_encode($boom);
    
            fwrite($fh, $stringData);
            fclose($fh);
        }
        
        
        echo json_encode($this->data);

    }


    // ==========================================================================    
    
    # ---
    # end class
}




