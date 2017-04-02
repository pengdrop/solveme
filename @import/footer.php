					<footer class="text-center text-muted">
						<address class="clear-margin">&copy; <a class="text-muted" href="//safflower.kr" target="_blank">Safflower</a>. All Rights Reserved.</address>
					</footer>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
	if(__IS_HTML_COMPRESS__){
		$content = ob_get_contents();
		ob_end_clean();
		echo strtr($content, array("\r" => null, "\n" => null, "\t" => null));
	}
