<?php
	# common header
	$title = __SITE__['title'];
	$need_login = false;
	$js_files = [
		'/assets/js/login.js'
	];
	$show_category = false;
	require __DIR__.'/header.php';
?>
					<main class="main-body">
						<form id="login-form" action="/login" method="POST">
							<div class="form-group">
								<label class="sr-only" for="login-username">Username</label>
								<input type="text" class="form-control" id="login-username" name="username" placeholder="Username" data-toggle="tooltip" data-placement="bottom" title="Enter your username.">
							</div>
							<div class="form-group">
								<label class="sr-only" for="login-password">Password</label>
								<input type="password" class="form-control" id="login-password" name="password" placeholder="Password" data-toggle="tooltip" data-placement="bottom" title="Enter your password.">
							</div>
							<div class="pull-left text-left">
								Do you've a problem?<br>
								<a class="admin-contact">Contact to admin.</a>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-default mr-10 show-join">Join</button><button type="submit" class="btn btn-success">Login</button>
							</div>
						</form>
						<form id="join-form" class="no-display" action="/join" method="POST">
							<div class="form-group">
								<label class="sr-only" for="join-username">Username</label>
								<input type="text" class="form-control" id="join-username" name="username" placeholder="Username" data-toggle="tooltip" data-placement="bottom" title="The username must be unique and 5 to 20 characters long and just alphanumeric characters(a-z, A-Z, 0-9), underscore(_), hyphen(-).">
							</div>
							<div class="form-group">
								<label class="sr-only" for="join-email">Email</label>
								<input type="text" class="form-control" id="join-email" name="email" placeholder="Email" data-toggle="tooltip" data-placement="bottom" title="The email must be unique and valid, It will use when you forgot password.">
								<div class="checkbox ml-10">
									<label><input type="checkbox" id="join-open-email" name="open-email> Email open to the public in <a href="/profile">profile page</a>.</label>
								</div>
							</div>
							<div class="form-group">
								<label class="sr-only" for="join-password">Password</label>
								<input type="password" class="form-control" id="join-password" name="password" placeholder="Password" data-toggle="tooltip" data-placement="bottom" title="The password must be case sensitive and 6 to 50 characters long.">
							</div>
							<div class="form-group">
								<label class="sr-only" for="join-confirm-password">Confirm Password</label>
								<input type="password" class="form-control" id="join-confirm-password" name="confirm-password" placeholder="Confirm Password" data-toggle="tooltip" data-placement="bottom" title="The password must be case sensitive and 6 to 50 characters long, and must be equal to above password.">
							</div>
							<div class="form-group">
								<label class="sr-only" for="join-comment">Comment</label>
								<input type="text" class="form-control" id="join-comment" name="comment" placeholder="Comment" data-toggle="tooltip" data-placement="bottom" title="The comment must be 30 characters long.">
							</div>
							<div class="pull-left text-left">
								Do you've a problem?<br>
								<a class="admin-contact">Contact to admin.</a>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-default mr-10 show-login">Login</button><button type="submit" class="btn btn-success">Join</button>
							</div>
						</form>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
