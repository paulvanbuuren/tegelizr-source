#!/bin/bash
# sh '/var/www/vhosts/tegelizr.nl/httpdocs/shellscripts/swiffer.sh'

echo "Swiffer hiero"

find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*van-harte-gefeliciteerd*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*hartelijk-gefeliciteerd*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*fijne-samenwerking*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*gefeliciteerd-met-je-verjaardag*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*woordvoerder-oeleh*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*woordvoerder-ouleh*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*gaat-met-pensioen*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*proficiat*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*lokwinske*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*langkous*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*youtube*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*2083*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*snicker*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*linkse-ratten*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*goud-olie-en-drugs*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*landverrader*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*appy-birthday*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*adolf-befbezem*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*amoorah*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*berber*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*buuren*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*marokka*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*lavendelnazi*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*limburgers*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*zuigen*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*anaal-is-gewoon-astronomie*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*aivd-luistert-mee*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*frambozenjam*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*drek-drek*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*lala-lala*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*out-of-office*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*ratelslangen*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*ohammed*" -exec rm -vf {} \;
find /var/www/vhosts/tegelizr.nl/httpdocs/ -type f -name "*http*" -exec rm -vf {} \;

rm /var/www/vhosts/tegelizr.nl/httpdocs/deleted_files/thumbs/*;
rm /var/www/vhosts/tegelizr.nl/httpdocs/deleted_files/tegeltjes/*;

echo "Opgeruimd staat netjes"
