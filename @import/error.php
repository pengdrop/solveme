<?php
	# error
	isset($_SERVER['REDIRECT_STATUS']) or $_SERVER['REDIRECT_STATUS'] = 404;
	switch($_SERVER['REDIRECT_STATUS']){
	case 403:
		header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
		$err_title = 'Forbidden';
		$err_contents = 'You don\'t have permission to access this page.';
		break;
	case 404:
	default:
		header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
		$err_title = 'Page Not Found';
		$err_contents = 'Please check that the URL is spelled correctly.';
		break;
	case 'sql':
		header($_SERVER['SERVER_PROTOCOL'].' 302 Moved Temporarily');
		$err_title = 'SQL Server Down';
		$err_contents = 'Please wait for a while. T.T';
		break;
	case 'method':
		header($_SERVER['SERVER_PROTOCOL'].' 302 Moved Temporarily');
		$err_title = 'Not Allowed Method';
		$err_contents = 'This page is not allowed <code>'.$_SERVER['REQUEST_METHOD'].'</code> method.';
		break;
	}

	# common header
	$title = __SITE__['title'].' » '.$err_title;
	$need_login = false;
	$js_files = [
		'/assets/js/jquery.min.js',
		'/assets/js/bootstrap.min.js',
		'/assets/js/common.js'
	];
	$show_category = false;
	require __DIR__.'/header.php';
?>
					<main class="main-body text-center">
						<img src="<?php echo get_new_cache_link('/assets/img/forbidden.png'); ?>" alt="🚫">
						<h3><?php echo $err_title; ?></h3>
						<?php echo $err_contents; ?><br>
						<a class="go-back" data-href="/">Go back</a> or <a href="/">Go home</a>.
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
