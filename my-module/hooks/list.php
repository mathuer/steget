<div class="hook my-module-list">
	<h2 class="title">#Latestmy-module#</h2>
	<?php foreach( $items as $item ) { ?>
	<?php $item->LoadURL(); ?>
	<div class="my-module-item">
		<h3 class="title"><a href="<?=$item->url->path?>"><?=$item->name?></a></h3>
		
		<?php if( $item->images[0] && (!isset( $img_show ) || $img_show == 1 )  ) { ?>
			<img src="<?=\BH::$Cache->Image( $item->images[0]->path, \BH::$Options->Get( "module.my-module.hook_list.img_width", $img_width ), \BH::$Options->Get( "module.my-module.hook_list.img_height", $img_height ), 1 )?>" class="img-responsive" />
		<?php } ?>

	</div>
	<?php } ?>
	
	<div class="text-right">
		<a href="<?=$reserved_page?>">#ShowAll# »</a>
	</div>
</div>