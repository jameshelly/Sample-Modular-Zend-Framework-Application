window.CMS = window.CMS || {};

//var HomepageManager = HomepageLibrary.extend
var HomepageManager = MediaLibrary.extend
(
{
    initialize: function()
    {
        console.info('Initialising: Homepage Manager Core');

        if($('button.manage-assets').closest('div.gallery').length)
        {
            this.set
            (
                {
                    originElement: $('button.manage-homepage-assets'),
                    galleryInstance: $('button.manage-homepage-assets').closest('div.gallery'),
                    galleryInstanceId: $('button.manage-homepage-assets').closest('div.gallery').attr('id').replace('gallery-', '')
                }
            );
        }
        else
        {
            console.log('Could not find the closest gallery container div.');
        }

        this.modal(this.show, this);
    },

    show: function()
    {
        console.info('Showing: If Case');

        var that = this;

        if(this.get('initialized') === true)
        {
            console.info('Showing: Homepage Manager & Media Library Core');
            console.info('Run: Homepage Manager');

            ich.grabTemplates();

            that.setupModal(that.setupModalCallback, that);
        }
        else
        {
            console.info('Showing: Else Case');
            this.get('content').load
            (
                window.CMS.config.site.uri + 'admin/home/manager',
                function()
                {
                    console.info('Loaded Content: Homepage Manager');

                    that.get('target').children('div').addClass('tabs-container');

                    that.applyInterface();

                    ich.grabTemplates();

                    that.setupModal(that.setupModalCallback, that);

                    that.setupMediaLibrary(that.setupHomepageManager, that);
                }
            );

            this.set
            (
                {
                    'initialized': true
                }
            );
        }
    },

    setupHomepageManager: function()
    {
        console.info('Initialising: Homepage Manager Data');

        this.jgrowl("Please wait while asset information is loaded.", { header: 'Asset Manager' });

        var that = this;

        this.set
        (
            {
                assetIds: this.get('galleryInstance').find('ul.media-grid').sortable('toArray'),
                //assetIds: this.get('gallery').attr('id'),
                assetListings: $('#current-gallery .asset-listings table tbody'),
                assetCount: $('#current-gallery .asset-listings table tfoot span.count'),
                assetPreview: $('#current-gallery .asset-editor img.preview')
            }
        );

        HomepageManagerAssetModelView = AssetModelView.extend
        (
            {
                render: function()
                {
                    $(this.el).html(ich.assetManagerAssetTemplate(this.model.toJSON()));

                    return this;
                }
            }
        );

        //this._assetModels = [];

        _.each
        (
            this.get('assetIds'),
            function(index)
            {
                var resource = index.replace('gallery_', '');
                if(resource == '')
                {
                    return;
                }

                $.getJSON
                (
                    window.CMS.config.site.uri + 'api/resources/' + resource + '.json',
                    function(returned)
                    {

/*
                        var asset = ich.assetTemplate(returned.response.data);

                        that._assetModelViews.push
                        (
                            new AssetModelView
                            (
                                {
                                    model : assetModel
                                }
                            )
                        );

                        that.set
                        (
                            {
                                'assetDetails': _(assetDetails)
                            }
                        );
*/
                        var assetModel = new AssetModel(returned.response.data);

                        //that._assetModels.push(assetModel);

                        assetModelView = new HomepageManagerAssetModelView
                        (
                            {
                                model : assetModel,
                                preview: that.get('assetPreview'),
                                counter: that.get('assetCount')
                            }
                        );

                        //that._assetModelViews.push
                        //(
                        //    assetModelView
                        //);

                        that.get('assetListings').append
                        (
                            assetModelView.render().el
                        );

                        //that.get('assetCount').text(that._assetModelViews.length);

                        that.get('assetCount').text
                        (
                            function(index, text)
                            {
                                return (parseInt(text) + 1)
                            }
                        );
                    }
                );
            }
        );

        this.get('assetListings')
        .on
        (
            'click',
            '.remove',
            function(event)
            {
                var resource = $(event.target).closest('tr');

                that._assetModels.push(returned.response.data);

                that.get('assetCount').text
                (
                    function(index, text)
                    {
                        return (parseInt(text) - 1)
                    }
                );
            }
        );
/*
        that.get('assetListings').append(that._assetModelViews);

        this.get('assetListings')
        .on
        (
            'click',
            '.preview',
            function(event)
            {
                var resource = $(event.target).closest('tr');

                that.get('assetPreview').attr
                (
                    'src',
                    function()
                    {
                        return $.getJSON
                        (
                            window.CMS.config.site.uri + 'api/resources/' + resource.data('id') + '.json',
                            function(returned)
                            {
                                return returned.response.data.id;
                            }
                        );
                    }
                );
            }
        )
        .on
        (
            'click',
            '.remove',
            function(event)
            {
                var resource = $(event.target).closest('tr');

                resource.remove();

                _(assetDetails)
            }
        );
*/
    },

    setupModalCallback: function()
    {
        console.info('Initialising: Return of Homepage Manager Resources to form');

        var assetIds;

        var gallery = $('input.resources', this.get('galleryInstance'));
        var featured = $('input.featured-resource', this.get('galleryInstance'));
        var grid = $('.media-grid');

        this.get('assetListings')
        .find('tr')
        .each
        (
            function (index, element)
            {
                if(index > 0)
                {
                    assetIds = assetIds + ', ' + $(element).find('.featured').val();
                    //assetIds = 'gallery_' + assetIds + ', ' + $(element).find('.featured').val();
                }
                else
                {
                    assetIds = $(element).find('.featured').val();
                    //assetIds = 'gallery_' + $(element).find('.featured').val();
                }
            }
        );

        gallery.val(assetIds);

        featured.val($("input[type='radio']:checked", '#current-gallery').val());

        grid.html('<p>Please save the post in order to see your new selection.</p>');
    }
}
);