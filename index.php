<?php

// ===================================================================================================================
// 
//    Tegelizr.nl
//    author:                        Paul van Buuren
//    contact:                    paul@wbvb.nl / wbvb.nl / twitter.com/paulvanbuuren
//    version:                    2.0

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

if ( isset( $zinnen[2] ) ) {
    $filename       = $zinnen[2] . ".png";
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

    $titeltw      = 'Zoek tegeltjes met ' . $zoektegeltje;
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
    
        echo '<section id="andere"><h2>Je zocht op \'' . $zoektegeltje . "'</h2>";
        echo '<ul class="thumbs">';
        
        foreach($results as $result) {

            $hashname = seoUrl( $result['file_thumb'] );
            $thumb =  $result['file_thumb'];
            
            echo '<li><a href="/'  . TEGELIZR_SELECTOR . '/' . $hashname . '" title="' . filtertext($result['txt_tegeltekst']) . ' - ' . $result[TEGELIZR_VIEWS] . ' keer bekeken"><img src="/' . TEGELIZR_THUMBS . '/' . $thumb . '" height="' . TEGELIZR_THUMB_WIDTH . '" width="' . TEGELIZR_THUMB_WIDTH . '" alt="' . filtertext($result['txt_tegeltekst']) . '" /></a>'; 
            echo '</li>';

            
        }    

        echo '</ul></section>';

    }
    else {
        echo '<p>Geen tegeltjes gevonden</p>';
    }
?>

  <?php echo wbvb_d2e_socialbuttons($desturl, $titeltw, TEGELIZR_SUMMARY) ?><?php echo showthumbs(12, $zinnen[2]);?>
  <p id="home"> <a href="/"><?php echo TEGELIZR_BACK ?></a> </p>
</article>
<?php



}
// ===================================================================================================================
// er wordt gevraagd om een tegeltje en het bestand bestaat ook al op de server
// ===================================================================================================================
elseif ( ( $zinnen[1] == TEGELIZR_SELECTOR ) && ( file_exists( $outpath.$filename ) ) && ( file_exists( $outpath.$desttextpath ) ) ) {

    $desturl        = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $zinnen[2];
    $imagesource    = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_TEGELFOLDER . '/' . $filename;
    $views          = getviews($outpath.$desttextpath,true);
    $txt_tegeltekst = filtertext($views['txt_tegeltekst']);
    $titel          = $txt_tegeltekst . ' - ' . TEGELIZR_TITLE;

?>
<meta property="og:title" content="<?php echo $titel; ?>" />
<meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>" />
<meta property="og:url" content="<?php echo $desturl; ?>" />
<meta property="article:tag" content="<?php echo $tekststring; ?>" />
<meta property="og:image" content="<?php echo $imagesource ?>" />
<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php echo htmlheader() ?>
<article class="resultaat">
  <h1><a href="/" title="Maak zelf ook een tegeltje"><?php echo returnlogo(); ?><?php echo TEGELIZR_TITLE ?></a></h1>
  <a href="<?php echo htmlspecialchars($desturl)?>" target="_blank"><img src="<?php echo $imagesource ?>" alt="<?php echo $titel ?>" class="tegeltje" /></a>
  <p class="view-counter">(<?php echo $views[TEGELIZR_VIEWS] ?> keer bekeken)</p>
  <p>Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>
  <?php echo wbvb_d2e_socialbuttons($desturl, $txt_tegeltekst, TEGELIZR_SUMMARY) ?><?php echo showthumbs(12, $zinnen[2]);?>
  <p id="home"> <a href="/"><?php echo TEGELIZR_BACK ?></a> </p>
</article>
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
      <input type="text" aria-describedby="tekst-tip" pattern="^[a-zA-Z0-9-_\.\, \?\!\@\(\)\=\-\:\;\'üëïöäéèêç]{1,<?php echo TEGELIZR_TXT_LENGTH ?>}$" class="form-control" name="txt_tegeltekst" id="txt_tegeltekst" required="required" value="<?php echo TEGELIZR_TXT_VALUE ?>" maxlength="<?php echo TEGELIZR_TXT_LENGTH ?>" size="<?php echo TEGELIZR_TXT_LENGTH ?>" autofocus />
      <div role="tooltip" id="tekst-tip">Alleen letters, cijfers en leestekens. Maximale lengte <?php echo TEGELIZR_TXT_LENGTH ?> tekens</div>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo TEGELIZR_SUBMIT ?></button>
  </form>
  <?php echo showthumbs(12); ?></article>
<?php 

    
}
// ===================================================================================================================


echo spitoutfooter();

if ( ( isset( $_GET[TEGELIZR_TRIGGER_KEY] ) ) && ( $_GET[TEGELIZR_TRIGGER_KEY] == TEGELIZR_TRIGGER_VALUE ) ) {
    
    flush();    
    maakoverzichtspagina();
}


?>
