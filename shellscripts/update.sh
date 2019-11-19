# sh '/shared-paul-files/Webs/git-repos/tegelizr-source/shellscripts/update.sh' &>/dev/null

# https://tegelizr.nl/includes/tegeltjesoppoetsen.php
# http://tegelizr.local/includes/tegeltjesoppoetsen.php

# rsync -r -a  '/shared-paul-files/Webs/git-repos/tegelizr-source/' '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/' 
rsync -r -a  '/shared-paul-files/Webs/git-repos/tegelizr-source/' '/shared-paul-files/Webs/webfaction/webapps/tegelizr/' 


# copy to temp dir
rsync -r -a --delete '/shared-paul-files/Webs/git-repos/tegelizr-source/' '/shared-paul-files/Webs/temp/'

# clean up temp dir
rm -rf '/shared-paul-files/Webs/temp/shellscripts/'
rm -rf '/shared-paul-files/Webs/temp/.codekit-cache/'
rm -rf '/shared-paul-files/Webs/temp/.git/'
rm '/shared-paul-files/Webs/temp/config.codekit'
rm '/shared-paul-files/Webs/temp/.config.codekit3'
rm '/shared-paul-files/Webs/temp/.gitignore'


rsync -r -a  '/shared-paul-files/Webs/temp/' '/shared-paul-files/Webs/webfaction/webapps/gc_plaatjesgenerator/' 

##	
# ================================================================================================
##	
##	rm -rf '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/.codekit-cache/'
##	rm '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/.gitignore'
##	rm '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/config.codekit'
##	# rm '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/config.codekit3'
##	
# ================================================================================================
##	rm -rf '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/thumbs/'
##	mkdir '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/thumbs/'
##	
##	rm -rf '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/tegeltjes/'
##	mkdir '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/tegeltjes/'
##	
##	rsync -r -a  '/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/' '/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/' 
##	rsync -r -a  '/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/' '/shared-paul-files/Webs/webfaction/webapps/hvd_plaatjesgenerator/' 
##	
# ================================================================================================
##	rm -rf '/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/thumbs/'
##	mkdir '/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/thumbs/'
##	
##	rm -rf '/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/tegeltjes/'
##	mkdir '/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/tegeltjes/'
##	
# ================================================================================================
##	rm -rf '/shared-paul-files/Webs/webfaction/webapps/hvd_plaatjesgenerator/thumbs/'
##	mkdir '/shared-paul-files/Webs/webfaction/webapps/hvd_plaatjesgenerator/thumbs/'
##	
##	rm -rf '/shared-paul-files/Webs/webfaction/webapps/hvd_plaatjesgenerator/tegeltjes/'
##	mkdir '/shared-paul-files/Webs/webfaction/webapps/hvd_plaatjesgenerator/tegeltjes/'
##	
# ================================================================================================
##	
