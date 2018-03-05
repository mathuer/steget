<?php

// --------------------------------------------------------------------------------------------- //
$fields = array();
$fields["my_module_item"]	= array( "required" => true, "empty" => false, "type" => "array" );
$fields["images"]		= array( "required" => true, "empty" => false, "type" => "array" );
$fields["seo"]			= array( "required" => false, "empty" => true, "type" => "array" );

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
if( !\M::MyModule()->GetReservedPage( ) ) {
	$this->AddError( "#ErrReservedPage#" );
	return false;
}

// --------------------------------------------------------------------------------------------- //
\BH::$Db->BeginTransaction();

// --------------------------------------------------------------------------------------------- //
$mymodule_item = new \OBJ\MyModuleItem( $_POST["my_module_item"] );

if( !$mymodule_item->DBCreate() ) {
	\BH::$Db->RollBack();
	$this->AddError( "Create failed (1)" );
	return false;
}

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
\BH::$Url->Redirect( \BH::$Url->UnsetArg( "action" ) );

// --------------------------------------------------------------------------------------------- //