<?php

// ===================================================================================================================
// 
//    Tegelizr.nl
//    author:                     Paul van Buuren
//    contact:                    paul@wbvb.nl / wbvb.nl / twitter.com/paulvanbuuren
//    version:                    6.0.0
//    version description:        Geklungel met zoeken vanuit de adresbalk in Chrome

// ===================================================================================================================


wbvb_set_hsts_policy();

/**
 * Enables the HTTP Strict Transport Security (HSTS) header.
 *
 * @since 1.0.0
 */
function wbvb_set_hsts_policy() {

  if ( $_SERVER['HTTP_HOST'] == 'tegelizr.nl' || $_SERVER['HTTP_HOST'] == 'wordsofwisdomtile.com' ) {

    define('TEGELIZR_PROTOCOL',         'https://');

    if($_SERVER["HTTPS"] != "on") {
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
        exit();
    }
   
    // 2 year expiration: 63072000
    header( 'Strict-Transport-Security: max-age=63072000; includeSubDomains; preload' );
  
  }
  else {
    define('TEGELIZR_PROTOCOL',         'https://');
  }

}

include("common.inc.php");     

// ===================================================================================================================

$url            = $_SERVER['REQUEST_URI'];
$zinnen         = explode('/', parse_url($url, PHP_URL_PATH));
$filename       = '';
$desttextpath   = '';
$tekststring    = 'tegel tegeltje tegeltjeswijsheden';
$afgevangenzoekstring = '';

if ( isset( $zinnen[2] ) ) {
    $filename       = $zinnen[2] . ".png";
    $fileid         = $zinnen[2];
    $desttextpath   = $zinnen[2] . ".txt";
}

if ( $zinnen[1] == TEGELIZR_SELECTOR ) {
 if ( ! file_exists( $outpath.$filename ) ) {
   $afgevangenzoekstring = $zinnen[2];
 }
}

// ===================================================================================================================

echo spitoutheader();

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
<article id="page"  class="resultaat">
  <h1 id="top"><a href="/" title="Maak zelf ook een tegeltje"><?php echo returnlogo(); ?><span>Redactie</span></a></h1>
  <p>Ik houd niet bij wie welk tegeltje gemaakt heeft. Als een tegeltje me niet bevalt, haal ik het weg. Zo kan ik niet zo goed tegen enorme spelfouten. En  tegeltjes die neerkomen op hetzelfde haal ik ook weg, zoals alle variaties op: 'Niet zo leuk als een tegel met een spreuk' of: 'Beter dik in de kist dan een feestje gemist'. <em>Been there, done that</em>. Ook tegeltjes met persoonsnamen houden het meestal niet zo lang vol hier.</p>
  <p>Door de tekst op een tegeltje te zetten verandert er niet opeens iets aan het auteursrecht van de tekst. Het auteursrecht erop valt niet  aan mij toe, noch aan degene de tekst invoerde.</p>
  <p>Wie teksten invoert op deze site moet ermee leren leven dat ik de teksten misschien aanpas. Zo wordt 'Facebook' altijd 'het satanische Facebook' op de tegeltjes. Als je dat niet leuk vindt, jammer.</p>
  <p>Maar goed, nu jij. <a href="/">Maak eens een leuk tegeltje</a>.</p>
  <?php echo wbvb_d2e_socialbuttons($desturl, $titel, TEGELIZR_SUMMARY) ?>
  <?php 
    echo showthumbs(12, '');
    echo TheModalWindow();
    ?>
</article>
<?php

}
// ===================================================================================================================
// er wordt gevraagd om te zoeken
// ===================================================================================================================
elseif ( 
  ( $afgevangenzoekstring ) ||  
  ( ( isset( $_GET[TEGELIZR_ZOEKTERMKEY] ) ) && ( filtertext($_GET[TEGELIZR_ZOEKTERMKEY], false) !== '' ) ) 
  ) {

    global $zoektegeltje;
    global $path;

    $zoektegeltje = filtertext($_GET[TEGELIZR_ZOEKTERMKEY], false);

    $titeltw    = 'Zoek tegeltjes met ' . $zoektegeltje;
    $desturl    = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ZOEKURL . '/?' . TEGELIZR_ZOEKTERMKEY . '=' . $zoektegeltje;
    
    
    $obj        = json_decode(file_get_contents($path . TEGELIZR_ALLES . "/index.txt"), true);
    
    $terms      = explode(" ", $zoektegeltje);
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
<article id="page"  class="resultaat">
  <h1 id="top"><a href="/" title="Maak zelf ook een tegeltje"><span><?php echo returnlogo() . $titel ; ?></span></a></h1>

<?php
    if ( $results ) {
    
        echo '<section id="zoekresultaten"><h2>Je zocht op \'' . $zoektegeltje . "'</h2>";
        echo '<ul class="thumbs results">';
        
        foreach($results as $result) {

            echo getSearchResultItem($result);

        }    

        echo '</ul></section>';

    }
    else {
        echo '<p>Geen tegeltjes gevonden</p>';
    }

    echo '<form method="get" class="search-form" action="' . TEGELIZR_PROTOCOL . $_SERVER["HTTP_HOST"] . '/' . TEGELIZR_ZOEKURL . '/" role="search">
    <meta itemprop="target" "' . TEGELIZR_PROTOCOL . $_SERVER["HTTP_HOST"] . '/' . TEGELIZR_ZOEKURL . '/?zoektegeltje={s}">
    <label for="' . TEGELIZR_ZOEKTERMKEY . '">Zoek opnieuw</label>
    <input itemprop="query-input" type="search" name="' . TEGELIZR_ZOEKTERMKEY . '" id="' . TEGELIZR_ZOEKTERMKEY . '" value="' . $zoektegeltje . '" placeholder="Hier je zoekterm">
    <input type="submit" value="Search">
</form>';        

    
    
	echo wbvb_d2e_socialbuttons($desturl, $titeltw, TEGELIZR_SUMMARY) ?><?php echo showthumbs(12, $zinnen[2]);
	echo TheModalWindow();
	  
  ?>
</article>

<?php
    echo includejs();
?>

<?php
    echo spitoutfooter();



}
// ===================================================================================================================
// er wordt gevraagd om een tegeltje en het bestand bestaat ook al op de server
// ===================================================================================================================
elseif ( ( $zinnen[1] == TEGELIZR_SELECTOR ) && ( file_exists( $outpath.$filename ) ) && ( file_exists( $outpath.$desttextpath ) ) ) {

    global $userip;
    
    $desturl        = '//' . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $zinnen[2];
    $imagesource    = '//' . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_TEGELFOLDER . '/' . $filename;
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
<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php echo htmlheader() ?>
<article id="page"  id="page" class="resultaat" itemscope itemtype="http://schema.org/ImageObject">
    <h1 id="top"><a href="/" title="Maak zelf ook een tegeltje"><?php echo returnlogo(); ?><span><?php echo TEGELIZR_TITLE ?></span></a></h1>

    <a href="<?php echo htmlspecialchars($desturl)?>" class="placeholder"><img src="<?php echo $imagesource ?>" alt="<?php echo $titel ?>" class="tegeltje"  itemprop="contentUrl" width="584" height="584" /><?php
    if ( ( isset( $_GET[TEGELIZR_TRIGGER_KEY] ) ) && ( $_GET[TEGELIZR_TRIGGER_KEY] == TEGELIZR_TRIGGER_VALUE ) ) {
//        echo '<p id="progress_now">&nbsp;</p><div id="progress">&nbsp;</div><div id="progress_bar"><div>&nbsp;</div></div>';
        echo '<p id="progress_now">&nbsp;</p><div id="progress">&nbsp;</div>';
    }    
?></a>

    <h2 itemprop="name"><?php echo $txt_tegeltekst ?></h2>

<?php
    if ( (isset($views[TEGELIZR_VORIGE])) || (isset($views[TEGELIZR_VOLGENDE])) ) {

        echo '<nav>';
        echo isset($views[TEGELIZR_VORIGE]) ? '<a class="vorige" href="' . TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $views[TEGELIZR_VORIGE] . '" title="Bekijk \'' . $views[TEGELIZR_VORIGE_TITEL] . '\'"><span class="pijl">&#10158;</span>' . $views[TEGELIZR_VORIGE_TITEL] . '</a>' : '';
        echo  isset($views[TEGELIZR_VOLGENDE])  ? '<a class="volgende" href="' . TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $views[TEGELIZR_VOLGENDE] . '" title="Bekijk \'' . $views[TEGELIZR_VOLGENDE_TITEL] . '\'">' . $views[TEGELIZR_VOLGENDE_TITEL] . '<span class="pijl">&#10157;</span></a>' : '';
        
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

    
    <p id="leuk">Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>
    <?php


    


    
    echo wbvb_d2e_socialbuttons($desturl, $txt_tegeltekst, TEGELIZR_SUMMARY);
    echo showthumbs(12, $zinnen[2]);

	echo TheModalWindow();
        
        
    ?>
</article>

<?php
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
//            $('#progress_bar div').html('&nbsp;');

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
//            $('#progress_bar div').css('width', Math.round(thelength));


            var opacityDiv = (Math.round( ( ( CURRENTDOCINDEX / TOTALNRDOCUMENTS ) * PROGRESSOPACITY ) * 10) / 10 );

//            $('#momentje').css('opacity', ( 1 - opacityDiv ) );
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
//                $('#progress').remove();
//                $('#progress_bar').remove();
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
<meta name="description" content="">
<meta name="author" content="">
<meta property="og:title" content="<?php echo TEGELIZR_TITLE ?>" />
<meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>" />
<meta property="og:url" content="<?php echo $_SERVER['SERVER_NAME']; ?>" />
<meta property="article:tag" content="<?php echo $tekststring; ?>" />
<meta property="og:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>" />
<title><?php echo TEGELIZR_TITLE ?>- WBVB Rotterdam</title>
<?php echo htmlheader() ?>
<article id="page">
  <h1 id="top"><?php echo returnlogo(); ?><span><?php echo TEGELIZR_TITLE ?></span></h1>
  <?php echo wbvb_d2e_socialbuttons(TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], TEGELIZR_TITLE, TEGELIZR_SUMMARY) ?>
  <p class="lead"> <?php echo TEGELIZR_FORM ?> </p>
  <aside>(maar Paul, <a href="//wbvb.nl/tegeltjes-maken-is-een-keuze/">wat heb je toch met die tegeltjes</a>?)</aside>
  <?php 
	  echo TheForm();
	  echo showthumbs(12); 
    echo TheModalWindow();
    ?>

  </article>

<?php
    echo includejs();
?>

<?php  
    echo spitoutfooter();

}
?>
