<?php

// test-mail.php

include( "../common.inc.php" );
include( "../mail.inc.php" );

$titel       = 'Je moeder is een testmail!';
$mailcontent = 'Ik vind: Je moeder is een testmail (' . $_SERVER['HTTP_HOST'] . ')';

send_mail( "vanbuuren@gmail.com", MAIL_PREFIX . ": " . $titel, $mailcontent, "From: info@tegelizr.nl" );

echo "mail verstuurd!";
