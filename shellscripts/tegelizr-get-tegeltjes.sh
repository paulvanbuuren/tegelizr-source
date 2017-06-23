# sh '/shared-paul-files/Webs/git-repos/tegelizr-source/shellscripts/tegelizr-get-tegeltjes.sh'

echo 'De tegeltjes!';
rsync -r -a  --delete  '/shared-paul-files/Backups/webfaction/tegelizr/tegeltjes/' '/shared-paul-files/Webs/git-repos/tegelizr-source/tegeltjes/'

echo 'En dan de thumbs';
rsync -r -a  --delete  '/shared-paul-files/Backups/webfaction/tegelizr/thumbs/' '/shared-paul-files/Webs/git-repos/tegelizr-source/thumbs/'

##echo 'Tenslotte de DB';
##rsync -r -a  --delete  '/shared-paul-files/Backups/webfaction/tegelizr/alle-tegeltjes/' '/shared-paul-files/Webs/git-repos/tegelizr-source/alle-tegeltjes/'

rm -rf /shared-paul-files/Webs/git-repos/tegelizr-source/deleted_files/thumbs/*;

rm -rf /shared-paul-files/Webs/git-repos/tegelizr-source/deleted_files/tegeltjes/*;



echo 'Klaar!';
