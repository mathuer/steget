// --------------------------------------------------------------------------------------------- //
Bliss.Admin.MyModule = function() {};

// --------------------------------------------------------------------------------------------- //
Bliss.Admin.MyModule.HandleAddImage = function() {
	$(document).on('submit', '.my-module_item-add-image', function( e ) {
		e.preventDefault();
		
		if( !$('input[name=image]').val() ){
			Bliss.Admin.AddError("Du måste ange en bildkälla");
		}

		if( Bliss.Admin.GetErrors().length ){
			Bliss.Admin.GetErrorMarkup( $(this) );
			return false;
		}
		
		var path		= $('#image','.my-module_item-add-image').val();
		var description	= encodeURIComponent( $('#description','.my-module_item-add-image').val() );
		var label = '<a href="'+path+'" class="fancybox"><img src="'+ path +'" class="img-responsive" width="30" height="30"><span class="text">' + Bliss.Admin.Basename( path ) + '</span></a>';
		
		var item = Bliss.Admin.CreateItem( label, "images", {path:path, description:description}, "?ajax=my-module_item.edit_image", true, $('#widget-images > *').length );
	
		var widget = $('#widget-images');
		widget.append( item );
		
		$.fancybox.close();
		
		$('hr', widget ).remove();
		var first_item = $('.item:first-of-type', widget);
		first_item.after("<hr>");
		
		return false;
	} );
};
// --------------------------------------------------------------------------------------------- //

Bliss.Admin.MyModule.ChooseFiles = function(input_name){
	moxman.browse(
		{	fields: input_name, 
			no_host: true,
			multiple: true, 
			remember_last_path: true,
			oninsert: function(args) {
				if(args.files.length > 1){
					for(i=0;i<args.files.length;i++){

						var path		= args.files[i].url;
						var description	= '';
						var label = '<a href="'+path+'" class="fancybox"><img src="'+ path +'" class="img-responsive" width="30" height="30"><span class="text">' + Bliss.Admin.Basename( path ) + '</span></a>';
					
						var item = Bliss.Admin.CreateItem( label, "images", {path:path, description:description}, "?ajax=my-module_item.edit_image", true, $('#widget-images > *').length );
						var widget = $('#widget-images');
						widget.append( item );
					}
					$.fancybox.close();
				}else{
					$('#image','.box > form').val(args.files[0].url);
				}
			}
		}
	);
};

// --------------------------------------------------------------------------------------------- //
jQuery( function($) {
	Bliss.Admin.MyModule.HandleAddImage();
} );