
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
                'update-single-tegeltje.php',
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



