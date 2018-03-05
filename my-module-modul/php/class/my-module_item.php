<?php

// --------------------------------------------------------------------------------------------- //
namespace OBJ;

// --------------------------------------------------------------------------------------------- //
class MyModuleItem extends \EXT\DBObject {
	
	// --------------------------------------------------------------------------------------------- //
	const DB_TABLE_NAME		= "my_module_item";
	const DB_EXCLUDE_FIELDS = "url,images";
	
	// --------------------------------------------------------------------------------------------- //
	public $id;
	public $name;
	public $content;
	public $language_id;
	
	public $url;
	public $images;
	
	// --------------------------------------------------------------------------------------------- //
	
	public function OnConstructId() {
		$this->DBLoad();
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function LoadURL() {
		if( $this->url ) {
			return true;
		}
		if( !$this->url = \BH::$Url->GetFromTarget( "my_module_item", $this->id, $this->language_id ) ) {
			return false;
		}
		return true;
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function OnDelete() {
		\BH::$Url->DeleteFromTarget( "my_module_item", $this->id, $this->language_id );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function OnCreate() {
		$reserved_page = \M::MyModule()->GetReservedPage( $this->language_id );
		$reserved_page->LoadURL();
		
		$url = new \OBJ\URL();
		$url->GeneratePathFromString( $this->name, $reserved_page->url->path );
		$url->language_id	= $this->language_id;
		$url->target_type	= "my_module_item";
		$url->target_id		= $this->id;
		$url->DBCreate();
		$this->url = $url;
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function OnUpdate() {
		if( !$this->LoadURL() ) {
			return false;
		}
		
		$reserved_page = \M::MyModule()->GetReservedPage( $this->language_id );
		$reserved_page->LoadURL();
		$this->url->language_id = $this->language_id;
		$this->url->GeneratePathFromString( $this->name, $reserved_page->url->path );
		$this->url->DBUpdate( array( "path", "language_id" ) );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function UpdateURL( $old_language_id ){
		$this->url = \BH::$Url->GetFromTarget( "my_module_item", $this->id, $old_language_id );

		if( !$this->url ) {
			return false;
		}
		
		$this->url->language_id = $this->language_id;
		$this->url->DBUpdate( "language_id" );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function RenderForm() {
		$args["mymodule_item"] = $this;

		\BH::$Template->Render( "my-module.admin_form", $args );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function LoadImages() {
		if( $this->images ) {
			return true;
		}
		
		if( $this->images === false ) {
			return false;
		}
		
		$this->images = \BH::$Db->GetObj( "SELECT * FROM my_module_item_image WHERE module_item_id=:id", array( "id" => $this->id ), "OBJ\MyModuleItemImage" );
		
		if( !$this->images ) {
			return false;
		}
		return true;
	}
	// --------------------------------------------------------------------------------------------- //
	public function ClearImages() {
		\BH::$Db->Set( "DELETE FROM my_module_item_image WHERE module_item_id=:id", array( "id" => $this->id ) );
		$this->images = null;
	}
	// --------------------------------------------------------------------------------------------- //
	public function RenderMainView() {
		$this->LoadImages();
		
		$args = array();
		$args["my-module_item"]	= $this;
		$args["name"]			= $this->name;
		$args["content"]		= $this->content;
		
		$args["main_image"]		= false;
		$args["images"]			= false;		
		if( $this->images && is_array( $this->images ) && !empty( $this->images ) ) {
			$args["main_image"] = reset( $this->images );
			
			if( count( $this->images ) > 1 ) {
				$args["images"] = array_slice( $this->images, 1 );
			}
		}
	
		\BH::$Template->Render( "my-module.view.main", $args );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function RenderListView() {
		
		$this->LoadURL();
		$this->LoadImages();
		$args = array();
		$args["my-module_item"]	= $this;
		$args["name"]			= $this->name;
		$args["content"]		= $this->content;
		$args["url"]			= $this->url->path;
		
		$args["main_image"]		= false;
		$args["images"]			= false;		
		if( $this->images && is_array( $this->images ) && !empty( $this->images ) ) {
			$args["main_image"] = reset( $this->images );
			
			if( count( $this->images ) > 1 ) {
				$args["images"] = array_slice( $this->images, 1 );
			}
		}
		
		\BH::$Template->Render( "my-module.view.list", $args );
	}
}

// --------------------------------------------------------------------------------------------- //