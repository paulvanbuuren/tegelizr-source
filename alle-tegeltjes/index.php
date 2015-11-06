<?php

// ===================================================================================================================
// 
//    Tegelizr.nl
//    author:                     Paul van Buuren
//    contact:                    paul@wbvb.nl / wbvb.nl / twitter.com/paulvanbuuren
//    version:                    5.0.1 - sorteermogelijkheid ingebouwd voor alle-tegeltjes-pagina

// ===================================================================================================================

include("../common.inc.php"); 



$defaultrecords = '10';

$sort_dir       =  isset( $_POST['sort_dir'] ) ? $_POST['sort_dir'] : ( isset( $_GET['sort_dir'] ) ? $_GET['sort_dir'] : 'asc' );
if ( ! isset( $arr_sort_dir[$sort_dir] ) ) {
    $sort_dir = 'asc';
}

$sort_by        =  isset( $_POST['sort_by'] ) ? $_POST['sort_by'] : ( isset( $_GET['sort_by'] ) ? $_GET['sort_by'] : 'name' );
if ( ! isset( $arr_sort_by[$sort_by] ) ) {
    $sort_by = 'name';
}

$max_items      =  intval(isset( $_POST['max_items'] ) ? $_POST['max_items'] : ( isset( $_GET['max_items'] ) ? $_GET['max_items'] : $defaultrecords ));

$pagenumber   =  intval(isset( $_POST['pagenumber'] ) ? $_POST['pagenumber'] : ( isset( $_GET['pagenumber'] ) ? $_GET['pagenumber'] : '1' ));



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

if ( intval($startrecs) > $totalcount ) {
    $startrecs = 0;
    $pagenumber= 1;
}


if ( $endrecs > $totalcount ) {
    $endrecs = $totalcount;
}

$temparr = array();
$counter = 0;

$prefix = 'sort_by=' . $sort_by . '&sort_dir=' . $sort_dir . '&max_items=' . $max_items . '&pagenumber=' . $pagenumber . '&startrecs=' . $startrecs . '&endrecs=' . $endrecs . '&totalcount=' . $totalcount; 


$desturl    = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ALLES . '/?' . htmlspecialchars($prefix);


$titel = ( $startrecs + 1 ) . " tot " . $endrecs . " van " . $totalcount . " tegeltjes";

$possiblepages = round( $totalcount / $max_items );

if ( $possiblepages > 4 ) {

    $i = 1;
    while ($i <= $possiblepages):
        $arrpaginas[$i] = 'op pagina ' . $i;
        $i++;
    endwhile;
    
}


echo spitoutheader();

?>    
<!-- start -->
<style>
<?php 
include'../css/wbvb.css';
include'../css/style.css';
?>
</style>
<!-- end -->

    
<meta property="og:title" content="592 tegeltjes" />
<meta property="og:description" content="Maak hier je eigen tegeltje. Een geintje van Paul van Buuren, van WBVB Rotterdam." />
<meta property="og:url" content="http://tegelizr:8185/alle-tegeltjes/" />
<meta property="article:tag" content="alle-tegeltjes" />
<meta property="og:image" content="http://wbvb.nl/images/kiezen-is-een-keuze.jpg" />


<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php echo htmlheader() ?>
<article class="resultaat">
<!--    <p id="prefix"><?php echo $prefix ?></p>-->
    <h1 id="top"><a href="/" title="Maak zelf ook een tegeltje"><?php echo returnlogo(); ?><span><?php echo $titel ?></span></a></h1>
    <p id="overzicht">Dit is een overzicht van alle tegeltjes, gesorteerd op <?php echo $arr_sort_by[$sort_by] ?>.</p><p>Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>

  <?php echo wbvb_d2e_socialbuttons($desturl, $titel, TEGELIZR_SUMMARY) ?>

    <section id="alle_tegeltjes">
        <?php echo writecontrolform() ?> 
        <h2>Wat anderen maakten:</h2>
        <ul class="all">
<?php

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

        foreach ($temparr as $key => $value) {
            echo $temparr[$key]['txt_tegeltekst'];

        }    


?>    

        </ul>
    </section>
    <p id="home"> <a href="/"><span>nog een tegeltje</span></a> </p>
        <?php  echo writecontrolform() ?>
</article>
<?php
    echo includejs();
?>

<script>

$(document).ready(function() {
    
    var sort_by = 'name';
    var max_items = <?php echo $defaultrecords ?>;
    var sort_dir = 'asc';
    var pagenumber = 1;
    var debug = 'true';


    $( ".select_sort_dir" ).change(function(event) {

        event.preventDefault();

        sort_dir = this.value;
        
        var mydinges = {
            sort_by : sort_by,
            sort_dir : sort_dir,
            debug : debug,
            max_items : max_items,
            pagenumber: pagenumber
        };
        triggerSort(mydinges);

    });


    $( ".select_pagenumber" ).change(function(event) {

        event.preventDefault();

        pagenumber = this.value;
        
        var mydinges = {
            sort_by : sort_by,
            sort_dir : sort_dir,
            debug : debug,
            max_items : max_items,
            pagenumber: pagenumber
        };
        triggerSort(mydinges);

    });

    $( ".select_sort_by" ).change(function(event) {

        event.preventDefault();

        sort_by = this.value;
        if ( ( this.value === 'rating' ) || ( this.value === 'views' )  ) {
            sort_dir = "desc";
        }
        pagenumber = 1;

        var mydinges = {
            sort_by : sort_by,
            sort_dir : sort_dir,
            debug : debug,
            max_items : max_items,
            pagenumber: pagenumber
        };
        triggerSort(mydinges);


        $( "#overzicht" ).empty();
        var letext = $(".select_sort_by:first option:selected").text();
        $( "#overzicht" ).html('Dit is een overzicht van alle tegeltjes, gesorteerd op ' + letext + '.');
        
        
    });

    $( ".select_max_items" ).change(function(event) {

        event.preventDefault();

        max_items = this.value;
        sort_dir = "desc";
        pagenumber = 1;
        
        var mydinges = {
            sort_by : sort_by,
            sort_dir : sort_dir,
            debug : debug,
            max_items : max_items,
            pagenumber: pagenumber
        };
        triggerSort(mydinges);
        
    });




    $( ".get_next" ).click(function(event) {

        pagenumber++;

        event.preventDefault();
        var mydinges = {
            sort_by : sort_by,
            sort_dir : sort_dir,
            debug : debug,
            max_items : max_items,
            pagenumber: pagenumber
        };
        triggerSort(mydinges);

    });

    $( ".get_previous" ).click(function(event) {
        
        pagenumber--;
        if ( pagenumber < 1 ) {
            pagenumber = 1;
        }

        event.preventDefault();
        var mydinges = {
            sort_by : sort_by,
            sort_dir : sort_dir,
            debug : debug,
            max_items : max_items,
            pagenumber: pagenumber
        };
        triggerSort(mydinges);
    });

    $( ".sort_asc" ).click(function(event) {

        sort_dir = 'asc';

        event.preventDefault();

        var mydinges = {
            sort_by : sort_by,
            sort_dir : sort_dir,
            debug : debug,
            max_items : max_items,
            pagenumber: pagenumber
        };
        triggerSort(mydinges);

    });

    $( ".sort_desc" ).click(function(event) {

        sort_dir = 'desc';

        event.preventDefault();

        var mydinges = {
            sort_by : sort_by,
            sort_dir : sort_dir,
            debug : debug,
            max_items : max_items,
            pagenumber: pagenumber
        };
        triggerSort(mydinges);

    });

    var prefdate = {
        sort_by : sort_by,
        sort_dir : sort_dir,
        max_items : max_items,
        pagenumber: pagenumber
    };

    triggerSort(prefdate);


    function triggerSort(out_data) {

        $( ".all" ).css( 'opacity', '.7' );

        $.post(
            '/alletegeltjes.php',
            out_data,
            function(resultset) {
                $( "#alle_tegeltjes" ).data( 'fsr', resultset );
                ShowResultSet(resultset);
            },
            'json'
        );
    }
    
    function ShowResultSet(data_in) {

        $( ".all" ).css( 'opacity', '1' );

        $( "#top a span" ).html( data_in.startrecs + " tot " + data_in.endrecs + " van " + data_in.totalcount + " tegeltjes");
        $( ".all" ).empty();
        $( "#prefix" ).html(data_in.sort_by);

        $.each(data_in.docs, function( index, value ) {
            $( ".all" ).append("<li>" + value.txt_tegeltekst + "</li>");
        });

//        console.log(JSON.stringify(data_in));
 
        if ( parseInt( data_in.pagenumber ) > 1 ) {

            if ( ! $( ".get_previous" ).length ) {
                var thehtml = $( ".controls fieldset" ).html();
                $( ".controls fieldset" ).html('<button type="submit" class="get_previous" name="pagenumber" value="' + ( pagenumber - 1 ) + '">&#10094;</button>' + thehtml);
            }
            
            $( ".get_previous" ).toggle(true);
        } else {
            $( ".get_previous" ).toggle(false);
        }
        
        if ( parseInt( data_in.endrecs ) >= parseInt( data_in.totalcount ) ) {
            $( ".get_next" ).toggle(false);
        } else {
            $( ".get_next" ).toggle(true);
        }


        var possiblepages = ( data_in.totalcount / max_items );

        $( ".select_pagenumber" ).toggle(false);

        if ( possiblepages > 4 ) {

            $( ".select_pagenumber" ).empty();
            var iCounter = 1;
        
            while (iCounter < possiblepages) {
                $( ".select_pagenumber" ).append('<option value="' + iCounter + '">op pagina ' + iCounter + '</option>');
                iCounter++;
            }
            $( ".select_pagenumber" ).toggle(true);
        }
console.log('sort_dir: ' + sort_dir);
        $( ".select_pagenumber" ).val(pagenumber);
        $( ".select_max_items" ).val(max_items);
        $( ".select_sort_by" ).val(sort_by);
        $( ".select_sort_dir" ).val(sort_dir);
        
    }
    

});

</script>
<?php
    echo spitoutfooter();
?>



<?php 
    
function makeoptionlist($thearray,$theid,$default='') {

    $makeoptionlist = '';
    
    if ( count($thearray) > 0 ) {
        
    
        $makeoptionlist = '<select class="select_' . $theid . '" name="' . $theid . '">';
        
    	foreach ($thearray as $i => $value) { 
        	$selected = '';
        	if ( $default == $i ) {
            	$selected = ' selected="selected"';
        	}
            $makeoptionlist .= '<option value="' . $i . '"' . $selected . '>' . $thearray[$i] . '</option>';
        }
        
        $makeoptionlist .= '</select>';
    }
    

    return $makeoptionlist;
}

function writecontrolform() {

    global $arr_sort_by;
    global $arrSteps;
    global $arr_sort_dir;
    global $defaultrecords;
    global $sort_by;
    global $sort_dir;
    global $max_items;
    global $pagenumber;
    global $startrecs;
    global $max_items;
    global $totalcount;
    global $arrpaginas;
    global $pagenumber;

    $returnstring = 'Sort: ' . $sort_by . ', ';
    $returnstring .= 'Sort-dir: ' . $sort_dir . ', ';
    $returnstring .= 'Max_items: ' . $max_items . ', ';
    $returnstring .= 'Pagenumber: ' . $pagenumber . '. ';
    $returnstring .= '.<form ';
    $returnstring .= 'role="form" class="controls" action="index.php" method="get">';
    $returnstring .= '<fieldset>';
    $returnstring .= '<legend>Sorteer op:</legend>';
    
    if ( $pagenumber > 1 ) {
        $returnstring .= '<button type="submit" class="get_previous" name="pagenumber" value="' . ( $pagenumber - 1 ). '">&#10094;</button>';
    }
    $returnstring .= makeoptionlist($arr_sort_by,'sort_by',$sort_by);
    $returnstring .= makeoptionlist($arr_sort_dir,'sort_dir',$sort_dir);
    $returnstring .= makeoptionlist($arrSteps,'max_items',$max_items);
    $returnstring .= makeoptionlist($arrpaginas,'pagenumber',$pagenumber);
    $returnstring .= '<button type="submit" class="get_next" name="pagenumber" value="' . ( $pagenumber + 1 ). '">&#10095;</button>';
    $returnstring .= '</fieldset>';
    $returnstring .= '</form>';
    
    return $returnstring;
}

?>    
    