window.CMS = window.CMS || {};

var WorkspaceRouter = Backbone.Router.extend
(
    {
        routes:
        {
            "*actions": "defaultRoute" // matches http://example.com/#anything-here
        },
        defaultRoute: function( actions )
        {
            // The variable passed in matches the variable in the route definition "actions"
            console.info('Matched Route: actions - ' + actions);
        }
    }
);