rsync -r -a -v --delete '/shared-paul-files/Webs/localhost-multisite/httpdocs/wp-content/themes/wbvb/fonts/' '/shared-paul-files/Webs/localhost-multisite/httpdocs/labs/tegelizr/fonts/';

rsync -r -a -v --delete '/shared-paul-files/Webs/localhost-multisite/httpdocs/wp-content/themes/wbvb/images/vergrootglas.png' '/shared-paul-files/Webs/localhost-multisite/httpdocs/labs/tegelizr/img/vergrootglas.png';

rsync -r -a -v --delete '/shared-paul-files/Webs/localhost-multisite/httpdocs/wp-content/themes/wbvb/images/vergrootglas.svg' '/shared-paul-files/Webs/localhost-multisite/httpdocs/labs/tegelizr/img/vergrootglas.svg';

rsync -r -a -v --delete '/shared-paul-files/Webs/localhost-multisite/httpdocs/wp-content/themes/wbvb/style.css' '/shared-paul-files/Webs/localhost-multisite/httpdocs/labs/tegelizr/css/wbvb.css';

rsync -r -a -v --delete '/shared-paul-files/Webs/localhost-multisite/httpdocs/wp-content/themes/shared-code-for-themes/less/reset-css.less' '/shared-paul-files/Webs/localhost-multisite/httpdocs/labs/tegelizr/less/reset-css.less';

rsync -r -a -v --delete '/shared-paul-files/Webs/localhost-multisite/httpdocs/wp-content/themes/shared-code-for-themes/less/socmed-color-codes.less' '/shared-paul-files/Webs/localhost-multisite/httpdocs/labs/tegelizr/less/socmed-color-codes.less';

rsync -r -a -v --delete '/shared-paul-files/Webs/localhost-multisite/httpdocs/labs/tegelizr/' '/shared-paul-files/Webs/webfaction/webapps/tegelizr/';

rsync -r -a -v --delete '/shared-paul-files/Webs/localhost-multisite/httpdocs/labs/tegelizr/' '/shared-paul-files/Webs/webfaction/webapps/testtegelizr/';


## rm -rfv /shared-paul-files/Webs/webfaction/webapps/tegelizr/tegeltjes/*
## rm -rfv /shared-paul-files/Webs/webfaction/webapps/tegelizr/thumbs/*
## rm -rfv /shared-paul-files/Webs/webfaction/webapps/tegelizr/alle-tegeltjes/*

rsync -r -a -v '/shared-paul-files/Webs/webfaction/webapps/tegelizr/' '/shared-paul-files/Webs/git-repos/tegelizr-source/' ;

rm -rfv /shared-paul-files/Webs/git-repos/tegelizr-source/tegeltjes/*
rm -rfv /shared-paul-files/Webs/git-repos/tegelizr-source/thumbs/*


	