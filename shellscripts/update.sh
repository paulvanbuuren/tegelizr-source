# sh '/shared-paul-files/Webs/git-repos/tegelizr-source/shellscripts/update.sh' &>/dev/null

rsync -r -a  '/shared-paul-files/Webs/git-repos/tegelizr-source/' '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/' 

rsync -r -a  '/shared-paul-files/Webs/git-repos/tegelizr-source/' '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/' 

rm -rf '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/.codekit-cache/'
rm '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/.gitignore'
rm '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/config.codekit'
rm '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/config.codekit3'


rm -rf '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/thumbs/'
mkdir '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/thumbs/'

rm -rf '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/tegeltjes/'
mkdir '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/tegeltjes/'

    