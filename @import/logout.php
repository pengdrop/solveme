<?php
	# anti csrf
	$argv[2] === get_logout_link($_SESSION['username']) and unset_login();

	redirect('/');