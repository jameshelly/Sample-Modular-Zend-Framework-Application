window.CMS = window.CMS || {};

var Tags = UI.extend
(
	{
		initialize: function()
		{
		    console.info('Initialising: Tags Core');

            this.loadTagsComponents();
		},
		
		loadTagsComponents: function()
		{
		    var that = this;
		
            $LAB
            .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/autoSuggest/v1.4/jquery.autoSuggest.js')
            .wait
            (
                function()
                {
                    var context = (that.get('context') != undefined) ? that.get('context') : document;
                    var tagdata = $('input.tags', context).val();
                    $('.tags', context).autoSuggest
                    (
                        that.get('uri'),
                        {
                            minChars: 2,
                            asHtmlID: 'tags',
                            preFill: tagdata,
                            searchObjProps: "title",
                            selectedItemProp: "title",
                            selectedValuesProp: "title",                            
                            retrieveComplete: function(data){ return data.response.data; }
                        }
                    );
                }
            );
		}
    }
);