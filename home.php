<?php
	# common header
	$title = 'Solve Me';
	$need_login = true;
	$css_files = array(
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css',
		'/css/common.css'
	);
	$js_files = array(
		'//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js',
		'//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js',
		'/js/common.js'
	);
	$show_category = true;
	require __DIR__.'/header.php';
?>
					<div class="main-body">
						<dl class="well">
							<dt class="main-dt">About</dt>
							<dd>
								The Solve Me offers the opportunity for you to test your knowledge.<br>
								The method of challenge is CTF (Capture The Flag).<br>
								To solve the challenge, find the <var>flag</var> (e.g. <code>flag{<?php echo md5(mt_rand()); ?>}</code>) in problem.<br>
								If you solve the challenge and authentication the <var>flag</var>, you'll get a score.<br>
								Have fun solving challenge!<br>
							</dd>
						</dl>
						<dl class="well">
							<dt class="main-dt">Rules</dt>
							<dd>
								<ul class="list-unstyled clear-margin">
									<li>Only one registration per person is allowed.</li>
									<li>Do not share the <var>flag</var> for solve the challenge. But, a small hint is allowed.</li>
									<li>Do not bruteforce authentication. Even if you've solved with it, it's very futile.</li>
									<li>Do not attack not specified service or server. If you find any unintended bug, please report to admin personally.</li>
								</ul>
							</dd>
						</dl>
						<dl class="well">
							<dt class="main-dt">Contact</dt>
							<dd>
								If you have any questions or problems, <a class="admin-contact">contact to admin</a>.<br>
								<noscript>
									<span class="text-danger">If you want to contact, please enable javascript on your web browser.</span><br>
								</noscript>
								I might not be able to answer immediately. ;)<br>
							</dd>
						</dl>
					</div>
<?php
	# common footer
	require __DIR__.'/footer.php';
