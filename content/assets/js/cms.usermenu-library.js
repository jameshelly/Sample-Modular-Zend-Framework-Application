window.CMS = window.CMS || {};
 
var UserMenuLibrary = UI.extend
(
{
    initialize: function()
    {
        this.setupUserMenuLibrary();
    },

    setupUserMenuLibrary: function(callback, scope)
    {

        if ($('aside#prop-listings .alert-message.error').length === 0) {
                $('aside#prop-listings').hide();
        }
        
        $('.delete-node').attr("disabled", true);
        
        var that = this;

        this.set
        (
            {
                userMenuPropertyListings: $('#user-menu #prop-listings')
            }
        );

        $LAB
        .script(window.CMS.config.site.uri + 'library/js/wednesday/controllers/AssetController.js')
        .wait
        (
            function()
            {
                UserMenuLibraryAssetModelView = AssetModelView.extend
                (
                    {
                        render: function()
                        {
                            $(this.el).html(ich.UserMenuLibraryAssetTemplate(this.model.toJSON()));

                            return this;
                        }
                    }
                );

                that.setupUserMenuLibraryTree();
                that.setupUserMenuLibraryDirectoryListings();

                if(callback || callback!='')
                {
                    if(typeof callback === 'undefined')
                    {
                        console.info('MediaLibrary.setupMediaLibrary: Callback function not found.');
                    }
                    else
                    {
                        console.info('MediaLibrary.setupMediaLibrary: Callback to ' + callback);
                        callback.call(scope);
                    }
                }
            }
        );
    },

    setupUserMenuLibraryTree: function()
    {
        console.info('Initialising: Media Library Tree Component');

        var that = this;
        var selected_resource = new Array();
        var resource_num_children;
                
        $LAB
        .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/jstree/v1.0rc3/jquery.jstree.js')
        .wait
        (
            function()
            {
                $.jstree._themes = window.CMS.config.theme.uri + 'css/jstree/';

                var tree = $('.browser-tree')
                .jstree
                (
                {
                    "json_data" :
                    {
                        "ajax" :
                        {
                            "url" : function ()
                            {
                                return '/admin/menuitems/tree';
                            },

                            "data" : function (n)
                            {

                            },

                            "success": function(returned)
                            {
                                return returned.response.data[0].children;
                            }
                        }
                    },
                    "search" :
                    {
                        "case_insensitive" : true,
                        "ajax" :
                        {
                            "url" : window.CMS.config.site.uri + 'api/resources/search.json'
                        }
                    },
                    
                    'rules' : {
                        'multitree' : true,
                        'draggable' : "all"
                    },
                    'themes' :
                    {
                        "theme" : "apple"
                    },
                    "contextmenu" : {
                        "items" : function ($node) {
                            return {
                           "rename" : {
                                "label" : "Rename",
                                "action" : function (obj) { this.rename(obj); }
                            }
                        };
                     }
                    },

                    'plugins':
                    [
                    'search',
                    //'themeroller',
                    'themes',
                    'json_data',
                    'ui',
                    'crrm',
                    'contextmenu',
                    'dnd'
                    ]
                }
                )
                .bind
                (
                    'loaded.jstree',
                    function(event, data)
                    {
                        console.info('Loaded: Media Library Tree Data');

						$('button.create-node')
						.on
						(
							'click',
							function(event)
							{
								tree.jstree('create');
							}
						);
                                                    
                                                    
                                                $('button.delete-node')
						.on
						(
							'click',
							function(event)
							{                                                               
                                                               if (resource_num_children !== 0)
                                                               {
                                                                  that.jgrowl("You can't delete a node with children", {header: 'Menu Items'});
                                                               }
                                                               else
                                                               {
                                                                      //tree.jstree("refresh");

                                                                    if (confirm("Are you sure?"))
                                                                    {                 
                                                                        $.ajax({
                                                                            url: "/api/menuitems/" +  selected_resource.id + '/tree.json',
                                                                            type: 'DELETE',
                                                                            data: { id: selected_resource.id },

                                                                            success: function(response){

                                                                                if (response.response.status == true) {
                                                                                    that.jgrowl("Menu item removed successfully", {header: 'Menu Items'});     
                                                                                }
                                                                                else{
                                                                                    that.jgrowl("Error removing item", {header: 'Menu Items'});     
                                                                                }
                                    
                                                                                $('aside#prop-listings').hide();
                                                                                tree.jstree("refresh");
                                                                            }
                                                                        }); 
                                                                        
                                                                    }
                                                               }

							}
						);

                    }
                )
                .bind
                (
                    'create.jstree',
                    function(event, data)
                    {
                        console.info('Created: Media Library Tree Data');


                        //disable the create node button, so the user can only create 1 node at a time
                        $('.create-node').attr("disabled", true);
                        
                        $('.browser-tree.jstree ul li a').removeClass('jstree-clicked');
                        data.rslt.obj.find('a').attr('class','jstree-clicked');

                        that.jgrowl("Please complete and save the form for the new menu element", {header: 'Menu Items'});


                        //get the new folder name and the absolute paths etc
                        var _newitem_name = data.rslt.name;
			var _parent_id = 1;
						
                        //we have a selected resource, so we need to get the data from that resource to build the new routes
                        //and insert the folder info in the database
			if (selected_resource.id != undefined) {
				_parent_id = selected_resource.id;
			} 

                        //attach new fields to the input form
                        
                        $('form#usermenu_form input#id').val('');
                        $('form#usermenu_form input#parent').val(_parent_id);
                        $('form#usermenu_form input#name').val(_newitem_name);
                        $('form#usermenu_form input#uri').val('');
                        $('form#usermenu_form input#publishstart').val('');
                        $('form#usermenu_form input#publishend').val('');

                        //show form
                        $('aside#prop-listings').show();
                        return false;
                    }
                )
                .bind
                (
                    'select_node.jstree',
                    function(event, data)
                    {
                        console.log('Selected: Tree id ' + data.rslt.obj.attr('id'));

                        var resource;
                        if (data.rslt.obj.attr('id') != undefined ) {
                            
                            resource = data.rslt.obj.attr('id').replace('node-', '');
                        }
                        else {
                            resource = '';
                        }
                            
                        selected_resource.id = resource;
                        $(event.target).closest('section').find('.qq-upload-button').removeAttr('disabled');

                        //that.get('userMenuPropertyListings').empty();
                        that.jgrowl("Please complete and save the form to edit the menu element", {header: 'Menu Items'});

                    	//we use the dir.json call in order to retrieve in one call the information of the directory and its contents.
                        window.UserMenuLibraryAssetController.collection.url = window.CMS.config.site.uri + 'admin/menuitems/resources/' + resource;
                        
                        window.UserMenuLibraryAssetController.collection.fetch
                        (
                            {
                                success: function(collection, resource)
                                {
                                
                                    resource_num_children = resource.response.data[0].children.length;

                                    var name = resource.response.data[0].data;
                                    var uri = resource.response.data[0].attr.uri;
                                    var type = resource.response.data[0].attr.type;
                                    var shoppable = resource.response.data[0].attr.shoppable;
                                    var publishstart = resource.response.data[0].attr.publishstart;
                                    var publishend = resource.response.data[0].attr.publishend;
                                    var parent_id = resource.response.data[0].attr.parent;

                                    
                                    if (shoppable == true){
                                        shoppable = 1;
                                    }
                                    else {
                                        shoppable = 0;
                                    }
                                        
                                    
                                    //populate form with the existing values
                                    $('form#usermenu_form input#uri').attr('value', uri);
                                    $('form#usermenu_form input#publishstart').attr('value', publishstart);
                                    $('form#usermenu_form input#publishend').attr('value', publishend);
                                    $('form#usermenu_form select#type option[value="'+type+'"]').attr('selected','selected');
                                    $('form#usermenu_form select#shoppable option[value="'+shoppable+'"]').attr('selected','selected');
                                    $('form#usermenu_form input#id').val(selected_resource.id);
                                    $('form#usermenu_form input#parent').val(parent_id);
                                    $('form#usermenu_form input#name').val(name);
                                        
                                    //show form
                                    $('aside#prop-listings').show();
                                    $('.delete-node').attr("disabled", false);

                                },
                                error: function(collection, response)
                                {
                                    console.info('Load failed: Assets for folder with resource ID of ' + response);
                                }
                            }
                        );
                    }
                    )
                .bind
                (
                    'open_node.jstree',
                    function(event, data)
                    {
                        //console.log('Opened: Tree id ' + data.args[0].attr("id"));
                    }
                )
                .bind
                (
                    'delete_node.jstree',
                    function(event, data)
                    {
                        
                           var node_id = data.args[0].attr("id").replace('node-', '');

                           if (confirm("Are you sure?")){
                           
                                $.ajax({
                                    url: "/api/menuitems/" +  _node_id + '/tree.json',
                                    type: 'DELETE',
                                    data: { id: node_id },

                                    success: function(response){

                                        if (response.response.status == true) {
                                            that.jgrowl("Menu item removed successfully", {header: 'Menu Items'});     
                                        }
                                        else{
                                            that.jgrowl("Error removing item", {header: 'Menu Items'});     
                                        }
                                    
                                    $('aside#prop-listings').hide();

                                }
                              }); 
                           }                                  
                    }
                )
                .bind
                (
                    'rename_node.jstree',
                    function(event, data)
                    {
                        var _node_id;
                        if (data.rslt.obj.attr('id') != undefined ) {
                            
                            _node_id = data.rslt.obj.attr('id').replace('node-', '');
                        }
                        else {
                            _node_id = '';
                        }
                        console.log(data);
                        
                        var _new_name = data.rslt.name;
                        var _uri = data.rslt.obj.attr("uri");
                        var _type = data.rslt.obj.attr("type");
                        var _shoppable = data.rslt.obj.attr("shoppable");
                        var _publishstart = data.rslt.obj.attr("publishstart");
                        var _publishend = data.rslt.obj.attr("publishend");
                        var _parent_id = data.rslt.obj.attr("parent");

                        
                        if (_shoppable == true){
                            _shoppable = 1;
                        }
                        else {
                            _shoppable = 0;
                        }
                        
                        $.ajax({
                            url: "/api/menuitems/" +  _node_id + '/tree.json',
                            type: 'PUT',
                            data: { id: _node_id, name: _new_name, uri: _uri, type: _type, shoppable: _shoppable, publishstart: _publishstart, publishend: _publishend, parent: _parent_id},

                            success: function(response){

                                if (response.response.status == true) {
                                    that.jgrowl("Menu item renamed successfully", {header: 'Menu Items'});  
                                    
                                    //need to change the name in the hidden form field
                                    $('form#usermenu_form input#name').val(_new_name);
                                }
                                else{
                                    that.jgrowl("Error renaming item", {header: 'Menu Items'});     
                                }
                            }
                        }); 
        

                    }
                )
                    
                .bind("move_node.jstree", function (event, data){ 
                    
                    var _id_moved = data.rslt.o.attr('id').replace('node-', '');
                    var _parent_id;
                    
                    if (data.rslt.np.attr('id') != undefined) {
                        _parent_id = data.rslt.np.attr('id').replace('node-', '');
                    }
                    else {
                        _parent_id = 1;
                    }
                    
                        //retrieve item to move information
                        window.UserMenuLibraryAssetController.collection.url = window.CMS.config.site.uri + 'admin/menuitems/resources/' + _id_moved;
                        
                        window.UserMenuLibraryAssetController.collection.fetch
                        (
                            {
                                success: function(collection, resource)
                                {
                                
                                    resource_num_children = resource.response.data[0].children.length;

                                    var _name = resource.response.data[0].data;
                                    var _uri = resource.response.data[0].attr.uri;
                                    var _type = resource.response.data[0].attr.type;
                                    var _shoppable = resource.response.data[0].attr.shoppable;
                                    var _publishstart = resource.response.data[0].attr.publishstart;
                                    var _publishend = resource.response.data[0].attr.publishend;

                                    if (_publishstart == null) {
                                        _publishstart = '';
                                    }
                                    if (_publishend == null) {
                                        _publishend = '';
                                    }
                                    
                                    if (_shoppable == true){
                                        _shoppable = 1;
                                    }
                                    else {
                                        _shoppable = 0;
                                    }
                                    
                                    
                                    $.ajax({
                                        url: "/api/menuitems/" +  _id_moved + '/tree.json',
                                        type: 'PUT',
                                        data: { id: _id_moved, name: _name, uri: _uri, type: _type, shoppable: _shoppable, publishstart: _publishstart, publishend: _publishend, parent: _parent_id},

                                        success: function(response){
                                            if (response.response.status == true) {
                                                that.jgrowl("Menu item moved successfully", {header: 'Menu Items'});     
                                                $('form#usermenu_form input#parent').val(_parent_id);
                                            }
                                            else{
                                                that.jgrowl("Error moving menu item", {header: 'Menu Items'});     
                                            }
                                        }
                                    }); 
                                        

                                },
                                error: function(collection, response)
                                {
                                    that.jgrowl("Error moving menu item", {header: 'Menu Items'});
                                }
                            }
                        );
                            
                });
                    

            }
            );
    },


    setupUserMenuLibraryDirectoryListings: function()
    {
        console.info('Initialising: UserMenu Library Directory Listings Component');

        window.UserMenuLibraryAssetController = new AssetController.init
        (
            {

            }
        );

        window.AssetEditorAssetModelView = AssetModelView.extend
        (
            {
                tagName: 'form',
                render: function()
                {
                    $(this.el).html(ich.editorAssetTemplate(this.model.toJSON()));

                    return this;
                }
            }
        );

        $('.close-asset-variants')
        .on
        (
            'click',
            function(event)
            {
                $('#media-library > div').removeClass('focus-asset-variants', 1000 );
            }
        );
    }
	    

}
);