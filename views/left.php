<ul class="nav nav-pills nav-stacked">
	<?php foreach( $this->nav as $nav ): ?>
		<li class="li_nav <?php echo ( $this->get_active() == $nav['url'] ) ? 'active': '';?>">
			<a href="<?php echo URL.$nav['url']; ?>"><?php echo $nav['name']; ?></a>
			<?php if( isset($nav['child'] ) &&  is_array($nav['child']) ) { ?>
				<ul class="child_nav">
					<?php foreach ($nav['child'] as $child ) { ?>					
						<li class="<?php echo ( $this->get_active() == $child['url'] ) ? 'active ': '';?><?php echo strtolower($child['name']); ?>">
							<a href="<?php echo URL.$child['url']; ?>">
								<?php echo $child['name'] ?>
							</a>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		</li>
	<?php endforeach; ?>
</ul>