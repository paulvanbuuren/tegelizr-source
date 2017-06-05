# sh '/shared-paul-files/Webs/git-repos/tegelizr-source/shellscripts/testtegelizr.sh'

echo "Testtegelizr: folder leegmaken"
rm -rf '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/'

mkdir '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/'

echo "Kopieren naar lokale folder"
rsync -r -a  --delete '/shared-paul-files/Webs/git-repos/tegelizr-source/' '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/' 

rm -rf '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/deleted_files/'
rm -rf '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/.codekit-cache/'
rm -rf '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/shellscripts/'
rm -rf '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/.git/'

mkdir '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/deleted_files/'
mkdir '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/deleted_files/tegeltjes/'
mkdir '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/deleted_files/thumbs/'

echo "Alle tegeltjes en thumbs er bij"

echo 'De tegeltjes!';
rsync -r -a  --delete  '/shared-paul-files/Backups/webfaction/tegelizr/tegeltjes/' '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/tegeltjes/'

echo 'En dan de thumbs';
rsync -r -a  --delete  '/shared-paul-files/Backups/webfaction/tegelizr/thumbs/' '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/thumbs/'

curl 'http://tegelizr:8185/includes/tegeltjesoppoetsen.php';

echo "Naar webfaction d'r mee"
rsync -r -a -v --delete /shared-paul-files/Webs/webfaction/webapps/testtegelizr/ paulvanb@paulvanb.webfactional.com:~/webapps/testtegelizr/

echo "Zo, klaar…"