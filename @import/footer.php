					<nav class="text-center mb-10 main-menu">
						<ul class="list-inline m-0">
							<li>
								<a href="//github.com/Safflower/Solve-Me">Github</a>
							</li><li>
								<a class="admin-contact">Contact</a>
							</li><li>
								<a href="/logout/<?php echo get_logout_link(); ?>">Logout</a>
							</li>
						</ul>
					</nav>
					<footer class="text-center text-muted">
						<address class="m-0">© <a class="text-muted" href="//peng.kr" target="_blank">Safflower</a>. All Rights Reserved.</address>
					</footer>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
	if(__IS_HTML_PACK__){
		$content = ob_get_contents();
		$content = strtr($content, ["\r" => null, "\n" => null, "\t" => null]);
		$content = preg_replace('/<!--.*?-->/', null, $content);
		ob_end_clean();
		echo $content;
	}
