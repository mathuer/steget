<?php

// --------------------------------------------------------------------------------------------- //
$fields = array();
$fields["my_module_item"]	= array( "required" => true, "empty" => false, "type" => "array" );
$fields["images"]		= array( "required" => false, "empty" => true, "type" => "array" );

// --------------------------------------------------------------------------------------------- //
if( !$this->Validate( $fields ) ) {
	return false;
}

// --------------------------------------------------------------------------------------------- //
$fields = array();
$fields["name"]			= array( "required" => true, "empty" => false, "min-length" => 3, "max-length" => 128, "type" => "string" );
$fields["content"]		= array( "required" => true, "empty" => false, "type" => "string" );
$fields["language_id"]	= array( "required" => true, "empty" => false, "min-length" => 2, "max-length" => 2, "type" => "string" );

// --------------------------------------------------------------------------------------------- //
if( !$this->Validate( $fields, $_POST["my_module_item"] ) ) {
	return false;
}
// --------------------------------------------------------------------------------------------- //
if( !\M::MyModule()->GetReservedPage( $_POST["my_module_item"]["language_id"]  ) ) {
	$this->AddError( "#ErrReservedPage#" );
	return false;
}

// --------------------------------------------------------------------------------------------- //
\BH::$Db->BeginTransaction();

// --------------------------------------------------------------------------------------------- //
if( !$mymodule_item = \OBJ\MyModuleItem::DBGet( $_GET["id"] ) ) {
	\BH::$Db->RollBack();
	$this->AddError( "Update failed (1)" );
	return false;
}

$old_language_id = $mymodule_item->language_id;;

$mymodule_item->SetFieldsFromArray( $_POST["my_module_item"] );
$mymodule_item->UpdateURL( $old_language_id );

if( !$mymodule_item->DBUpdate() ) {
	\BH::$Db->RollBack();
	$this->AddError( "Update failed (2)" );
	return false;
}

// --------------------------------------------------------------------------------------------- //

// Images
if( isset( $_POST["images"] ) ) {
	foreach( $_POST["images"] as $image_data ) {
		$image = new \OBJ\MyModuleItemImage();
		$image->SetFieldsFromArray( $image_data );
		$image->description = urldecode( $image->description );
		$image->module_item_id = $mymodule_item->id;
		if( !$image->DBCreate() ) {
			\BH::$Db->RollBack();
			$this->AddError( "Create failed (6)" );
			return false;
		}
	}
}

// --------------------------------------------------------------------------------------------- //
\BH::$Db->Commit();

// --------------------------------------------------------------------------------------------- //
\BH::$Url->Redirect( \BH::$Url->UnsetArgs( array( "action", "id" ) ) );

// --------------------------------------------------------------------------------------------- //