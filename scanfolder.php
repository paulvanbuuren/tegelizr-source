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
        $index_table    = $path . TEGELIZR_ALLES . "/index-table.html";
    
$table = "<html><body><table>";
    
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

$table .= "<tr><td>";
            
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


                if ( $time ) {
                    $date = strtotime($time[1] . '/'. $time[2]. '/' . $time[0] .' ' .$time[3].':'.$time[4].':'.$time[5]);
                    $boom[$thumb_filename]['file_date']             = $date;
                    $boom[$thumb_filename]['file_date_readable']    = strftime('%e %B %Y',$date);
                } 


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

                if ( is_numeric($alt) ) {
                    $boom[$thumb_filename]['sort_name']         = 'zzzzz' . strtolower(preg_replace("/[^A-Za-z0-9]/", '', $alt));
                }
                else {
                    $boom[$thumb_filename]['sort_name']         = strtolower(preg_replace("/[^A-Za-z0-9]/", '', $alt));
                }

$table .= '<img src="/' . TEGELIZR_THUMBS . '/' . $boom[$thumb_filename]['file_thumb'] . '" height="' . ( TEGELIZR_THUMB_WIDTH / 2 ). '" width="' . ( TEGELIZR_THUMB_WIDTH / 2 ) . '" alt="' . $alt . '" /></td>';
                
$table .= "<td>" . $alt . "</td>";

$volgende = '<span style="color: red;">GEEN ' . TEGELIZR_VOLGENDE . '</span>';
$vorige = '<span style="color: red;">GEEN ' . TEGELIZR_VORIGE . '</span>';

if ( isset($boom[$thumb_filename][TEGELIZR_VORIGE]) ) {
    $vorige = $boom[$thumb_filename][TEGELIZR_VORIGE];
}
if ( isset($boom[$thumb_filename][TEGELIZR_VOLGENDE]) ) {
    $volgende = $boom[$thumb_filename][TEGELIZR_VOLGENDE];
}


$table .= "<td>" . TEGELIZR_VORIGE . ":" .  $vorige . "</td>";
$table .= "<td>" . TEGELIZR_VOLGENDE . ":" .  $volgende . "</td>";



//                $list .= '<li><a href="/'  . TEGELIZR_SELECTOR . '/' . $info[1] . '" title="' . $alt . ' - ' . $boom[$thumb_filename][TEGELIZR_VIEWS] . ' keer bekeken"><img src="/' . TEGELIZR_THUMBS . '/' . $thumb_filename . '" height="' . TEGELIZR_THUMB_WIDTH . '" width="' . TEGELIZR_THUMB_WIDTH . '" alt="' . $alt . '" /></a></li>'; 

//                $list .= '<li><a href="/'  . TEGELIZR_SELECTOR . '/' . $info[1] . '" title="' . $alt . ' - ' . $boom[$thumb_filename][TEGELIZR_VIEWS] . ' keer bekeken">' . $alt . '</a></li>'; 

                $list .= getSearchResultItem($boom[$thumb_filename], false);

            }
            else {
$table .= '<img src="/' . TEGELIZR_THUMBS . '/' . $image . '.png" height="' . ( TEGELIZR_THUMB_WIDTH / 2 ). '" width="' . ( TEGELIZR_THUMB_WIDTH / 2 ) . '" alt="" /></td>';

        $images         = $outpath_thumbs . $image . ".png";

$table .= '<td colspan="4"><span style="color: red;">Groot image bestaat niet: ' . $images . '</span></td>';




unlink($images);

                
            }

$table .= "</td></tr>";

        }    

$table .= "</table></body></html>";

        $desturl        = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ALLES . '/';

        $stringData     = spitoutheader() . 
            '<meta property="og:title" content="' . $alt . '" />
            <meta property="og:description" content="' . TEGELIZR_SUMMARY . '" />
            <meta property="og:url" content="' . $desturl . '" />
            <meta property="article:tag" content="' . TEGELIZR_ALLES . '" />
            <meta property="og:image" content="' . TEGELIZR_DEFAULT_IMAGE . '" />
            <title>' . TEGELIZR_TITLE . ' - alle ' . $tegelcounter . ' tegeltjes</title>' .  htmlheader() . '
            <article class="resultaat">
            <h1 id="top"><a href="/" title="Maak zelf ook een tegeltje">' . returnlogo() . '<span>Alle ' . $tegelcounter . ' tegeltjes</span></a></h1>
            <p>Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>' .  wbvb_d2e_socialbuttons($desturl, $alt, TEGELIZR_SUMMARY) . '
            <section id="alle_tegeltjes"><h2>Wat anderen maakten:</h2><ul class="all">' . $list . '</ul></section>
            <p id="home"> <a href="/">' .  TEGELIZR_BACK . '</a> </p></article>' . includejs() . ' ' . spitoutfooter();


//        if ( file_exists( $index_html ) ) {
    
//            $alt = count($images) . ' tegeltjes';
//            $fh             = fopen($index_html, 'w') or die("can't open file: " . $index_html );
//            fwrite($fh, $stringData);
//            fclose($fh);
//        }
//        else {

            $fh             = fopen($index_html, 'w') or die("can't open file: " . $index_html );
            fwrite($fh, $stringData);
            fclose($fh);
            
//            die('file does not exist : ' . $index_html);
//        }

//        if ( file_exists( $index_txt ) ) {
            $fh             = fopen($index_txt, 'w') or die("can't open file: " . $index_txt);
            $stringData     = json_encode($boom);
    
            fwrite($fh, $stringData);
            fclose($fh);
//        }

    
        $fh             = fopen($index_table, 'w') or die("can't open file: " . $index_table);

        fwrite($fh, $table);
        fclose($fh);

        
        
        echo json_encode($this->data);

    }


    // ==========================================================================    
    
    # ---
    # end class
}




