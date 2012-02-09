window.CMS = window.CMS || {};

var MediaLibrary = UI.extend
(
{
    initialize: function()
    {
        this.setupMediaLibrary();
    },

    setupMediaLibrary: function(callback, scope)
    {

        console.info('Initialising: Media Library Core');

        var that = this;

        this.set
        (
            {
                mediaLibraryAssetListings: $('#media-library .asset-listings table tbody'),
                mediaLibraryAssetCount: $('#media-library .asset-listings table tfoot span.count'),
                mediaVariationsAssetCount: $('#media-library .thumbnail-listings table.footer tfoot tr td span.count'),
                mediaVariationsAssetListings: $('#media-library .thumbnail-listings .table-scroller table tbody')

            }
        );

        $LAB
        .script(window.CMS.config.site.uri + 'library/js/wednesday/controllers/AssetController.js')
        .script(window.CMS.config.site.uri + 'library/js/wednesday/controllers/MediaVariationController.js')
        .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/jcrop/jquery.Jcrop.min.js')
        .wait
        (
            function()
            {
                MediaLibraryAssetModelView = AssetModelView.extend
                (
                    {
                        render: function()
                        {
                            $(this.el).html(ich.mediaLibraryAssetTemplate(this.model.toJSON()));

                            return this;
                        }
                    }
                );


                MediaVariationsModelView = MediaVariationModelView.extend
                (
                    {
                        render: function()
                        {
                            $(this.el).html(ich.mediavariationManagerTemplate(this.model.toJSON()));

                            return this;
                        }
                    }
                );

                that.setupMediaLibraryTree();
                that.setupMediaLibraryDirectoryListings(that.get('editor'));
                that.setupMediaLibraryUploader();

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

    setupMediaLibraryTree: function()
    {
        console.info('Initialising: Media Library Tree Component');

        var that = this;
        var selected_resource = new Array();

        $LAB
        .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/jstree/v1.0rc3/jquery.jstree.js')
        .wait
        (
            function()
            {
                $.jstree._themes = window.CMS.config.theme.uri + 'css/jstree/';
                var tree = $('.browser-tree', that.get('content'))
                .jstree
                (
                {
                    "json_data" :
                    {
                        "ajax" :
                        {
                            "url" : function ()
                            {
//                                var resource = this.id ? this.id : '1';
//                                return window.CMS.config.site.uri + 'api/resources/' + resource + '/tree.json';
                                    return window.CMS.config.site.uri + 'api/resources/tree.json';
                            },

                            "data" : function (n)
                            {
                                //return { id : n.attr ? n.attr("id") : 0 };
                                //return null;
                                return { type : 'dir' };
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
                    'themes' :
                    {
                        "theme" : "apple"
                    },
                    'plugins':
                    [
                    'search',
                    //'themeroller',
                    'themes',
                    'json_data',
                    'ui',
                    'crrm',
                    'contextmenu'
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

                    }
                )
                .bind
                (
                    'create.jstree',
                    function(event, data)
                    {
                        console.info('Created: Media Library Tree Data');
                        console.log('Save new folder action');

						//get the new folder name and the absolute paths etc
						var _folder_name = data.rslt.name;

						_folder_name = _folder_name.replace(/\s+/g, "-");

						var _path = '/assets/content';
						var _parent_id = 1;
						var _root = 1;

						//we have a selected resource, so we need to get the data from that resource to build the new routes
						//and insert the folder info in the database
						if (selected_resource.name != undefined) {
							_parent_id = selected_resource.id;
							_path = selected_resource.path + '/' + selected_resource.name;
							_link = selected_resource.link + '/' + _folder_name;
						}
						else {
							_link = '/assets/content/' + _folder_name;
						}

						//create the folder using AssetModel
                        window.treeAssetModel = new AssetModel
                        (
                            {
                            	name: _folder_name,
                            	parent: _parent_id,
                            	mimetype: 'application/directory',
                                title: _folder_name,
                                stored: null,
                                root: _root,
                                cdn: 'false',
                                sortorder: 1,
                                lft: 1,
                                lvl: 0,
                                rgt: 1,
                                type: 'dir',
                                path: _path,
                                link: _link
                            }
                        );

                        treeAssetModelView = new MediaLibraryAssetModelView
                        (
                            {
                                model : window.treeAssetModel
                            }
                        );


					    treeAssetModel.save();
                        console.log(JSON.stringify(treeAssetModel));

                    }
                )
                .bind
                (
                    'select_node.jstree',
                    function(event, data)
                    {
                        console.log('Selected: Tree id ' + data.rslt.obj.attr('id'));

                        that.jgrowl("Please wait while asset information is loaded.", { header: 'Media Library' });

                        var resource = data.rslt.obj.attr('id').replace('node-', '');

                        selected_resource.id = resource;

                        $(event.target).closest('section').find('.qq-upload-button').removeAttr('disabled');

                        that.get('mediaLibraryAssetListings').empty();

                        that.get('mediaLibraryAssetCount').text(0);

                    	//we use the dir.json call in order to retrieve in one call the information of the directory and its contents.
                        window.MediaLibraryAssetController.collection.url = window.CMS.config.site.uri + 'api/resources/' + resource + '/dir.json';

                        window.MediaLibraryAssetController.collection.fetch
                        (
                            {
                                success: function(collection, resource)
                                {

                                	console.log('RESPONSE', resource.response);

                                    selected_resource.name = resource.response.data.name;
                            		selected_resource.link = resource.response.data.link;
                            		selected_resource.path = resource.response.data.path;


                                	window
                                	.uploader
                                	.setParams
                                	(
                                    	{
                                        	filepath: resource.response.data.path + '/' + resource.response.data.name
                                    	}
                                	);

                                	that.set
                                	(
                                    	{
                                        	filepath: resource.response.data.path + '/' + resource.response.data.name
                                    	}
                                	);

									//we already have the information for the children elements, so let's loop into them and show them!
									for (child_element in resource.response.data.children) {

										//if the file is not an image, then we need to load its preview icon according with its mimetype
										if (resource.response.data.children[child_element].mimetype.indexOf('image') != 0)
										{
											//we replace the link of the data received in order to show a proper preview
											resource.response.data.children[child_element].link = that.getFilePreviewIconPath(resource.response.data.children[child_element].mimetype);
										}

                                    	var assetModel = new AssetModel(resource.response.data.children[child_element]);




                                    	assetModelView = new MediaLibraryAssetModelView
                                    	(
                                        	{
                                            	model : assetModel
                                        	}
                                    	);

                                    	that.get('mediaLibraryAssetListings').append
                                    	(
                                        	assetModelView.render().el
                                    	);

                                    	that.get('mediaLibraryAssetCount').text
                                    	(
                                        	function(index, text)
                                        	{
                                            	return (parseInt(text) + 1)
                                        	}
                                    	);
                                    }

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
                        console.log('Opened: Tree id ' + data.args[0].attr("id"));
                    }
                    );

            }
            );
    },

	/*
	* getFilePreviewIconPath
	* given the mimetype of the file, returns the path of the preview icon that corresponds with that mimetype
	*/
	getFilePreviewIconPath: function(mimetype)
	{
		var preview_path = '';

		//generic: all video mimetypes
		if (mimetype.indexOf('video') == 0)
		{
			preview_path = window.CMS.config.assets.img_custom_video_uri;
		}
		//generic: all audio mimetypes
		else if (mimetype.indexOf('audio') == 0)
		{
			preview_path = window.CMS.config.assets.img_custom_audio_uri;
		}
		else //specific cases
		{
			switch(mimetype)
			{
				case 'application/pdf':
  				preview_path = window.CMS.config.assets.img_custom_pdf_uri;
  				break;
				case 'application/vnd.ms-powerpoint':
  				preview_path = window.CMS.config.assets.img_custom_ppt_uri;
  				break;
				case 'application/vnd.ms-excel':
				case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
				case 'application/vnd.openxmlformats-officedocument.spreadsheetml.template':
  				preview_path = window.CMS.config.assets.img_custom_excel_uri;
  				break;
				case 'application/msword':
				case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
				case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
  				preview_path = window.CMS.config.assets.img_custom_word_uri;
  				break;
				default:
  				preview_path = window.CMS.config.assets.img_custom_doc_uri;
			}
		}

		return preview_path;

	},

    setupMediaLibraryDirectoryListings: function(editor)
    {
        var that = this;


        console.info('Initialising: Media Library Directory Listings Component');

        window.MediaVariationController = new MediaVariationController.init
        (
            {

            }
        );


        window.MediaLibraryAssetController = new AssetController.init
        (
            {

            }
        );

        window.AssetEditorAssetModelView = AssetModelView.extend
        (
            {
                tagName: 'form',
                render: function(editor)
                {

                    switch (editor) {
                        case 'homepage':
                            $(this.el).html(ich.homepageEditorAssetTemplate(this.model.toJSON()));
                            break;
                        case 'default':
                        default:
                            $(this.el).html(ich.editorAssetTemplate(this.model.toJSON()));
                            break;
                    }

//                    $(this.el).html(ich.editorAssetTemplate(this.model.toJSON()));
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
                $('#media-library > div').removeClass('focus-asset-variants-variations', 1000 );
            }
        );

        $('.asset-variants-variations .actions .btn.reset')
        .on
        (
            'click',
            function(event)
            {
                $('#media-library > div').removeClass('focus-asset-variants-variations', 1000 );
            }
        );

        $('.asset-variants-variations .actions .btn.save')
        .on
        (
            'click',
            function(event)
            {

               var _variation_id = $('#cropping-tool img#jcrop_target').attr('variation_id');
               var offset_x = $('#cropping-tool div.jcrop-holder div.jcrop-tracker').attr('coord_x');
               var offset_y = $('#cropping-tool div.jcrop-holder div.jcrop-tracker').attr('coord_y');
               var width = $('#cropping-tool div.jcrop-holder div.jcrop-tracker').attr('coord_w');
               var height = $('#cropping-tool div.jcrop-holder div.jcrop-tracker').attr('coord_h');
               var _orig_width = $('#cropping-tool img#jcrop_target').attr('original_width');
               var _orig_height = $('#cropping-tool img#jcrop_target').attr('original_height');
               var _crop_area_width = $("#cropping-tool img#jcrop_target").width();
               var _crop_area_height = $("#cropping-tool img#jcrop_target").height();

               if (_variation_id != undefined && offset_x != undefined && offset_y != undefined && width != undefined && height != undefined) {

                    $.ajax({
                        url: window.CMS.config.site.uri + 'api/mediavariations/crop.json',
                        type: 'POST',
                        dataType: 'json',
                        async: false,
                        data: { id: 'crop', variation_id: _variation_id,  pos_x: offset_x, pos_y: offset_y, img_width: width, img_height: height, orig_width: _orig_width, orig_height: _orig_height,
                                crop_img_width: _crop_area_width, crop_img_height: _crop_area_height},

                        success: function(response){
                            if (response.response.status == 'success'){
                                that.jgrowl("Variation successfully cropped.", { header: 'Media Library' });
                            } else {
                                that.jgrowl("Error cropping variation", { header: 'Media Library' });
                            }

                        },

                        error: function(response) {
                            that.jgrowl("Error cropping variation", { header: 'Media Library' });
                        }
                    });


                    //reload the model with the new data after save it
                    window.MediaVariationController.collection.url = window.CMS.config.site.uri + 'api/mediavariations/get/' + editorAssetModelView.model.id + '.json';

                    window.MediaVariationController.collection.fetch
                    (
                        {
                            success: function(collection, resource)
                            {
                                $('#media-library .thumbnail-listings .table-scroller table tbody').empty();

                                for (element in resource.response.data) {

                                    var assetModel = new MediaVariationModel(resource.response.data[element]);

                                    editorMediaVariationModelView = new MediaVariationsModelView
                                    (
                                        {
                                            model : assetModel
                                        }
                                    );

                                    $('#media-library .thumbnail-listings .table-scroller table tbody').append
                                    (
                                        editorMediaVariationModelView.render().el
                                    );
                                }

                                $('#media-library .thumbnail-listings table.footer tfoot tr td span.count').text(resource.response.data.length);
                            },
                            error: function(collection, response)
                            {
                                console.info('Load failed: Assets for folder with resource ID of ' + response);
                            }
                        }
                    );

                    $('#media-library > div').removeClass('focus-asset-variants-variations', 1000 );
               }
            }
        );


        $('.asset-variants-variations .actions .btn.delete')
        .on
        (
            'click',
            function(event)
            {

               var _variation_id = $('#cropping-tool img#jcrop_target').attr('variation_id');

               if (_variation_id != undefined) {

                    $.ajax({
                        url: window.CMS.config.site.uri + 'api/mediavariations/delete.json',
                        type: 'POST',
                        async: false,
                        dataType: 'json',
                        data: { id: 'delete', variation_id: _variation_id  },

                        success: function(response){
                            if (response.response.status == 'success'){
                                that.jgrowl("Variation successfully deleted.", { header: 'Media Library' });
                            } else {
                                that.jgrowl("Error removing variation", { header: 'Media Library' });
                            }

                        },

                        error: function(response) {
                            that.jgrowl("Error removing variation", { header: 'Media Library' });
                        }
                    });


                //reload the model with the new data after the deletion
                window.MediaVariationController.collection.url = window.CMS.config.site.uri + 'api/mediavariations/get/' + editorAssetModelView.model.id + '.json';

                window.MediaVariationController.collection.fetch
                (
                    {
                        success: function(collection, resource)
                        {
                            $('#media-library .thumbnail-listings .table-scroller table tbody').empty();

                            for (element in resource.response.data) {

                                var assetModel = new MediaVariationModel(resource.response.data[element]);

                                editorMediaVariationModelView = new MediaVariationsModelView
                                (
                                    {
                                        model : assetModel
                                    }
                                );

                                $('#media-library .thumbnail-listings .table-scroller table tbody').append
                                (
                                    editorMediaVariationModelView.render().el
                                );
                            }

                            $('#media-library .thumbnail-listings table.footer tfoot tr td span.count').text(resource.response.data.length);
                        },
                        error: function(collection, response)
                        {
                            console.info('Load failed: Assets for folder with resource ID of ' + response);
                        }
                    }
                );

                 $('#media-library > div').removeClass('focus-asset-variants-variations', 1000 );
               }
            }
        );
    },

    setupMediaLibraryUploader: function()
    {
        console.info('Initialising: Media Library Uploader Component');

        var that = this;

        $LAB
        .script(window.CMS.config.site.uri + 'library/js/fileuploader/vb3b20b156d/fileuploader.js')
        .wait
        (
            function()
            {
                window.uploader = new qq.FileUploader
                (
                {
                    // pass the dom node (ex. $(selector)[0] for jQuery users)
                    element: that.get('content').find('.uploader')[0],

                    //button: null,
                    // url of the server-side upload script, should be on the same domain
                    action: window.CMS.config.site.uri + 'admin/assets/upload',

                    // additional data to send, name-value pairs
                    params: {},

                    // validation
                    //allowedExtensions: ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'mp4', 'm4v', 'pdf', '.doc', 'docx', 'xls', 'xlsx'],
                    allowedExtensions: [],

                    // each file size limit in bytes
                    // this option isn't supported in all browsers
                    sizeLimit: 0, // max size
                    minSizeLimit: 0, // min size

                    template: '<div class="qq-uploader">' + '<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' + '<div class="qq-upload-button btn primary">Upload New Asset(s)' + '<ul class="qq-upload-list"></ul>' + '</div>',

                    // set to true to output server response to console
                    debug: true,

                    // events
                    // you can return false to abort submit
                    onSubmit: function(id, fileName)
                    {
                        that.jgrowl("Please wait while the asset is uploaded.", { header: 'Media Library' });

                        window.uploadAssetModel = new AssetModel
                        (
                            {
                                id: 'upload-' + id,
                                preview: window.CMS.config.site.uri + 'assets/img/holding.jpg',
                                progress: 0,
                                title: fileName,
                                type: 'Unknown',
                                status: 'uploading',
                                link: '/assets' + that.get('filepath') + '/' + fileName
                            }
                        );

                        uploadAssetModelView = new MediaLibraryAssetModelView
                        (
                            {
                                model : window.uploadAssetModel
                            }
                        );

                        that.get('mediaLibraryAssetListings').append
                        (
                            uploadAssetModelView.render().el
                        );

                        that.get('mediaLibraryAssetCount').text
                        (
                            function(index, text)
                            {
                                return (parseInt(text) + 1)
                            }
                        );

                        console.log(JSON.stringify(uploadAssetModel));
                    },

                    onProgress: function(id, fileName, loaded, total)
                    {
                        var percentage = parseInt((loaded / total) * 100);

                        //var uploading = window.MediaLibraryAssetController.collection.get('upload-' + id);
                        window.uploadAssetModel.set
                        (
                            {
                                progress: percentage
                            }
                        );

                        console.log(JSON.stringify(window.uploadAssetModel));
                    },

                    onComplete: function(id, fileName, responseJSON)
                    {
                        window.uploadAssetModel.set
                        (
                            {
                                id: responseJSON.id,
                                preview: responseJSON.title,
                                status: 'ready'
                            }
                        );

                        that.jgrowl("Asset was successfully uploaded.", { header: 'Media Library' });

                        console.log(JSON.stringify(responseJSON));
                    },

                    onCancel: function(id, fileName)
                    {
                        that.jgrowl("Asset was uploaded process was cancelled.", { header: 'Media Library' });

                        console.log('cancelled');
                    },

                    messages:
                    {
                        // error messages, see qq.FileUploaderBasic for content
                        typeError: '{file} has invalid extension. Only {extensions} are allowed.',
                        sizeError: '{file} is too large, maximum file size is {sizeLimit}.',
                        minSizeError: '{file} is too small, minimum file size is {minSizeLimit}.',
                        emptyError: '{file} is empty, please select files again without it.',
                        onLeave: 'The files are being uploaded, if you leave now the upload will be cancelled.'
                    },

                    showMessage: function(message)
                    {
                        console.log('message: ' + message);
                    }
                }
                );

                that.get('content').find('button.upload').bind
                (
                    'click',
                    function()
                    {
                        $('div.qq-upload-button input').trigger('click');
                    }
                    );

            }
            );
    }


}
);