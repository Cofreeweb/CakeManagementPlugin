<? $this->append( 'scripts') ?>
<script type="text/javascript">
	try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
</script>
<? $this->end()  ?>
<?#= $this->element( 'template/shortcuts')?>

<?= $this->AdminNav->nav() ?>


<div class="sidebar-collapse" id="sidebar-collapse">
	<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
</div>
<? $this->append( 'scripts') ?>
<script type="text/javascript">
	try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
</script>
<? $this->end()  ?>
