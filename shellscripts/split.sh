# sh '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/shellscripts/split.sh'

echo "Splitscript hiero"

echo 'Plaatje op halen';
rsync -va --del -e ssh tegelizr@globaldotcom.nl:/var/www/vhosts/plaatjesgenerator.nl/tegelizr/tegeltjes/ '/Users/paul/shared-paul-files/Webs/vps-transip-paulvanbuuren/plaatjesgenerator.nl/tegelizr/tegeltjes';


rm -rf /Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/tegeltjes;
rm -rf /Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/tegeldb;
rm -rf /Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/thumbs;

rm /Users/paul/shared-paul-files/Webs/vps-transip-paulvanbuuren/plaatjesgenerator.nl/tegelizr/tegeldb/*;

echo "\n\n-----------------------------------------------------------------\n"
echo 'Eerst de folder met plaatjes en txt-bestanden kopieren';
cp -R '/Users/paul/shared-paul-files/Webs/vps-transip-paulvanbuuren/plaatjesgenerator.nl/tegelizr/thumbs/' '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/thumbs'
cp -R '/Users/paul/shared-paul-files/Webs/vps-transip-paulvanbuuren/plaatjesgenerator.nl/tegelizr/tegeltjes/' '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/tegeltjes'

echo 'Dan klonen';
cp -R '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/tegeltjes/' '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/tegeldb'

# in tegeldb alleen de txt bestanden bewaren
echo "\n\n-----------------------------------------------------------------\n"
echo "Plaatjes verwijderen uit DB-folder";
find '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/tegeldb/' -type f -name "*.png" -exec rm -vf {} \;

# uit plaatjesfolder de txt bestanden verwijderen
echo "\n\n-----------------------------------------------------------------\n"
echo "txt-bestanden verwijderen uit plaatjes-folder";
find '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/tegeltjes/' -type f -name "*.txt" -exec rm -vf {} \;

#### 
#### echo "\n\n-----------------------------------------------------------------\n"
#### echo "deze folders naar tegelizr development";
#### echo "\n-Plaatjes ---------------------------------------------------------\n"
#### cp -R '/Users/paul/shared-paul-files/Webs/vps-transip-paulvanbuuren/plaatjesgenerator.nl/tegelizr/tegeltjes/' '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/tegeltjes'
#### echo "\n-txt-bestanden ----------------------------------------------------\n"
#### cp -R '/Users/paul/shared-paul-files/Webs/vps-transip-paulvanbuuren/plaatjesgenerator.nl/tegelizr/tegeldb/' '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/tegeldb'
#### 

rsync -va --del -e ssh '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/tegeldb/' tegelizr@globaldotcom.nl:/var/www/vhosts/plaatjesgenerator.nl/tegelizr/tegeldb;

echo "Opgeruimd staat netjes"
