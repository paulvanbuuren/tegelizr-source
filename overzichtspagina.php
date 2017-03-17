<?php

// ===================================================================================================================
// 
//    Tegelizr.nl
//    author:                     Paul van Buuren
//    contact:                    paul@wbvb.nl / wbvb.nl / twitter.com/paulvanbuuren
//    version:                    3.2.0

// ===================================================================================================================

include("common.inc.php"); 




//$rating = new documentscan( $_GET['widget_id'] );
$rating = new documentscan( '' );


class documentscan {
    
    private $widget_id;
    private $data = array();
    
    // ==========================================================================    
    function __construct($wid) {
        global $outpath_thumbs;
        global $outpath;
        global $path;
        $list = '';
        $tegelcounter = 0;
        
        $index_html     = $path . TEGELIZR_ALLES . "/index.html";
        $index_txt      = $path . TEGELIZR_ALLES . "/index.txt";

        $this->test   = isset( $_POST['test'] ) ? $_POST['test'] : isset( $_GET['test'] ) ? $_GET['test'] : '' ; 
        $this->vorige   = isset( $_POST['vorige'] ) ? $_POST['vorige'] : isset( $_GET['vorige'] ) ? $_GET['vorige'] : '' ; 
        $this->huidige   = isset( $_POST['huidige'] ) ? $_POST['huidige'] : isset( $_GET['huidige'] ) ? $_GET['huidige'] : '' ; 
        $this->volgende   = isset( $_POST['volgende'] ) ? $_POST['volgende'] : isset( $_GET['volgende'] ) ? $_GET['volgende'] : '' ; 
    

        if ( $this->test ) {

            $this->data['test']     = $this->test;
            die(json_encode($this->data));
        } 
        elseif (is_dir($outpath_thumbs)) {

            if ( $this->huidige && (! ( $this->vorige && $this->volgende ) ) ) {

                $this->data['kees']     = 'is klaar 1';
                die(json_encode($this->data));
            } 
            elseif ( $this->huidige && ( $this->vorige || $this->volgende ) ) {

                // door alle thumbs heen lopen

                $this->data['kees']     = 'is klaar 2';
                die(json_encode($this->data));

                $info           = explode('_', $this->huidige );
                $time           = explode('-', $info[0] );
    
                $this->pngbestand   = $outpath . $info[1] . '.png';
                $this->txtbestand   = $outpath . $info[1] . '.txt';

                $this->thumb        = $outpath_thumbs . $this->huidige . '.png';

                if ( file_exists( $this->txtbestand ) &&  file_exists( $this->pngbestand ) ) {
                    
                    $all = file_get_contents( $this->txtbestand );
                    
                    if($all) {
                        $this->data = json_decode($all, true);


                        $info           = explode('_', $this->vorige );
                        $time           = explode('-', $info[0] );
            
                        if ( isset( $info[1] )) {
                            $this->VORIGEPNG   = $outpath . $info[1] . '.png';
                            $this->VORIGETXT   = $outpath . $info[1] . '.txt';
                            
                            if ( file_exists( $this->VORIGEPNG ) &&  file_exists( $this->VORIGETXT ) ) {
                                $this->data[TEGELIZR_VORIGE]  = $info[1];
                            }
                        }


                        $info           = explode('_', $this->volgende );
                        $time           = explode('-', $info[0] );
            
                        if ( isset( $info[1] )) {
                            
                            $this->VOLGENDEPNG   = $outpath . $info[1] . '.png';
                            $this->VOLGENDETXT   = $outpath . $info[1] . '.txt';
                            
                            if ( file_exists( $this->VOLGENDEPNG ) &&  file_exists( $this->VOLGENDETXT ) ) {
                                $this->data[TEGELIZR_VOLGENDE]  = $info[1];
                            }
                        }
                    }

                    $newJsonString = json_encode($this->data);
                    file_put_contents($this->txtbestand, $newJsonString);

                    echo json_encode($this->data);
                    
                }

            }
            else {
                $images     = glob($outpath_thumbs . "*.png");
                $replace    = $outpath_thumbs;
                $with       = '';
                $pattern    = '|' . $replace . '|i';
                $images     = preg_replace($pattern, $with, $images);

                $with       = '';
                $pattern    = '|(\.png)|i';
                $images     = preg_replace($pattern, $with, $images);
                
                rsort($images);
    
                $this->data['nrdocs']   = count($images);
                $this->data['docs']     = $images;
                
//                echo json_encode('kees 2');
                echo json_encode($this->data);
            }
    
        }
        else {
            dodebug('niet bereikbaar: outpath_thumbs ' . $outpath_thumbs);
        }
    }


    // ==========================================================================    
    
    # ---
    # end class
}




