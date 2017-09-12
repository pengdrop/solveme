<?php
	# anti csrf
	if($argv[2] === md5($_SESSION['username'])){
		unset_login();
	}

	redirect('/');