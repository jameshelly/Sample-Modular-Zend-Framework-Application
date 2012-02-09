/*
 * http://wednesday-london.com
 *
 * window.CMS
 * © 2011 Wednesday
 *
 */

/*
 *
 * The core purpose of LABjs is to be an all-purpose, on-demand JavaScript loader, capable of loading any JavaScript resource, from any location, into any page, at any time. Loading your scripts with LABjs reduces resource blocking during page-load, which is an easy and effective way to optimize your site's performance.
 * LABjs by default will load (and execute) all scripts in parallel as fast as the browser will allow. However, you can easily specify which scripts have execution order dependencies and LABjs will ensure proper execution order. This makes LABjs safe to use for virtually any JavaScript resource, whether you control/host it or not, and whether it is standalone or part of a larger dependency tree of resources.
 * Using LABjs will replace all that ugly "<script> tag soup" -- that is all the <script> tags that commonly appear in the <head> or end of the <body> of your HTML page. The API is expressive and chaining, to let you specify which scripts to load, and when to wait ("block"), if necessary, for execution before proceeding with further execution. The API also easily allows inline code execution coupling (think: inline <script> tags).
 *
 */

window.CMS = window.CMS || {};

window.CMS.execute = function()
{
    console.group('Execute');

    window.UI = new UI;

    window.Sidebar = new Sidebar
    (
        {
            target: $('div#main > aside.sidebar')
        }
    );

    var assetEditorType = 'default';

    if($('html.homepage-manager').length)
    {
        assetEditorType = 'homepage';
    }
    console.info('EDITOR : setting the editor type '+assetEditorType);

    window.AssetManager = new AssetManager
    (
        {
            target: $('div.modal#asset-manager'),
            editor: assetEditorType
        }
    );

    if($('html.homepage-manager').length)
    {
        window.HomepageManager = new HomepageManager
        (
            {
                target: $('div.modal#homepage-asset-manager')
            }
        );
    }

    if($('html.asset-manager').length)
    {
        window.MediaLibrary = new MediaLibrary
        (
            {
                //target: $('article > #media-library'),
                content: $('article > #media-library')
            }
        );
    }
    if($('html.homepage-manager').length)
    {
        window.HomepageLibrary = new HomepageLibrary
        (
            {
                //target: $('article > #media-library'),
                content: $('article > #media-library')
            }
        );
    }


    window.SortableGalleries = new SortableGalleries
    (
        {

        }
    );

    window.SortableGalleries = new SortableGroupedLooks
    (
        {

        }
    );


    window.DeleteConfirmationDialogue = new DeleteConfirmationDialogue
    (
        {
            target: $('#actions-confirmdeletionbtn')
        }
    );

    window.Tags = new Tags
    (
        {
            uri: window.CMS.config.site.uri + 'api/tags.json'
        }
    );

    console.groupEnd();
}

window.CMS.initialize = function()
{
    //console.group('Initialize');

    $LAB
    .setGlobalDefaults
    (
        {
            Debug: window.CMS.config.debug
        }
    )
    .script(window.CMS.config.site.uri + 'library/js/modernizr/v2.0.6/modernizr-v2.0.6.js')
    .script(window.CMS.config.site.uri + 'library/js/jquery/v1.7.1/jquery-1.7.1.js')
    .script(window.CMS.config.site.uri + 'library/js/json/v2011-10-19/json2.js')
    .script(window.CMS.config.site.uri + 'library/js/underscore/v1.2.1/underscore.js')
    .script(window.CMS.config.site.uri + 'library/js/backbone/v0.5.3/backbone.js')
    .script(window.CMS.config.site.uri + 'library/js/backbone-relational/v0.4.0/backbone-relational.js')
    .script(window.CMS.config.site.uri + 'library/js/form2js/form2js.js')
    .script(window.CMS.config.site.uri + 'library/js/form2js/jquery.toObject.js')
    //.script(window.CMS.config.site.uri + 'library/js/mustache/v0.4.0-dev/mustache.js')
    .script(window.CMS.config.site.uri + 'library/js/icanhaz/v0.9/ICanHaz.js')
    .wait
    (
        function()
        {
            console.info('Loaded: Core Libraries');
        }
    )
    .script(window.CMS.config.theme.uri + 'js/cms.config.js')
    .script(window.CMS.config.theme.uri + 'js/cms.routes.js')
    .wait
    (
        function()
        {
            console.info('Loaded: Routes');
            window.WorkspaceRouter = new WorkspaceRouter;

            Backbone.history.start
            (
                {
                    pushState: true
                }
            );
        }
    )
    .script(window.CMS.config.theme.uri + 'js/cms.ui.js')
    .wait
    (
        function()
        {
            console.info('Loaded: Core window.CMS Components');
        }
    )
    .script(window.CMS.config.theme.uri + 'js/cms.ui.sidebar.js')
    .script(window.CMS.config.theme.uri + 'js/cms.ui.tags.js')
    .script(window.CMS.config.theme.uri + 'js/cms.ui.sortable-galleries.js')
    .script(window.CMS.config.theme.uri + 'js/cms.ui.sortable-groupedlooks.js')
    .script(window.CMS.config.theme.uri + 'js/cms.media-library.js')
    .script(window.CMS.config.theme.uri + 'js/cms.homepage-library.js')
    .script(window.CMS.config.theme.uri + 'js/cms.asset-manager.js')
    .script(window.CMS.config.theme.uri + 'js/cms.homepage-manager.js')
    .script(window.CMS.config.theme.uri + 'js/cms.ui.confirm-deletion.js')

    .script(window.CMS.config.site.uri + 'library/js/farbtastic/v1.2/farbtastic.js')

    //.script(window.CMS.config.theme.uri + 'js/cms.forms.save.js')
    .wait
    (
        function()
        {
            console.info('Loaded: Core Component Set window.CMS ');
            window.CMS.execute();
        }
    );

}