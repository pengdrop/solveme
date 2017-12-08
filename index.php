<?php
	# config
	require __DIR__.'/@import/config.php';

	# parse url
	$link = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	if($link !== '/' && substr($link, -1) === '/'){
		$link = substr($link, 0, -1);
	}
	$argv = explode('/', strtolower($link));
	$argc = count($argv);

	# url router
	switch($argv[1]){
	case '':
		if($argc !== 2) break;
		check_method('GET') and (include(__DIR__.'/@import/home.php')) or
		error('method');
		die;

	case 'login':
		if($argc !== 2) break;
		check_method('POST') and (include(__DIR__.'/@import/~login.php')) or
		error('method');
		die;

	case 'join':
		if($argc !== 2) break;
		check_method('POST') and (include(__DIR__.'/@import/~join.php')) or
		error('method');
		die;

	case 'logout':
		if($argc !== 3) break;
		check_method('GET') and (include(__DIR__.'/@import/logout.php')) or
		error('method');
		die;

	case 'chall':
		isset($argv[2]) or $argv[2] = '';
		switch($argv[2]){
		case '':
			if($argc !== 2) break;
			check_method('GET') and (include(__DIR__.'/@import/chall.php')) or
			error('method');
			die;

		case 'auth':
			if($argc !== 3) break;
			check_method('POST') and (include(__DIR__.'/@import/~chall.auth.php')) or
			error('method');
			die;

		default:
			if($argc !== 3) break;
			check_method('GET') and (include(__DIR__.'/@import/chall.php')) or
			error('method');
			die;
		}
		break;

	case 'rank':
		if($argc !== 2) break;
		check_method('GET') and (include(__DIR__.'/@import/rank.php')) or
		error('method');
		die;

	case 'status':
		if($argc !== 2) break;
		check_method('GET') and (include(__DIR__.'/@import/status.php')) or
		error('method');
		die;

	case 'writeup':
		isset($argv[2]) or $argv[2] = '';
		switch($argv[2]){
		case '':
			if($argc !== 2) break;
			check_method('GET') and (include(__DIR__.'/@import/writeup.php')) or
			error('method');
			die;

		case 'upload':
			if($argc !== 3) break;
			check_method('GET') and (include(__DIR__.'/@import/writeup.upload.php')) or
			check_method('POST') and (include(__DIR__.'/@import/~writeup.upload.php')) or
			error('method');
			die;

		case 'delete':
			if($argc !== 3) break;
			check_method('GET') and (include(__DIR__.'/@import/writeup.delete.php')) or
			check_method('POST') and (include(__DIR__.'/@import/~writeup.delete.php')) or
			error('method');
			die;

		default:
			if($argc !== 3) break;
			check_method('GET') and (include(__DIR__.'/@import/writeup.read.php')) or
			error('method');
			die;
		}
		break;

	case 'profile':
		isset($argv[2]) or $argv[2] = '';
		switch($argv[2]){
		case '':
			if($argc !== 2) break;
			check_method('GET') and (include(__DIR__.'/@import/profile.php')) or
			error('method');
			die;

		case 'edit':
			if($argc !== 3) break;
			check_method('GET') and (include(__DIR__.'/@import/profile.edit.php')) or
			check_method('POST') and (include(__DIR__.'/@import/~profile.edit.php')) or
			error('method');
			die;

		default:
			if($argc !== 3 || !is_username($argv[2])) break;
			check_method('GET') and (include(__DIR__.'/@import/profile.php')) or
			error('method');
			die;
		}
		break;

	case 'wechall':
		isset($argv[2]) or $argv[2] = '';
		switch($argv[2]){
		case 'validatemail':
			if($argc !== 3) break;
			check_method('GET') and (include(__DIR__.'/@import/wechall/validatemail.php')) or
			error('method');
			die;

		case 'userscore':
			if($argc !== 3) break;
			check_method('GET') and (include(__DIR__.'/@import/wechall/userscore.php')) or
			error('method');
			die;
		}
		break;
	}

	# page not found
	error();
	#error(404);