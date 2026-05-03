# sh '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/shellscripts/update.sh' &>/dev/null

# https://tegelizr.nl/includes/tegeltjesoppoetsen.php
# http://tegelizr.local/includes/tegeltjesoppoetsen.php

# rsync -r -a  '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/' '/Users/paul/shared-paul-files/Webs/webfaction/webapps/testtegelizr/' 
rsync -r -a  '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/' '/Users/paul/shared-paul-files/Webs/webfaction/webapps/tegelizr/' 


# copy to temp dir
rsync -r -a --delete '/Users/paul/shared-paul-files/Webs/git-repos/tegelizr-source/' '/Users/paul/shared-paul-files/Webs/temp/'

# clean up temp dir
rm -rf '/Users/paul/shared-paul-files/Webs/temp/shellscripts/'
rm -rf '/Users/paul/shared-paul-files/Webs/temp/.codekit-cache/'
rm -rf '/Users/paul/shared-paul-files/Webs/temp/.git/'
rm '/Users/paul/shared-paul-files/Webs/temp/config.codekit'
rm '/Users/paul/shared-paul-files/Webs/temp/.config.codekit3'
rm '/Users/paul/shared-paul-files/Webs/temp/.gitignore'
rm '/Users/paul/shared-paul-files/Webs/temp/google07c653d0eb7815ba.html'
rm '/Users/paul/shared-paul-files/Webs/temp/lintme.js'
rm '/Users/paul/shared-paul-files/Webs/temp/js.zip'



rsync -r -a  '/Users/paul/shared-paul-files/Webs/temp/' '/Users/paul/shared-paul-files/Webs/webfaction/webapps/gc_plaatjesgenerator/' 

##	
# ================================================================================================
##	
##	rm -rf '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/.codekit-cache/'
##	rm '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/.gitignore'
##	rm '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/config.codekit'
##	# rm '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/config.codekit3'
##	
# ================================================================================================
##	rm -rf '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/thumbs/'
##	mkdir '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/thumbs/'
##	
##	rm -rf '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/tegeltjes/'
##	mkdir '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/tegeltjes/'
##	
##	rsync -r -a  '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hmd_plaatjesgenerator/' '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/' 
##	rsync -r -a  '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/' '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hvd_plaatjesgenerator/' 
##	
# ================================================================================================
##	rm -rf '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/thumbs/'
##	mkdir '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/thumbs/'
##	
##	rm -rf '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/tegeltjes/'
##	mkdir '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hkd_plaatjesgenerator/tegeltjes/'
##	
# ================================================================================================
##	rm -rf '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hvd_plaatjesgenerator/thumbs/'
##	mkdir '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hvd_plaatjesgenerator/thumbs/'
##	
##	rm -rf '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hvd_plaatjesgenerator/tegeltjes/'
##	mkdir '/Users/paul/shared-paul-files/Webs/webfaction/webapps/hvd_plaatjesgenerator/tegeltjes/'
##	
# ================================================================================================
##	
