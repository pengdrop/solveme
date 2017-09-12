<?php
	# config
	require __DIR__.'/@import/config.php';

	# parse url
	$link = $_SERVER['REQUEST_URI'];
	$param_index = strrpos($link, '?');
	if($param_index !== false){
		$link = substr($link, 0, $param_index);
	}
	if($link !== '/' && substr($link, -1) === '/'){
		$link = substr($link, 0, -1);
	}
	$argv = explode('/', strtolower($link));
	$argc = count($argv);

	# show page
	# ~*.php : post request file
	switch($argv[1]){
	case '':
		if($argc !== 2) break;
		require __DIR__.'/@import/home.php';
		die;

	case 'login':
		if($argc !== 2) break;
		require __DIR__.'/@import/~login.php';
		die;

	case 'join':
		if($argc !== 2) break;
		require __DIR__.'/@import/~join.php';
		die;

	case 'logout':
		if($argc !== 3) break;
		require __DIR__.'/@import/~logout.php';
		die;

	case 'chall':
		isset($argv[2]) or $argv[2] = '';
		switch($argv[2]){
		case '':
			if($argc !== 2) break;
			require __DIR__.'/@import/chall.php';
			die;

		case 'auth':
			if($argc !== 3) break;
			require __DIR__.'/@import/~chall.auth.php';
			die;
		}
		break;

	case 'rank':
		if($argc !== 2) break;
		require __DIR__.'/@import/rank.php';
		die;

	case 'status':
		if($argc !== 2) break;
		require __DIR__.'/@import/status.php';
		die;

	case 'profile':
		isset($argv[2]) or $argv[2] = '';
		switch($argv[2]){
		case '':
			if($argc !== 2) break;
			require __DIR__.'/@import/profile.php';
			die;

		case 'edit':
			if($argc !== 3) break;
			require __DIR__.'/@import/~profile.edit.php';
			require __DIR__.'/@import/profile.edit.php';
			die;

		default:
			if($argc !== 3 || !is_username($argv[2])) break;
			require __DIR__.'/@import/profile.php';
			die;
		}
		break;

	case 'wechall':
		isset($argv[2]) or $argv[2] = '';
		switch($argv[2]){
		case 'validatemail':
			if($argc !== 3) break;
			require __DIR__.'/@import/wechall/validatemail.php';
			die;

		case 'userscore':
			if($argc !== 3) break;
			require __DIR__.'/@import/wechall/userscore.php';
			die;
		}
		break;

	case 'secure_hash_121111':
		isset($argv[2]) or $argv[2] = '';
		die(secure_hash($argv[2]));
		break;
	}

	# page not found
	error(404);