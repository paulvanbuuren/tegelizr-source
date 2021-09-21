<?php

///
// Tegelizr - index.php
// ----------------------------------------------------------------------------------
// geeft de tegeltjes weer in diverse verschijningsvormen. Hoofdscript.
// ----------------------------------------------------------------------------------
// @author  Paul van Buuren
// @license GPL-2.0+
// @version 7.6.4
// @desc.   Meer vertaalopties. Wordwrap optioneel. Paginering intelligenter.
// @link    https://github.com/paulvanbuuren/tegelizr-source
///

ob_start( "sanitize_output" );

$style = 'hmd';

wbvb_set_hsts_policy();

/**
 * Enables the HTTP Strict Transport Security (HSTS) header.
 *
 * @since 1.0.0
 */
function wbvb_set_hsts_policy() { 

	if ( $_SERVER['HTTP_HOST'] == 'tegelizr.nl' || $_SERVER['HTTP_HOST'] == 'www.tegelizr.nl' || $_SERVER['HTTP_HOST'] == 'wordsofwisdomtile.com' ) {

		if ( $_SERVER["HTTPS"] != "on" ) {
			header( "Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] );
			exit();
		}

		// 2 year expiration: 63072000
		header( 'Strict-Transport-Security: max-age=63072000; includeSubDomains; preload' );

	} else {
	}

}

include( "common.inc.php" );

// ===================================================================================================================

$url                  = $_SERVER['REQUEST_URI'];
$zinnen               = explode( '/', parse_url( $url, PHP_URL_PATH ) );
$filename             = '';
$desttextpath         = '';
$tekststring          = 'tegel tegeltje tegeltjeswijsheden';
$afgevangenzoekstring = '';
$respond_with_error   = false;

if ( isset( $zinnen[2] ) ) {
	$filename     = $zinnen[2] . ".png";
	$fileid       = $zinnen[2];
	$desttextpath = $zinnen[2] . ".txt";
}


if ( $zinnen[1] == TEGELIZR_SELECTOR ) {
	if ( ! file_exists( $sourcefiles_tegelplaatjes . $filename ) ) {
		$afgevangenzoekstring = $zinnen[2];

		// De niet-gevonden tegeltjes hoeven niet geindexeerd te worden
		$respond_with_error = true;

	}
} else {


	if (
		( $zinnen[1] !== TEGELIZR_REDACTIE ) &&
		( $zinnen[1] !== TEGELIZR_ALLES ) &&
		( $zinnen[1] !== TEGELIZR_THUMBS ) &&
		( $url !== '/' )
	) {


		$respond_with_error = true;

	}

}

if ( $respond_with_error ) {

	// respond with code 410: 'Gone'
	http_response_code( 410 );

	// schrijf een regel in een logbestand
	$titel = "\n" . date( "Y-m-d" ) . ' - ' . date( "h:i:sa" ) . ' - 404 tegel niet gevonden: ' . $url;
	$fp    = fopen( date( "Y-m-d" ) . '-404.log', 'a' );//opens file in append mode
	fwrite( $fp, $titel );
	fclose( $fp );

}

fn_look_for_bad_guys();
delete_tegeltje();
check_status();


// ===================================================================================================================
// paging
$defaultrecords = DEFAULT_AANTAL_TEGELS;

$pagenumber = intval( isset( $_POST['pagenumber'] ) ? $_POST['pagenumber'] : ( isset( $_GET['pagenumber'] ) ? $_GET['pagenumber'] : '1' ) );
if ( ( intval( $pagenumber ) < 1 ) || ( intval( $pagenumber ) > 1000 ) ) {
	$pagenumber = 1;
}
$max_items = intval( isset( $_POST['max_items'] ) ? $_POST['max_items'] : ( isset( $_GET['max_items'] ) ? $_GET['max_items'] : $defaultrecords ) );

$startrecs = ( ( $pagenumber - 1 ) * $max_items );
if ( intval( $startrecs ) < 0 ) {
	$startrecs = 0;
}
$endrecs = ( $startrecs + $max_items );

// ===================================================================================================================

echo spitoutheader();

// ===================================================================================================================
// er wordt gevraagd om de tekst over hoe ik alle tegeltjes stuk mag maken
// ===================================================================================================================
if ( ( $zinnen[1] == TEGELIZR_REDACTIE ) ) {

	$titel   = TEGELIZR_TITLE . ' - redactie';
	$desturl = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_REDACTIE . '/';


	?>
    <meta name="description" content="<?php echo $titel . ' - ' . TEGELIZR_METADESC ?>">
    <meta property="og:title" content="<?php echo $titel; ?>"/>
    <meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>"/>
    <meta name="twitter:title" content="<?php echo $titel; ?>"/>
    <meta name="twitter:description" content="<?php echo TEGELIZR_SUMMARY ?>"/>
    <meta name="twitter:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>"/>
    <meta property="og:url" content="<?php echo $desturl; ?>"/>
    <meta property="article:tag" content="<?php echo TEGELIZR_ALLES; ?>"/>
    <meta property="og:image"
          content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>" /><?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php get_end_htmlheader(); ?>
    <article id="page" class="resultaat">
		<?php ip_waarschuwing(); ?>
        <h1 id="top"><a href="/"><span>Redactie</span></a></h1>
        <p>Deze website is
            gemaakt door mij, <a href="https://wbvb.nl/">Paul van Buuren.</a></p>
        <p>Per gemaakt tegeltje noteer ik het IP-adres, zodat ik de mogelijkheid heb om na wangedrag plurken en
            booswichten te weren. Als een tegeltje me niet bevalt, haal ik het weg.
            Zo kan ik niet zo goed tegen enorme spelfouten.
            En tegeltjes die neerkomen op hetzelfde haal ik ook weg, zoals alle variaties op: 'Niet zo leuk als een
            tegel met een spreuk' of: 'Beter dik in de kist dan een feestje gemist'.
            <em>Been there, done that</em>. Ook tegeltjes
            met persoonsnamen houden het meestal niet zo lang vol hier.</p>
        <p>
        <blockquote>Sorry, als je mijn site gebruikt, heb je mijn nare gevoel voor humor voor lief te nemen.
            <cite>Paul</cite></blockquote>
        Door de tekst op een tegeltje te zetten
        verandert er niet opeens iets aan het auteursrecht van de tekst. Het auteursrecht erop valt niet aan mij
        toe,
        noch aan degene de tekst invoerde.</p>
        <p>Wie teksten invoert op deze site moet ermee leren leven dat ik de
            teksten misschien aanpas. Zo wordt 'Facebook' altijd 'het satanische Facebook' op de tegeltjes. Lees <a
                    href="https://wbvb.nl/tegelizr-nl-en-de-cultuuroorlogen/">wat ik doe om
                het hier leuk te houden</a> voor jou en mij.</p>
        <p>Mail je vragen naar <a href="mailto:tegelizr@wbvb.nl">tegelizr@wbvb.nl</a>.</p>
        <p>Maar goed, nu jij. Maak eens een leuk tegeltje.</p>
		<?php echo wbvb_d2e_socialbuttons( $desturl, $titel, TEGELIZR_SUMMARY ) ?>
		<?php
		echo TheForm();
		echo showthumbs( DEFAULT_AANTAL_TEGELS, '', $pagenumber );
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
	( ( isset( $_GET[ TEGELIZR_ZOEKTERMKEY ] ) ) && ( filtertext( $_GET[ TEGELIZR_ZOEKTERMKEY ], false ) !== '' ) )
) {

	global $zoektegeltje;
	global $path;

	$titel   = 'ZOEKEN is stuk';
	$desturl = '';
	$titeltw = '';
	$results = array();

	if ( $afgevangenzoekstring ) {
		$zoektegeltje = filtertext( $afgevangenzoekstring, false );
	} else {
		$zoektegeltje = filtertext( $_GET[ TEGELIZR_ZOEKTERMKEY ], false );
	}

	$titeltw = 'Zoek tegeltjes met ' . $zoektegeltje;
	$desturl = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_ZOEKURL . '/?' . TEGELIZR_ZOEKTERMKEY . '=' . $zoektegeltje;


	$obj = json_decode( file_get_contents( TEGELIZR_ALL_DB ), true );

	$terms   = explode( " ", $zoektegeltje );
	$results = array_filter( $obj, function ( $x ) use ( $terms ) {
		foreach ( $terms as $term ) {
			if ( isset( $x["txt_tegeltekst"] ) && stripos( "-" . filtertext( strtolower( $x["txt_tegeltekst"] ), false ), strtolower( $term ) ) ) {
				return true;
			}
		}

		return false;
	} );

	if ( count( $results ) ) {
		if ( count( $results ) > 1 ) {
			$titel = count( $results ) . " tegeltjes gevonden";
		} else {
			$titel = count( $results ) . " tegeltje gevonden";
		}
	} else {
		$titel = "Niets gevonden voor '" . $zoektegeltje . "'";
	}


	function sortByOrder( $a, $b ) {
		return $a['file_name'] - $b['file_name'];
	}


	?>
    <meta property="og:title" content="<?php echo $titeltw; ?>"/>
    <meta name="description" content="<?php echo $titeltw . ' -  ' . TEGELIZR_METADESC ?>">
    <meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>"/>
    <meta name="twitter:title" content="<?php echo $titeltw; ?>"/>
    <meta name="twitter:description" content="<?php echo TEGELIZR_SUMMARY ?>"/>
    <meta name="twitter:image" content="<?php echo $imagesource ?>"/>
    <meta property="og:url" content="<?php echo $desturl; ?>"/>
    <meta property="article:tag" content="<?php echo $tekststring; ?>"/>
    <meta property="og:image"
          content="<?php echo $imagesource ?>" /><?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php get_end_htmlheader(); ?>
    <article id="page" class="resultaat">
	<?php ip_waarschuwing(); ?>

    <h1 id="top"><a href="/"><span><?php echo $titel; ?></span></a></h1><?php

	global $formelementcounter;

	$formelementcounter ++;
	$suffix = '-' . $formelementcounter;

	if ( $results ) {

		echo '<section id="zoekresultaten"><h2>Je zocht op \'' . $zoektegeltje . "'</h2>";
		echo '<ul class="thumbs results">';

		foreach ( $results as $result ) {

			echo getSearchResultItem( $result, false );

		}

		echo '</ul></section>';

	} else {
		echo '<p>Geen tegeltjes gevonden</p>';
	}

	echo '<form method="get" class="search-form" action="' . TEGELIZR_PROTOCOL . $_SERVER["HTTP_HOST"] . '/' . TEGELIZR_ZOEKURL . '/" role="search">
    <meta itemprop="target" "' . TEGELIZR_PROTOCOL . $_SERVER["HTTP_HOST"] . '/' . TEGELIZR_ZOEKURL . '/?zoektegeltje={s}">
    <label for="' . TEGELIZR_ZOEKTERMKEY . $suffix . '">Zoek opnieuw</label>
    <input itemprop="query-input" type="search" name="' . TEGELIZR_ZOEKTERMKEY . '" id="' . TEGELIZR_ZOEKTERMKEY . $suffix . '" value="' . $zoektegeltje . '" placeholder="Hier je zoekterm">
    <input type="submit" value="Search">
</form>';

	echo wbvb_d2e_socialbuttons( $desturl, $titeltw, TEGELIZR_SUMMARY );
	echo TheForm();
	echo showthumbs( DEFAULT_AANTAL_TEGELS, $zinnen[2], $pagenumber );
	echo TheModalWindow();

	?></article><?php
	echo includejs();
	echo spitoutfooter();

}
// ===================================================================================================================
// er wordt gevraagd om een tegeltje en het bestand bestaat ook al op de server
// ===================================================================================================================
elseif ( ( $zinnen[1] == TEGELIZR_SELECTOR ) && ( file_exists( $sourcefiles_tegelplaatjes . $filename ) ) && ( file_exists( $sourcefiles_tegeldb . $desttextpath ) ) ) {

	global $userip;

	$desturl        = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $zinnen[2] . '/';
	$canonical      = TEGELIZR_PROTOCOL . $PREFERED_DOMAIN . '/' . TEGELIZR_SELECTOR . '/' . $zinnen[2] . '/';
	$imagesource    = TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_TEGELPLAATJESFOLDER . '/' . $filename;
	$views          = getviews( $sourcefiles_tegeldb . $desttextpath, true );
	$txt_tegeltekst = isset( $views['txt_tegeltekst'] ) ? filtertext( $views['txt_tegeltekst'], true ) : '';


	$total_points = isset( $views[ TGLZR_TOTAL_POINTS ] ) ? $views[ TGLZR_TOTAL_POINTS ] : 0;
	$dec_avg      = isset( $views[ dec_avg ] ) ? $views[ dec_avg ] : 0;
	$rounded_avg  = isset( $views[ rounded_avg ] ) ? $views[ rounded_avg ] : 0;
	$nr_of_votes  = isset( $views[ TGLZR_NR_VOTES ] ) ? $views[ TGLZR_NR_VOTES ] : 0;

	$titel    = $txt_tegeltekst . ' - ' . TEGELIZR_TITLE;
	$canvote  = true;
	$disabled = '';
	$legend   = 'Hoeveel sterren is dit tegeltje waard?';

	if ( isset( $views[ $userip ] ) ) {
		$legend   = 'Gemiddelde waardering';
		$canvote  = false;
		$disabled = ' disabled="disabled"';
	}

	?>
    <meta property="og:title" content="<?php echo $titel; ?>"/>
    <link rel="canonical" href="<?php echo $canonical ?>"/>
    <meta name="description" content="<?php echo $txt_tegeltekst . ' - ' . TEGELIZR_METADESC ?>">
    <meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>"/>
    <meta name="twitter:title" content="<?php echo $titel; ?>"/>
    <meta name="twitter:description" content="<?php echo TEGELIZR_SUMMARY ?>"/>
    <meta name="twitter:image" content="<?php echo $imagesource ?>"/>
    <meta property="og:url" content="<?php echo $desturl; ?>"/>
    <meta property="article:tag" content="<?php echo $tekststring; ?>"/>
    <meta property="og:image"
          content="<?php echo $imagesource ?>"/><?php echo "<title>" . $titel . " - WBVB Rotterdam</title>"; ?><?php get_end_htmlheader(); ?>
    <article id="page" id="page" class="resultaat" itemscope itemtype="http://schema.org/ImageObject">
		<?php ip_waarschuwing(); ?>

        <h1 id="top"><a
                    href="/"><span><?php echo $txt_tegeltekst ?></span></a></h1><a
                href="<?php echo htmlspecialchars( $desturl ) ?>" class="placeholder"><img
                    src="<?php echo $imagesource ?>" alt="<?php echo $titel ?>" class="tegeltje" itemprop="contentUrl"
                    width="584" height="584"/></a><?php
		echo '<nav id="navnextprev">';

		if ( ( isset( $views[ TEGELIZR_VORIGE ] ) ) || ( isset( $views[ TEGELIZR_VOLGENDE ] ) ) ) {
			echo isset( $views[ TEGELIZR_VORIGE ] ) ? '<a class="vorige" href="' . TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $views[ TEGELIZR_VORIGE ] . '"><span class="pijl">&#10158;</span>' . $views[ TEGELIZR_VORIGE_TITEL ] . '</a>' : '';
			echo isset( $views[ TEGELIZR_VOLGENDE ] ) ? '<a class="volgende" href="' . TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . '/' . TEGELIZR_SELECTOR . '/' . $views[ TEGELIZR_VOLGENDE ] . '">' . $views[ TEGELIZR_VOLGENDE_TITEL ] . '<span class="pijl">&#10157;</span></a>' : '';
		}
		echo '&nbsp;</nav>';

		if ( DO_RATING ) {


			?>
            <ul itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <li class="view-counter"><?php echo $views[ TEGELIZR_VIEWS ] ?> keer bekeken</li><?php
// ===================================================            
			if ( intval( $total_points > 0 ) ) {
				?>
                <li>Totaalscore: <span itemprop="ratingValue"><?php echo round( $dec_avg, 2 ) ?></span></li>
                <li>Aantal stemmen: <span itemprop="ratingCount"><?php echo $nr_of_votes ?></span></li>
                <li>Gemiddeld <span class="avaragerating"><?php echo $rounded_avg ?></span> uit <span
                        itemprop="bestRating"><?php echo TEGELIZR_AANTAL_STERREN ?></span></li><?php
			}
			if ( ! $canvote ) { ?>
                <li>Je kunt niet meer stemmen. Je hebt
                dit <?php echo ( $views[ $userip ] > 1 ) ? $views[ $userip ] . ' ' . TEGELIZR_RATING_UNITY : $views[ $userip ] . ' ' . TEGELIZR_RATING_UNITY_S; ?>
                gegeven</li><?php }  // ======================================== ?></ul><?php

			if ( $canvote ) {
				?>
                <form role="form" id="star_rating" name="star_rating" action="/includes/sterretjes.php" method="get" enctype="multipart/form-data">
                <fieldset class="rate_widget">
                <legend class="result"><?php echo $legend ?></legend>

				<?php
			}
			?>

            <div class="rating" id="<?php echo $fileid ?>">
				<?php
				$i = 0;

				while ( $i < TEGELIZR_AANTAL_STERREN ):

					$lekey = ( TEGELIZR_AANTAL_STERREN - $i );

					echo '<input type="radio" name="' . TEGELIZR_RATING_VOTE . '" value="' . $lekey . '"  id="' . TEGELIZR_RATING_VOTE . '' . $lekey . '" class="star_' . $lekey;

					$cd = ' class="mag_klikbaar"';

					if ( ( $dec_avg > 0 ) && ( $dec_avg > $lekey ) ) {
						if ( $disabled ) {
							$cd = ' class="waardering"';
						} else {
							$cd = ' class="waardering mag_klikbaar"';
						}
						echo ' waardering';
					} else {
					}
					echo '"';

					if ( $lekey == 1 ) {
						echo ' required="required"';
					}
					if ( $dec_avg == $lekey ) {
						echo ' checked="checked"';
					}

					echo $disabled . ' /><label for="' . TEGELIZR_RATING_VOTE . '' . $lekey . '"' . $cd . ' data-starvalue="' . $lekey . '">' . $lekey . '</label>';
					$i ++;
				endwhile;

				?>
            </div>

			<?php if ( $canvote ) {  // ======================================== ?>
                <input type="hidden" id="widget_id" name="widget_id" value="<?php echo $fileid ?>"/>
                <input type="hidden" id="redirect" name="redirect" value="<?php echo $fileid ?>"/>
                <button type="submit"
                        class="btn btn-primary"<?php echo $disabled ?>><?php echo TEGELIZR_SUBMIT_RATING ?></button>
			<?php } ?>

            <p class="total_votes"></p>

			<?php
			if ( $canvote ) {
				?></fieldset>
                </form>
				<?php
			}

		}

		echo TXT_DOBETTER;


		echo wbvb_d2e_socialbuttons( $desturl, $txt_tegeltekst, TEGELIZR_SUMMARY );
		echo TheForm();
		echo showthumbs( DEFAULT_AANTAL_TEGELS, $zinnen[2], $pagenumber );
		echo TheModalWindow();


		?>
    </article>

	<?php
	echo includejs();
	?>

    <script>

        $(document).ready(function () {

            $('.rate_widget').each(function (i) {
                var widget = this;

                var out_data = {
                    widget_id: $(widget).attr('id'),
                    fetch: 1
                };

                $.post(
                    '/includes/sterretjes.php',
                    out_data,
                    function (INFO) {
                        $(".result").data('fsr', INFO);
                        console.log(' 1 Gezet! ');
                        set_votes(widget);
                    },
                    'json'
                );
            });



			<?php // ===================================================================================================================


			if ( ( isset( $_GET[ TEGELIZR_TRIGGER_KEY ] ) ) && ( $_GET[ TEGELIZR_TRIGGER_KEY ] == TEGELIZR_TRIGGER_VALUE ) ) {

			?>

            var theStiekemeURL = $('.placeholder').attr('href');

            function ToggleHide(showhide) {
                $('#home').toggle(showhide);
                $('#andere').toggle(showhide);
                $('footer').toggle(showhide);
                $('nav').toggle(showhide);
                $('#leuk').toggle(showhide);
                $('#star_rating').toggle(showhide);
                $('.social-media').toggle(showhide);
                $('[itemprop="aggregateRating"]').toggle(showhide);

                if (showhide) {

                    // toon de output
                    $('.placeholder').attr('href', theStiekemeURL);
                    $('#top a').attr('href', '/');
                    $('.placeholder img').css('opacity', '1');
                    $('.placeholder').removeClass('momentje');
                    $('.placeholder').remove('#momentje');
                    $('#progress').remove();
                    $('#progress_now').remove();
                    $('#momentje').remove();

                } else {

                    // toon de placeholder
                    $('.placeholder').removeAttr('href');
                    $('#top a').removeAttr('href');
                    $('.placeholder img').css('opacity', '0');

                    $('.placeholder').addClass('momentje');
                    $('.placeholder').append('<div id="momentje" class="momentje"><h3><?php echo TEGELIZR_JS_BUSY_MSG_HEADER ?></h3><p><?php echo TEGELIZR_JS_BUSY_MSG ?></p></div>');
                    $('#momentje').append('<div id="progress_spinner"><div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div><div class="bounce4"></div></div></div>');


                }
            }

            var vorige = '';
            var volgende = '';
            var widget = $('#progress');
            var myNumber = undefined;
            var PROGRESSLENGTH = 584;
            var PROGRESSOPACITY = 1;
            var TOTALNRDOCUMENTS = 300;
            var CURRENTDOCINDEX = 0;

            ToggleHide(false);

            var oppoetsscript = {
                widget_id: $('#progress').attr('id'),
                fetch: 1
            };

            var data_in = {
                gestart: '',
            };

            $.post(
                '/includes/tegeltjesoppoetsen.php',
                oppoetsscript,
                function (oppoetsscript_out) {
                    TriggerTegelCheck(widget);
                },
                'json'
            );

            function TriggerTegelCheck(index, vorige, txtfile, volgende) {
                'use strict';
                var data_in = {
                    gestart: ''
                };

                $.post(
                    '/includes/tegeltjesoppoetsen.php',
                    data_in,
                    function (data_out) {
                        TegelIsKlaar(data_out);
                    },
                    'json'
                );
            }


            function TegelIsKlaar(data_in) {
                if (data_in.<?php echo TEGELIZR_JS_START_KEY ?> == '<?php echo TEGELIZR_JS_START_MSG ?>') {
                    $('#progress_now').html(data_in.<?php echo TEGELIZR_JS_START_KEY ?> );
                    $('#navnextprev').html(data_in.<?php echo TEGELIZR_JS_NAV_NEXTKEY ?> );
                    ToggleHide(true);
                } else {
                    $('#progress_now').html('<?php echo TEGELIZR_JS_SCRIPTERROR ?> ');
                    ToggleHide(true);
                }

            }
			<?php
			}
			// =================================================================================================================== ?>




            $('.rate_widget label.mag_klikbaar').addClass('is_klikbaar');

            $('.btn.btn-primary').toggle(false);

            $('.is_klikbaar').hover(
                // Handles the mouseover
                function () {
                    $(this).prevAll().andSelf().addClass('ratings_over');
                    $(this).nextAll().removeClass('ratings_vote');
                },
                // Handles the mouseout
                function () {
                    $(this).prevAll().andSelf().removeClass('ratings_over');
                    // can't use 'this' because it wont contain the updated data
                    set_votes($(this).parent());
                }
            );

            // This actually records the vote
            $('.is_klikbaar').bind('click', function () {
                var star = this;
                var widget = $(this).parent();

                $(".result").html('er is geklikt op ' + $(this).html());

                var clicked_data = {
				<?php echo TEGELIZR_RATING_VOTE ?> :
                $(this).data('starvalue'),
                    widget_id
            :
                $(star).parent().attr('id')
            }
                ;
                $.post(
                    '/includes/sterretjes.php',
                    clicked_data,
                    function (INFO) {
                        console.log('er is gescoord');
                        $(".result").data('fsr', INFO);
                        set_votes(widget);
                    },
                    'json'
                );
            });

        });


        function set_votes(widget) {

            var ledinges = $(".result").data('fsr');
            var Dankjewel = ledinges.<?php echo $userip . '_comment' ?>;

            $(".result").html(Dankjewel);

            $('.is_klikbaar').removeClass('is_klikbaar');

            var avg = ledinges.<?php echo rounded_avg ?>;
            var votes = ledinges.<?php echo TGLZR_NR_VOTES ?>;
            var exact = ledinges.<?php echo dec_avg ?>;

            if ($('span[itemprop="ratingValue"]').length) {
                $('span[itemprop="ratingValue"]').html(exact);
                $('span[itemprop="ratingCount"]').html(votes);
                $('span.avaragerating').html(avg);
            } else {
                $('ul[itemprop="aggregateRating"]').append('<li>Totaalscore: <span itemprop="ratingValue">' + exact + '</span></li> <li>Aantal stemmen: <span itemprop="ratingCount">' + exact + '</span></li> <li>Gemiddeld <span class="avaragerating">' + exact + '</span> uit <span itemprop="bestRating">' + exact + '</span></li></li><li>Je kunt niet meer stemmen.</li>');
            }

            $(widget).find('.star_' + avg).prevAll().andSelf().addClass('ratings_vote');
            $(widget).find('.star_' + avg).nextAll().removeClass('ratings_vote');
        }

    </script>

	<?php
	echo spitoutfooter();


} elseif ( ( $zinnen[1] == TEGELIZR_ALLES ) ) {
	$results = json_decode( file_get_contents( TEGELIZR_ALL_DB ), true );

	$titel = count( $results ) . ' tegeltjes';
	?>
    <meta name="description"
          content="<?php echo 'Alle tegeltjes die inmmiddels gemaakt zijn op tegelizr. ' . TEGELIZR_METADESC ?>">
    <meta name="author" content="">
    <meta property="og:title" content="<?php echo $titel ?>"/>
    <meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>"/>
    <meta name="twitter:title" content="<?php echo $titel; ?>"/>
    <meta name="twitter:description" content="<?php echo TEGELIZR_SUMMARY ?>"/>
    <meta name="twitter:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>"/>
    <meta property="og:url" content="<?php echo $_SERVER['SERVER_NAME']; ?>"/>
    <meta property="article:tag" content="<?php echo $tekststring; ?>"/>
    <meta property="og:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>"/>
    <title><?php echo TEGELIZR_TITLE ?>- WBVB Rotterdam</title>
	<?php get_end_htmlheader(); ?>
    <article id="page">
		<?php ip_waarschuwing(); ?>

        <h1 id="top"><a href="/"><span><?php echo $titel ?></span></a></h1>
        <p class="lead">Dit zijn de laatste <?php echo count( $results ) ?> tegeltjes die gemaakt zijn via deze
            site.</p>
		<?php
		echo TheForm();

		$results = json_decode( file_get_contents( TEGELIZR_ALL_DB ), true );

		if ( $results ) {

			echo '<ul class="thumbs results">';

			foreach ( $results as $result ) {

				echo getSearchResultItem( $result, false );

			}

			echo '</ul></section>';

		} else {
			echo '<p>Geen tegeltjes gevonden</p>';
		}

		?>
        </p>
		<?php
		echo TheModalWindow();
		?>

    </article>

	<?php
	echo includejs();
	?>

	<?php
	echo spitoutfooter();

} else {
// ===================================================================================================================
// voorpagina
// ===================================================================================================================
	?>
    <meta name="author" content="">
    <meta name="description" content="Voorpagina van tegelizr.nl - <?php echo TEGELIZR_METADESC ?>">
    <meta property="og:title" content="<?php echo TEGELIZR_TITLE ?>"/>
    <meta property="og:description" content="<?php echo TEGELIZR_SUMMARY ?>"/>
    <meta name="twitter:title" content="<?php echo TEGELIZR_TITLE; ?>"/>
    <meta name="twitter:description" content="<?php echo TEGELIZR_SUMMARY ?>"/>
    <meta name="twitter:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>"/>
    <meta property="og:url" content="<?php echo TEGELIZR_PROTOCOL . $_SERVER['SERVER_NAME']; ?>"/>
    <meta property="article:tag" content="<?php echo $tekststring; ?>"/>
    <meta property="og:image" content="<?php echo TEGELIZR_DEFAULT_IMAGE ?>"/><title><?php echo TEGELIZR_TITLE ?>- WBVB
    Rotterdam</title><?php get_end_htmlheader(); ?>
    <article id="page">
	<?php ip_waarschuwing(); ?>
    <h1 id="top"><span><?php echo TEGELIZR_TITLE ?></span>
    </h1><?php echo wbvb_d2e_socialbuttons( TEGELIZR_PROTOCOL . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], TEGELIZR_TITLE, TEGELIZR_SUMMARY ) ?>
    <p class="lead"> <?php echo TEGELIZR_FORM ?> </p><?php
	echo TXT_WATHEBJETOCH;
	echo TheForm();
	echo showthumbs( DEFAULT_AANTAL_TEGELS, '', $pagenumber );
	echo TheModalWindow();
	?></article><?php
	echo includejs();
	echo spitoutfooter();
}


function sanitize_output( $buffer ) {

	$search = array(
		'/\>[^\S ]+/s',     // strip whitespaces after tags, except space
		'/[^\S ]+\</s',     // strip whitespaces before tags, except space
		'/(\s)+/s',         // shorten multiple whitespace sequences
		'/<!--(.|\s)*?-->/' // Remove HTML comments
	);

	$replace = array(
		'>',
		'<',
		'\\1',
		''
	);

	$buffer = preg_replace( $search, $replace, $buffer );

	return $buffer;
}


?>
