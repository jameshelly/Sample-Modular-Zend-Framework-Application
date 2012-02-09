window.CMS = window.CMS || {};

var Sidebar = UI.extend
(
	{
		initialize: function()
		{
		    console.info('Initialising: Sidebar Core');

            this.attachSidebarUiBehavior();
		},

		attachSidebarUiBehavior: function()
		{
		    var target = this.get('target');

			$LAB
			.script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/v1.8.16/jquery-ui-1.8.16.js')
			.wait
			(
                function()
                {
                    var topLevelMenuItems = $('div > ul.navigation > li', target);

                    topLevelMenuItems
                    .children('a')
                    .click
                    (
                        function(event)
                        {
                            console.log('clicked: ' + event.target)
                           $(this).next('ul').slideToggle('slow');
                           event.preventDefault();
                           return false;
                        }
                    )
                    .next('ul')
                    .hide();

                    topLevelMenuItems
                    .filter('.active')
                    .children('ul')
                    .show();
                }
            );
        }

    }
);