<?php 

namespace OBJ;

// --------------------------------------------------------------------------------------------- //


class MyModuleItemImage extends \EXT\DbObject {

	const DB_TABLE_NAME = "my_module_item_image";
	const DB_EXCLUDE_FIELDS	= null;
	
	public $id;
	public $module_item_id;
	public $path;
	public $description;
}