<?php
	# need login
	if($need_login && !is_login()){
		require(__DIR__.'/login.php');
		die;
	}

	if(__IS_HTML_COMPRESS__){
		ob_start();
	}

	# e.g. /css/test.css?12341234
	$get_link = function($filename){
		return $filename.(is_file(__DIR__.'/..'.$filename) ? '?'.filemtime(__DIR__.'/..'.$filename) : null);
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php echo $title; ?></title>
		<link rel="icon" href="<?php echo $get_link('/img/favicon.png'); ?>">
<?php
	# import css files
	for($i = 0; isset($css_files[$i]); ++$i){
?>
		<link rel="stylesheet" href="<?php echo $get_link($css_files[$i]); ?>">
<?php
	}
	# import js files
	for($i = 0; isset($js_files[$i]); ++$i){
?>
		<script src="<?php echo $get_link($js_files[$i]); ?>"></script>
<?php
	}
?>
	</head>
	<body>
		<div class="container-fluid v-center">
			<div class="row">
				<div class="col-sm-6 col-md-4 h-center">
					<header class="text-center">
						<a class="text-black clear-decoration" href="/">
							<img class="logo-mark" src="/img/bulb.png"><span class="logo-major">Solve Me</span><br>
							<span class="text-muted">Yet another new CTF for hackers</span>
						</a>
					</header>
<?php
	if($show_category){
?>
					<nav class="text-center m-t-10">
						<ul class="list-inline clear-margin">
							<li>
								<a class="text-black" href="/">Home</a>
							</li><li>
								<a class="text-black" href="/chall">Challenge</a>
							</li><li>
								<a class="text-black" href="/rank">Rank</a>
							</li><li>
								<a class="text-black" href="/status">Status</a>
							</li><li>
								<a class="text-black" href="/profile">Profile</a>
							</li>
						</ul>
					</nav>
<?
	}
?>