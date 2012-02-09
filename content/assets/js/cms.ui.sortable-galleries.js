window.CMS = window.CMS || {};

var SortableGalleries = UI.extend
(
	{
		initialize: function()
		{
		    console.info('Initialising: Sortable Galleries Core');

            this.loadSortableGalleryComponents();
		},
		
		loadSortableGalleryComponents: function()
		{
		    this.attachSortableGalleryBehavior();
		    this.attachGalleryItemBehaviors();
		},
		
		attachSortableGalleryBehavior: function()
		{
            var sortableGallery = $('ul.media-grid');
//            var gallery = $(this).closest('div.gallery');

            var that = this;

			$LAB
			.script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/v1.8.16/jquery-ui-1.8.16.js')
			.wait
			(
				function()
				{
                    sortableGallery.sortable
                    (
                        {
                            placeholder: "ui-state-highlight ui-sortable-placeholder",
                            //forcePlaceholderSize: true,
                            stop: function(event, ui)
                            {
                                var gallery = $(event.target).closest('div.gallery');
                                var mediaGrid = $(event.target).closest('ul.media-grid');
                                
                                //var order = $(event.target).sortable('serialize');
                                var order = mediaGrid.sortable('toArray');
                                
                                console.log('Setting gallery contents to: ' + order);
                                
                                $('input:hidden.resources', gallery).val(order);
                            }
                        }
                    );

					console.info('Activated: SortableGalleries.attachSortableGalleryBehavior');
				}
			);

		},
		
		attachGalleryItemBehaviors: function()
		{
            var that = this;

            $('div.media-grid.featured button.media-remove').bind
            (
                'click',
                function(event)
                {
                    var gallery = $(event.target).closest('div.gallery');

                    $(event.target)
                    .parent()
                    .fadeOut
                    (
                        'slow',
                        function()
                        {
                            $(this).remove();

                            console.log('Setting featured item contents to nothing.');

                            $('input.featured-resource', gallery).val('');
                        }
                    );
                }
            );
        
            $('ul.media-grid button.media-remove').bind
            (
                'click',
                function(event)
                {
                    var gallery = $(event.target).closest('div.gallery');
                    var mediaGrid = $(event.target).closest('ul.media-grid');

                    $(event.target)
                    .parent()
                    .fadeOut
                    (
                        'slow',
                        function()
                        {
                            $(this).remove();

                            var order = mediaGrid.sortable('toArray');

                            console.log('Setting gallery contents to: ' + order);

                            $('input.resources', gallery).val(order);
                        }
                    );
                }
            );

            $('button.media-edit').bind
            (
                'click',
                function(event, ui)
                {
                    var context = $(event.target).parent('li');
                    //CMS.mediaLibrary.initialise(context, 'edit');
                }
            );
        
            $('button.media-info').bind
            (
                'click',
                function(event, ui)
                {
                    var resource = $(event.target).parent('li');
                    that.modal(this.showPreview, resource);
                    //CMS.mediaLibrary.initialise(context, 'information');
                }
            );

		},
		
		showPreview: function(resource)
		{
            var target = this.get('target'); //needs to be passed to the constructor

			var that = this;

            this.get('content').load
            (
                window.CMS.config.site.uri + 'admin/assetmanager/preview/' + resource.data('resourceId'),
                function()
                {
                    console.info('Loaded Content: Asset Manager');

                    that.setupModal();
                }
            );
		}

    }
);