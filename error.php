<?php
	# config
	require_once __DIR__.'/@import/config.php';

	# error
	isset($_SERVER['REDIRECT_STATUS']) or $_SERVER['REDIRECT_STATUS'] = 404;
	switch($_SERVER['REDIRECT_STATUS']){
	case 403:
		header('HTTP/1.1 403 Forbidden');
		$err_title = 'Forbidden';
		$err_contents = 'You don\'t have permission to access this page.';
		break;
	case 404:
	default:
		header('HTTP/1.1 404 Not Found');
		$err_title = 'Page Not Found';
		$err_contents = 'Please check that the URL is spelled correctly.';
		break;
	case 444:
		header('HTTP/1.1 302 Moved Temporarily');
		$err_title = 'SQL Server Down';
		$err_contents = 'Please wait for a while. T.T';
		break;
	}

	# common header
	$title = 'Solve Me » '.$err_title;
	$need_login = false;
	$css_files = array(
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
		'//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css',
		'/css/common.css'
	);
	$js_files = array(
		'//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js',
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
		'/js/common.js'
	);
	$show_category = false;
	require __DIR__.'/@import/header.php';
?>
					<div class="main-body text-center">
						<img src="/img/error.png">
						<h3><?php echo $err_title; ?></h3>
						<?php echo $err_contents; ?><br>
						<a href="/">Go back to home page.</a>
					</div>
<?php
	# common footer
	require __DIR__.'/@import/footer.php';
