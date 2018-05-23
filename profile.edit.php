<?php
	# get user info
	$p = $pdo->prepare("
		SELECT
			`email`,
			`open_email`,
			`comment`
		FROM
			`{$db_prefix}_user`
		WHERE
			`username`=:username
		LIMIT
			1
	");
	$p->bindParam(':username', $_SESSION['username']);
	$p->execute();
	$user_info = $p->fetch(PDO::FETCH_ASSOC) or error(404);

	# common header
	$title = __SITE__['title'].' » Edit Profile';
	$need_login = true;
	$css_files = [
	];
	$js_files = [
		'/assets/js/profile.js'
	];
	$show_category = true;
	require __DIR__.'/header.php';
?>
					<main class="main-body">
						<form id="edit-form">
							<div class="form-group">
								<label class="sr-only" for="edit-username">Username</label>
								<input type="text" class="form-control" id="edit-username" placeholder="Username" value="<?php echo $_SESSION['username']; ?>" disabled>
							</div>
							<div class="form-group mb-0">
								<label class="sr-only" for="edit-email">Email</label>
								<input type="text" class="form-control" id="edit-email" placeholder="Email" data-toggle="tooltip" data-placement="bottom" title="The email must be unique and valid, It will use when you forgot password." value="<?php echo secure_escape($user_info['email']); ?>">
							</div>
							<div class="checkbox ml-10">
								<label><input type="checkbox" id="edit-open-email"<?php if($user_info['open_email'] === '1') echo ' checked'; ?>> Email open to the public in <a href="/profile">profile page</a>.</label>
							</div>
							<div class="form-group mb-0">
								<label class="sr-only" for="edit-current-password">Current Password</label>
								<input type="password" class="form-control" id="edit-current-password" placeholder="Current Password" data-toggle="tooltip" data-placement="bottom" title="The password must be case sensitive and 6 to 50 characters long.">
							</div>
							<div class="checkbox ml-10">
								<label data-toggle="collapse" data-target="#edit-input-password" aria-expanded="false" aria-controls="edit-input-password"><input type="checkbox" id="edit-change-password"> Change the password.</label>
							</div>
							<div id="edit-input-password" class="collapse">
								<div class="form-group">
									<label class="sr-only" for="edit-new-password">New Password</label>
									<input type="password" class="form-control" id="edit-new-password" placeholder="New Password" data-toggle="tooltip" data-placement="bottom" title="The password must be case sensitive and 6 to 50 characters long.">
								</div>
								<div class="form-group">
									<label class="sr-only" for="edit-confirm-new-password">Confirm New Password</label>
									<input type="password" class="form-control" id="edit-confirm-new-password" placeholder="Confirm New Password" data-toggle="tooltip" data-placement="bottom" title="The password must be case sensitive and 6 to 50 characters long, and must be equal to above password.">
								</div>
							</div>
							<div class="form-group">
								<label class="sr-only" for="edit-comment">Comment</label>
								<input type="text" class="form-control" id="edit-comment" placeholder="Comment" data-toggle="tooltip" data-placement="bottom" title="The comment must be 30 characters long." value="<?php echo secure_escape($user_info['comment']); ?>">
							</div>
							<div class="text-right">
								<button type="submit" class="btn btn-dark mr-5">Edit</button><button class="btn btn-default go-back" data-href="/profile">Cancel</button>
							</div>
						</form>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
