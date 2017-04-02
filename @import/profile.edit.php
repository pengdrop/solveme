<?php
	# common header
	$title = 'Solve Me » Edit Profile';
	$need_login = true;
	$css_files = array(
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
		'/css/common.css'
	);
	$js_files = array(
		'//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js',
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
		'/js/common.js',
		'/js/profile.js'
	);
	$show_category = true;
	require __DIR__.'/header.php';

	$p = $pdo->prepare('
		SELECT 
			email
		FROM user
		WHERE username=:username
		LIMIT 1
	');
	$p->bindParam(':username', $_SESSION['username']);
	$p->execute();
	$user_info = $p->fetch(PDO::FETCH_ASSOC) or error(404);
?>
					<div class="main-body">
						<form id="edit-form">
							<div class="form-group">
								<label class="sr-only" for="edit-username">Username</label>
								<input type="text" class="form-control" id="edit-username" placeholder="Username" value="<?php echo $_SESSION['username']; ?>" disabled>
							</div>
							<div class="form-group">
								<label class="sr-only" for="edit-email">Email</label>
								<input type="email" class="form-control" id="edit-email" placeholder="Email" data-toggle="tooltip" data-placement="bottom" title="The email must be unique and valid, It will use when you forgot password." value="<?php echo $user_info['email']; ?>">
							</div>
							<div class="form-group">
								<label class="sr-only" for="edit-current-password">Current Password</label>
								<input type="password" class="form-control" id="edit-current-password" placeholder="Current Password" data-toggle="tooltip" data-placement="bottom" title="The password must be case sensitive and 6 to 50 characters long.">
							</div>
							<div class="form-group">
								<label class="sr-only" for="edit-new-password">New Password</label>
								<input type="password" class="form-control" id="edit-new-password" placeholder="New Password" data-toggle="tooltip" data-placement="bottom" title="The password must be case sensitive and 6 to 50 characters long.">
							</div>
							<div class="form-group">
								<label class="sr-only" for="edit-confirm-new-password">Confirm New Password</label>
								<input type="password" class="form-control" id="edit-confirm-new-password" placeholder="Confirm New Password" data-toggle="tooltip" data-placement="bottom" title="The password must be case sensitive and 6 to 50 characters long, and must be equal to above password.">
							</div>
							<div class="alert-parent"></div>
							<div class="text-right">
								<a class="btn btn-default m-r-10" href="/profile">Cancel</a><button type="submit" class="btn btn-success">Edit</button>
							</div>
						</form>
					</div>
<?php
	# common footer
	require __DIR__.'/footer.php';
