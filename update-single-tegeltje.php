<?php

// ===================================================================================================================
// 
//    Tegelizr.nl
//    author:                     Paul van Buuren
//    contact:                    paul@wbvb.nl / wbvb.nl / twitter.com/paulvanbuuren
//    version:                    3.2.0

// ===================================================================================================================

include("common.inc.php"); 

$rating = new documentscan( '' );
$status = '';


class documentscan {
    
    private $widget_id;
    private $info = array();
    
    // ==========================================================================    
    function __construct($wid) {
        global $outpath_thumbs;
        global $outpath;
        global $path;
        global $status;
        $list = '';
        $tegelcounter = 0;
        
        $huidige    =  isset( $_POST['huidige'] )   ? $_POST['huidige']     : ( isset( $_GET['huidige'] )   ? $_GET['huidige']      : 'huidige' );
        $vorige     =  isset( $_POST['vorige'] )    ? $_POST['vorige']      : ( isset( $_GET['vorige'] )    ? $_GET['vorige']       : '' );
        $volgende   =  isset( $_POST['volgende'] )  ? $_POST['volgende']    : ( isset( $_GET['volgende'] )  ? $_GET['volgende']     : '' );
        $temp_wid   =  isset( $_POST['widget_id'] ) ? $_POST['widget_id']   : ( isset( $_GET['widget_id'] ) ? $_GET['widget_id']    : 'widget_id' );

    
        //===================

       if (is_dir($outpath_thumbs)) {
            
            $info           = explode('_', $huidige );
            $time           = explode('-', $info[0] );

            $this->info['pngbestand']   = $outpath . $info[1] . '.png';
            $this->info['txtbestand']   = $outpath . $info[1] . '.txt';

            $this->thumb        = $outpath_thumbs . $huidige . '.png';

            if ( $huidige && ( (! $vorige) && (! $volgende ) ) )  {
                setstatus('\$vorige & \$vorige zijn leeg: ' . $vorige . ' | ' . $volgende);
            } 
            elseif ( $huidige && ( $vorige || $volgende ) ) {

                // door alle thumbs heen lopen
                setstatus('$huidige ' . $huidige);
//                setstatus('$vorige ' . $vorige);
//                setstatus('$volgende ' . $volgende);

                $info           = explode('_', $huidige );
                $time           = explode('-', $info[0] );
    
                $this->info['pngbestand']   = $outpath . $info[1] . '.png';
                $this->info['txtbestand']   = $outpath . $info[1] . '.txt';

                $this->thumb        = $outpath_thumbs . $huidige . '.png';

                if ( file_exists( $this->info['txtbestand'] ) &&  file_exists( $this->info['pngbestand'] ) ) {

//                    setstatus('file_exists ' . $this->info['txtbestand']);
                    setstatus('file_exists ' . $this->info['pngbestand']);
                    
                    $json_data      = file_get_contents($this->info['txtbestand']);
                    $all            = json_decode($json_data, true);

                    
                    if($all) {

                        // check de vorige
                        $info           = explode('_',  $vorige );
                        $time           = explode('-',  $info[0] );
            
                        if ( isset( $info[1] )) {
                            $this->info['vorige-png']   = $outpath . $info[1] . '.png';
                            $this->info['vorige-txt']   = $outpath . $info[1] . '.txt';
                            
                            if ( file_exists( $this->info['vorige-png'] ) &&  file_exists( $this->info['vorige-txt'] ) ) {

                                $views          = getviews($this->info['vorige-txt'],false);
                                                                                                
                                $all[TEGELIZR_VORIGE]  = $info[1];
                                $all[TEGELIZR_VORIGE_TITEL]  = isset($views['txt_tegeltekst']) ? filtertext($views['txt_tegeltekst']) : '?';
                                
                            }
                        }

                        // check de volgende
                        $info           = explode('_', $volgende );
                        $time           = explode('-', $info[0] );
            
                        if ( isset( $info[1] )) {
                            
                            $this->info['volgende-png']   = $outpath . $info[1] . '.png';
                            $this->info['volgende-txt']   = $outpath . $info[1] . '.txt';
                            
                            if ( file_exists( $this->info['volgende-png'] ) &&  file_exists( $this->info['volgende-txt'] ) ) {

                                $views          = getviews($this->info['volgende-txt'],false);
                                                                                                
                                $all[TEGELIZR_VOLGENDE]  = $info[1];
                                $all[TEGELIZR_VOLGENDE_TITEL]  = isset($views['txt_tegeltekst']) ? filtertext($views['txt_tegeltekst']) : '?';
                                
                            }
                        }
                    }

                    $all['status']       = $status;
                    $all['widget_id']    = $temp_wid;

                    $newJsonString = json_encode($all);
                    file_put_contents($this->info['txtbestand'], $newJsonString);

                    
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
    
                $this->info['nrdocs']   = count($images);
                $this->info['docs']     = $images;
                
                $this->info['step']     = 'step 3';
            }
    
        }
        else {
            writedebug('niet bereikbaar: outpath_thumbs ' . $outpath_thumbs);
        }

        //===================
        echo $newJsonString;

    }


    // ==========================================================================    
    
    # ---
    # end class
}


function setstatus($inputstatus) {
    global $status;
//    $status .= '<br />' . $inputstatus;
}


