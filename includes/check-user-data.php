<?php


$INGELOGD	= false;

if ( isset( $_COOKIE["username"] ) ) {

	$password_txt	= isset( $_COOKIE["password"] ) ? $_COOKIE["password"] : '';
	$username		= isset( $_COOKIE["username"] ) ? $_COOKIE["username"] : '';
	$sql  			= "SELECT `username`, `password` FROM `tegelizr`.`users` WHERE `username` = '" . $username . "';";
	$message		= 'cookie ' . $sql;

	/* Select queries return a resultset */
	$result = $mysqli->query( $sql );
	
	if ( $result->num_rows ) {

//		$hash   	=  password_hash( $password, PASSWORD_BCRYPT );

	    // output data of each row
	    while($row = $result->fetch_assoc()) {
			
			echo '<div style="position: fixes; background: white; padding: 1em; border: 1px solid black;"><ul>';
			echo '<li>' . $row["password"] . '</li>';
			echo '<li>' . $password_txt . '</li>';
			echo '</ul></div>';

			if (password_verify( $password_txt, $row["password"] )) {
//			    echo 'Password is valid!';

				$INGELOGD	= true;
				$message	= 'ingelogd';
		
				$sql 		= "UPDATE `tegelizr`.`users` SET last_login=CURRENT_TIMESTAMP WHERE `username` = '" . $username . "';";
				$mysqli->query( $sql );

			}
		}
	}
}
else {
	
	$username   	=  (string) ( isset( $_POST['username'] ) ? $_POST['username'] : ( isset( $_GET['username'] ) ? $_GET['username'] : '' ) );
	$password_txt	=  (string) ( isset( $_POST['password'] ) ? $_POST['password'] : ( isset( $_GET['password'] ) ? $_GET['password'] : '' ) );


	if ( $username && $password_txt ) {
	
		$sql  			= "SELECT `username`, `password` FROM `tegelizr`.`users` WHERE `username` = '" . $username . "';";
		$message		= 'form ' . $sql;
	
		/* Select queries return a resultset */
		$result = $mysqli->query( $sql );
		
		if ( $result->num_rows ) {

		    // output data of each row
		    while($row = $result->fetch_assoc()) {
	
				if (password_verify( $password_txt, $row["password"] )) {
	
					$INGELOGD	= true;
					$message	= 'ingelogd';
			
					$sql 		= "UPDATE `tegelizr`.`users` SET last_login=CURRENT_TIMESTAMP WHERE `username` = '" . $username . "';";
					$mysqli->query( $sql );
				
					// write a cookie
					setcookie("username", $username, $expire_14, "/");
					setcookie("password", $password_txt, $expire_14, "/");
				    
				}
				else {
					$INGELOGD		= false;
					$message		= ': niet ingelogd (' . $sql . ')';
				}
		    }
		}
	}
}


?>