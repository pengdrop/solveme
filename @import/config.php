<?php
	error_reporting(0);
	session_start();

	######################################################################################################################
	### basic settings
	######################################################################################################################

	# website settings
	define('__SITE__', [
		'title' => 'Solve Me',
		'description' => 'Do you want to play yet another challenge?',
		'keyword' => 'CTF, Wargame, Security, Hacking',
	]);

	# sql settings
	define('__DB__', [
		'host' => '**secret**',
		'username' => '**secret**',
		'password' => '**secret**',
		'name' => '**secret**',
		'prefix' => 'solveme',
	]);
	$db_prefix = __DB__['prefix'];

	# about security settings
	define('__ADMIN__', [
		'Admin',
		'Safflower'
	]);
	define('__CONTRIB__', [
		'Debukuk'
	]);
	define('__HASH_SALT__', '**secret**');

	# option settings
	define('__IS_HTML_PACK__', true);

	######################################################################################################################
	### basic commands
	######################################################################################################################

	# connect sql
	try{
		$pdo = new PDO('mysql:host='.__DB__['host'].';dbname='.__DB__['name'], __DB__['username'], __DB__['password']);
	}catch(exception $e){
		error('sql');
	}

	# set timezone
	date_default_timezone_set("UTC");
	$pdo->query("SET time_zone='+00:00'");


	######################################################################################################################
	### about http functions
	######################################################################################################################

	function redirect($page){
		header('Location: '.$page);
		die;
	}
	function get_http_header($key){
		$headers = getallheaders();
		return isset($headers[$key]) ? $headers[$key] : false;
	}
	function is_json(){
		return get_http_header('X-Requested-With') === 'XMLHttpRequest';
	}
	function check_method($method){
		return $_SERVER['REQUEST_METHOD'] === $method;
	}
	function error($code = null){
		if($code !== null) $_SERVER['REDIRECT_STATUS'] = $code;
		require __DIR__.'/error.php';
		die;
	}
	function get_url(){
		$_SERVER['REQUEST_SCHEME'] = isset($_SERVER['REQUEST_SCHEME'][0]) ?
										$_SERVER['REQUEST_SCHEME'] :
										isset($_SERVER['HTTPS']) ? 'https' : 'http';
		return $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
	function get_new_cache_link($filename){
		$filepath = $_SERVER['DOCUMENT_ROOT'].'/'.$filename;
		return $filename.(is_file($filepath) ? '?'.filemtime($filepath) : null);
	}
	function output($data){
		if(!is_json()){
			die('<script>history.back();</script>');
		}
		header('Content-Type: application/json; charset=utf-8');
		die(json_encode($data));
	}

	######################################################################################################################
	### convert functions
	######################################################################################################################

	function secure_hash($value){
		return hash('sha256', hash('sha256', __HASH_SALT__.$value));
	}
	function secure_escape($value, $is_bbcode = false){
		$value = htmlentities($value, ENT_QUOTES, 'UTF-8');
		if($is_bbcode){
			$value = strtr($value, ["\r" => null, "\n" => '<br>']);
			$value = preg_replace('#\[b\](.+)\[/b\]#isU', '<b>$1</b>', $value);
			$value = preg_replace('#\[u\](.+)\[/u\]#isU', '<u>$1</u>', $value);
			$value = preg_replace('#\[i\](.+)\[/i\]#isU', '<i>$1</i>', $value);
			$value = preg_replace('#\[s\](.+)\[/s\]#isU', '<s>$1</s>', $value);
			$value = preg_replace('#\[pre\](.+)\[/pre\]#isU', '<pre>$1</pre>', $value);
			$value = preg_replace('#\[quote\](.+)\[/quote\]#isU', '<blockquote>$1</blockquote>', $value);
			$value = preg_replace('#\[img\](.+)\[/assets/img\]#isU', '<img src="$1">', $value);
			$value = preg_replace('#\[file\](.+)\[/file\]#isU', '<a href="$1" download>$1</a>', $value);
			$value = preg_replace('#\[url\](.+)\[/url\]#isU', '<a href="$1" target="_blank">$1</a>', $value);
			$value = preg_replace('#\[code\](.+)\[/code\]#isU', '<code>$1</code>', $value);
			$value = preg_replace('#\[color=(.+)\](.+)\[/color\]#isU', '<span style="color:$1">$2</span>', $value);
			$value = preg_replace('#\[size=(.+)\](.+)\[/size\]#isU', '<span style="font-size:$1px">$2</span>', $value);
		}
		return $value;
	}
	function get_logout_link(){
		return sha1(__HASH_SALT__.$_SESSION['username']);
	}
	function get_chall_link($title){
		return strtr(strtolower($title), ' ', '_');
	}
	function email_encode($email){
		return 'mailto:'.strtr($email, ['@' => '&#64;', '.' => '&#46;']);
	}


	######################################################################################################################
	### about session functions
	######################################################################################################################

	function is_login(){
		return isset($_SESSION['username'], $_SESSION['login']) &&
			secure_hash($_SESSION['username'].'|'.ip2long($_SERVER['REMOTE_ADDR'])) === $_SESSION['login'];
	}
	function is_admin(){
		return is_login() && is_admin_username($_SESSION['username']);
	}
	function set_login($username){
		session_regenerate_id(true);
		$_SESSION['username'] = $username;
		$_SESSION['login'] = secure_hash($username.'|'.ip2long($_SERVER['REMOTE_ADDR']));
	}
	function unset_login(){
		unset($_SESSION['username'], $_SESSION['login'], $_SESSION['admin']);
	}


	######################################################################################################################
	### format check functions
	######################################################################################################################

	function check_length($value, $min, $max){
		$len = mb_strlen($value, 'UTF-8');
		return $min <= $len && $len <= $max;
	}
	function is_username($username){
		return preg_match('/\A[a-z0-9_-]{5,20}\z/i', $username);
	}
	function is_admin_username($username){
		if(!is_username($username)) return false;
		$username = strtolower($username);
		$admin_list = array_map('strtolower', __ADMIN__);
		return in_array($username, $admin_list, true);
	}
	function is_contrib_username($username){
		if(!is_username($username)) return false;
		$username = strtolower($username);
		$contrib_list = array_map('strtolower', __CONTRIB__);
		return in_array($username, $contrib_list, true);
	}
	function is_email($email){
		return check_length($email, 6, 320) &&
			preg_match('/\A[a-z0-9_.+-]+@[a-z0-9-]+(\.[a-z0-9-]+)+\z/is', $email);
	}
	function is_open_email($enabled){
		return $enabled === '1' || $enabled === '0';
	}
	function is_password($password){
		return check_length($password, 6, 50);
	}
	function is_comment($comment){
		return check_length($comment, 0, 30);
	}
	function is_chall_link($link){
		return preg_match('/\A[a-z0-9_]{1,50}\z/', $link);
	}
	function is_writeup_no($no){
		return preg_match('/\A[0-9]{1,10}\z/', $no);
	}
	function is_writeup_contents($contents){
		return check_length($contents, 1, 10240);
	}

	function get_gravatar_link($email, $size){
		$email = md5($email);
		return 'https://www.gravatar.com/avatar/'.$email.'?s='.$size.'&d=https://github.com/identicons/'.$email.'.png';
	}
