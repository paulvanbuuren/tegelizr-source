<?php

// ===================================================================================================================
// 
//    Tegelizr.nl
//    author:                     Paul van Buuren
//    contact:                    paul@wbvb.nl / wbvb.nl / twitter.com/paulvanbuuren
//    version:                    3.2.0

// ===================================================================================================================



$nieuwescan = new scanfolder( '' );


$table = "<html><body><table>";
    

$errorcounter = 0;
$tegelcounter = 0;

class scanfolder {
    
    private $widget_id;
    private $data = array();
    
    // ==========================================================================    
    function __construct($wid) {
        global $outpath_thumbs;
        global $outpath;
        global $path;
        global $table;

        
        $list = '';
        $tegelcounter   = 0;
        $errorcounter   = 0;
        
        $index_html     = $path . TEGELIZR_ARCHIEFFOLDER . "/index.html";
        $index_txt      = $path . TEGELIZR_ARCHIEFFOLDER . "/index.txt";
        $index_table    = $path . TEGELIZR_ARCHIEFFOLDER . "/index-table.html";
    
        $images = glob($outpath_thumbs . "*.{jpg,png,gif}", GLOB_BRACE);        

            
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

        dodebug( true, '<h1>' . count($images) . ' plaatjes</h1>');
        dodebug( true, '<ol>');        

        $currentcounter = 0;

        $save_to_txt = array();
        

        foreach($images as $image) {
    
    
    
            $table .= "<tr><td>";
            
            // door alle thumbs heen lopen
            $stack          = explode('/', $image);
            $thumb_filename = array_pop($stack);
            $info           = explode('_', $thumb_filename );
            $time           = explode('-', $info[0] );
            
            $groot_image    = $outpath . $info[1] . '.png';
            $groot_txt      = $outpath . $info[1] . '.txt';
    
            dodebug( true, '<li>');        
    
            dodebug( true, '<strong>' . $info[1] . '.png</strong><br> thumb: ' . $thumb_filename);
    
    
            dodebug( true, '<ul>');
            
            /*
            criteria:
            - het grote plaatje bestaat
            - het txt-bestand bestaat
            - de tekst moet bestaan
            - de vorige moet gezet zijn, indien mogelijk
            - de volgende moet gezet zijn, indien mogelijk
            
            */
            
            // als de grote plaat ook bestaat
            if ( ! file_exists( $groot_image ) ) {
    
                dodebug( false, '<li>O fuck, het grote plaatje bestaat niet: ' . $info[1] . '.png</li>');
                
    
                dodebug( false, '<li style="border: 1px solid red; background: yellow; padding: 1em;">Groot image bestaat niet: ' . $groot_image . "</li>");
                
                
                $table .= '<img src="/' . TEGELIZR_THUMBS . '/' . $image . '.png" height="' . ( TEGELIZR_THUMB_WIDTH / 2 ). '" width="' . ( TEGELIZR_THUMB_WIDTH / 2 ) . '" alt="" /></td>';
                
                $images         = $outpath_thumbs . $image . ".png";
                
                $table .= '<td colspan="4"><span style="color: red;">Groot image bestaat niet: ' . $images . '</span></td>';
                
                
                
                
                unlink( $image );
                
//                return false;
            }
            else {        
    
                if ( ! file_exists( $groot_txt ) ) {
        
                    dodebug( false, '<li>Ja jezus, het tekstbestand bestaat niet: ' . $info[1] . '.txt</li>');
                    
//                    return false;
                }
                
                $tegelcounter++;
            
                
                $boom[$thumb_filename]                      = getviews($groot_txt, false);
                $boom[$thumb_filename]['file_thumb']        = $thumb_filename . '.png';
                $boom[$thumb_filename]['file_name']         = $info[1];
                
                $thekey = $info[1];
                
                $save_to_txt[$thekey]['file_name']          = $info[1];
                $save_to_txt[$thekey]['file_thumb']         = $thumb_filename . '.png';

                if (isset( $boom[$thumb_filename]['txt_tegeltekst'] ) ) {
                    $save_to_txt[$thekey]['txt_tegeltekst']         = $boom[$thumb_filename]['txt_tegeltekst'];
                }
                
                if (isset( $boom[$thumb_filename]['file'] ) ) {
                    $save_to_txt[$thekey]['file']         = $boom[$thumb_filename]['file'];
                }
                
                if (isset( $boom[$thumb_filename]['file_date'] ) ) {
                    $save_to_txt[$thekey]['file_date']         = $boom[$thumb_filename]['file_date'];
                }
                
                if (isset( $boom[$thumb_filename]['file_thumb'] ) ) {
                    $save_to_txt[$thekey]['file_thumb']         = $boom[$thumb_filename]['file_thumb'];
                }

                if (isset( $boom[$thumb_filename]['views'] ) ) {
                    $save_to_txt[$thekey]['views']         = $boom[$thumb_filename]['views'];
                }

                if (isset( $boom[$thumb_filename]['tglzr_TGLZR_NR_VOTES'] ) ) {

                    if ( $boom[$thumb_filename]['tglzr_TGLZR_NR_VOTES'] > 0 ) {
                        $save_to_txt[$thekey]['tglzr_TGLZR_NR_VOTES']         = $boom[$thumb_filename]['tglzr_TGLZR_NR_VOTES'];
        
                        if (isset( $boom[$thumb_filename]['tglzr_TGLZR_TOTAL_POINTS'] ) ) {
                            $save_to_txt[$thekey]['tglzr_TGLZR_TOTAL_POINTS']         = $boom[$thumb_filename]['tglzr_TGLZR_TOTAL_POINTS'];
                        }
        
                        if (isset( $boom[$thumb_filename]['tglzr_dec_avg'] ) ) {
                            $save_to_txt[$thekey]['tglzr_dec_avg']         = $boom[$thumb_filename]['tglzr_dec_avg'];
                        }
        
                        if (isset( $boom[$thumb_filename]['tglzr_rounded_avg'] ) ) {
                            $save_to_txt[$thekey]['tglzr_rounded_avg']         = $boom[$thumb_filename]['tglzr_rounded_avg'];
                        }
        
                        if (isset( $boom[$thumb_filename]['tglzr_TGLZR_TOTAL_POINTS'] ) ) {
                            $save_to_txt[$thekey]['tglzr_TGLZR_TOTAL_POINTS']         = $boom[$thumb_filename]['tglzr_TGLZR_TOTAL_POINTS'];
                        }
                    }
                }


                $volgende = '<span style="color: red;">GEEN ' . TEGELIZR_VOLGENDE . '</span>';
                $vorige = '<span style="color: red;">GEEN ' . TEGELIZR_VORIGE . '</span>';
                
                $vorigeisgoed       = false;
                $volgendeisgoed     = false;
                
                if ( isset($boom[$thumb_filename][TEGELIZR_VORIGE]) ) {
                    $vorige = $boom[$thumb_filename][TEGELIZR_VORIGE];
                    $vorigeisgoed = true;
                }
                else {
                    if ( isset( $images[ ( $currentcounter - 1 ) ] ) ) {
                        $vorige = 'reset: ' . $images[ ( $currentcounter - 1 ) ];
                    }
                    else {
                        $vorigeisgoed = true;
                    }
                }
                if ( isset($boom[$thumb_filename][TEGELIZR_VOLGENDE]) ) {
                    $volgende = $boom[$thumb_filename][TEGELIZR_VOLGENDE];
                     $volgendeisgoed = true;
                }
                else {
                    if ( isset( $images[ ( $currentcounter + 1 ) ] ) ) {
                        $volgende = 'reset: ' . $images[ ( $currentcounter + 1 ) ];
                    }
                    else {
                        $volgendeisgoed = true;
                    }
                }
            
                dodebug( false, '<li>Nieuwer: ' . $vorige . "</li>");
                dodebug( false, '<li>Ouder: ' . $volgende . "</li>");        
    
                if ( $volgendeisgoed  &&  $volgendeisgoed ) {
                    dodebug( false, '<li><strong style="background: green; color: white; padding: 1em;">Alles goed</strong></li>');
                }
            
                if ( $time ) {
                    $date = strtotime($time[1] . '/'. $time[2]. '/' . $time[0] .' ' .$time[3].':'.$time[4].':'.$time[5]);
                    $boom[$thumb_filename]['file_date']             = $date;
                    $boom[$thumb_filename]['file_date_readable']    = strftime('%e %B %Y',$date);
                } 
            
            
            
                if (isset( $boom[$thumb_filename]['txt_tegeltekst'] ) ) {
            
                    dodebug( false, '<li>Tekst is bekend: ' . $boom[$thumb_filename]['txt_tegeltekst'] . "</li>");
            
                    $alt = filtertext($boom[$thumb_filename]['txt_tegeltekst'], true);
                }
                else {
            
            
                    $errorcounter++;
                    $boom[$thumb_filename]['txt_tegeltekst']         = preg_replace("/[\-]/", " ", filtertext($info[1], true));
            
                    $correct_txt    = $outpath . $info[1] . '_corrected.txt';
            
                    $fh             = fopen($groot_txt, 'w') or die("can't open file: " . $groot_txt);
                    $stringData     = json_encode($boom[$thumb_filename]);
            
                    fwrite($fh, $stringData);
                    fclose($fh);
            
                    $boom[$thumb_filename]['txt_tegeltekst']         = "ERROR " . $errorcounter . " - " . $boom[$thumb_filename]['txt_tegeltekst'];
                    $alt = $info[1];
            
                    dodebug( false, '<li style="border: 1px solid red; background: yellow; padding: 1em;">Geen tekst is bekend: ' . $boom[$thumb_filename]['txt_tegeltekst'] . "</li>");
                    
                    
                }
            
                if ( is_numeric($alt) ) {
                    // geinponems die hun tegeltje met een getal beginnen
                    $boom[$thumb_filename]['sort_name']         = 'zzzzz' . strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $alt));
                }
                else {
                    $boom[$thumb_filename]['sort_name']         = strtolower(preg_replace("/[^A-Za-z0-9]/", '-', $alt));
                }
            
                dodebug( false, '<li>Sorteernaam: ' . $boom[$thumb_filename]['sort_name'] . "</li>");

                if (isset( $boom[$thumb_filename]['sort_name'] ) ) {
                    $save_to_txt[$thekey]['sort_name']         = $boom[$thumb_filename]['sort_name'];
                }
            
            
//                $table .= '<img src="/' . TEGELIZR_THUMBS . '/' . $boom[$thumb_filename]['file_thumb'] . '" height="' . ( TEGELIZR_THUMB_WIDTH / 2 ). '" width="' . ( TEGELIZR_THUMB_WIDTH / 2 ) . '" alt="' . $alt . '" /></td>';
                                
//                $table .= "<td>" . $alt . "</td>";
                
    
                
                
//                $table .= "<td>" . TEGELIZR_VORIGE . ":" .  $vorige . "</td>";
//                $table .= "<td>" . TEGELIZR_VOLGENDE . ":" .  $volgende . "</td>";
                
                
    //            $list .= getSearchResultItem($boom[$thumb_filename], false);
            }
    
            dodebug( true, '</ul></li>');
            
            $table .= "</td></tr>";
            
            
         
         
                $currentcounter++;
        }    

        if ( file_exists( $index_txt ) ) {
            $fh             = fopen($index_txt, 'w') or die("can't open file: " . $index_txt);
            $stringData     = json_encode($save_to_txt);
    
            dovardump( $save_to_txt );
    
            fwrite($fh, $stringData);
            fclose($fh);
        }


        dodebug( true, '</ol>');

        $table .= "</table></body></html>";

        $desturl        = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ALLES . '/';

//        $stringData     = spitoutheader() . '<meta property="og:title" content="' . $alt . '" /><meta property="og:description" content="' . TEGELIZR_SUMMARY . '" /><meta property="og:url" content="' . $desturl . '" /><meta property="article:tag" content="' . TEGELIZR_ALLES . '" /><meta property="og:image" content="' . TEGELIZR_DEFAULT_IMAGE . '" /><title>' . TEGELIZR_TITLE . ' - alle ' . $tegelcounter . ' tegeltjes</title>' .  htmlheader() . '<article class="resultaat"><h1 id="top"><a href="/" title="Maak zelf ook een tegeltje">' . returnlogo() . '<span>Alle ' . $tegelcounter . ' tegeltjes</span></a></h1><p>Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>' .  wbvb_d2e_socialbuttons($desturl, $alt, TEGELIZR_SUMMARY) . '<section id="alle_tegeltjes"><h2>Wat anderen maakten:</h2><ul class="all">' . $list . '</ul></section><p id="home"> <a href="/">' .  TEGELIZR_BACK . '</a> </p></article>' . includejs() . ' ' . spitoutfooter();


//        if ( file_exists( $index_html ) ) {
    
//            $alt = count($images) . ' tegeltjes';
//            $fh             = fopen($index_html, 'w') or die("can't open file: " . $index_html );
//            fwrite($fh, $stringData);
//            fclose($fh);
//        }
//        else {

//            $fh             = fopen($index_html, 'w') or die("can't open file: " . $index_html );
//            fwrite($fh, $stringData);
//            fclose($fh);
            
//            die('file does not exist : ' . $index_html);
//        }


    
        $fh             = fopen($index_table, 'w') or die("can't open file: " . $index_table);

        fwrite($fh, $table);
        fclose($fh);

        
        
//        echo json_encode($this->data);

    }

    // ==========================================================================    
    
    # ---
    # end class
}






