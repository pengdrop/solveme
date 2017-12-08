<?php
	# common header
	$title = __SITE__['title'];
	$need_login = true;
	$js_files = [
		'/assets/js/jquery.min.js',
		'/assets/js/bootstrap.min.js',
		'/assets/js/common.js'
	];
	$show_category = true;
	require __DIR__.'/header.php';
?>
					<main class="main-body">
						<dl class="well">
							<dt class="main-dt">About</dt>
							<dd>
								The Solve Me offers the opportunity for you to test your knowledge.<br>
								To solve the challenge, find the <var>flag</var> (<code>flag\{[a-f0-9]{32}\}</code>) in challenge.<br>
								If you solve the challenge and authentication the <var>flag</var>,<br>
								 you'll get a score and you can read and write solutions in <a href="/writeup">writeup page</a>.<br>
								Have fun solving challenge!
							</dd>
						</dl>
						<dl class="well">
							<dt class="main-dt">Rules</dt>
							<dd>
								<ul class="list-unstyled m-0">
									<li>Only one registration per person is allowed.</li>
									<li>Do not bruteforce authentication.<br>
									Even if you've solved with it, it's very futile.</li>
									<li>Do not attack not specified service or server.<br>
									If you find any unintended bug, please report to admin personally.</li>
								</ul>
							</dd>
						</dl>
						<dl class="well">
							<dt class="main-dt">Contact</dt>
							<dd>
								If you have any questions or problems, <a class="admin-contact">contact to admin</a>.<br>
								I might not be able to answer immediately. ;)<br>
							</dd>
						</dl>
					</main>
<?php
	# common footer
	require __DIR__.'/footer.php';
