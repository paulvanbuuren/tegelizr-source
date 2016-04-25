
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
//                console.log(' 1 Gezet! '  ) ;                    
                set_votes(widget);
            },
            'json'
        );
    });

    function set_votes(widget) {

        var ledinges    = $( ".result" ).data('fsr');
//        var Dankjewel   = ledinges.<?php echo $userip . '_comment' ?>;
        var Dankjewel   = 'Dank je wel';
        
        $( ".result" ).html( Dankjewel );

        $('.is_klikbaar').removeClass('is_klikbaar');

        var avg   = ledinges.tglzr_rounded_avg;
        var votes = ledinges.tglzr_TGLZR_NR_VOTES;
        var exact = ledinges.tglzr_dec_avg;

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
    
    
});
