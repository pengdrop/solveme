<?php
	# common header
	$title = 'Solve Me';
	$need_login = false;
	$css_files = array(
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
		'/css/common.css'
	);
	$js_files = array(
		'//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js',
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
		'/js/common.js',
		'/js/login.js'
	);
	$show_category = false;
	require __DIR__.'/header.php';
?>
					<div class="main-body">
						<form id="login-form">
							<div class="form-group">
								<label class="sr-only" for="login-username">Username</label>
								<input type="text" class="form-control" id="login-username" placeholder="Username" data-toggle="tooltip" data-placement="bottom" title="Enter your username.">
							</div>
							<div class="form-group">
								<label class="sr-only" for="login-password">Password</label>
								<input type="password" class="form-control" id="login-password" placeholder="Password" data-toggle="tooltip" data-placement="bottom" title="Enter your password.">
							</div>
							<div class="pull-left text-left">
								Do you've a problem?<br>
								<a class="admin-contact">Contact to admin.</a>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-default m-r-10 show-join">Join</button><button type="submit" class="btn btn-success">Login</button>
							</div>
						</form>
						<form id="join-form" class="no-display">
							<div class="form-group">
								<label class="sr-only" for="join-username">Username</label>
								<input type="text" class="form-control" id="join-username" placeholder="Username" data-toggle="tooltip" data-placement="bottom" title="The username must be unique and 5 to 20 characters long and just alphanumeric characters(a-z, A-Z, 0-9), underscore(_), hyphen(-).">
							</div>
							<div class="form-group">
								<label class="sr-only" for="join-email">Email</label>
								<input type="email" class="form-control" id="join-email" placeholder="Email" data-toggle="tooltip" data-placement="bottom" title="The email must be unique and valid, It will use when you forgot password.">
							</div>
							<div class="form-group">
								<label class="sr-only" for="join-password">Password</label>
								<input type="password" class="form-control" id="join-password" placeholder="Password" data-toggle="tooltip" data-placement="bottom" title="The password must be case sensitive and 6 to 50 characters long.">
							</div>
							<div class="form-group">
								<label class="sr-only" for="join-confirm-password">Confirm Password</label>
								<input type="password" class="form-control" id="join-confirm-password" placeholder="Confirm Password" data-toggle="tooltip" data-placement="bottom" title="The password must be case sensitive and 6 to 50 characters long, and must be equal to above password.">
							</div>
							<div class="pull-left text-left">
								Do you've a problem?<br>
								<a class="admin-contact">Contact to admin.</a>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-default m-r-10 show-login">Login</button><button type="submit" class="btn btn-success">Join</button>
							</div>
						</form>
						<div class="alert-parent"></div>
					</div>
<?php
	# common footer
	require __DIR__.'/footer.php';
