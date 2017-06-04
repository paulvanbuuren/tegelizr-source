<?php
// ===================================================================================================================

    // ==========================================================================    
    public function get_redirect() {

        global $sourcefiles_tegels;

        if ( isset( $_POST['redirect'] ) ||  isset($_GET['redirect'] ) ) {
            $this->desturl            = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/';

            if ( file_exists( $this->filetxt ) &&  file_exists( $sourcefiles_tegels . $this->widget_id . '.png' ) ) {
                 $this->desturl .=  TEGELIZR_SELECTOR . '/' . $this->widget_id;
            }
            header('Location: ' . $this->desturl);    
        }
        else {
            $this->get_documentscan();
        }

    }

    // ==========================================================================    
    public function get_documentscan() {

        echo json_encode($this->data);

    }
    // ==========================================================================    
    public function vote() {

        global $userip;
        global $sourcefiles_tegels;
    
        # Get the value of the vote
        if ( isset( $_POST[TEGELIZR_RATING_VOTE] ) ||  isset($_GET[TEGELIZR_RATING_VOTE] ) ) {

            $score = isset( $_POST[TEGELIZR_RATING_VOTE] ) ? $_POST[TEGELIZR_RATING_VOTE] : $_GET[TEGELIZR_RATING_VOTE]; 

            if ( $score > 0 && $score <= TEGELIZR_AANTAL_STERREN) {

                if (!isset( $this->data[$userip] ) ) {

                    $this->data[$userip . '_comment']   = 'Dank je wel';
                    $this->data[$userip]                = $score;
                    $this->data[TGLZR_NR_VOTES]         += 1;
                    
                    $this->data[TGLZR_TOTAL_POINTS] += $score;
        
                    $this->data[dec_avg]        = ( $this->data[TGLZR_TOTAL_POINTS] / $this->data[TGLZR_NR_VOTES] );
                    $this->data[rounded_avg]    = round( $this->data[dec_avg] );
                
                }
                else {
                    $this->data[$userip . '_comment'] = 'Dank, maar je had al gestemd: ' . ( $this->data[$userip] > 1 ) ? $this->data[$userip] . ' ' . TEGELIZR_RATING_UNITY : $this->data[$userip] . ' ' . TEGELIZR_RATING_UNITY_S;
                    
                }
            }

            $newJsonString = json_encode($this->data);
            file_put_contents($this->filetxt, $newJsonString);
            
        }


        if ( isset( $_POST['redirect'] ) ||  isset($_GET['redirect'] ) ) {
            $this->desturl            = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/';

            if ( file_exists( $this->filetxt ) &&  file_exists( $sourcefiles_tegels . $this->widget_id . '.png' ) ) {
                 $this->desturl .=  TEGELIZR_SELECTOR . '/' . $this->widget_id;
            }
            header('Location: ' . $this->desturl);    
        }
        else {
            $this->get_documentscan();
        }

    }


function maakoverzichtspagina() {

    global $sourcefiles_thumbs;
    global $sourcefiles_tegels;
    global $path;
    $list = '';
    $tegelcounter = 0;
    
    $index_html     = $path . TEGELIZR_ALLES . "/index.html";
    $index_txt      = $path . TEGELIZR_ALLES . "/index.txt";

    if (is_dir($sourcefiles_thumbs)) {

        $images = glob($sourcefiles_thumbs . "*.png");
        
        rsort($images);
        
        foreach($images as $image) {
            
            // door alle thumbs heen lopen
            $stack          = explode('/', $image);
            $thumb_filename = array_pop($stack);
            $info           = explode('_', $thumb_filename );
            $time           = explode('-', $info[0] );

            $groot_image    = $sourcefiles_tegels . $info[1] . '.png';
            $groot_txt      = $sourcefiles_tegels . $info[1] . '.txt';
            
            // als de grote plaat ook bestaat
            if ( file_exists( $groot_image ) ) {

                $tegelcounter++;

                $boom[$thumb_filename]                      = getviews($groot_txt, false);
                $boom[$thumb_filename]['file_thumb']        = $thumb_filename;
                $boom[$thumb_filename]['file_name']         = $info[1];
                
                $alt = isset( $boom[$thumb_filename]['txt_tegeltekst'] ) ? filtertext($boom[$thumb_filename]['txt_tegeltekst']) : '';

                $list .= '<li><a href="/'  . TEGELIZR_SELECTOR . '/' . $info[1] . '" title="' . $alt . ' - ' . $boom[$thumb_filename][TEGELIZR_VIEWS] . ' keer bekeken"><img src="/' . TEGELIZR_THUMBS . '/' . $thumb_filename . '" height="' . TEGELIZR_THUMB_WIDTH . '" width="' . TEGELIZR_THUMB_WIDTH . '" alt="' . $alt . '" /></a></li>'; 

            }
        }    

        if ( file_exists( $index_html ) ) {
    
            $desturl        = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ALLES . '/';
            $stringData     = spitoutheader() . '<meta property="og:title" content="' . filtertext($boom[$thumb_filename]['txt_tegeltekst']) . '" /><meta property="og:description" content="' . TEGELIZR_SUMMARY . '" /><meta property="og:url" content="' . $desturl . '" /><meta property="article:tag" content="' . TEGELIZR_ALLES . '" /><meta property="og:image" content="' . TEGELIZR_DEFAULT_IMAGE . '" /><title>' . TEGELIZR_TITLE . ' - alle ' . $tegelcounter . ' tegeltjes</title>' .  htmlheader() . '<article class="resultaat"><h1><a href="/" title="Maak zelf ook een tegeltje">' . returnlogo() . 'Alle ' . $tegelcounter . ' tegeltjes</a></h1><p>Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>' .  wbvb_d2e_socialbuttons($desturl, filtertext($boom[$thumb_filename]['txt_tegeltekst']), TEGELIZR_SUMMARY) . '<section id="andere"><h2>Wat anderen maakten:</h2><ul class="thumbs">' . $list . '</ul></section><p id="home"> <a href="/">' .  TEGELIZR_BACK . '</a> </p></article>' . spitoutfooter();
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


