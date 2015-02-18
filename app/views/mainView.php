<!doctype html>
<html lang="fr">
	<head>
		<title><?php echo (isset($TITLE)) ? $TITLE.' - ' : ''; echo NAME; ?></title>
		<meta charset="utf-8" />
		<link type="text/css" rel="stylesheet" href="<?php echo CSS.'bootstrap.min.css'; ?>" media="screen" />
		<link type="text/css" rel="stylesheet" href="<?php echo CSS.'style.css'; ?>" media="screen" />
		<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />
		<meta name="format-detection" content="telephone=no" />
		<link rel="apple-touch-icon" href="<?php echo IMG.'icon.png'; ?>" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<link rel="apple-touch-startup-image" href="<?php echo IMG.'splash.png'; ?>" />
		<link rel="icon" href="<?php echo IMG.'favicon.png'; ?>" />
		<script type="text/javascript">var _webroot_ = <?php echo WEBROOT; ?>;</script>
	</head>

	<body>
		<?php require_once @$appView; ?>
		
		<script type="text/javascript" src="<?php echo JS.'bootstrap.min.js'; ?>"></script>
		<script type="text/javascript" src="<?php echo JS.'script.js?v=2'; ?>"></script>
	</body>
</html>