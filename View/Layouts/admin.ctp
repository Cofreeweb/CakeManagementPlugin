<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title><?= Configure::read( 'Config.siteName') ?> Admin</title>

		<meta name="description" content="Common form elements and layouts" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <?= $this->fetch( 'css') ?>
    
		<!-- basic styles -->

    <?= $this->Html->css( array(
      '/management/css/bootstrap.min',
      '/management/css/font-awesome',
      '/management/css/jquery-ui-1.10.3.custom.min',
      '/management/css/chosen',
      '/management/css/datepicker',
      '/management/css/bootstrap-timepicker',
      '/management/css/bootstrap-editable',
      '/management/css/daterangepicker',
      '/management/css/colorpicker',
      '/management/css/ace-fonts',
      '/management/css/ace',
      '/management/css/ace-rtl',
      '/management/css/ace-skins.min',
      '/management/css/dropzone.css',
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

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
    <?= $this->Html->script( array(
      '/management/css/html5shiv.min',
      '/management/css/respond.min'
    )) ?>
		<![endif]-->
	</head>

	<body class="navbar-fixed">
	  <?= $this->Session->flash() ?>
		<div class="navbar navbar-default navbar-fixed-top" id="navbar">
	
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
				// Vamos a dar la opción al usuario de cambiar la forma de visualización? Este script es para eso, no?
			</script>

			<div class="navbar-container" id="navbar-container">
				<?= $this->element( 'header') ?>
			</div>
		</div>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="main-container-inner">
				<a class="menu-toggler" id="menu-toggler" href="#"><span class="menu-text"></span></a>

				<div class="sidebar sidebar-fixed" id="sidebar">
					<?= $this->element( 'menu') ?>
				</div>
        
				<div class="main-content">
					
					<div class="page-content">
					
						<div class="page-header">
					
							<h1><?= isset( $adminTitle) ? $adminTitle : ucfirst( $this->request->controller) ?>
						  <? if( isset( $adminSubtitle)): ?>
						     <small><i class="icon-double-angle-right"></i> <?= $adminSubtitle ?></small>
						  <? endif ?>																
							<span class="header-actions">
								<?= $this->element( 'actions', array(
               		'plugin' => 'Management'
              	)) ?>
							</span></h1>
						</div>
					
						<?= $this->fetch( 'content')?>
					
					</div>
				</div><!-- /.main-content -->

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->
	</div>
</body>
		<!-- basic scripts -->

		<!--[if !IE]> -->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?= $this->Html->webroot( 'management/js/jquery-2.0.3.min.js') ?>'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
window.jQuery || document.write("<script src='<?= $this->Html->webroot( 'management/js/jquery-1.10.2.min.js') ?>'>"+"<"+"/script>");
</script>
<![endif]-->

		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?= $this->Html->webroot( 'management/js/jquery.mobile.custom.min.js') ?>'>"+"<"+"/script>");0
			
			
		</script>
    
    <?= $this->Html->script( array(
      '/management/js/bootstrap.min',
      '/management/js/typeahead-bs2.min'
    )) ?>

		<!-- page specific plugin scripts -->

		<!--[if lte IE 8]>
    <?= $this->Html->script( array(
      '/management/js/excanvas.min',
    )) ?>
		<![endif]-->
    
    <?= $this->Html->script( array(
      '/management/js/jquery-ui-1.10.3.custom.min',
      '/management/js/jquery.ui.touch-punch.min',
      '/management/js/chosen.jquery.min',
      '/management/js/fuelux/fuelux.spinner.min',
      '/management/js/date-time/bootstrap-datepicker.min',
      '/management/js/date-time/bootstrap-timepicker.min',
      '/management/js/date-time/moment.min',
      '/management/js/date-time/daterangepicker.min',
      '/management/js/jquery.knob.min',
      '/management/js/jquery.autosize.min',
      '/management/js/jquery.inputlimiter.1.3.1.min',
      '/management/js/jquery.maskedinput.min',
      '/management/js/bootstrap-tag.min',
      '/management/js/bootstrap-colorpicker.min',
      '/management/js/ckeditor/ckeditor',
      '/management/js/ckeditor/adapters/jquery',
      '/management/js/x-editable/bootstrap-editable.min.js',
      '/management/js/x-editable/ace-editable.min.js',
      '/management/js/select2.min.js'
    )) ?>
    

		<!-- ace scripts -->
    
    <?= $this->Html->script( array(
      '/management/js/ace-elements.min',
      '/management/js/ace.min',
      '/management/js/ace-extra.min'
    )) ?>
    
		<!-- inline scripts related to this page -->
    <script type="text/javascript">
      //editables on first profile page
			$.fn.editable.defaults.mode = 'inline';
			$.fn.editableform.loading = "<div class='editableform-loading'><i class='light-blue icon-2x icon-spinner icon-spin'></i></div>";
		  $.fn.editableform.buttons = '<button type="submit" class="btn btn-info editable-submit"><i class="icon-ok icon-white"></i></button>'+
		                                '<button type="button" class="btn editable-cancel"><i class="icon-remove"></i></button>';
		                                
      $('[data-rel=tooltip]').tooltip({container:'body'});
      $('[data-rel=popover]').popover({container:'body'});
      $('.date-picker').datepicker({
          autoclose:true, 
          format: 'yyyy-mm-dd', 
          language: 'es'
      }).next().on(ace.click_event, function(){
				$(this).prev().focus();
			});
    </script>
    <?= $this->fetch( 'script') ?>
    <?= $this->fetch( 'css') ?>
    <?= $this->Js->writeBuffer() ?>
		
		<?= $this->element('sql_dump'); ?>
	
</html>
