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

// ===================================================================================================================
// check of er gevraagd wordt om een tegeltje
// de sleutel is TEGELIZR_SELECTOR 
$url            = $_SERVER['REQUEST_URI'];
$zinnen         = explode('/', parse_url($url, PHP_URL_PATH));
$filename       = '';
$desttextpath   = '';
$tekststring    = 'tegel tegeltje tegeltjeswijsheden';

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
<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php echo htmlheader() ?>
<article class="resultaat">
  <h1><a href="/" title="Maak zelf ook een tegeltje"><?php echo returnlogo(); ?>Redactie</a></h1>
  <p>Ik houd niet bij wie welk tegeltje gemaakt heeft. Als een tegeltje me niet bevalt, haal ik het weg. </p>
  <p>Door de tekst op een tegeltje te zetten verandert er niet opeens iets aan het auteursrecht van de tekst. Het auteursrecht erop valt niet  aan mij toe, noch aan degene de tekst invoerde.</p>
  <p>Wie teksten invoert op deze site moet ermee leren leven dat ik de teksten misschien aanpas. Zo wordt 'Facebook' altijd 'het satanische Facebook' op de tegeltjes. Als je dat niet leuk vindt, jammer.</p>
  <p>Maar goed, nu jij. <a href="/">Maak eens een leuk tegeltje</a>.</p>
  <?php echo wbvb_d2e_socialbuttons($desturl, $titel, TEGELIZR_SUMMARY) ?>
  <?php 
    echo showthumbs(12, '');
    ?>
  <p id="home"> <a href="/"><?php echo TEGELIZR_BACK ?></a> </p>
</article>
<?php

}
// ===================================================================================================================
// er wordt gevraagd om alle tegeltjes
// ===================================================================================================================
elseif ( ( $zinnen[1] == TEGELIZR_ZOEKEN ) ) {

    global $zoektegeltje;
    global $path;

    $zoektegeltje = filtertext($_GET[TEGELIZR_ZOEKTERM]);

    $titeltw    = 'Zoek tegeltjes met ' . $zoektegeltje;
    $desturl    = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ZOEKEN . '/?' . TEGELIZR_ZOEKTERM . '=' . $zoektegeltje;
    
    
    $obj        = json_decode(file_get_contents($path . TEGELIZR_ALLES . "/index.txt"), true);
    
    $terms      = explode(" ", $zoektegeltje);
    $results    = array_filter($obj, function ($x) use ($terms){
        foreach($terms as $term){
            if ( isset($x["txt_tegeltekst"]) && stripos("-" . filtertext(strtolower($x["txt_tegeltekst"])), strtolower($term)) ) {
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
        $titel   = "Niets gevonden voor '" . $zoektegeltje . "'";
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
<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php echo htmlheader() ?>
<article class="resultaat">
  <h1><a href="/" title="Maak zelf ook een tegeltje"><?php echo returnlogo() . $titel ; ?></a></h1>

<?php
    if ( $results ) {
    
        echo '<section id="zoekresultaten"><h2>Je zocht op \'' . $zoektegeltje . "'</h2>";
        echo '<ul class="thumbs results">';
        
        foreach($results as $result) {
//echo '<pre>';
//var_dump($result);
//echo '</pre>';

            $hashname = seoUrl( $result['file_name'] );
            $thumb =  $result['file_thumb'];
            
            echo '<li><a href="/'  . TEGELIZR_SELECTOR . '/' . $hashname . '" title="' . filtertext($result['txt_tegeltekst']) . ' - ' . $result[TEGELIZR_VIEWS] . ' keer bekeken"><img src="/' . TEGELIZR_THUMBS . '/' . $thumb . '" height="' . TEGELIZR_THUMB_WIDTH . '" width="' . TEGELIZR_THUMB_WIDTH . '" alt="' . filtertext($result['txt_tegeltekst']) . '" /></a><h3><a href="/'  . TEGELIZR_SELECTOR . '/' . $hashname . '" title="' . filtertext($result['txt_tegeltekst']) . ' - ' . $result[TEGELIZR_VIEWS] . ' keer bekeken">' . filtertext($result['txt_tegeltekst']) . '</a></h3><span class="aantalkeer">' . $result[TEGELIZR_VIEWS] . ' keer bekeken</span>';
if ( $result[TGLZR_NR_VOTES] > 0 ) {
    echo '<br /><span class="aantalkeer">waardering: ' . $result[rounded_avg] . ' ';
    if ( $result[TGLZR_NR_VOTES] > 1 ) {
        echo TEGELIZR_RATING_UNITY;
    }
    else {
        echo TEGELIZR_RATING_UNITY_S;
    }
    echo '</span>';


    
}
             
            echo '</li>';

            
        }    

        echo '</ul></section>';

    }
    else {
        echo '<p>Geen tegeltjes gevonden</p>';
    }

    echo '<form method="get" class="search-form" action="' . TEGELIZR_PROTOCOL . $_SERVER["HTTP_HOST"] . '/' . TEGELIZR_ZOEKEN . '/" role="search">
    <meta itemprop="target" "' . TEGELIZR_PROTOCOL . $_SERVER["HTTP_HOST"] . '/' . TEGELIZR_ZOEKEN . '/?zoektegeltje={s}">
    <label for="' . TEGELIZR_ZOEKTERM . '">Zoek opnieuw</label>
    <input itemprop="query-input" type="search" name="' . TEGELIZR_ZOEKTERM . '" id="' . TEGELIZR_ZOEKTERM . '" value="' . $zoektegeltje . '" placeholder="Hier je zoekterm">
    <input type="submit" value="Search">
</form>';        

    
?>

  <?php echo wbvb_d2e_socialbuttons($desturl, $titeltw, TEGELIZR_SUMMARY) ?><?php echo showthumbs(12, $zinnen[2]);?>
  <p id="home"> <a href="/"><?php echo TEGELIZR_BACK ?></a> </p>
</article>
<?php
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
    $txt_tegeltekst = isset($views['txt_tegeltekst']) ? filtertext($views['txt_tegeltekst']) : '';


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
<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php echo htmlheader() ?>
<article class="resultaat" itemscope itemtype="http://schema.org/ImageObject">
    <h1><a href="/" title="Maak zelf ook een tegeltje"><?php echo returnlogo(); ?><?php echo TEGELIZR_TITLE ?></a></h1>

    <a href="<?php echo htmlspecialchars($desturl)?>" title=""><img src="<?php echo $imagesource ?>" alt="<?php echo $titel ?>" class="tegeltje"  itemprop="contentUrl" /></a>
    <h2 itemprop="name"><?php echo $txt_tegeltekst ?></h2>

<?php
    if ( (isset($views[TEGELIZR_VORIGE])) || (isset($views[TEGELIZR_VOLGENDE])) ) {

        echo '<nav>';
        echo isset($views[TEGELIZR_VORIGE]) ? '<a class="vorige" href="' . TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $views[TEGELIZR_VORIGE] . '" title="Bekijk \'' . $views[TEGELIZR_VORIGE_TITEL] . '\'">' . $views[TEGELIZR_VORIGE_TITEL] . '</a>' : '';
        echo  isset($views[TEGELIZR_VOLGENDE])  ? '<a class="volgende" href="' . TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $views[TEGELIZR_VOLGENDE] . '" title="Bekijk \'' . $views[TEGELIZR_VOLGENDE_TITEL] . '\'">' . $views[TEGELIZR_VOLGENDE_TITEL] . '</a>' : '';
        
        echo '</nav> ';
    }
?>    

    <ul itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <li class="view-counter"><?php echo $views[TEGELIZR_VIEWS] ?> keer bekeken</li>
        <?php 
        // ===================================================            
        if ( intval( $total_points > 0 ) ) { 
        ?>
            <li>Totaalscore: <span itemprop="ratingValue"><?php echo round($dec_avg,2) ?></span></li> 
            <li>Aantal stemmen: <span itemprop="ratingCount"><?php echo $nr_of_votes ?></span></li> 
            <li>Gemiddeld <span class="avaragerating"><?php echo $rounded_avg ?></span> uit <span itemprop="bestRating"><?php echo TEGELIZR_AANTAL_STERREN ?></span></li>

            <?php 
                }
            if ( !$canvote ) { ?>
                <li>Je kunt niet meer stemmen. Je hebt dit <?php echo ( $views[$userip] > 1 ) ? $views[$userip] . ' ' . TEGELIZR_RATING_UNITY : $views[$userip] . ' ' . TEGELIZR_RATING_UNITY_S; ?> gegeven</li>
            <?php }  // ======================================== ?>


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
        <?php }  ?>

            <p class="total_votes"></p>

    <?php
    if ( $canvote ) {
        ?></fieldset>
        </form>
        <?php
    }
    ?>

    
    <p>Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>
    <?php


    

$thumbs = 12;

    if ( ( isset( $_GET[TEGELIZR_TRIGGER_KEY] ) ) && ( $_GET[TEGELIZR_TRIGGER_KEY] == TEGELIZR_TRIGGER_VALUE ) ) {
        $thumbs = 4;
        echo '<p id="progress_now">&nbsp;</p>';
        echo '<p id="progress">&nbsp;</p>';
        echo '<div id="progress_bar"><div>&nbsp;</div></div>';
    }    

    
    echo wbvb_d2e_socialbuttons($desturl, $txt_tegeltekst, TEGELIZR_SUMMARY);
    echo showthumbs($thumbs, $zinnen[2]);

        
        
    ?>
    <p id="home"> <a href="/"><?php echo TEGELIZR_BACK ?></a> </p>
</article>
<?php
    echo spitoutfooter();
?>    


    <script src="http://code.jquery.com/jquery-latest.js"></script>
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
        var PROGRESSLENGTH      = 580;
        var TOTALNRDOCUMENTS    = 300;
        var CURRENTDOCINDEX     = 1;
        $(widget).html('gestart');

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
            $('#progress').html( '<p>' + TOTALNRDOCUMENTS + ' documenten om te scannen</p><ul>');
            $('#progress_bar div').html('&nbsp;');

            $.each(ledinges.docs, function( index, value ) {

                volgende        = ( TOTALNRDOCUMENTS > index+1 ) ? ledinges.docs[index+1] : '';
                vorige          = ( index == 0 ) ? '' : ledinges.docs[index-1] ;

                StartDocumentScan(index, vorige, value, volgende);
                
            });

            $('#progress').append( '</ul>');

                        
        }

        function alertprogress(index, element) {
            $('#progress_now').html(index + ': ' + element);
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

        function SetProgress(data_in) {

            CURRENTDOCINDEX++;
            console.log('SetProgress: ' + CURRENTDOCINDEX + '/' + data_in.widget_id);

            var thelength = ( ( CURRENTDOCINDEX / TOTALNRDOCUMENTS ) * PROGRESSLENGTH);
            $('#progress_bar div').css('width', Math.round(thelength));
            $('#progress').html( '<p>' + CURRENTDOCINDEX + ' van ' + TOTALNRDOCUMENTS + ' tegeltjes gescand.</p>');

<?php
if (22==23) {
?>    
            
            $('#progress').append('<li>' + data_in.widget_id + ': ' + data_in.status + '</li>');

<?php
}
?>    

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


    
}
else {
// ===================================================================================================================
// schrijf formulier
// ===================================================================================================================
?>
<meta name="description" content="">
<meta name="author" content="">
<meta property="og:title" content="<?php echo TEGELIZR_TITLE ?>" />
<meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>" />
<meta property="og:url" content="<?php echo $_SERVER['SERVER_NAME']; ?>" />
<meta property="article:tag" content="<?php echo $tekststring; ?>" />
<meta property="og:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>" />
<title><?php echo TEGELIZR_TITLE ?>- WBVB Rotterdam</title>
<?php echo htmlheader() ?>
<article>
  <h1><?php echo returnlogo(); ?><?php echo TEGELIZR_TITLE ?></h1>
  <?php echo wbvb_d2e_socialbuttons(TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], TEGELIZR_TITLE, TEGELIZR_SUMMARY) ?>
  <p class="lead"> <?php echo TEGELIZR_FORM ?> </p>
  <aside>(maar Paul, <a href="http://wbvb.nl/tegeltjes-maken-is-een-keuze/">wat heb je toch met die tegeltjes</a>?)</aside>
  <form role="form" id="posterform" name="posterform" action="generate.php" method="get" enctype="multipart/form-data">
    <div class="form-group tekstveld">
      <label for="txt_tegeltekst">Jouw tekst:</label>
      <input type="text" aria-describedby="tekst-tip" pattern="^[a-zA-Z0-9-_\.\, \?\!\@\(\)\=\-\:\;\'ùûüÿàâæçéèêëïîôœÙÛÜÀÂÆÇÉÈÊËÏÎÔŒ]{1,<?php echo TEGELIZR_TXT_LENGTH ?>}$" class="form-control" name="txt_tegeltekst" id="txt_tegeltekst" required="required" value="<?php echo TEGELIZR_TXT_VALUE ?>" maxlength="<?php echo TEGELIZR_TXT_LENGTH ?>" size="<?php echo TEGELIZR_TXT_LENGTH ?>" autofocus />
      <div role="tooltip" id="tekst-tip">Alleen letters, cijfers en leestekens. Maximale lengte <?php echo TEGELIZR_TXT_LENGTH ?> tekens</div>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo TEGELIZR_SUBMIT ?></button>
  </form>
  <?php echo showthumbs(12); ?>

  </article>

<?php  
    echo spitoutfooter();

}
?>
