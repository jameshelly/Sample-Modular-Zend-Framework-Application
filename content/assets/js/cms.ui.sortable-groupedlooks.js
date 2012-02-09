window.CMS = window.CMS || {};

var SortableGroupedLooks = UI.extend
(
	{
		initialize: function()
		{
		    console.info('Initialising: Sortable Grouped Looks Core');

            this.loadSortableGroupedLooksComponents();
		},
		
		loadSortableGroupedLooksComponents: function()
		{
		    this.attachSortableGroupedLooksBehavior();
		    this.attachGroupedLookBehaviors();
		},
		
		attachSortableGroupedLooksBehavior: function()
		{
            var sortableGroupedLooks = $('div.look-imagery ul.looks-grid');

            var that = this;

			$LAB
			.script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/v1.8.16/jquery-ui-1.8.16.js')
			.wait
			(
				function()
				{
                    sortableGroupedLooks.sortable
                    (
                        {
                            placeholder: "ui-state-highlight ui-sortable-placeholder",
                            //forcePlaceholderSize: true,
                            stop: function(event, ui)
                            {
                                var gallery = $(event.target).closest('div.ui-sortable');
                                var mediaGrid = $(event.target).closest('div.look-imagery ul.looks-grid');
                                
                                //var order = $(event.target).sortable('serialize');
                                var order = mediaGrid.sortable('toArray');
                                
                                console.log('Setting gallery contents to: ' + order);
                                
                                $('input:hidden.imagery', gallery).val(order);
                            }
                        }
                    );

					console.info('Activated: SortableGroupedLooks.attachSortableGroupedLooksBehavior');
				}
			);

		},
		
		attachGroupedLookBehaviors: function()
		{
            var that = this;

//            $('div.ui-sortable.featured button.media-remove').bind
//            (
//                'click',
//                function(event)
//                {
//                    var gallery = $(event.target).closest('div.ui-sortable');
//
//                    $(event.target)
//                    .parent()
//                    .fadeOut
//                    (
//                        'slow',
//                        function()
//                        {
//                            $(this).remove();
//
//                            console.log('Setting featured item contents to nothing.');
//
//                            $('input.featured-resource', gallery).val('');
//                        }
//                    );
//                }
//            );
        
            $('div.look-imagery ul.looks-grid button.media-remove').bind
            (
                'click',
                function(event)
                {
                    var gallery = $(event.target).closest('div.ui-sortable');
                    var mediaGrid = $(event.target).closest('div.look-imagery ul.looks-grid');

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
                
            $('div.look-imagery ul.looks-grid  li').bind
            (
                'mouseenter',
                function(event, ui)
                {
//                    var context = $(event.target).parent('li');
                    console.log('Over! ');
                    var num = $(this).index();
                    console.log(num);
                    $("div.grid-flexible-container .spotlight").removeClass('highlight');
                    $("div.grid-flexible-container .spotlight.look"+num).addClass('highlight');
                }
            );
//            
//            $('button.media-edit').bind
//            (
//                'click',
//                function(event, ui)
//                {
//                    var context = $(event.target).parent('li');
//                    //CMS.mediaLibrary.initialise(context, 'edit');
//                }
//            );
        
//            $('button.media-info').bind
//            (
//                'click',
//                function(event, ui)
//                {
//                    var resource = $(event.target).parent('li');
//                    that.modal(this.showPreview, resource);
//                    //CMS.mediaLibrary.initialise(context, 'information');
//                }
//            );

		}
		
//		showPreview: function(resource)
//		{
//            var target = this.get('target'); //needs to be passed to the constructor
//
//			var that = this;
//
//            this.get('content').load
//            (
//                window.CMS.config.site.uri + 'admin/assetmanager/preview/' + resource.data('resourceId'),
//                function()
//                {
//                    console.info('Loaded Content: Asset Manager');
//
//                    that.setupModal();
//                }
//            );
//		}

    }
);