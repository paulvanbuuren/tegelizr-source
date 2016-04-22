<?php

// ===================================================================================================================
// 
//    Tegelizr.nl
//    author:                     Paul van Buuren
//    contact:                    paul@wbvb.nl / wbvb.nl / twitter.com/paulvanbuuren
//    version:                    3.0

// ===================================================================================================================

include("common.inc.php"); 
    
echo spitoutheader();

// if ( 22 == 23 ) {

//echo '<h1>SERVER_NAME: ' . $_SERVER['SERVER_NAME'] . '</h1>';

if ( $_SERVER['SERVER_NAME'] == 'tegelizr' ) {
    // ik ben een dev-server en ik wil geen css inline
    echo '<link href="/css/stylesheet.css" rel="stylesheet" type="text/css">';

    if ( PVB_DEBUG ) {
        echo '
        <style type="text/css">';
            include("css/debug.css");
        echo '</style>';
    }
    
}
else {
    // ik ben geen dev server
    // doe mij maar inline shizzle        
    echo '
    <style type="text/css">';
    include("css/stylesheet.css");
    
    if ( PVB_DEBUG ) {
        include("css/debug.css");
    }
    echo '</style>';

}


// }

// ===================================================================================================================
// check of er gevraagd wordt om een tegeltje
// de sleutel is TEGELIZR_SELECTOR 
$url            = $_SERVER['REQUEST_URI'];
$zinnen         = explode('/', parse_url($url, PHP_URL_PATH));
$filename       = '';
$desttextpath   = '';
$tekststring    = 'tegel tegeltje tegeltjeswijsheden';

$defaultrecords = DEFAULT_AANTAL_TEGELS;

$pagenumber       =  intval(isset( $_POST['pagenumber'] ) ? $_POST['pagenumber'] : ( isset( $_GET['pagenumber'] ) ? $_GET['pagenumber'] : '1' ));
if ( ( intval( $pagenumber ) < 1 ) || ( intval( $pagenumber ) > 1000 ) ) {
    $pagenumber = 1;    
}
$max_items      =  intval(isset( $_POST['max_items'] ) ? $_POST['max_items'] : ( isset( $_GET['max_items'] ) ? $_GET['max_items'] : $defaultrecords ));

$startrecs      = ( ( $pagenumber - 1 ) * $max_items );
if ( intval($startrecs) < 0 ) {
    $startrecs = 0;
}

$endrecs        = ( $startrecs + $max_items );



    
if ( isset( $zinnen[2] ) ) {
    $filename       = $zinnen[2] . ".png";
    $fileid         = $zinnen[2];
    $desttextpath   = $zinnen[2] . ".txt";
}

// ===================================================================================================================
// er wordt gevraagd om de tekst over hoe ik alle tegeltjes stuk mag maken
// ===================================================================================================================
if ( ( $zinnen[1] == TEGELIZR_REDACTIE ) ) {
    
    $titel      = TEGELIZR_TITLE . ' - redactie';
    $desturl    = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_REDACTIE . '/';


?>
<meta property="og:title" content="<?php echo $titel; ?>" />
<meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>" />
<meta property="og:url" content="<?php echo $desturl; ?>" />
<meta property="article:tag" content="<?php echo TEGELIZR_ALLES; ?>" />
<meta property="og:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>" />
<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php echo htmlheader('over-de-site') ?>
<?php echo returnheader("Over deze site", "Maak zelf ook een tegeltje", true, 'h1'); ?>
<article id="page"  class="resultaat">
  <p>Ik houd niet bij wie welk tegeltje gemaakt heeft.</p>
  <p>Door de tekst op een tegeltje te zetten verandert er niet opeens iets aan het auteursrecht van de tekst. Het auteursrecht erop valt niet  aan mij toe, noch aan degene de tekst invoerde.</p>
  <p>Wie teksten invoert op deze site moet ermee leren leven dat ik de teksten misschien aanpas. Zo wordt 'Facebook' altijd 'het satanische Facebook' op de tegeltjes. Als je dat niet leuk vindt, jammer.</p>
  <p>Als een tegeltje me niet bevalt, haal ik het weg.</p>
  
<p>Op mijn website <a href="http://wbvb.nl/tegeltjes-maken-is-een-keuze/">schreef ik iets waarom ik deze site gemaakt heb</a>.</p>  

<h3>Contact via mail of Twitter:</h3><ul><li><a href="mailto:paul@wbvb.nl">paul@wbvb.nl</a></li><li><a href="https://twitter.com/paulvanbuuren" title="Twitter">@paulvanbuuren</a></li></ul>
  
  <p>Maar goed, nu jij.</p>
  <?php 
    echo TheModalWindow();
    ?>
</article>
<?php
    echo showthumbs(12, '', $pagenumber);
    echo spitoutfooter();


}

// ===================================================================================================================
// er wordt gevraagd om alle tegeltjes // voorlopig even uitgeschakeld
// ===================================================================================================================
elseif ( ( $zinnen[1] == TEGELIZR_ALLES ) && ( 22 == 33 )  ) {
    $titeltw    = 'Alle tegeltjes';
    $desturl    = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ALLES . '/';
    
    
    $sort_dir       =  isset( $_POST['sort_dir'] ) ? $_POST['sort_dir'] : ( isset( $_GET['sort_dir'] ) ? $_GET['sort_dir'] : 'asc' );
    if ( ! isset( $arr_sort_dir[$sort_dir] ) ) {
        $sort_dir = 'asc';
    }
    
    $sort_by        =  isset( $_POST['sort_by'] ) ? $_POST['sort_by'] : ( isset( $_GET['sort_by'] ) ? $_GET['sort_by'] : 'name' );
    if ( ! isset( $arr_sort_by[$sort_by] ) ) {
        $sort_by = 'name';
    }
    
    
    
    
    
    $index_html     = $path . TEGELIZR_ALLES . "/index.html";
    $index_txt      = $path . 'xxx-alle-tegeltjes' . "/index.txt";
    
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


    
?>
<meta property="og:title" content="<?php echo $titeltw; ?>" />
<meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>" />
<meta property="og:url" content="<?php echo $desturl; ?>" />
<meta property="og:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>" />
<?php echo "<title>" . $titeltw . " - WBVB Rotterdam</title>"; ?><?php echo htmlheader(TEGELIZR_ALLES) ?>
<?php echo returnheader( $titeltw, "Maak zelf ook een tegeltje", true, 'h1') ; ?>

<article id="alle_tegeltjes"  class="resultaat">


        <?php echo writecontrolform() ?> 

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
//            echo 'eh<br />';
            echo $temparr[$key]['txt_tegeltekst'];
        }




    echo writecontrolform(); 
    echo TheModalWindow();
      
  ?>
</article>

<?php
//    echo showthumbs(12, $zinnen[2]);
    echo includejs();
    echo spitoutfooter();

}
// ===================================================================================================================
// er wordt gezocht naar tegeltjes
// ===================================================================================================================
elseif ( ( $zinnen[1] == TEGELIZR_ZOEKEN ) ) {

    global $q;
    global $path;

    $q = filtertext($_GET[TEGELIZR_ZOEKTERM], false);

    $titeltw    = 'Zoek tegeltjes met ' . $q;
    $desturl    = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ZOEKEN . '/?' . TEGELIZR_ZOEKTERM . '=' . $q;
    
    
    $obj        = json_decode(file_get_contents($path . TEGELIZR_ALLES . "/index.txt"), true);
    
    $terms      = explode(" ", $q);
    $results    = array_filter($obj, function ($x) use ($terms){
        foreach($terms as $term){
            if ( isset($x["txt_tegeltekst"]) && stripos("-" . filtertext(strtolower($x["txt_tegeltekst"]), false ), strtolower($term)) ) {
                return true;
            }
        }
        return false;
    });
    
    if ( count($results) ) {
        if ( count($results) > 1 ) {
            $titel   = count($results) . " tegeltjes gevonden";
        }
        else {
            $titel   = count($results) . " tegeltje gevonden";
        }
    }
    else {
        $titel   = "Niets gevonden voor '" . $q . "'";
    }
    

function sortByOrder($a, $b) {
    return $a['file_name'] - $b['file_name'];
}

    

?>
<meta property="og:title" content="<?php echo $titeltw; ?>" />
<meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>" />
<meta property="og:url" content="<?php echo $desturl; ?>" />
<meta property="article:tag" content="<?php echo $tekststring; ?>" />
<meta property="og:image" content="<?php echo $imagesource ?>" />
<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php echo htmlheader(TEGELIZR_ZOEKEN) ?>
<?php echo returnheader('zoekresultaten', "Maak zelf ook een tegeltje", true, 'p') ; ?>
<article id="page"  class="resultaat">

<?php
    if ( $results ) {
    
        echo '<section id="zoekresultaten"><h1>' . $titel . ' voor \'' . $q . "'</h1>";
        echo '<ul class="thumbs results">';
        
        foreach($results as $result) {

            echo getSearchResultItem($result);

        }

        echo '</ul></section>';

    }
    else {
        echo '<p>Geen tegeltjes gevonden</p>';
    }

    echo '<form method="get" class="search-form" action="' . TEGELIZR_PROTOCOL . $_SERVER["HTTP_HOST"] . '/' . TEGELIZR_ZOEKEN . '/" role="search">
    <meta itemprop="target" "' . TEGELIZR_PROTOCOL . $_SERVER["HTTP_HOST"] . '/' . TEGELIZR_ZOEKEN . '/?q={s}">
    <label for="' . TEGELIZR_ZOEKTERM . '">Zoek opnieuw</label>
    <input itemprop="query-input" type="search" name="' . TEGELIZR_ZOEKTERM . '" id="' . TEGELIZR_ZOEKTERM . '" value="' . $q . '" placeholder="Hier je zoekterm">
    <input type="submit" value="Search">
</form>';        

    echo wbvb_d2e_socialbuttons($desturl, $titeltw, TEGELIZR_SUMMARY); 
    echo TheModalWindow();
      
  ?>
</article>

<?php
    echo showthumbs(12, $zinnen[2], $pagenumber);
    echo includejs();
    echo spitoutfooter();



}
// ===================================================================================================================
// er wordt gevraagd om een tegeltje en het bestand bestaat ook al op de server
// ===================================================================================================================
elseif ( ( $zinnen[1] == TEGELIZR_SELECTOR ) && ( file_exists( $outpath.$filename ) ) && ( file_exists( $outpath.$desttextpath ) ) ) {

    global $userip;
    
    $desturl        = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $zinnen[2];
    $imagesource    = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_TEGELFOLDER . '/' . $filename;
    $views          = getviews($outpath.$desttextpath,true);
    $txt_tegeltekst = isset($views['txt_tegeltekst']) ? filtertext($views['txt_tegeltekst'], true) : '';


    $total_points   = isset($views[TGLZR_TOTAL_POINTS]) ? $views[TGLZR_TOTAL_POINTS] : 0;
    $dec_avg        = isset($views[dec_avg]) ? $views[dec_avg] : 0;
    $rounded_avg    = isset($views[rounded_avg]) ? $views[rounded_avg] : 0;
    $nr_of_votes    = isset($views[TGLZR_NR_VOTES]) ? $views[TGLZR_NR_VOTES] : 0;

    $titel          = $txt_tegeltekst . ' - ' . TEGELIZR_TITLE;
    $canvote        = true;
    $disabled       = '';
    $legend         = 'Hoeveel sterren is dit tegeltje waard?';
        
    if ( isset($views[$userip] ) ) {
        $legend     = 'Gemiddelde waardering';
        $canvote    = false;
        $disabled   = ' disabled="disabled"';
    }

?>
<meta property="og:title" content="<?php echo $titel; ?>" />
<meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>" />
<meta property="og:url" content="<?php echo $desturl; ?>" />
<meta property="article:tag" content="<?php echo $tekststring; ?>" />
<meta property="og:image" content="<?php echo $imagesource ?>" />
<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php echo htmlheader('tegeltje') ?>
<?php echo returnheader('home', "Naar de homepage", true, 'p'); ?>
<article id="page"  id="page" class="resultaat" itemscope itemtype="http://schema.org/ImageObject">

    <h1 itemprop="name" id="header1"><?php echo $txt_tegeltekst ?></h1>

    <a href="<?php echo htmlspecialchars($desturl)?>" class="placeholder">

    <?php
    
        if ( isset( $zinnen[2] ) ) {
            $fileprefix    = $zinnen[2];
        }
        
        $imgcounter = 0;
        
        $srcset = "\n srcset=\"";
        $sizes     = "\n sizes=\"";
        $sources = '';
    
        
//        foreach ( array_reverse( $arr_thumb_sizes ) as $i => $value) { 
        foreach ( $arr_thumb_sizes  as $i => $value) { 
            // output path voor grote tegel
            
            $currenfilename = $fileprefix . "_" . $value['width'] . "_" . $i;
            $resizedfile    = $currenfilename . '.' . TEGELIZR_RESIZE_EXT;
            $currentimage    = '/' . TEGELIZR_TEGELFOLDER . '/' . $resizedfile;
            
            if ( ! file_exists( $outpath.$resizedfile ) ) {
//                writedebug('bestaat niet: ' . $outpath.$resizedfile );
                resize($value['width'],$outpath.$currenfilename,$outpath.$filename);
            }
        
            $imgcounter++;
            if ( $imgcounter > 1 ) {
                $srcset .= ", ";
                $sizes    .= ", ";
            }
            
            $screenwidth    = ( $value['screenwidth'] );
            $mediawidth        = ( $value['width'] );
            
            $srcset .= $currentimage . " " . $mediawidth . 'w';
            $sizes     .= "(max-width: " . $screenwidth . "px) " . $mediawidth . 'px';
            $sources.= '<source media="(max-width: ' . $screenwidth . 'px)" srcset="' . $currentimage . '">';

            $imagesource    = $currentimage;
            
        }
    
        $srcset .= '"';
        $sizes     .= ', 254px"';
    
    if ( 22 == 22 ) { ?>

<?php    if ( 23 == 22 ) { ?>

<pre style="border: 1px solid black; background: white; color: grey; font-family: monospace; font-size: 11px; padding-top: 10em;">
&lt;img id="" aria-describedby="header1" itemprop="contentUrl" alt="<?php echo $titel; ?>" src="<?php echo $imagesource ?>" <?php echo $srcset ; ?> <?php echo $sizes; ?>/&gt;    


&lt;img src="cat.jpg" alt="cat"
  srcset="cat-160.jpg 160w, cat-320.jpg 320w, cat-640.jpg 640w, cat-1280.jpg 1280w"
  sizes="(max-width: 480px) 100vw, (max-width: 900px) 33vw, 254px"&gt;
  
</pre>

<?php } ?>

        <img id="resp_tegeltje" aria-describedby="header1" itemprop="contentUrl" alt="<?php echo $titel; ?>" 
            src="<?php echo $imagesource ?>" 
            <?php echo $srcset ; ?>
            <?php echo $sizes; ?>
              />    <?php


                  
    }
    else {    ?>

        <picture>
            <?php echo $sources; ?>
            <img src="<?php echo $imagesource ?>" alt="<?php echo $titel ?>" id="resp_tegeltje">
        </picture>    <?php
    }

    if ( ( isset( $_GET[TEGELIZR_TRIGGER_KEY] ) ) && ( $_GET[TEGELIZR_TRIGGER_KEY] == TEGELIZR_TRIGGER_VALUE ) ) {
        echo '<p id="progress_now">&nbsp;</p><div id="progress">&nbsp;</div>';
    }
    ?></a>

<section id="header_and_counter">

    <ul itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <li class="view-counter"><?php echo $views[TEGELIZR_VIEWS] ?> keer bekeken</li>
        <?php 
        // ===================================================            
        if ( intval( $total_points > 0 ) ) { 
        ?>
            <li class="visiblyhidden">Totaalscore: <span itemprop="ratingValue"><?php echo round($dec_avg,2) ?></span></li> 
            <li class="visiblyhidden">Aantal stemmen: <span itemprop="ratingCount"><?php echo $nr_of_votes ?></span></li> 
            <li class="visiblyhidden">Gemiddeld <span class="avaragerating"><?php echo $rounded_avg ?></span> uit <span itemprop="bestRating"><?php echo TEGELIZR_AANTAL_STERREN ?></span></li>

            <?php 
                }
            if ( !$canvote ) { ?>
                <li>Je kunt niet meer stemmen. Je hebt dit <?php echo ( $views[$userip] > 1 ) ? $views[$userip] . ' ' . TEGELIZR_RATING_UNITY : $views[$userip] . ' ' . TEGELIZR_RATING_UNITY_S; ?> gegeven</li>
            <?php }// ======================================== ?>


    </ul>

    <?php

    if ( $canvote ) {
    ?>
    <form role="form" id="star_rating" name="star_rating" action="sterretjes.php" method="get" enctype="multipart/form-data">
        <fieldset class="rate_widget">
            <legend class="result"><?php echo $legend ?></legend>
            
    <?php
    }
    ?>
    
            <div class="rating" id="<?php echo $fileid ?>">
            <?php
            $i = 0;

            while ($i < TEGELIZR_AANTAL_STERREN):
            
                $lekey = ( TEGELIZR_AANTAL_STERREN - $i); 
                
                echo '<input type="radio" name="' . TEGELIZR_RATING_VOTE . '" value="' . $lekey . '"  id="' . TEGELIZR_RATING_VOTE . '' . $lekey . '" class="star_' . $lekey;

                $cd = ' class="mag_klikbaar"';

                if ( ( $dec_avg > 0 ) && ( $dec_avg > $lekey ) ) {
                    if ( $disabled ) {
                        $cd = ' class="waardering"';
                    }
                    else {
                        $cd = ' class="waardering mag_klikbaar"';
                    }
                    echo ' waardering';
                }
                else {
                }
                echo '"';

                if ( $lekey == 1 ) {
                    echo ' required="required"';
                }
                if ( $dec_avg == $lekey ) {
                    echo ' checked="checked"';
                }

                echo  $disabled . ' /><label for="' . TEGELIZR_RATING_VOTE . '' . $lekey . '"'  . $cd . ' data-starvalue="' . $lekey . '">' . $lekey . '</label>';
                $i++;
            endwhile;

            ?>
        </div>

        <?php if ( $canvote ) {  // ======================================== ?>
            <input type="hidden" id="widget_id" name="widget_id" value="<?php echo $fileid ?>" />
            <input type="hidden" id="redirect" name="redirect" value="<?php echo $fileid ?>" />
            <button type="submit" class="btn btn-primary"<?php echo $disabled ?>><?php echo TEGELIZR_SUBMIT_RATING ?></button>
        <?php }?>

            <p class="total_votes"></p>

    <?php
    if ( $canvote ) {
        ?></fieldset>
        </form>
        <?php
    }

    echo wbvb_d2e_socialbuttons($desturl, $txt_tegeltekst, TEGELIZR_SUMMARY);
    echo TheModalWindow();
    ?>
</section>

<?php
    if ( (isset($views[TEGELIZR_VORIGE])) || (isset($views[TEGELIZR_VOLGENDE])) ) {


        echo '<nav id="previousnext">';
        echo isset($views[TEGELIZR_VORIGE]) ? '<a class="vorige" href="' . TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $views[TEGELIZR_VORIGE] . '" title="Bekijk \'' . $views[TEGELIZR_VORIGE_TITEL] . '\'">' . HTML_PIJL_VORIGE . $views[TEGELIZR_VORIGE_TITEL] . '</a>' : '';
        echo  isset($views[TEGELIZR_VOLGENDE])  ? '<a class="volgende" href="' . TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $views[TEGELIZR_VOLGENDE] . '" title="Bekijk \'' . $views[TEGELIZR_VOLGENDE_TITEL] . '\'">' . HTML_PIJL_VOLGENDE . $views[TEGELIZR_VOLGENDE_TITEL] . '</a>' : '';
        
        echo '</nav> ';
    }

        
        
    ?>

</article>

<?php
    echo showthumbs(12, $zinnen[2], $pagenumber);

    echo includejs();
?>

    <script>

    $(document).ready(function() {

        $('.rate_widget').each(function(i) {
            var widget = this;

            var out_data = {
                widget_id : $(widget).attr('id'),
                fetch: 1
            };

            $.post(
                'sterretjes.php',
                out_data,
                function(INFO) {
                    $( ".result" ).data( 'fsr', INFO );
                    console.log(' 1 Gezet! '  ) ;                    
                    set_votes(widget);
                },
                'json'
            );
        });
    


<?php // ===================================================================================================================


    if ( ( isset( $_GET[TEGELIZR_TRIGGER_KEY] ) ) && ( $_GET[TEGELIZR_TRIGGER_KEY] == TEGELIZR_TRIGGER_VALUE ) ) {
        
        ?>

        var vorige              = '';
        var volgende            = '';
        var widget              = $('#progress');
        var myNumber            = undefined;
        var PROGRESSLENGTH      = 584;
        var PROGRESSOPACITY     = 1;
        var TOTALNRDOCUMENTS    = 300;
        var CURRENTDOCINDEX     = 0;
        $(widget).html('gestart');

        $('.placeholder img').css('opacity','0');
        $('.placeholder').addClass('momentje');
        $('.placeholder').append('<div id="momentje" class="momentje"><h3>Hallo</h3><p>Momentje, alsjeblieft.<br />Je tegeltje is bijna klaar.</p></div>');
        ToggleHide(false);
        
        var generate_data = {
            widget_id : $('#progress').attr('id'),
            fetch: 1
        };

        $.post(
            'scanfolder.php',
            generate_data,
            function(generate_data_out) {
                $('#progress').data( 'progress', generate_data_out );
                startgenerate(widget);
            },
            'json'
        );

        
        function startgenerate(widget) {
    
            var ledinges        = $('#progress').data( 'progress');
            var thelength       = 0;
            TOTALNRDOCUMENTS    = ledinges.nrdocs;
            $('#progress').html(TOTALNRDOCUMENTS + ' documenten om te scannen');

            $.each(ledinges.docs, function( index, value ) {
                volgende        = ( TOTALNRDOCUMENTS > index+1 ) ? ledinges.docs[index+1] : '';
                vorige          = ( index == 0 ) ? '' : ledinges.docs[index-1] ;
                StartDocumentScan(index, vorige, value, volgende);
            });
        }

        function StartDocumentScan(index, vorige, txtfile, volgende) {
            'use strict';
        
            var data_in = {
                widget_id: index,
                total_docs: TOTALNRDOCUMENTS,
                vorige: vorige,
                huidige: txtfile,
                volgende: volgende
            };

            $.post(
                'documentscan.php',
                data_in,
                function( data_out ) {
                    SetProgress(data_out);
                },
                'json'
            );
        }

        function ToggleHide(showhide) {
            $('#home').toggle(showhide);
            $('#andere').toggle(showhide);
            $('footer').toggle(showhide);
            $('nav').toggle(showhide);
            $('#leuk').toggle(showhide);
            $('#star_rating').toggle(showhide);
            $('.social-media').toggle(showhide);
            $('[itemprop="aggregateRating"]').toggle(showhide);
            if ( showhide ) {
                $('#top a').attr('href','/' );
            }
            else {
                $('#top a').removeAttr('href');
            }
        }
            
        function SetProgress(data_in) {

            CURRENTDOCINDEX++;
            console.log('SetProgress: ' + CURRENTDOCINDEX + '/' + data_in.widget_id);

            var thelength = ( ( CURRENTDOCINDEX / TOTALNRDOCUMENTS ) * PROGRESSLENGTH);
            var opacityDiv = (Math.round( ( ( CURRENTDOCINDEX / TOTALNRDOCUMENTS ) * PROGRESSOPACITY ) * 10) / 10 );

            $('#momentje').css('height', ( PROGRESSLENGTH - Math.round(thelength) ) );
            $('#progress').html( '<p>' + CURRENTDOCINDEX + ' van ' + TOTALNRDOCUMENTS + ' tegeltjes gescand.</p>');
            $('#progress_now').html('Even alle tegeltjes tellen en oppoetsen');
            
            if ( opacityDiv > 0.1 ) {
                $('.placeholder img').css('opacity', opacityDiv  );
            }
            if ( opacityDiv > 0.25 ) {
                $('#progress_now').html('Daar gaan we dan.');
            }
            if ( opacityDiv > 0.35 ) {
                $('#progress_now').html('Leuke tekst heb je uitgekozen.');
            }
            if ( opacityDiv > 0.45 ) {
                $('#progress_now').html('Echt, hoor.');
            }
            if ( opacityDiv > 0.55 ) {
                $('#progress_now').html('Nee, echt!');
            }
            if ( opacityDiv > 0.65 ) {
                $('#progress_now').html('We zijn er bijna.');
            }
            if ( opacityDiv > 0.7 ) {
                $('#progress_now').html('Wat zit je haar leuk');
            }
            if ( opacityDiv > 0.8 ) {
                $('#progress_now').html('Tadaaa!');
            }
            if ( opacityDiv > 0.85 ) {
                $('#progress_now').html("Daar is 'ie al");
            }
            if ( opacityDiv > 0.9 ) {
                $('#progress_now').html("Veel plezier!");
                $('#momentje').remove();
                ToggleHide(true);
            }
            if ( opacityDiv > 0.99 ) {
//                $('#progress_now').remove();
            }
        }

<?php

    }
    // =================================================================================================================== ?>

        $('.rate_widget label.mag_klikbaar').addClass('is_klikbaar');

        $('.btn.btn-primary').toggle(false);

        $('.is_klikbaar').hover(
            // Handles the mouseover
            function() {
                $(this).prevAll().andSelf().addClass('ratings_over');
                $(this).nextAll().removeClass('ratings_vote'); 
            },
            // Handles the mouseout
            function() {
                $(this).prevAll().andSelf().removeClass('ratings_over');
                // can't use 'this' because it wont contain the updated data
                set_votes($(this).parent());
            }
        );

        // This actually records the vote
        $( '.is_klikbaar' ).bind('click', function() {
            var star    = this;
            var widget  = $(this).parent();
            
            $( ".result" ).html( 'er is geklikt op ' + $(this).html() );

            var clicked_data = {
                <?php echo TEGELIZR_RATING_VOTE ?> : $(this).data('starvalue'),
                widget_id : $(star).parent().attr('id')
            };
            $.post(
                'sterretjes.php',
                clicked_data,
                function(INFO) {
                    console.log('er is gescoord');                    
                    $( ".result" ).data( 'fsr', INFO );
                    set_votes(widget);
                },
                'json'
            ); 
        });

    });



    function set_votes(widget) {

        var ledinges    = $( ".result" ).data('fsr');
        var Dankjewel   = ledinges.<?php echo $userip . '_comment' ?>;
        
        $( ".result" ).html( Dankjewel );

        $('.is_klikbaar').removeClass('is_klikbaar');

        var avg   = ledinges.<?php echo rounded_avg ?>;
        var votes = ledinges.<?php echo TGLZR_NR_VOTES ?>;
        var exact = ledinges.<?php echo dec_avg ?>;

        if ( $('span[itemprop="ratingValue"]').length ) {
            $('span[itemprop="ratingValue"]').html(exact);
            $('span[itemprop="ratingCount"]').html(votes);
            $('span.avaragerating').html(avg);
        }
        else {
            $('ul[itemprop="aggregateRating"]').append('<li>Totaalscore: <span itemprop="ratingValue">' + exact + '</span></li> <li>Aantal stemmen: <span itemprop="ratingCount">' + exact + '</span></li> <li>Gemiddeld <span class="avaragerating">' + exact + '</span> uit <span itemprop="bestRating">' + exact + '</span></li></li><li>Je kunt niet meer stemmen.</li>');
        }

        $(widget).find('.star_' + avg).prevAll().andSelf().addClass('ratings_vote');
        $(widget).find('.star_' + avg).nextAll().removeClass('ratings_vote'); 
    }

    </script>

<?php
    echo spitoutfooter();

    

    
}
else {
// ===================================================================================================================
// schrijf formulier
// ===================================================================================================================
?>

<meta name="description" content="<?php echo TEGELIZR_SUMMARY ?>">
<meta name="author" content="<?php echo TEGELIZR_AUTHOR ?>">
<meta property="og:title" content="<?php echo TEGELIZR_TITLE ?>" />
<meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>" />
<meta property="og:url" content="<?php echo $_SERVER['SERVER_NAME']; ?>" />
<meta property="article:tag" content="<?php echo $tekststring; ?>" />
<meta property="og:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>" />
<title><?php echo TEGELIZR_TITLE ?>- WBVB Rotterdam</title>
<?php echo htmlheader('home') ?>
<?php echo returnheader(TEGELIZR_TITLE, '', false); ?>
<article>
  <p class="lead"> <?php echo TEGELIZR_FORM ?> <br />
  (maar Paul, <a href="http://wbvb.nl/tegeltjes-maken-is-een-keuze/">wat heb je toch met die tegeltjes</a>?)</p>
    <?php 
        echo TheForm();
    ?>
  </article>
    <?php 
        echo showthumbs(12, '', $pagenumber); 
        echo includejs();
    ?>

<?php  
    echo spitoutfooter();

}

if ( 22 == 23) {
    
    function sanitize_output($buffer) {
    
        $search = array(
            '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
            '/[^\S ]+\</s',  // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );
    
        $replace = array(
            '>',
            '<',
            '\\1'
        );
    
        $buffer = preg_replace($search, $replace, $buffer);
    
        return $buffer;
    }
    
    ob_start("sanitize_output");
}


?>

