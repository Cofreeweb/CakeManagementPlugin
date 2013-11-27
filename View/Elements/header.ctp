<div class="navbar-header pull-left">
	<a href="/admin" class="navbar-brand">
		<small>
			<i class="icon-cog"></i>
			<?= Configure::read( 'Management.webname')?> Admin
		</small>
	</a><!-- /.brand -->
</div><!-- /.navbar-header -->

<div class="navbar-header pull-right" role="navigation">
	<ul class="nav ace-nav">
		<?#= $this->element( 'template/notifications') ?>

		<li class="light-blue">
			<a data-toggle="dropdown" href="#" class="dropdown-toggle">
				<span class="user-info">
					<?= $this->Auth->user( 'name') ?>
				</span>

				<i class="icon-caret-down"></i>
			</a>

			<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
				<li>
					<a href="<?= $this->Html->url( array(
					  'plugin' => 'acl',
					  'controller' => 'users',
					  'action' => 'edit'
					)) ?>">
						<i class="icon-user"></i>
						<?= __d( 'admin', "Mis datos")?>
					</a>
				</li>
				<li>
					<a href="<?= $this->Html->url( array(
					  'admin' => false,
					  'plugin' => 'acl',
					  'controller' => 'users',
					  'action' => 'logout'
					)) ?>">
						<i class="icon-off"></i>
						<?= __d( 'admin', "Salir")?>
					</a>
				</li>
			</ul>
		</li>
	</ul><!-- /.ace-nav -->
</div><!-- /.navbar-header -->