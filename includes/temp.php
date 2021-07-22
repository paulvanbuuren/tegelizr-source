<?php


/**
 *  Rijkshuisstijl (Digitale Overheid) - dossier-helper-functions.php
 *  ----------------------------------------------------------------------------------
 *  functies voor de header boven content in een dossier
 *  ----------------------------------------------------------------------------------
 *
 * @author  Paul van Buuren
 * @license GPL-2.0+
 * @package wp-rijkshuisstijl
 * @version 2.12.17
 * @desc.   Skiplink-structuur aangepast.
 * @link    https://github.com/ICTU/digitale-overheid-wordpress-theme-rijkshuisstijl
 */

$tellertje = 0;

//========================================================================================================
/*
 * Deze functie checkt of op de pagina de context voor een dossier getoond moet worden of niet. Indien ja:
 * tonen van een illustratie
 */
function rhswp_dossier_get_dossiercontext() {

	global $post;

	$is_dossier = true;
	$dossier    = '';
	dodebug_do( ' rhswp_dossier_get_dossiercontext check ' );

	if ( ! taxonomy_exists( RHSWP_CT_DOSSIER ) ) {
		// niks doen, want dossier bestaat niet
		dodebug_do( RHSWP_CT_DOSSIER . ' bestaat niet ' );
		$is_dossier = false;
	}

	if ( ! is_object( $post ) ) {
		// niks doen, want dit is geen post (bijv. 404 page)
		dodebug_do( ' Dit is geen post ' );
		$is_dossier = false;
	}
	if ( is_posts_page() || is_search() ) {
		// niks doen, voor search-pagina, voor berichten-pagina
		dodebug_do( ' Dit is post of search ' );
		$is_dossier = false;
	}

	if ( ! has_term( '', RHSWP_CT_DOSSIER, get_the_id() ) ) {
		// post / page zit in een dossier EN heeft een beleidskleur, dus toon plaatje van beleidskleur
		// zie functie rhswp_check_caroussel_or_featured_img, waar het plaatje getoond wordt
		dodebug_do( ' Deze post heeft geen dossier ' );
		$is_dossier = false;
	}

	if (
		( 'page_toolbox-home.php' == get_page_template_slug( get_the_ID() ) ) ||
		( 'page_digibeter-home.php' == get_page_template_slug( get_the_ID() ) ) ||
		( 'page_showalldossiers-nieuwestyling.php' == get_page_template_slug( get_the_ID() ) ) ||
		( 'page_toolbox-cyberincident.php' == get_page_template_slug( get_the_ID() ) ) ) {
		// toolbox layout: dus geen plaatje tonen
		dodebug_do( ' Verkeerde template' );
		$is_dossier = false;
	}
	dodebug_do( ' rhswp_dossier_get_dossiercontext result "' . $is_dossier . '"' );

	if ( ! $is_dossier ) {
		return false;
	} else {
		// checken of dit een post is en is_single() en of in de URL de juiste dossier-contetxt is meegegeven.
		$posttype  = get_post_type();
		$loop      = rhswp_get_context_info();
		$tellertje = 1;

		if ( 'single' == $loop && get_query_var( RHSWP_CT_DOSSIER ) ) {
			// het is een single en het dossier is uit de queryvar te halen

			if ( get_query_var( RHSWP_DOSSIERPOSTCONTEXT ) ) {
				$url           = get_query_var( RHSWP_DOSSIERPOSTCONTEXT );
				$contextpageID = url_to_postid( $url );
				$dossier_terms = get_the_terms( $contextpageID, RHSWP_CT_DOSSIER );

				$args['markerforclickableactivepage'] = $contextpageID;

				if ( $dossier_terms && ! is_wp_error( $dossier_terms ) ) {
					// het dossier bestaat
					$dossier = array_pop( $dossier_terms );
				}
			} elseif ( get_query_var( RHSWP_CT_DOSSIER ) ) {
				// het is geen single, maar het dossier is uit de queryvar te halen
				$dossier = get_term_by( 'slug', get_query_var( RHSWP_CT_DOSSIER ), RHSWP_CT_DOSSIER );
			}
		} // ( 'single' == $loop && get_query_var( RHSWP_CT_DOSSIER ) )
		elseif ( 'page' == $loop && get_query_var( RHSWP_CT_DOSSIER ) ) {
			// het is een pagina en het dossier is uit de query var te halen
			if (
				( RHSWP_DOSSIERCONTEXTPOSTOVERVIEW == get_query_var( 'pagename' ) ) ||
				( RHSWP_DOSSIERCONTEXTEVENTOVERVIEW == get_query_var( 'pagename' ) ) ||
				( RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW == get_query_var( 'pagename' ) )
			) {
				$dossier = get_term_by( 'slug', get_query_var( RHSWP_CT_DOSSIER ), RHSWP_CT_DOSSIER );
			}

		} // ( 'page' == $loop && get_query_var( RHSWP_CT_DOSSIER ) ) {
		elseif ( RHSWP_CPT_EVENT == $posttype && 'single' == $loop ) {
			// niks doen voor een single event
			return;
		} elseif ( 'archive' == $loop ) {
			// niks doen voor een archive
			return;
		} elseif ( 'category' == $loop ) {
			// niks doen voor een category archive
			return;
		} elseif ( 'tag' == $loop ) {
			// niks doen voor een tag archive
			return;
		} elseif ( 'tax' == $loop ) {
			// het is een andersoortige taxonomie

			if ( is_tax( RHSWP_CT_DOSSIER ) ) {
				// sterker nog, het is een dossierpagina
				$currentID = get_queried_object()->term_id;
				$dossier   = get_term( $currentID, RHSWP_CT_DOSSIER );
			}
		} else {
			// het is iets heeeeeeel anders

			if ( is_singular( 'page' ) || ( is_singular( 'post' ) && ( isset( $wp_query->query_vars['category_name'] ) ) && ( get_query_var( RHSWP_CT_DOSSIER ) ) ) ) {

				// dus:
				// of: het is een pagina maar we kunnen niks uit de queryvar halen
				// of: het is een single bericht en we kunnen iets uit category_name EN iets met de queryvar voor het dossier
				// get the dossier for pages OR for posts for which a context was provided

				$currentID     = $post->ID;
				$dossier_terms = get_the_terms( $currentID, RHSWP_CT_DOSSIER );
				$parentID      = wp_get_post_parent_id( $currentID );
				$parent        = get_post( $parentID );

				if ( $dossier_terms && ! is_wp_error( $dossier_terms ) ) {
					$dossier = array_pop( $dossier_terms );
				}

				if ( is_single() && 'post' == $posttype ) {
					dodebug_do( 'ja, is single en post' );
				}
			} else {
				dodebug_do( 'ja, is single en post maar geen cat noch dossier' );
			}
		}

	}

	return $dossier;

}

//========================================================================================================

/*
 * Voeg een extra class toe aan <body> als er dossiercontext getoond wordt
 */
function rhswp_dossier_append_bodyclass( $classes ) {

	if ( rhswp_dossier_get_dossiercontext() ) {
		$classes[] = 'in-dossier-context';
	}

	return $classes;

}

//========================================================================================================

function rhswp_dossier_get_term_title( $dossier ) {

	$standaardpaginanaam = $dossier->name;
	$headline            = wp_strip_all_tags( get_term_meta( $dossier->term_id, 'headline', true ) );

	// Use term name if empty
	if ( empty( $headline ) ) {
		$headline = $standaardpaginanaam;
	} else {
		if ( is_array( $headline ) ) {
			if ( 'string' == gettype( $headline[0] ) ) {
				$headline = $standaardpaginanaam . ' - ' . $headline[0];
			} else {
				$headline = $standaardpaginanaam;
			}
		} else {
			if ( 'Array' !== $headline ) {
				$headline = $standaardpaginanaam . ' - ' . $headline;
			} else {
				$headline = $standaardpaginanaam;
			}
		}
	}

	if ( is_tax( RHSWP_CT_DOSSIER ) ) {
		$titletag_start = '<h1 class="taxonomy-title">H1 ';
		$titletag_end   = '</h1>';
	}

	return $titletag_start . $headline . $titletag_end;

}

//========================================================================================================

function rhswp_dossier_title_checker() {

	global $post;
	global $wp_query;
	global $tellertje;
	global $wp;

	dodebug_do( 'rhswp_dossier_title_checker start' );

	$dossier = rhswp_dossier_get_dossiercontext();

	if ( ! $dossier ) {
		return;
	}

	$currentID = 0;

	$subpaginas              = '';
	$shownalready            = '';
	$dossier_overzichtpagina = '';
	$parentID                = '';
	$standaardpaginanaam     = $dossier->name;

	$args = array(
		'dossier_overzichtpagina'      => '',
		'menu_voor_dossier'            => '',
		'markerforclickableactivepage' => '',
		'currentpageid'                => '',
		'preferedtitle'                => '',
		'maxlength'                    => 65,
	);


	// dossiercontext tonen als
	// er een taxonomie bekend is en het geen archief is
	if ( $dossier ) {

		$dossierinhoudpagina = '';
		$args['theterm']     = $dossier->term_id;

		echo '<div class="dossier-overview" id="' . ID_DOSSIER_DIV . '"><div class="wrap">';


		$titletag_start        = '<h2 class="taxonomy-title">';
		$titletag_end          = '</h2>';
		$spancurrentpage_start = '<i class="visuallyhidden">' . _x( "You are on this page: ", "Label dossier navigatie", 'wp-rijkshuisstijl' ) . ' </i>';


		// reset the page title
		$args['preferedtitle'] = '';

		if ( is_tax( RHSWP_CT_DOSSIER ) ) {
			// dit is de pagina met informatie over het dossier

			$args['currentpageid'] = $dossier->term_id;

			$dossierinhoudpagina = '<li class="selected case01"><span>' . $spancurrentpage_start . _x( 'Overzicht', 'Standaardlabel voor het menu in de dossiers', 'wp-rijkshuisstijl' ) . '</span></li>';

		} else {
			// dit is een andere pagina,
			$args['currentpageid'] = get_the_id();

			$dossierinhoudpagina = '<li><a href="' . get_term_link( $dossier ) . '">' . _x( 'Overzicht', 'Standaardlabel voor het menu in de dossiers', 'wp-rijkshuisstijl' ) . '</a></li>';

		}


		echo rhswp_dossier_get_term_title( $dossier );


//		$subpaginas .= rhswp_dossier_get_menu( $dossier );
		echo rhswp_dossier_get_menu( $dossier, $args );
//		$subpaginas .= rhswp_dossier_get_links_postsdocumentsevents( $dossier );
		echo rhswp_dossier_get_links_postsdocumentsevents( $dossier, $args );

		if ( $dossierinhoudpagina || $subpaginas ) {
			if ( $tellertje > 1 ) {
				echo '<p class="screen-reader-text">';
				echo sprintf( _n( 'This topic contains %s item.', 'This topic contains %s items.', 'wp-rijkshuisstijl' ), $tellertje );
				echo '</p>';
			}

			echo '<nav aria-label="' . sprintf( _x( "Important pages about %s.", "Label dossier navigatie", 'wp-rijkshuisstijl' ), $dossier->name ) . '"><ul>' . $dossierinhoudpagina . $subpaginas . '</ul></nav>';

		}

		echo '</div>';
		echo '</div>';


	} //  if ( $dossier && ( 'archive' !== $loop ) ) ofwel: dossiercontext tonen als er een taxonomie bekend is en het geen archief is


	// RESET THE QUERY
	wp_reset_query();


}

//========================================================================================================

function rhswp_dossier_get_menu( $dossier ) {


	// alle pagina's in dit dossier ophalen
	$paginas    = '';
	$subpaginas = '';
	$args       = array(
		'post_type' => 'page',
		'tax_query' => array(
			array(
				'taxonomy' => RHSWP_CT_DOSSIER,
				'field'    => 'term_id',
				'terms'    => $dossier->term_id,
			),
		),
	);

	$pages_for_dossier = new WP_query();
	$pages_for_dossier->query( $args );

	if ( $pages_for_dossier->have_posts() ) {

		while ( $pages_for_dossier->have_posts() ) : $pages_for_dossier->the_post();
			$paginas .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
		endwhile;

	}
	$dossier_overzichtpagina = get_field( 'dossier_overzichtpagina', $dossier );
	$menu_voor_dossier       = get_field( 'menu_pages', $dossier );

	$itemsinmenu = [];

	if ( is_object( $dossier_overzichtpagina ) ) {
		$itemsinmenu[] = $dossier_overzichtpagina->ID;
	}

	// als een menu is ingevoerd, sorteer de pagina's
	if ( $menu_voor_dossier ) {

		if ( is_array( $menu_voor_dossier ) ) {

			if ( 'string' == gettype( $menu_voor_dossier[0] ) ) {
				// a string, so we best unserialize it.
				$menu_voor_dossier = maybe_unserialize( $menu_voor_dossier[0] ); // serialize

				if ( is_array( $menu_voor_dossier ) || is_object( $menu_voor_dossier ) ) {
					foreach ( $menu_voor_dossier as $menuitem ):
						$itemsinmenu[] = intval( $menuitem );
					endforeach;
				}
			} else {
				foreach ( $menu_voor_dossier as $menuitem ):
					// this is an object
					$itemsinmenu[] = intval( $menuitem->ID );
				endforeach;
			}
		}

		$args['menu_voor_dossier'] = $itemsinmenu;

	}


	if ( $dossier_overzichtpagina ) {
		// de overzichtspagina is bekend


		// we gaan de inhoudspagina tonen
		$shownalready                    = is_object( $dossier_overzichtpagina ) ? $dossier_overzichtpagina->ID : 0;
		$parentID                        = is_object( $dossier_overzichtpagina ) ? $dossier_overzichtpagina->ID : 0;
		$args['dossier_overzichtpagina'] = is_object( $dossier_overzichtpagina ) ? $dossier_overzichtpagina->ID : 0;

		if ( is_tax( RHSWP_CT_DOSSIER ) ) {
			$args['currentpageid'] = $dossier->term_id;
		}

		$alttitel = get_field( 'dossier_overzichtpagina_alt_titel', $dossier );
		if ( 'Inhoud' !== $alttitel ) {
			$args['preferedtitle'] = sanitize_text_field( $alttitel );
		} else {
			$args['preferedtitle'] = _x( 'Inhoud', 'Standaardlabel voor het 2de item in het dossiermenu', 'wp-rijkshuisstijl' );
		}

		$subpaginas .= rhswp_dossier_get_pagelink( $dossier_overzichtpagina, $args );

	}


	if ( $menu_voor_dossier ) {

		foreach ( $menu_voor_dossier as $menuitem ):
			if ( is_object( $menuitem ) ) {
				if ( ( $parentID !== $menuitem->ID ) && ( $shownalready !== $menuitem->ID ) ) {
					$subpaginas .= rhswp_dossier_get_pagelink( $menuitem, $args );
				}
			}
		endforeach;

	} else {
		// er is geen menu voor dit dossier
		if ( $parentID ) {

			$args['currentpageid'] = $parentID;
			$user                  = wp_get_current_user();
			if ( in_array( 'manage_categories', (array) $user->allcaps ) ) {
				echo '<p style="padding: .2em .5em; background: red; color: white;"><strong>Hallo redactie,<br>Er is nog geen menu ingevoerd voor dit dossier.</strong><br>Dit menu toont nu de directe pagina\'s onder de pagina "<a href="' . get_permalink( $parentID ) . '" style="color: white;">' . get_the_title( $parentID ) . '</a>"</p>';
			}

			$argspages = array(
				'child_of'     => $parentID,
				'parent'       => $parentID,
				'hierarchical' => 1,
				'sort_column'  => 'menu_order',
				'sort_order'   => 'asc'
			);
			$pages     = get_pages( $argspages );

			if ( $pages ) {
				foreach ( $pages as $page ) {
					if ( ( $parentID !== $page->ID ) && ( $shownalready !== $page->ID ) ) {
						$subpaginas .= rhswp_dossier_get_pagelink( $page, $args );
					}
				}
			}
		} else {
			// Geen menu bekend en ook geen parent ID. Haal alle pagina's op uit dit dossier

			$user = wp_get_current_user();
			if ( in_array( 'manage_categories', (array) $user->allcaps ) ) {
				echo '<p style="padding: .2em .5em; background: red; color: white;"><strong>Hallo redactie,<br>Er is nog geen menu ingevoerd voor dit dossier.</strong><br>Dit menu toont nu de directe pagina\'s onder de pagina "<a href="' . get_permalink( $parentID ) . '" style="color: white;">' . get_the_title( $parentID ) . '</a>"</p>';
			}
			$subpaginas .= $paginas;
		}
	}

	return $subpaginas;

}

//========================================================================================================

function rhswp_dossier_get_links_postsdocumentsevents( $term, $args ) {

	global $wp;

	if ( ! $term->term_id ) {
		// alleen met een geldige taxonomie ID gaan we door
		return;
	}

	$subpaginas  = '';
	$current_url = home_url( add_query_arg( array(), $wp->request ) );

	// check for posts -------------------------------------------------------------------------------
	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'tax_query'      => array(
			'relation' => 'AND',
			array(
				'taxonomy' => RHSWP_CT_DOSSIER,
				'field'    => 'term_id',
				'terms'    => $term->term_id
			),
		)
	);

	$wp_queryposts = new WP_Query( $args );

	if ( $wp_queryposts->post_count > 0 ) {

		// er zijn berichten
		$berichten = sprintf( _n( '%s post', '%s posts', $wp_queryposts->post_count, 'wp-rijkshuisstijl' ), $wp_queryposts->post_count );
		$titel     = sprintf( __( '%s for topic %s.', 'wp-rijkshuisstijl' ), $berichten, $term->name );
		$threshold = get_field( 'dossier_post_overview_categor_threshold', 'option' );

		// zijn er meer dan [$threshold] berichten in dit dossier? Dan checken of we aparte categorieen moeten tonen
		if ( intval( $wp_queryposts->post_count ) >= intval( $threshold ) ) {

			$categories = get_field( 'dossier_post_overview_categories', 'option' );

			if ( $categories ) {

				dodebug_do( "rhswp_dossier_title_checker: 'We gaan de loop in.'" );

				// er zijn categorieen ingesteld, dus deze categorieen aflopen en een link maken
				foreach ( $categories as $category ) {

					$theterm = get_term( $category, 'category' );

					$args          = array(
						'post_type'      => 'post',
						'post_status'    => 'publish',
						'posts_per_page' => - 1,
						'tax_query'      => array(
							'relation' => 'AND',
							array(
								'taxonomy' => RHSWP_CT_DOSSIER,
								'field'    => 'term_id',
								'terms'    => $term->term_id
							),
							array(
								'taxonomy' => 'category',
								'field'    => 'slug',
								'terms'    => $theterm->slug,
							),
						)
					);
					$wp_queryposts = new WP_Query( $args );

					if ( $wp_queryposts->post_count > 0 ) {

						$berichten  = sprintf( _n( '%s post', '%s posts', $wp_queryposts->post_count, 'wp-rijkshuisstijl' ), $wp_queryposts->post_count );
						$titel      = sprintf( __( '%s for topic %s and category %s.', 'wp-rijkshuisstijl' ), $berichten, $term->name, $theterm->name );
						$isselected = '';
						$indicator  = '';

						if ( trailingslashit( $current_url ) === trailingslashit( get_term_link( $term->term_id, RHSWP_CT_DOSSIER ) . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/' . RHSWP_DOSSIERCONTEXTCATEGORYPOSTOVERVIEW . '/' . $theterm->slug ) ) {
							$isselected = ' class="selected case02"';
							$indicator  = $spancurrentpage_start;
						}

						$subpaginas .= '<li' . $isselected . '>' .
						               '<a href="' .
						               get_term_link( $term->term_id, RHSWP_CT_DOSSIER ) .
						               RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/' .
						               RHSWP_DOSSIERCONTEXTCATEGORYPOSTOVERVIEW . '/' . $theterm->slug . '/">' . $indicator . $theterm->name . '</a></li>';

					}
				}
			} else {
				// er zijn geen categorieen ingesteld

				$isselected = '';
				$indicator  = '';

				if ( trailingslashit( $current_url ) === trailingslashit( get_term_link( $term->term_id, RHSWP_CT_DOSSIER ) . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW ) ) {
					$isselected = ' class="selected case03"';
					$indicator  = $spancurrentpage_start;
				}

				$subpaginas .= '<li' . $isselected . '><a href="' . get_term_link( $term->term_id, RHSWP_CT_DOSSIER ) . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/">' . $indicator . _x( 'Posts', 'post types', 'wp-rijkshuisstijl' ) . '</a></li>';

			}
		} else {

			// te weinig berichten om ze op te delen in aparte categorieen
			$isselected = '';
			$indicator  = '';

			if ( trailingslashit( $current_url ) === trailingslashit( get_term_link( $term->term_id, RHSWP_CT_DOSSIER ) . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW ) ) {
				$isselected = ' class="selected case04"';
				$indicator  = $spancurrentpage_start;
			}

			$subpaginas .= '<li' . $isselected . '><a href="' . get_term_link( $term->term_id, RHSWP_CT_DOSSIER ) . RHSWP_DOSSIERCONTEXTPOSTOVERVIEW . '/" title="' . $titel . '">' . $indicator . _x( 'Posts', 'post types', 'wp-rijkshuisstijl' ) . '</a></li>';

		}

	}

	// check for documents ---------------------------------------------------------------------------
	$args = array(
		'post_type'      => RHSWP_CPT_DOCUMENT,
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'tax_query'      => array(
			'relation' => 'AND',
			array(
				'taxonomy' => RHSWP_CT_DOSSIER,
				'field'    => 'term_id',
				'terms'    => $term->term_id
			)
		)
	);


	$wp_queryposts = new WP_Query( $args );

	if ( $wp_queryposts->post_count > 0 ) {
		// er zijn documenten gevonden voor dit dossier

		$berichten = sprintf( _n( '%s document', '%s documents', $wp_queryposts->post_count, 'wp-rijkshuisstijl' ), $wp_queryposts->post_count );
		$titel     = sprintf( __( '%s for topic %s.', 'wp-rijkshuisstijl' ), $berichten, $term->name );

		$isselected = '';
		$indicator  = '';

		if ( trailingslashit( $current_url ) === trailingslashit( get_term_link( $term->term_id, RHSWP_CT_DOSSIER ) . RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW ) ) {
			// de gebruiker heeft om het overzicht van documenten voor dit dossier gevraagd
			$isselected = ' class="selected case05"';
			$indicator  = $spancurrentpage_start;
		}

		$subpaginas .= '<li' . $isselected . '><a href="' . get_term_link( $term->term_id, RHSWP_CT_DOSSIER ) . RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW . '/">' . $indicator . _x( 'Documents', 'post types', 'wp-rijkshuisstijl' ) . '</a></li>';

	}

	// check for events ------------------------------------------------------------------------------
	if ( class_exists( 'EM_Events' ) ) {

		$eventargs       = array( RHSWP_CT_DOSSIER => $term->slug, 'scope' => 'future' );
		$eventlist       = EM_Events::output( $eventargs );
		$listitemcounter = substr_count( $eventlist, '<li' ); // 2

		if ( ( intval( $listitemcounter ) < 1 )
		     || $eventlist == get_option( 'dbem_no_events_message' )
		     || $eventlist == get_option( 'dbem_location_no_events_message' )
		     || $eventlist == get_option( 'dbem_category_no_events_message' )
		     || $eventlist == get_option( 'dbem_tag_no_events_message' ) ) {

			// no events

		} else {
			// some events
			$isselected = '';
			$indicator  = '';

			if ( trailingslashit( $current_url ) === trailingslashit( get_term_link( $term->term_id, RHSWP_CT_DOSSIER ) . RHSWP_DOSSIERCONTEXTEVENTOVERVIEW ) ) {
				$isselected = ' class="selected case06"';
				$indicator  = $spancurrentpage_start;
			}

			$subpaginas .= '<li' . $isselected . '><a href="' . get_term_link( $term->term_id, RHSWP_CT_DOSSIER ) . RHSWP_DOSSIERCONTEXTEVENTOVERVIEW . '/">' . $indicator . _x( 'Events', 'post types', 'wp-rijkshuisstijl' ) . '</a></li>';

		}
	}

	return $subpaginas;
}

//========================================================================================================

function rhswp_dossier_get_pagelink( $theobject, $args ) {

	global $wp;
	global $tellertje;

	$tellertje ++;
	$childpages = [];

	if ( ! taxonomy_exists( RHSWP_CT_DOSSIER ) ) {
		return;
	}

	if (
		is_tax() ||
		( RHSWP_DOSSIERCONTEXTPOSTOVERVIEW == get_query_var( 'pagename' ) ) ||
		( RHSWP_DOSSIERCONTEXTEVENTOVERVIEW == get_query_var( 'pagename' ) ) ||
		( RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW == get_query_var( 'pagename' ) )
	) {

		$pagerequestedbyuser = 1;
		//dodebug_do('rhswp_dossier_get_pagelink: is tax, page = ' . $pagerequestedbyuser );
	} elseif ( isset( $args['currentpageid'] ) && $args['currentpageid'] ) {
		$pagerequestedbyuser = $args['currentpageid'];
		//dodebug_do('rhswp_dossier_get_pagelink: set \$pagerequestedbyuser: ' . $args['currentpageid'] );
	} else {

		if ( 'page' == get_post_type() ) {
			// for pages get $pagerequestedbyuser based on current page slug
			$slug = add_query_arg( array(), $wp->request );

			$pagerequestedbyuser = get_postid_by_slug( $slug, 'page' );
			//dodebug_do('rhswp_dossier_get_pagelink: ELSE PAGE set \$pagerequestedbyuser: to get_the_id() / pagerequestedbyuser=' . $pagerequestedbyuser . ' / slug=' . $slug );
		} else {
			$pagerequestedbyuser = get_the_id();
			//dodebug_do('rhswp_dossier_get_pagelink: ELSE set \$pagerequestedbyuser: to get_the_id()' );
		}

	}

	// use alternative title?
	if ( isset( $args['preferedtitle'] ) && $args['preferedtitle'] ) {
		$maxposttitle = $args['preferedtitle'];
	} else {
		$maxposttitle = $theobject->post_title;
	}

	if ( isset( $args['maxlength'] ) && $args['maxlength'] ) {
		$maxlength = $args['maxlength'];
	} else {
		$maxlength = 65;
	}

	if ( strlen( $maxposttitle ) > $maxlength ) {
		$maxposttitle = substr( $maxposttitle, 0, $maxlength ) . ' (...)';
	}

	$current_menuitem_id = isset( $theobject->ID ) ? $theobject->ID : 0;

	$pagetemplateslug = basename( get_page_template_slug( $current_menuitem_id ) );

	$selectposttype = '';
	$checkpostcount = false;
	$addlink        = false;

	// IS GEPUBLICEERD?
	$poststatus = get_post_status( $current_menuitem_id );

	if ( 'page_dossiersingleactueel.php' == $pagetemplateslug ) {
		$selectposttype = 'post';
		$checkpostcount = true;
	} elseif ( 'page_dossier-document-overview.php' == $pagetemplateslug ) {
		$selectposttype = RHSWP_CPT_DOCUMENT;
		$checkpostcount = true;
	} elseif ( 'page_dossier-events-overview.php' == $pagetemplateslug ) {
		$selectposttype = RHSWP_CPT_EVENT;
		$checkpostcount = true;
	} else {
		$selectposttype = '';
		$checkpostcount = false;
		$addlink        = true;
	}

	if ( $poststatus != 'publish' ) {
		$addlink = false;
	}

	if ( $checkpostcount && $selectposttype ) {

		if ( $selectposttype == 'pagina-met-onderliggende-paginas' ) {

			$args    = array(
				'child_of'     => $current_menuitem_id,
				'parent'       => $current_menuitem_id,
				'hierarchical' => 0,
				'sort_column'  => 'menu_order',
				'sort_order'   => 'asc'
			);
			$mypages = get_pages( $args );

			if ( count( $mypages ) > 0 ) {
				$addlink = true;

				// we have child pages. Save this for checking if we are displaying any of its parents
				foreach ( $mypages as $childpage ):
					$childpages[] = $childpage->ID;
				endforeach;

			}
		} else {

			$filter    = get_field( 'wil_je_filteren_op_categorie_op_deze_pagina', $current_menuitem_id );
			$filters   = get_field( 'kies_de_categorie_waarop_je_wilt_filteren', $current_menuitem_id );
			$argsquery = array(
				'post_type' => $selectposttype,
				'tax_query' => array(
					'relation' => 'AND',
					array(
						'taxonomy' => RHSWP_CT_DOSSIER,
						'field'    => 'term_id',
						'terms'    => $args['theterm']
					)
				)
			);

			if ( RHSWP_CPT_EVENT == $selectposttype ) {
				if ( class_exists( 'EM_Events' ) ) {
					$eventlist = EM_Events::output( array( RHSWP_CT_DOSSIER => $args['theterm'] ) );

					if ( $eventlist == get_option( 'dbem_no_events_message' ) ) {
						// er zijn dus geen evenementen
						$addlink = false;
					} else {
						$addlink = true;
					}
				}
			} else {

				if ( $filter !== 'ja' ) {
					// no filtering, no other arguments needed
				} else {
					// yes! Do filtering

					if ( $filters ) {

						$slugs = array();

						foreach ( $filters as $filter ):
							$terminfo = get_term_by( 'id', $filter, 'category' );
							$slugs[]  = $terminfo->slug;
						endforeach;

						$argsquery = array(
							'post_type' => $selectposttype,
							'tax_query' => array(
								'relation' => 'AND',
								array(
									'taxonomy' => RHSWP_CT_DOSSIER,
									'field'    => 'term_id',
									'terms'    => $args['theterm']
								),
								array(
									'taxonomy' => 'category',
									'field'    => 'slug',
									'terms'    => $slugs,
								)
							)
						);
					}
				}

				$wp_query = new WP_Query( $argsquery );

				if ( $wp_query->have_posts() ) {
					if ( $wp_query->post_count > 0 ) {
						$addlink = true;
					}
				}
			}
		}
	} else {
		// no $checkpostcount, no special page templates
	}

	// haal de ancestors op voor de huidige pagina
	$ancestors = get_post_ancestors( $pagerequestedbyuser );

	// check of the parent niet al ergens in het menu voorkomt
	$postparent = wp_get_post_parent_id( $pagerequestedbyuser );

	$komtvoorinderestvanmenu_en_isnietdehuidigepagina = false;

	$spancurrentpage_start = '<i class="visuallyhidden">' . _x( "You are on this page: ", "Label dossier navigatie", 'wp-rijkshuisstijl' ) . ' </i>';

	if ( isset( $args['menu_voor_dossier'] ) && is_array( $args['menu_voor_dossier'] ) ) {
		if ( in_array( $pagerequestedbyuser, $args['menu_voor_dossier'] ) ) {
			// de gevraagde pagina komt voor in het menu
			if ( in_array( $postparent, $args['menu_voor_dossier'] ) ) {
				$komtvoorinderestvanmenu_en_isnietdehuidigepagina = true;
			}
		}
	}

	if (
		( RHSWP_DOSSIERCONTEXTPOSTOVERVIEW == get_query_var( 'pagename' ) ) ||
		( RHSWP_DOSSIERCONTEXTEVENTOVERVIEW == get_query_var( 'pagename' ) ) ||
		( RHSWP_DOSSIERCONTEXTDOCUMENTOVERVIEW == get_query_var( 'pagename' ) )
	) {

		$komtvoorinderestvanmenu_en_isnietdehuidigepagina = true;
	}

	if ( intval( $pagerequestedbyuser ) == intval( $current_menuitem_id ) ) {
		// the user asked for this particular page / post
		return '<li class="selected case07"><span>' . $spancurrentpage_start . $maxposttitle . '</span></li>';
	} else {
		// this is not the currently active page

		if ( $addlink ) {
			// so we should show the link

			if ( isset( $args['markerforclickableactivepage'] ) && $args['markerforclickableactivepage'] == $current_menuitem_id ) {
				// this is requested page itself
				return '<li class="selected case08"><a href="' . get_permalink( $current_menuitem_id ) . '">' . $spancurrentpage_start . $maxposttitle . '</a></li>';

			} elseif ( $current_menuitem_id && isset( $args['dossier_overzichtpagina'] ) && $args['dossier_overzichtpagina'] && in_array( $current_menuitem_id, $ancestors ) && ( $args['dossier_overzichtpagina'] != $current_menuitem_id ) ) {
				// user requested a page that is a child of the current menu item
				return '<li class="selected case09"><a href="' . get_permalink( $current_menuitem_id ) . '">' . $spancurrentpage_start . $maxposttitle . '</a></li>';

			} elseif ( wp_get_post_parent_id( $pagerequestedbyuser ) == $current_menuitem_id && ( ! $komtvoorinderestvanmenu_en_isnietdehuidigepagina ) ) {
				// this is the direct parent of the requested page
				return '<li class="case10"><a href="' . get_permalink( $current_menuitem_id ) . '">' . $spancurrentpage_start . $maxposttitle . '</a></li>';

			} elseif ( in_array( $pagerequestedbyuser, $childpages ) ) {
				// this is a child of the current menu item
				return '<li class="selected case11"><a href="' . get_permalink( $current_menuitem_id ) . '">' . $spancurrentpage_start . $maxposttitle . '</a></li>';

			} else {
				// this menu item should be clickable
				return '<li><a href="' . get_permalink( $current_menuitem_id ) . '">' . $maxposttitle . '</a></li>';

			}
		} // if ( $addlink ) {
	}

}

//========================================================================================================
