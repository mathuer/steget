<div id="my-module-list">
	<div class="row title">
		<div class="col-sm-12 text-center">
			<h1>#MyModule#</h1>
		</div>
	</div>
	<div class="items">
	<?php if( !empty( $items ) ) { ?>
		<?php foreach( $items as $key => $item ) { ?>
			<?php $item->RenderListView(); ?>
		<?php } ?>
	<?php } else { ?>
		<div class="not-found text-center">
			<i class="fa fa-warning"></i>
			<h2>#NoModuleAvailable#</h2>
		</div>
	<?php } ?>
	</div>
	<?php if( isset( $pager ) ) { ?>
	<?=$pager?>
	<?php } ?>
</div>