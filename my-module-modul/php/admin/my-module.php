<?php

// --------------------------------------------------------------------------------------------- //
if( isset( $_GET["action"] ) ) {
	switch( $_GET["action"] ) {
		
		// --------------------------------------------------------------------------------------------- //
		case "create":
			$mymodule_item = new \OBJ\MyModuleItem();
	
			if( !empty( $_POST["my_module_item"] ) ) {
				$mymodule_item->SetFieldsFromArray( $_POST["my_module_item"] );
			} 

			// Images
			if( isset( $_POST["images"] ) ) {
				foreach( $_POST["images"] as $image_data ) {

					$image = new \OBJ\MyModuleItemImage();
					$image->SetFieldsFromArray( $image_data );
					$mymodule_item->images[] = $image;
				}
			}
			$mymodule_item->url = new \OBJ\URL();
			
			$mymodule_item->RenderForm();
		break;
		
		// --------------------------------------------------------------------------------------------- //
		case "edit":
			$mymodule_item = new \OBJ\MyModuleItem( $_GET["id"] );
			
			if( !empty( $_POST["my_module_item"] ) ) {
				$mymodule_item->SetFieldsFromArray( $_POST["my_module_item"] );
			}
			// Images
			if( isset( $_POST["images"] ) ) {
				foreach( $_POST["images"] as $image_data ) {
					$image = new \OBJ\MyModuleItemImage();
					$image->SetFieldsFromArray( $image_data );
					$mymodule_item->images[] = $image;
				}
				
			} else {
				$mymodule_item->LoadImages();
				
			}
			
			if( !$mymodule_item->LoadURL() ) {
				$mymodule_item->url = new \OBJ\URL();
			}
	
			$mymodule_item->RenderForm();
		break;
		
		// --------------------------------------------------------------------------------------------- //
	}
	return;
}

// --------------------------------------------------------------------------------------------- //
$list = new \OBJ\AdminList();
$list->SetShowView( true );
$list->SetDeleteTask( "module.my-module.delete" );
$list->SetDeleteTranslation( "module.my-module.form.delete" );

$list->SetSqlSelect( "my_module_item AS mi JOIN url AS u ON u.target_type='my_module_item' AND u.target_id=mi.id" );


$list->AddField( array(
	"name"		=> "id",
	"show"		=> false,
	"table"		=> "mi",
) );

$list->AddField( array(
	"name"		=> "name",
	"text"		=> "#Name#",
	"table"		=> "mi",
) );

$list->AddField( array(
	"name"		=> "path",
	"show"		=> false,
	"table"		=> "u",
	"is-url"	=> true
) );


$listMarkup = $list->Fetch();

// --------------------------------------------------------------------------------------------- //
$markup = <<<MARKUP
<span class="webpage-my-module-list"><a><span></span></a></span>
<h1>#ListMyModule#</h1>
<div class="box">$listMarkup</div>
MARKUP;
// --------------------------------------------------------------------------------------------- //
\BH::$Lang->Translate( $markup, array( "[admin]module.my-module.admin.list", "[admin]languages" ) );

// --------------------------------------------------------------------------------------------- //
echo $markup;

// --------------------------------------------------------------------------------------------- //