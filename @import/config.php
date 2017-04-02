<?php
	error_reporting(0);
	session_start();

	# sql setting
	define('__DB_HOST__','**SECRET**');
	define('__DB_USERNAME__','**SECRET**');
	define('__DB_PASSWORD__','**SECRET**');
	define('__DB_NAME__','**SECRET**');

	# website setting
	define('__HASH_SALT__', '**SECRET**');
	define('__IS_HTML_COMPRESS__', true);
	define('__IS_DEBUG__', false);
	$admin_list = array('admin', 'safflower');

	# debug mode
	if(__IS_DEBUG__){
		ini_set('display_errors', 'On');
		error_reporting(E_ALL);
	}

	try{
		$pdo = new PDO('mysql:host='.__DB_HOST__.';dbname='.__DB_NAME__, __DB_USERNAME__, __DB_PASSWORD__);
	}catch(exception $e){
		error(444);
	}

	# basic functions
	function redirect($page){
		header('Location: '.$page);
		die;
	}
	function error($code){
		$_SERVER['REDIRECT_STATUS'] = $code;
		require __DIR__.'/../error.php';
		die;
	}
	function secure_hash($value){
		return hash('sha256', hash('sha256', __HASH_SALT__.$value));
	}
	function secure_escape($value, $is_bbcode = false){
		$value = htmlentities($value, ENT_QUOTES, 'UTF-8');
		if($is_bbcode){
			$value = strtr($value, array("\r" => null, "\n" => '<br>'));
			$value = preg_replace('#\[b\](.+)\[/b\]#isU', '<b>$1</b>', $value);
			$value = preg_replace('#\[u\](.+)\[/u\]#isU', '<u>$1</u>', $value);
			$value = preg_replace('#\[i\](.+)\[/i\]#isU', '<i>$1</i>', $value);
			$value = preg_replace('#\[s\](.+)\[/s\]#isU', '<s>$1</s>', $value);
			$value = preg_replace('#\[quote\](.+)\[/quote\]#isU', '<blockquote>$1</blockquote>', $value);
			$value = preg_replace('#\[img\](.+)\[/img\]#isU', '<img src="$1">', $value);
			$value = preg_replace('#\[file\](.+)\[/file\]#isU', '<a href="$1">$1</a>', $value);
			$value = preg_replace('#\[url\](.+)\[/url\]#isU', '<a href="$1" target="_blank">$1</a>', $value);
			$value = preg_replace('#\[code\](.+)\[/code\]#isU', '<code>$1</code>', $value);
			$value = preg_replace('#\[color=(.+)\](.+)\[/color\]#isU', '<span style="color:$1;">$2</span>', $value);
			$value = preg_replace('#\[size=(.+)\](.+)\[/size\]#isU', '<span style="font-size:$1px;">$2</span>', $value);
		}
		return $value;
	}
	function is_number($value){
		return is_numeric($value) && 0 < $value;
	}
	function check_range($value, $min, $max){
		return is_numeric($value) && $min <= $value && $value <= $max;
	}
	function check_length($value, $min, $max){
		$len = mb_strlen($value, 'UTF-8');
		return $min <= $len && $len <= $max;
	}
	# user functions
	function is_login(){
		return isset($_SESSION['username'], $_SESSION['login']) && 
			secure_hash($_SESSION['username'].'|'.ip2long($_SERVER['REMOTE_ADDR'])) === $_SESSION['login'];
	}
	function is_admin(){
		return is_login() && isset($_SESSION['admin']) && $_SESSION['admin'] === true;
	}
	function set_login($username){
		session_regenerate_id(true);
		$_SESSION['username'] = $username;
		$_SESSION['login'] = secure_hash($username.'|'.ip2long($_SERVER['REMOTE_ADDR']));
		global $admin_list;
		if(in_array(strtolower($username), $admin_list, true)){
			$_SESSION['admin'] = true;
		}
	}
	function unset_login(){
		unset($_SESSION['no'], $_SESSION['login'], $_SESSION['admin']);
	}
	function is_username($username){
		return preg_match('/^[a-z0-9_-]{5,20}$/is', $username);
	}
	function is_email($email){
		return check_length($email, 6, 320) && 
			preg_match('/^[a-z0-9_.+-]+@[a-z0-9-]+\.[a-z0-9-.]+$/is', $email);
	}
	function is_password($password){
		return check_length($password, 6, 50);
	}