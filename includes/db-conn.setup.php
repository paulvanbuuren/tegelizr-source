<?php


global $mysqli;
global $expire_14;

$sql = "SELECT `username` FROM `users` where `username` = '" . TEGELIZR_ADMIN_UID . "';";

/* Select queries return a resultset */
$result = $mysqli->query( $sql );

if ( $result->num_rows ) {



	// alles normaal
//	echo 'alles normaal: ' . $result->num_rows;
//    printf("Select returned %d rows.\n", $result->num_rows);
    

    /* free result set */
    $result->close();
}
else {

	$username   	=  (string) ( isset( $_POST['username'] ) ? $_POST['username'] : ( isset( $_GET['username'] ) ? $_GET['username'] : '' ) );
	$password   	=  (string) ( isset( $_POST['password'] ) ? $_POST['password'] : ( isset( $_GET['password'] ) ? $_GET['password'] : '' ) );

	// write a cookie
	setcookie("username", $username, $expire_14, "/");

	
	if ( $username === TEGELIZR_ADMIN_UID ) {
	
		$password   	=  password_hash( $password, PASSWORD_DEFAULT );
	
		$mysqli->query("INSERT INTO `tegelizr`.`users` (`username`, `password`, `last_login`) VALUES ('" . TEGELIZR_ADMIN_UID . "', '" . $password . "', CURRENT_TIMESTAMP);");
	
		if ( $mysqli->affected_rows ) {
		}
	}
	else {
		die('<h1>Set Up</h1><form method="post" class="set-up" action="/">
		<fieldset>
			<legend>Kies een wachtwoord</legend>
		    <label for="username">Gebruikersnaam</label>
		    <input type="text" name="username" id="username" value="' . $username . '" placeholder="gebruikersnaam">
			<label for="password">Wachtwoord</label>
			<input id="password" name="password" type="password" value="" />
		    <input type="submit" value="Aanmaken">
		</fieldset>	    
	</form>
	');
	}


}


?>