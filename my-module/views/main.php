<div class="row">
	<div class="col-sm-12">
		<div class="my-module-item">
			<div class="title">
					<h2><?=$name?></h2>
				
			</div>
			<div class="info">
				
				<?php if( $main_image ) { ?>
					<div class="col-sm-12 gallery">
						<div class="row">
							<div class="col-sm-12 no-padding main_image">
								<a class="fancybox" href="<?=$main_image->path?>" title="<?=$main_image->description?>" data-fancybox="images">
									<img src="<?=\BH::$Cache->Image( $main_image->path, \BH::$Options->Get( "module.my-module.list.main_img_width", 850 ), \BH::$Options->Get( "module.my-module.list.main_img_height", 400 ), 1 )?>" class="img-responsive">
								</a>
							</div>
						</div>
					<?php if( $images ) { ?>
						<div class="row">
						<?php foreach( $images as $image ) { ?>
							<div class="col-xs-2 no-padding">
								<a class="fancybox" data-fancybox="images" href="<?=$image->path?>" title="<?=$image->description?>"><img src="<?=\BH::$Cache->Image( $image->path, \BH::$Options->Get( "module.my-module.list.thumb_img_width", 150 ), \BH::$Options->Get( "module.my-module.list.thumb_img_height", 150 ), 1 )?>" class="img-responsive"></a>
							</div>
						<?php } ?>
						</div>
					<?php } ?>
					</div>
				<?php } ?>
				<div class="row">
					<div class="col-sm-12">
						<div class="content"><?=$content?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>