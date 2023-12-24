<?php if (!defined('ACCESS')) die('Not access'); ?>

</div>

<div id="footer">
    <span style="font-size: large">&bull; &bull; IZeroCs &bull; &bull;</span><br />
    <span>Version: <?php echo VERSION_MAJOR; ?>.<?php echo VERSION_MINOR; ?>.<?php echo VERSION_PATCH; ?></span>
</div>

</body>

</html>
<?php ob_end_flush(); ?>