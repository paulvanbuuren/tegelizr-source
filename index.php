<?php

// ===================================================================================================================
// 
//	Tegelizr.nl
//	author:						Paul van Buuren
//	contact:					paul@wbvb.nl / wbvb.nl / twitter.com/paulvanbuuren
//	version:					1.6
//	version description:		
//	1.6 - = teken toegevoegd aan toegestane tekens
//	1.5 - redactiepagina toegevoegd; blokken in footer responsive
//	1.4 - blokken in footer naast elkaar
//	1.3 - mogelijk tonen van alle tegeltjes toegevoegd
//	1.2 - URL gecorrigeerd voor deelknoppen op default pagina
//	1.1 - File clean up
//	1.0 - First checkin
// 
// ===================================================================================================================

include("common.inc.php"); 
	
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="http://wbvb.nl/images/favicon.ico" type="image/x-icon" />
<link rel="publisher" href="https://plus.google.com/u/0/+PaulvanBuuren"/>
<meta name="twitter:card" content="summary"/>
<meta name="twitter:site" content="@paulvanbuuren"/>
<meta name="twitter:domain" content="WBVB"/>
<meta name="twitter:creator" content="@paulvanbuuren"/>
<meta property="og:locale" content="nl_NL" />
<meta property="og:type" content="article" />
<meta property="og:site_name" content="Webbureau Van Buuren Rotterdam" />
<meta property="article:publisher" content="https://www.facebook.com/webbureauvanbuuren" />

<?php 

// ===================================================================================================================
// check of er gevraagd wordt om een tegeltje
// de sleutel is TEGELIZR_SELECTOR 
$url		= $_SERVER['REQUEST_URI'];

$zinnen		= explode('/', parse_url($url, PHP_URL_PATH));
$filename	= '';
$filetxt	= '';

if ( isset( $zinnen[2] ) ) {
	$filename       = $zinnen[2] . ".png";
	$filetxt       	= $zinnen[2] . ".txt";
}



// ===================================================================================================================
// er wordt gevraagd om de tekst over hoe ik alle tegeltjes stuk mag maken
// ===================================================================================================================
if ( ( $zinnen[1] == TEGELIZR_REDACTIE ) ) {
	
	$titel 		= TEGELIZR_TITLE . ' - redactie';
	$desturl	= TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_REDACTIE . '/';


?>
<meta property="og:title" content="<?php echo $titel; ?>" />
<meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>" />
<meta property="og:url" content="<?php echo $desturl; ?>" />
<meta property="article:tag" content="<?php echo TEGELIZR_ALLES; ?>" />
<meta property="og:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>" />

<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?>
<?php echo htmlheader() ?>

<article class="resultaat">
  <h1><a href="/" title="Maak zelf ook een tegeltje"><?php echo returnlogo(); ?>Redactie</a></h1>
  <p>Ik houd niet bij wie welk tegeltje gemaakt heeft. Als een tegeltje me niet bevalt, haal ik het weg. </p>
  <p>Nu jij. <a href="/">Maak eens een leuk tegeltje</a>.</p>
  <?php echo wbvb_d2e_socialbuttons($desturl, $txt_tegeltekst, TEGELIZR_SUMMARY) ?>
  <?php 
	echo showhumbs(12, '');
	?>
  <p id="home"> <a href="/"><?php echo TEGELIZR_BACK ?></a> </p>
</article>
<?php

}
// ===================================================================================================================
// er wordt gevraagd om alle tegeltjes
// ===================================================================================================================
elseif ( ( $zinnen[1] == TEGELIZR_ALLES ) ) {

	$titel 		= TEGELIZR_TITLE . ' - alle tegeltjes';
	$desturl	= TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ALLES . '/';


?>
<meta property="og:title" content="<?php echo $titel; ?>" />
<meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>" />
<meta property="og:url" content="<?php echo $desturl; ?>" />
<meta property="article:tag" content="<?php echo TEGELIZR_ALLES; ?>" />
<meta property="og:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>" />

<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?>
<?php echo htmlheader() ?>

<article class="resultaat">
  <h1><a href="/" title="Maak zelf ook een tegeltje"><?php echo returnlogo(); ?>Alle tegeltjes</a></h1>
  <p>Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>
  <?php echo wbvb_d2e_socialbuttons($desturl, $txt_tegeltekst, TEGELIZR_SUMMARY) ?>
  <?php 
	echo showhumbs(0, '');
	?>
  <p id="home"> <a href="/"><?php echo TEGELIZR_BACK ?></a> </p>
</article>
<?php

}
// ===================================================================================================================
// er wordt gevraagd om een tegeltje en het bestand bestaat ook al op de server
// ===================================================================================================================
elseif ( ( $zinnen[1] == TEGELIZR_SELECTOR ) && ( file_exists( $outpath.$filename ) ) && ( file_exists( $outpath.$filetxt ) ) ) {

	$desturl		= TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $zinnen[2];
	$imagesource	= TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . "/" . TEGELIZR_TEGELFOLDER . "/".$filename;
	
	$json_data		= file_get_contents($outpath.$filetxt);
	$archieftekst	= json_decode($json_data, true);

	$txt_tegeltekst	= preg_replace("/[^a-zA-Z0-9-_\.\, \?\!\(\)\=\-\:\;\'üëïöäéèêç]+/", "", trim($archieftekst['txt_tegeltekst']));

	$titel = TEGELIZR_TITLE . ' - ' . $txt_tegeltekst;

?>
<meta property="og:title" content="<?php echo $titel; ?>" />
<meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>" />
<meta property="og:url" content="<?php echo $desturl; ?>" />
<meta property="article:tag" content="<?php echo $tekststring; ?>" />
<meta property="og:image" content="<?php echo $imagesource ?>" />

<?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?>
<?php echo htmlheader() ?>

<article class="resultaat">
  <h1><a href="/" title="Maak zelf ook een tegeltje"><?php echo returnlogo(); ?><?php echo TEGELIZR_TITLE ?></a></h1>
  <a href="<?=htmlspecialchars($desturl)?>" target="_blank"><img src="<?php echo $imagesource ?>" alt="<?php echo $titel ?>" class="tegeltje" /></a>
  <p>Leuk? Of kun jij het beter? <a href="/">Maak je eigen tegeltje</a>.</p>
  <?php echo wbvb_d2e_socialbuttons($desturl, $txt_tegeltekst, TEGELIZR_SUMMARY) ?>
  <?php 
	echo showhumbs(24, $zinnen[2]);
	?>
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

<title><?php echo TEGELIZR_TITLE ?> - WBVB Rotterdam</title>
<?php echo htmlheader() ?>


<article>
  <h1><?php echo returnlogo(); ?><?php echo TEGELIZR_TITLE ?></h1>
  <?php echo wbvb_d2e_socialbuttons(TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], TEGELIZR_TITLE, TEGELIZR_SUMMARY) ?>
  <p class="lead"> <?php echo TEGELIZR_FORM ?> </p>
  <aside>(maar Paul, <a href="http://wbvb.nl/tegeltjes-maken-is-een-keuze/">wat heb je toch met die tegeltjes</a>?)</aside>
  <form role="form" id="posterform" name="posterform" action="generate.php" method="get" enctype="multipart/form-data">
    <div class="form-group tekstveld">
      <label for="txt_tegeltekst">Jouw tekst:</label>

      <input type="text" aria-describedby="tekst-tip" pattern="^[a-zA-Z][a-zA-Z0-9-_\.\, \?\!\(\)\=\-\:\;\'üëïöäéèêç]{1,<?php echo TEGELIZR_TXT_LENGTH ?>}$" class="form-control" name="txt_tegeltekst" id="txt_tegeltekst1" required="required" value="<?php echo TEGELIZR_TXT_VALUE ?>" maxlength="<?php echo TEGELIZR_TXT_LENGTH ?>" size="<?php echo TEGELIZR_TXT_LENGTH ?>" autofocus />


      <div role="tooltip" id="tekst-tip">Alleen letters, cijfers en leestekens. Maximale lengte <?php echo TEGELIZR_TXT_LENGTH ?> tekens</div>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo TEGELIZR_SUBMIT ?></button>
  </form>
  <?php 
			echo showhumbs(24);
		?>
</article>
<?php 

	
}
// ===================================================================================================================


// schrijf footer en de Analytics teller
?>
<footer>
	<div id="footer-contact">
  <h3>Contact</h3>
  <ul>
    <li><a href="mailto:paul@wbvb.nl">mail</a></li>
    <li><a href="https://twitter.com/paulvanbuuren">twitter</a></li>
    <li><a href="https://wbvb.nl/">wbvb.nl</a></li>
  </ul>
	</div>
	<div id="footer-about">
  <h3>Over de site</h3>
  <ul>

    <li><a href="<?php echo TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_REDACTIE . '/';?>">redactie</a></li>
    <li><a href="<?php echo TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ALLES . '/';?>">alle tegeltjes</a></li>
    <li><a href="http://wbvb.nl/tegeltjes-maken-is-een-keuze/">waarom tegeltjes</a></li>
  </ul>
	</div>
</footer>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-1780046-36', 'auto');
  ga('send', 'pageview');

</script>
</body>
</html>
