<?php

// ===================================================================================================================
// 
//    Tegelizr.nl
//    author:                     Paul van Buuren
//    contact:                    paul@wbvb.nl / wbvb.nl / twitter.com/paulvanbuuren
//    version:                    5.0 - sorteermogelijkheid ingebouwd voor alle-tegeltjes-pagina

// ===================================================================================================================

include("common.inc.php"); 


// Report all PHP errors (see changelog)
error_reporting(E_ALL);


$rating = new sorttegeltjes( '' );


class sorttegeltjes {
    
    private $widget_id;
    private $data = array();
    
    // ==========================================================================    
    function __construct($wid) {
        global $sourcefiles_thumbs;
        global $sourcefiles_tegels;
        global $path;
        global $arr_sort_by;
        global $arrSteps;
        global $arr_sort_dir;
        global $defaultrecords;
        
        $list = '';
        $tegelcounter   = 0;
        $errorcounter   = 0;
        $max            = 40;
        
        
        $sort_dir       =  isset( $_POST['sort_dir'] ) ? $_POST['sort_dir'] : ( isset( $_GET['sort_dir'] ) ? $_GET['sort_dir'] : 'asc' );
        if ( ! isset( $arr_sort_dir[$sort_dir] ) ) {
            $sort_dir = 'asc';
        }

        $sort_by        =  isset( $_POST['sort_by'] ) ? $_POST['sort_by'] : ( isset( $_GET['sort_by'] ) ? $_GET['sort_by'] : 'name' );
        if ( ! isset( $arr_sort_by[$sort_by] ) ) {
            $sort_by = 'name';
        }

        $max_items      =  intval(isset( $_POST['max_items'] ) ? $_POST['max_items'] : ( isset( $_GET['max_items'] ) ? $_GET['max_items'] : $defaultrecords ));

        $pagenumber   =  intval(isset( $_POST['pagenumber'] ) ? $_POST['pagenumber'] : ( isset( $_GET['pagenumber'] ) ? $_GET['pagenumber'] : 'pagenumber' ));
        
        $startrecs  = ( ( $pagenumber - 1 ) * $max_items );
        if ( intval($startrecs) < 0 ) {
            $startrecs = 0;
        }
        $endrecs    = ( $startrecs + $max_items );
        
        $index_html     = $path . TEGELIZR_ALLES . "/index.html";
        $index_txt      = $path . TEGELIZR_ALLES . "/index.txt";
    
        // read the file
        $obj        = json_decode(file_get_contents($index_txt), true);
        $totalcount = count($obj);
        
        if ( $endrecs > $totalcount ) {
            $endrecs = $totalcount;
        }

        $temparr = array();
        $counter = 0;

        $prefix = 'now=' . time() . 
                '&sort_by=' . $sort_by . 
                '&sort_dir=' . $sort_dir . 
                '&max_items=' . $max_items . 
                '&pagenumber=' . $pagenumber . 
                '&startrecs=' . $startrecs . 
                '&endrecs=' . $endrecs; 


        if ( $sort_by == 'rating' ) {
            $sort_flags = SORT_STRING;
        }
        elseif ( $sort_by == 'views' ) {
            $sort_flags = SORT_STRING;
        }
        else {
            $sort_flags = SORT_REGULAR;
        }
        
        $sort_flags = SORT_REGULAR;

//echo '<pre>';


        foreach ($obj as $key => $value) {
            $counter++;


            $file_name = $obj[$key]['file_name'];
            
            if ( isset($obj[$key]['sort_name']) ) {
                $file_name = $obj[$key]['sort_name'];
            }
            
            if ( $sort_by == 'rating' ) {
                $sortkey = DoPrefix('rating_'.$obj[$key]['tglzr_TGLZR_TOTAL_POINTS'].'_','0',20) . $file_name;
            }
            elseif ( $sort_by == 'views' ) {
                $sortkey = DoPrefix('rating_'.$obj[$key]['views'].'_','0',20) . $file_name;
            }
            elseif ( $sort_by == 'filter_date' ) {
                if ( isset($obj[$key]['file_date']) ) {
                    $sortkey = DoPrefix('date_'.$obj[$key]['file_date'].'_','0',20) . $file_name;
                }
            }
            else {
                $sortkey = $file_name;
            }
            
            $temparr[$sortkey] = array(
                "txt_tegeltekst"    => getSearchResultItem($obj[$key],true),
//                "txt_tegeltekst"    => $obj[$key]['txt_tegeltekst'],
//                "txt_tegeltekst"    => filtertext($obj[$key]['txt_tegeltekst']),
                "rating"            => $obj[$key]['tglzr_TGLZR_TOTAL_POINTS'],
                "views"             => $obj[$key]['views']
            );


        }
        

        
        if ( $sort_dir == 'desc' ) {
            krsort($temparr, $sort_flags );
        }
        else {
            ksort($temparr, $sort_flags );
        }



        // select the slice from the array
        $temparr = array_slice($temparr, $startrecs, $max_items);

        $this->data['startrecs']    = ( $startrecs + 1 );
        $this->data['pagenumber']   = $pagenumber;
        $this->data['endrecs']      = ( $endrecs );
        $this->data['totalcount']   = $totalcount;
        $this->data['maxpages']     = round( ( $totalcount / $max_items ),1);
        $this->data['docs']         = $temparr;
        $this->data['sort_by']      = $prefix;


        echo json_encode($this->data);

    }
    


    // ==========================================================================    
    
    # ---
    # end class
}

    function getTegelCounter() {
        global $tegelcounter;
        return $tegelcounter++;
    }



// ===================================================================================================================
function sort_by_order($a, $b) {
//    return $a['tglzr_rounded_avg'] > $b['tglzr_rounded_avg'];
    return $a['views'] < $b['views'];
}

// ===================================================================================================================
function sort_by_views($a, $b) {
    return $a['views'] > $b['views'];
}
