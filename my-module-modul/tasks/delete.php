<?php

// --------------------------------------------------------------------------------------------- //
$fields["id"] = array( "required" => true, "empty" => false, "type" => "int" );

// --------------------------------------------------------------------------------------------- //
if( !$this->Validate( $fields ) ) {
	return false;
}

// --------------------------------------------------------------------------------------------- //
$news_item = new \OBJ\MyModuleItem();
$news_item->id = $_POST["id"];
$news_item->DBDelete();

// --------------------------------------------------------------------------------------------- //