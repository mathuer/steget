<div class="hook my-module-item">
	<h2 class="title"><i class="fa fa-my-modulepaper-o"></i> <a href="<?=$mymodule_item->url->path?>"><?=$mymodule_item->name?></a> </h2>
<?php if( $mymodule_item->image && (!isset( $img_show ) || $img_show == 1 ) ) { ?>
	<img src="<?=\BH::$Cache->Image( $mymodule_item->image, \BH::$Options->Get( "my-module.hook_item.img_width", $img_width ), \BH::$Options->Get( "my-module.hook_item.img_height", $img_height ), 1 )?>" />
<?php } ?>
	<div class="content"><?=$mymodule_item->content?></div>
</div>