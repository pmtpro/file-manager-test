<?php if (!defined('ACCESS')) die('Not access'); ?>

</div>

<div id="footer">
    <span>
		ngatngay cooperation with linh
	</span><br />
    <span>Version: <?php echo VERSION_MAJOR; ?>.<?php echo VERSION_MINOR; ?>.<?php echo VERSION_PATCH; ?></span>
</div>

<div
    id="scroll"
    class="scroll-to-top scroll-to-top-icon"
    style="display: block; visibility: visible; opacity: 0.5; display: none;"
></div>

<script src="<?php echo asset('script.js') ?>"></script>

</body>

</html>

<?php ob_end_flush(); ?>