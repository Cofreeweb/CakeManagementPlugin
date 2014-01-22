<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?= Configure::read( 'Config.siteName') ?> Admin</title>

		<meta name="description" content="Common form elements and layouts" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- basic styles -->

    <?= $this->Html->css( array(
      '/management/css/bootstrap.min',
      '/management/css/font-awesome.min',
      '/management/css/jquery-ui-1.10.3.custom.min',
      '/management/css/chosen',
      '/management/css/datepicker',
      '/management/css/bootstrap-timepicker',
      '/management/css/daterangepicker',
      '/management/css/colorpicker',
      '/management/css/ace-fonts',
      '/management/css/ace',
      '/management/css/ace-rtl',
      '/management/css/ace-skins.min',
      '/management/css/admin.css'
    )) ?>
    
    
		<!--[if IE 7]>
    <?= $this->Html->css( array(
      '/management/css/font-awesome-ie7.min',
    )) ?>
		<![endif]-->


		<!--[if lte IE 8]>
    <?= $this->Html->css( array(
      '/management/css/ace-ie.min',
    )) ?>
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->

    <?= $this->Html->script( array(
      '/management/js/ace-extra.min'
    )) ?>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
    <?= $this->Html->script( array(
      '/management/css/html5shiv.min',
      '/management/css/respond.min'
    )) ?>
		<![endif]-->
	</head>

	<body class="login-layout">
	  <?= $this->Session->flash() ?>
	  
		<div class="main-container">
			<div class="main-content">
				<?= $this->fetch( 'content')?>
			</div>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->

		<script type="text/javascript">
		window.jQuery || document.write("<script src='<?= $this->Html->webroot( 'management/js/jquery-2.0.3.min.js') ?>'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">

 window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>

		<!-- inline scripts related to this page -->

		<script type="text/javascript">
			function show_box(id) {
			 jQuery('.widget-box.visible').removeClass('visible');
			 jQuery('#'+id).addClass('visible');
			}
		</script>
	</body>
	<?= $this->fetch( 'script') ?>
	<?= $this->fetch( 'scripts') ?>
</html>
