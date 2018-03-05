
<span class="webpage-my-module-create"><a><span></span></a></span>
<h1>#<?=$mymodule_item->id ? "Update" : "Create"?>MyModuleItem#</h1>
<?php \BH::$Admin->RenderTaskErrors( "module.my-module.".($mymodule_item->id ? "update" : "create" ) ); ?>
<div class="box">
	<form method="post" class="standard" autocomplete="off">
		<div class="accordion">
			<div class="accordion_header">
				<span class="general-icon"></span>
				<span>#General#</span>
			</div>
			<fieldset class="column">
				<label for="name">#Name# <span class="required-asterisk">*</span></label>
				<input type="text" name="my_module_item[name]" id="name" value="<?=$mymodule_item->name?>" />
			</fieldset>
					<fieldset class="column">
				<label for="language_id">#Language#</label>
				<?php \BH::$Template->Render( "admin.language_select", array( "name" => "my_module_item[language_id]", "selected" => $mymodule_item->language_id ) ); ?>
			</fieldset>
		</div>
		<div class="accordion expanded">
			<div class="accordion_header">
				<span class="general-icon"></span>
				<span>#Images#</span>
			</div>
			<fieldset class="column">
				<div class="admin-widget-items sortable">
					<div class="header">
						<h1>#Images#</h1>
						<a class="button green small fancybox" data-type="ajax" data-src="/admin/?ajax=my-module_item.add_image"><i class="fa fa-plus"></i></a>
					</div>
					<div class="items" id="widget-images">
						<h4>#MainImage#</h4>
						<?php if( $mymodule_item->images ) { ?>
							<?php foreach( $mymodule_item->images as $key => $image ) { ?>

								<div class="item">
									<span class="title">
									
										<a class="fancybox" href="<?=$image->path?>">
											<img src="<?=\BH::$Cache->Image($image->path, 30, 30, 3)?>" class="img-responsive">
											<span class="text"><?=basename( $image->path )?></span>
										</a>
									</span>
									<input type="hidden" name="images[<?=$key?>][path]" value="<?=$image->path?>" />
									<input type="hidden" name="images[<?=$key?>][description]" value="<?=urlencode( $image->description )?>" />
									<div class="actions">
										<a class="delete"></a>
										<a class="edit"></a>
									</div>
								</div>
								<?php if($key==0){ ?>
									<hr>
								<?php } ?>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</fieldset>
		</div>
		<div class="accordion expanded">
			<div class="accordion_header">
				<span class="general-icon"></span>
				<span>#Content# *</span>
			</div>
			<fieldset>
				<textarea name="my_module_item[content]" id="content" class="tinymce_full"><?=$mymodule_item->content?></textarea>
			</fieldset>
		</div>

		<fieldset class="last">
			<button type="submit" class="button green"><i class="fa fa-<?=$mymodule_item->id ? "save" : "plus"?>"></i> #<?=$mymodule_item->id ? "Update" : "Create"?>#</button>
			<input type="hidden" name="task" value="module.my-module.<?=$mymodule_item->id ? "update" : "create"?>" />
		</fieldset>
	</form>
</div>