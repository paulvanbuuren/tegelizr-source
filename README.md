# teglizr-source
Public source code for tegelizr.nl. 
Correct me if I'm wrong. Copy me if I'm right.

## Tegelizr.nl
author:    Paul van Buuren

version:  7.7.1 

## contact:                    
* ([paul@wbvb.nl](paul@wbvb.nl))
* Contact: [wbvb.nl/contact](https://wbvb.nl/contact/)
* Twitter: [@paulvanbuuren](https://twitter.com/paulvanbuuren/)

## Versions
* 7.7.1 - Added TEGELIZR_LAST_1000_IMAGES to limit the nr of images to scan by CRON job. Better text replacements.
* 7.6.6 - CSS: font-swap toegevoegd. Quick fix voor update-script. Socmed-knopjes foetsie.
* 7.6.5 - Print-stijl aangepast: alleen het tegeltje™ afdrukken.
* 7.6.4 - Meer vertaalopties. Wordwrap optioneel. Paginering intelligenter.
* 7.6.3 - Poging emoji uit te bannen. Met vuur.
* 7.6.2 - Minify HTML; extra teksten uit vertaling; minify JS.
* 7.6.1 - Accessibility issues met contrast. Documentstructuur aangepast.
* 7.5.1 - Styling in een apart bestand, zodat meerdere websites eigen stijl kunnen krijgen
* 7.4.8 - Toegankelijkheidsdingetje. Label toegevoegd. Unieke ID.
* 7.4.7 - Autofocus weggehaald. Form voor tegeltjes op juiste plek.
* 7.4.6 - Op alle-tegeltjespagina socialbuttons weggepoetst.
* 7.4.5 - Code publiek gemaakt op github.
* 7.4.4 - CSS bugs.
* 7.4.3 - Zoekresultaat bijgewerkt.
* 7.4.2 - CSS bugs.
* 7.4.1 - Layout voor alle-tegeltjespagina bijgewerkt.
* 7.3.3 - Meta-tag voor description bijgewerkt.
* 7.3.2 - Twitter-card gefikst. Zoekresultaat bijgewerkt.
* 7.3.1 - Paging in de tegeltjes.
* 7.2.4 - Kleine tekstuele wijzigingen.
* 7.2.3 - Andere tegelkleur.
* 7.2.2 - Form elements, list items with images.
* 7.2.1 - Header fonts en tegelizr-logo
* 7.1.1 - Inline css voor performance.
* 7.1.0 - CSS structuur aangepast.
* 7.0.3 - Favicon. Tegeldatum.
* 7.0.2 - Alle tegeltjes tonen.
* 7.0.1 - CSS bijgewerkt, zoekdata bijgwerkt, zoekmogelijkheid hersteld.
* 7.0.0 - Filecheck aangepast. Navigatie retour. Wachtanimatie toegevoegd.
* 6.0.0 - Geklungel met zoeken vanuit de adresbalk in Chrome
* 5.3.2 - GA-tracking gecorrigeerd
* 5.3.1 - GA-tracking gecorrigeerd
* 5.3.0 - GA-tracking gecorrigeerd
* 5.2.1 - Regex verbeterd
* 5.2.0 - pop-up voor tegeldetailpagina's. Regex verbeterd
* 5.1.0 - ander pijltje
* 5.0.1 - bugfixes
* 5.0 - sorteermogelijkheid ingebouwd voor alle-tegeltjes-pagina
* 4.2.0 - github link weggesloopt
* 4.1.0 - JS: tonen van top-link afhankelijk van scrollpositie
* 4.0.5 - small css fixes
* 4.0.4 - bugfixes
* 4.0.3 - andere weergave van zoekresultaten
* 4.0.2 - max breedte voor navigatielinks vorige en volgende
* 4.0.1 - bugfixes
* 4.0 - navigatie voor vorige / volgende. JS gebaseerde genereren van overzichtspagina. Andere zoekresultaten.
* 3.2.3 - ster-rating bugfixes 
* 3.2.2 - ster-rating bugfixes 
* 3.2.1 - ster-rating bugfixes 
* 3.2.0 - ster-rating toegevoegd 
* 3.1.0 - Zoekfilter toegevoegd
* 3.0.2 - Bugfixes
* 3.0.1 - Bugfixes
* 3.0 - Aantal keer bekeken volledig herzien, aanmaken van pagina met alle tegeltjes
* 2.0.3 - Spaties, geen tabs. By popular demand...
* 2.0.2 - Sitetitel gewijzigd. En <title> voor een tegel
* 2.0.1 - footer weer witte achtergrond gegeven
* 2.0 - complete herziening van de uitlijning in generate.php, bugfixes
* 1.11 - verwijzing naar Github toegevoegd in footer
* 1.10 - kleine stijlaanpassing voor soc-med-knoppen en cijfers toegevoegd aan eerste karakters in input
* 1.9 - @-teken toegevoegd aan toegestane tekens
* 1.8 - CSS correctie op footer links
* 1.7 - view counter toegevoegd
* 1.6 - = teken toegevoegd aan toegestane tekens
* 1.5 - redactiepagina toegevoegd; blokken in footer responsive
* 1.4 - blokken in footer naast elkaar
* 1.3 - mogelijk tonen van alle tegeltjes toegevoegd
* 1.2 - URL gecorrigeerd voor deelknoppen op default pagina
* 1.1 - File clean up
* 1.0 - First checkin

## Must do:
* in txt alleen relatieve URL voor thumb opslaan. Niet de volledige filename met serverfolder. 
* pagina voor alle tegeltjes bijwerken

## To do:
* Mogelijkheid een tegeltje te verwijderen als mislukt. Deze keuze direct na genereren
* Zoeken vanuit de adresbalk foutloos. Niet laten doorschieten naar genereren van een tegeltje
* ~~paging voor thumbnailoverzicht~~ Begin mee gemaakt. Dit nog doen verbeteren met javascript. En voor de alle-tegeltjespagina mogelijkheid om aantal tegels per pagina te kiezen. 
* paging voor ALLE tegeltjes-pagina?
* Zou het niet handiger zijn om een folder /tegeltjes/ te hebben?
* Vertaal de strings voor wordsofwisdomtile.com / tegelizr.nl
* mogelijkheid om breaks te gebruiken
* automatische compressie van de PNGs op de server
* RSS-feed aanmaken.
* sitemap laten genereren en submitten.
* Site kloonbaar maken voor bijvoorbeeld een HMD-plaatjesgenerator.
* Dynamic blur: meer tekst = minder blur.
