<?php

// --------------------------------------------------------------------------------------------- //
namespace C\Module;

// --------------------------------------------------------------------------------------------- //
class MyModule extends \EXT\Addon {
	
	// --------------------------------------------------------------------------------------------- //
    public function OnInit() {
		
		// --------------------------------------------------------------------------------------------- //
		// Autoloads
		$this->AddAutoload( "OBJ\MyModuleItem"			, "php/class/my-module_item.php" );
		$this->AddAutoload( "OBJ\MyModuleItemImage"		, "php/class/my-module_item_image.php" );
		// --------------------------------------------------------------------------------------------- //

	}
	
	public function OnInitAdminPanel() {
		
		// --------------------------------------------------------------------------------------------- //
		// Events
		$this->AddEvent( "admin.webpage.my-module", "RenderAdmin" );
		
		// --------------------------------------------------------------------------------------------- //
		// Templates
		$this->AddTpl( "my-module.admin_form", "tpl/admin/form.php", array( "[admin]module.my-module.admin.form", "[admin]languages" ) );
		$this->AddTpl( "my-module_item.add_image"	, "tpl/admin/ajax.add_image.php"		, "[admin]product.add_image" );
		// Ajax
		$this->AddAjaxTpl( "my-module_item.add_image" , "my-module_item.add_image" );
		// --------------------------------------------------------------------------------------------- //
		// Tasks
		$this->AddTask( "module.my-module.create", "tasks/create.php" );
		$this->AddTask( "module.my-module.update", "tasks/update.php" );
		$this->AddTask( "module.my-module.delete", "tasks/delete.php" );
		
		// --------------------------------------------------------------------------------------------- //
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function OnInitNormal() {
		
		// --------------------------------------------------------------------------------------------- //
		// Javascript
		$this->AddJs( "m.my-module"						, "js/my-module.js" );
		
		// --------------------------------------------------------------------------------------------- //
		// Events
		
		$this->AddEvent( "m.my_module.reserved_page"		, "ReservedPage" );
		
		// --------------------------------------------------------------------------------------------- //
		// URL Handler
		$this->AddEvent( "c.url_target.my_module_item", "URLTarget" );
		
		// --------------------------------------------------------------------------------------------- //
		// Hooks
		$this->AddHook( "my-module-list", "Hook_List" );
		$this->AddHook( "my-module-item", "Hook_Item" );
		
		// --------------------------------------------------------------------------------------------- //
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function OnLoadAdmin() {
		$this->Admin_AddAdminMenuItems();
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function Admin_AddAdminMenuItems() {
		\BH::$Menu->AddItem( "admin-menu", "webpage-my-module"		, "#my-module#"		, "/admin/?section=webpage&part=my-module"				, "webpage"		, \BH::$Admin->Is( "webpage", "my-module" )			, "k", null, "fa fa-calendar" );
		\BH::$Menu->AddItem( "admin-menu", "webpage-my-module-create", "#Createmy-module#", "/admin/?section=webpage&part=my-module&action=create"	, "webpage-my-module", \BH::$Admin->Is( "webpage", "my-module", "create" ), "a",  null, "fa fa fa-plus-circle" );
		\BH::$Menu->AddItem( "admin-menu", "webpage-my-module-list"	, "#Listmy-module#"	, "/admin/?section=webpage&part=my-module"				, "webpage-my-module", \BH::$Admin->Is( "webpage", "my-module", false )	, "b" , null, "fa fa fa-list-ul" );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function OnLoadAdminPanel() {
		$this->Admin_AddResources();
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function Admin_AddResources() {
		if( !\BH::$Admin->Is( "webpage", "my-module" ) ) {
			return false;
		}
		/*\M::jQuery()->EnableAddon( "datetimepicker" );*/
		$this->AddJs( "module.my-module.admin", "js/admin.js", true );
	}
	
	// --------------------------------------------------------------------------------------------- //
	
	public function Render_Menu( $current=null ) {
		$items_sql = \BH::$Db->Get(
			"SELECT m.id, m.name FROM my_module_item AS m JOIN url AS u ON u.target_id=m.id WHERE u.target_type='my_module_item' ORDER BY m.id ASC",
			array(
				"language_id" => \BH::$Lang->id,
			)
		);
		
		$reserved_page = $this->GetReservedPage();
		$reserved_page->LoadURL();
		
		$first_month = null;
		$menu = new \OBJ\Menu();
		$menu->AddItem( "RP", "#All My-Module#", $reserved_page->url->path );
		
		$args["items"]	= $menu->Build();
		$args["name"]	= "#Archives#";
		$args["class"]	= "my-module-menu";
		$args["url"]	= \M::ReservedPage()->GetURLPath("m.my_module.reserved_page");
		
		\BH::$Template->Render( "c.snippet.side_menu", $args, array( "date.month", "m.my_module.archive" ) );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function ReservedPage() {
		$items_count = \BH::$Db->GetSingle( "SELECT COUNT(*) AS count FROM my_module_item" );
		$this->AddEvent( "sidebar-left"	, "Render_Menu" );
		$this->AddEvent( "content"		, "Render_ReservedPage" );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function URLTarget( $mymodule_item ) {
		$this->AddEvent( "sidebar-left"	, "Render_Menu", $mymodule_item );
		$this->AddEvent( "content"		, "Render", $mymodule_item );
	}
	
	
	// --------------------------------------------------------------------------------------------- //
	public function RenderAdmin() {
		require( $this->GetPath() . "php/admin/my-module.php" );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function Hook_List( $args=null ) {
		if( !isset( $args["item-count"] ) ) {
			$limit = \BH::$Options->Get( "module.my-module.item_limit", 5 );	
		}
		else{
			$limit = $args["item-count"];
		}
		
		$items_sql = \BH::$Db->Get( "SELECT * FROM my_module_item LIMIT $limit", array( "language_id" => \BH::$Lang->id ) );
		
		if( !$items_sql ) {
			return false;
		}
		
		$items = array();
		foreach( $items_sql as $item ) {
			$mymodule_item = new \OBJ\MyModuleItem( $item );
			$mymodule_item->LoadImages();
			$items[] = $mymodule_item;
		}
		
		$args["items"] = $items;
		
		if( !isset( $args["img-width"] ) ) {
			$args["img-width"] = 512;
		}
		if( !isset( $args["img-height"] ) ) {
			$args["img-height"] = 128;
		}
		
		$reserved_page = $this->GetReservedPage();
		$reserved_page->LoadURL();
		$args["reserved_page"] = $reserved_page->url->path;
		
		\BH::$Template->Render( "my-module.hook.list", $args );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function Hook_Item( $args=null ) {
		if( !isset( $args["id"] ) ) {
			return false;
		}

		$mymodule_item = \OBJ\MyModuleItem::DBGet( $args["id"] );
		if( !$mymodule_item ) {
			return false;
		}
		
		if( !isset( $args["img-width"] ) ) {
			$args["img-width"] = 512;
		}
		if( !isset( $args["img-height"] ) ) {
			$args["img-height"] = 128;
		}
		
		$args["my_module_item"] = $mymodule_item;
		
		$mymodule_item->LoadURL();

		\BH::$Template->Render( "my-module.hook.main", $args );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function Render( $mymodule_item ) {
		$mymodule_item->RenderMainView();
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function Render_ReservedPage() {
		$limit = \BH::$Options->Get( "module.my-module.item_limit", 5 );
		
		$offset = 0;
		if( isset( $_GET["page"] ) && ctype_digit( $_GET["page"] ) ) {
			$offset = ($_GET["page"] - 1) * $limit;
		}
		
		$items_count = \BH::$Db->GetSingle( "SELECT COUNT(*) AS count FROM my_module_item", array( "language_id" => \BH::$Lang->id ), false );
		
		$items_sql = \BH::$Db->Get( "SELECT * FROM my_module_item LIMIT $offset,$limit", array( "language_id" => \BH::$Lang->id ) );
		
		$args["items"] = array();
		if( $items_sql ) {
			foreach( $items_sql as $item ) {
				$args["items"][] = new \OBJ\MyModuleItem( $item );
			}
			$args["count"] = count( $args["items"] );
		}
		
		if( $items_count["count"] > $limit ) {
			$pager = new \OBJ\Pager();
			$pager->SetTotal( $items_count["count"] );
			$pager->SetPerPage( $limit );
			$pager->SetCurrent( isset( $_GET["page"] )?$_GET["page"]:1 );
			$args["pager"] = $pager->RenderBootstrap( true );
		}
		
		\BH::$Template->Render( "my_module.reserved_page", $args );
	}
	
	// --------------------------------------------------------------------------------------------- //
	public function GetReservedPage( $language_id=null ) {
		return \M::ReservedPage()->Get( "m.my_module.reserved_page", $language_id );
	}
	
	// --------------------------------------------------------------------------------------------- //
}

// --------------------------------------------------------------------------------------------- //
