<?php

// ===================================================================================================================
// 
//    Tegelizr.nl
//    author:                     Paul van Buuren
//    contact:                    paul@wbvb.nl / wbvb.nl / twitter.com/paulvanbuuren
//    version:                    3.2.0

// ===================================================================================================================

include("../common.inc.php"); 




if ( isset( $_POST['widget_id'] ) ) {
    $rating = new ratings( $_POST['widget_id'] );
}
elseif ( isset($_GET['widget_id'] ) ) {
    $rating = new ratings( $_GET['widget_id'] );
}
else {
    die('no widget id');
}




class ratings {
    
//    var $data_file =  $_POST['widget_id'] . '.txt';
    private $widget_id;
    private $data = array();
    
    // ==========================================================================    
    function __construct($wid) {
        
        global $sourcefiles_tegelplaatjes;
        global $sourcefiles_tegeldb;

        $this->desturl      = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/';

        $this->widget_id    = $wid;
        $this->filetxt      = $sourcefiles_tegeldb . $this->widget_id . '.txt';

        if ( file_exists( $this->filetxt ) &&  file_exists( $sourcefiles_tegelplaatjes . $this->widget_id . '.png' ) ) {
            
            $all = file_get_contents($this->filetxt);
            
            if($all) {
                $this->data = json_decode($all, true);
            }

            if ( isset( $_POST[TEGELIZR_RATING_VOTE] ) ||  isset($_GET[TEGELIZR_RATING_VOTE] ) ) {
                $this->vote();
            }
            else {
                if ( isset( $_POST['fetch'] ) ||  isset($_GET['fetch'] ) ) {
                    $this->get_ratings();
                }
                else {
                    $this->get_redirect();
                }
            }
        }
        else {
//            echo 'error: no file ' . $this->filetxt;
//            die($this->desturl);
            $this->get_redirect();
        }
    }


    // ==========================================================================    
    public function get_redirect() {

        global $sourcefiles_tegelplaatjes;

        if ( isset( $_POST['redirect'] ) ||  isset($_GET['redirect'] ) ) {
            $this->desturl            = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/';

            if ( file_exists( $this->filetxt ) &&  file_exists( $sourcefiles_tegelplaatjes . $this->widget_id . '.png' ) ) {
                 $this->desturl .=  TEGELIZR_SELECTOR . '/' . $this->widget_id;
            }
            header('Location: ' . $this->desturl);    
        }
        else {
            $this->get_ratings();
        }

    }

    // ==========================================================================    
    public function get_ratings() {

        echo json_encode($this->data);

    }
    // ==========================================================================    
    public function vote() {

        global $userip;
        global $sourcefiles_tegelplaatjes;
    
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

            if ( file_exists( $this->filetxt ) &&  file_exists( $sourcefiles_tegelplaatjes . $this->widget_id . '.png' ) ) {
                 $this->desturl .=  TEGELIZR_SELECTOR . '/' . $this->widget_id;
            }
            header('Location: ' . $this->desturl);    
        }
        else {
            $this->get_ratings();
        }

    }
    // ==========================================================================    
    
    # ---
    # end class
}





