<?php if (!defined('ACCESS')) die('Not access'); ?>

</div>

<div id="footer">
    <span>
		ngatngay cooperation with linh
	</span><br />
    <span>Version: <?php echo VERSION_MAJOR; ?>.<?php echo VERSION_MINOR; ?>.<?php echo VERSION_PATCH; ?></span>
</div>

</body>

</html>
<?php ob_end_flush(); ?>
<?php
/*
	var_dump(
		REMOTE_FILE_CURRENT,
		REMOTE_FILE_NEW
	);
*/
?>