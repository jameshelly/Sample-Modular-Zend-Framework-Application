window.CMS = window.CMS || {};

var UI = Backbone.Model.extend
(
{
    initialize: function(context)
    {
        context = context ? context : document;

        console.group("UI");

        this.applyInterface(context);
			
        console.groupEnd();
    },

    applyInterface: function(context)
    {
        //this.button(context);
        this.jgrowl();
        this.preview(context);
        this.dropdown(context);
        this.tabs(context);
        this.popover(context);
        this.twipsy(context);
        this.tablesorter(context);
        this.tablescroller(context);
        this.datepicker(context);
        this.timepicker(context);
        this.wysiwyg(context);
    },

    jgrowl: function(message, options)
    {
        console.info('Initialising: UI.jgrowl');

        $LAB
        .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/jgrowl/v1.2.6/jquery.jgrowl_minimized.js')
        .wait
        (
            function()
            {
                if(typeof message != 'undefined')
                {
                    $.jGrowl(message, options);
                }
                
                console.info('Activated: UI.jgrowl');
            }
        );
    },

    modal: function(callback, scope)
    {
        console.info('Initialising: UI.modal');

        var target = this.get('target');

        this.set
        (
        {
            content: target.find('.modal-body'),
            closeButton: target.find('.modal-exit'),
            callback: callback
        }
        );

        var that = this;

        $LAB
        .script(window.CMS.config.site.uri + 'library/js/bootstrap/v1.4.0/bootstrap-modal.js')
        .wait
        (
            function()
            {
                target
                .modal
                (
                {
                    backdrop: 'static'
                }
                )
                .bind
                (
                    'show',
                    function(e)
                    {
                        console.info('Activating: Modal');

                        if(callback || callback!='')
                        {
                            if(typeof callback === 'undefined')
                            {
                                console.info('UI.modal: Callback function not found.');
                            }
                            else
                            {
                                console.info('UI.modal: Callback to ' + callback);
                                callback.call(scope);
                            }
                        }
                    }
                    )
                .bind
                (
                    'shown',
                    function(e)
                    {
                        console.info('Activated: Modal');
                    }
                    );
            }
            );
    },

    preview: function(context)
    {
        console.info('Initialising: UI.preview');

        $('a[rel="preview"]', context).attr
        (  
        {
            target: '_blank',
            title:  'Open in a new window'
        }
        );

        console.info('Activated: UI.preview');
    },

    dropdown: function(context)
    {
        console.info('Initialising: UI.dropdown');

        $LAB
        .script(window.CMS.config.site.uri + 'library/js/bootstrap/v1.4.0/bootstrap-dropdown.js')
        .wait
        (
            function(context)
            {
                $('.dropdown', context).dropdown();

                console.info('Activated: UI.dropdown');
            }
            );
    },

    tabs: function(context)
    {
        console.info('Initialising: JQUI.tabs');

        $LAB
        //.script(window.CMS.config.site.uri + 'library/js/bootstrap/v1.4.0/bootstrap-tabs.js')
        //.script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/v1.8.16/jquery-ui-1.8.16.custom.min.js')
        .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/v1.8.16/jquery-ui-1.8.16.js')
        .wait
        (
            function(context)
            {
                //$('.tabs', context).tabs();
                $('.tabs-container', context).tabs();
					
                console.info('Activated: JQUI.tabs');
            }
        );
    },

    popover: function(context)
    {
        console.info('Initialising: UI.popover');

        $LAB
        .script(window.CMS.config.site.uri + 'library/js/bootstrap/v1.4.0/bootstrap-twipsy.js')
        .script(window.CMS.config.site.uri + 'library/js/bootstrap/v1.4.0/bootstrap-popover.js')
        .wait
        (
            function(context)
            {
                $('a[rel=popover], button[rel=popover], .popover', context).popover
                (
                {
                    live: true,
                    html: true,
                    placement: 'below'
                }
                )
                .click
                (
                    function(e, context)
                    {
                        $(this).popover('hide');

                        console.info('Activated: UI.popover on ' + e);
                    }
                    );
            }
            );
    },

    twipsy: function(context)
    {
        console.info('Initialising: UI.twipsy');

        $LAB
        .script(window.CMS.config.site.uri + 'library/js/bootstrap/v1.4.0/bootstrap-twipsy.js')
        .wait
        (
            function(context)
            {
                $('a[rel=twipsy], button[rel=twipsy], .twipsy, table thead th', context).twipsy
                (
                {
                    live: true,
                    html: true
                }
                );

                console.info('Activated: UI.twipsy');
            }
            );
    },

    tablesorter: function(context)
    {
        console.info('Initialising: UI.tablesorter');

        $LAB
        .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/tablesorter/v2.0.5b/jquery.tablesorter.js')
        .wait
        (
            function(context)
            {
                $('table.sortable', context).tablesorter();

                console.info('Activated: UI.tablesorter');
            }
            );
    },

    tablescroller: function(context)
    {
        console.info('Initialising: UI.tablescroller');

        $LAB
        .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/tablescroll/vb33f240/jquery.tablescroll.js')
        .wait
        (
            function(context)
            {
                console.info('Activated: UI.tableScroll');
            }
            );
    },

    button: function(context)
    {
        console.info('Initialising: UI.button');

        $LAB
        //.script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/v1.8.16/jquery-ui-1.8.16.custom.min.js')
        .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/v1.8.16/jquery-ui-1.8.16.js')
        .wait
        (
            function(context)
            {
                $('.button, .checkbox', context).button();

                if(callback || callback!='')
                {
                    if(typeof callback === 'undefined')
                    {
                        console.info('UI.button: Callback function not found.');
                    }
                    else
                    {
                        console.info('UI.button: Callback to ' + callback);
                        callback.call(scope);
                    }
                }

                console.info('Activated: UI.button');
            }
            );
    },

    datepicker: function(context)
    {
        console.info('Initialising: UI.datepicker');

        var that = this;
            
        var locale = (window.CMS.config.locale != undefined) ? window.CMS.config.uri : 'en-GB';
        var dateFormat = (window.CMS.config.date.format != undefined) ? window.CMS.config.date.format : 'dd/mm/yy';

        $LAB
        //.script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/v1.8.16/jquery-ui-1.8.16.custom.min.js')
        .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/v1.8.16/jquery-ui-1.8.16.js')
        .wait
        (
            function(context)
            {
                $.datepicker.setDefaults($.datepicker.regional[locale]);
                $('.datepicker', context).datepicker({
                    dateFormat: dateFormat,
                    beforeShow: function()
                    {
                        //$("#ui-datepicker-div").wrap("<div class='my-scope' />")
                        $('.ui-datepicker', context).wrap("<div class='jquery-ui' />");
                    }
                });
                console.info('Activated: UI.datepicker');
            }
            );
    },

    timepicker: function(context)
    {
        console.info('Initialising: UI.timepicker');

        $LAB
        //.script('assets/js/jquery/jquery-ui-1.8.16.js') //tabs conflicts with Bootstrap's tabs!
        //.script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/v1.8.16/jquery-ui-1.8.16.custom.min.js')
        .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/v1.8.16/jquery-ui-1.8.16.js')
        .script(window.CMS.config.site.uri + 'library/js/jquery/plugins/ui/plugins/timepicker-fgelinas/v0.2.6/jquery.ui.timepicker.js')
        .wait
        (
            function(context)
            {
                $('.timepicker', context).timepicker();

                console.info('Activated: UI.timepicker');
            }
        );
    },
		
    wysiwyg: function()
    {
        $LAB
        .script(window.CMS.config.site.uri + 'library/js/ckeditor/v3.6.2/ckeditor.js')
        .script(window.CMS.config.site.uri + 'library/js/ckeditor/v3.6.2/adapters/jquery.js')
        .wait
        (
            function()
            {
                console.info('Initialising: UI.wysiwyg');

                $('textarea.wysiwyg.basic').ckeditor
                (
                {
                    toolbar: 'Basic',
                    width: 760
                }
                );
        
                $('textarea.wysiwyg.advanced').ckeditor
                (
                {
                    toolbar: 'Advanced',
                    width: 760
                }
                );
        
                $('textarea.wysiwyg.custom-headers').ckeditor
                (
                {
                    toolbar:
                    [
                    {
                        name: 'clipboard', 
                        items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ]
                    },
{
                        name: 'editing', 
                        items : [ 'Source','Find','Replace','-','SelectAll','-','Scayt' ]
                    },
{
                        name: 'insert', 
                        items : [ 'Image','Table','HorizontalRule','SpecialChar' ]
                    },
                    '/',
                    {
                        name: 'styles', 
                        items : [ 'Format' ]
                    },
{
                        name: 'basicstyles', 
                        items : [ 'Bold','Italic','Strike','-','RemoveFormat' ]
                    },
{
                        name: 'paragraph', 
                        items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ]
                    },
{
                        name: 'links', 
                        items : [ 'Link','Unlink','Anchor' ]
                    }
                    ],
                    width: 760
                }
                );
                    
            }
            );
    },

    setupModal: function(callback, scope)
    {
        console.info('Initialising: UI.setupModal');

        var target = this.get('target'); //needs to be passed to the constructor

        var content = target.find('.modal-body');
        var closeButton = target.find('.modal-close');
        var continueButton = target.find('.modal-confirm');

        closeButton.bind
        (
            'click',
            function()
            {
                target.modal('hide');
                return false;
            }
            );

        continueButton.bind
        (
            'click',
            function()
            {
                if(callback || callback!='')
                {
                    if(typeof callback === 'undefined')
                    {
                        console.info('UI.modal close: Callback function not found.');
                    }
                    else
                    {
                        console.info('UI.modal close: Callback to ' + callback);
                        callback.call(scope);
                    }
                }

                //target.data('form').submit();
                target.modal('hide');
                return false;
            }
            );
            
        console.info('Activated: UI.setupModal');
    }

}
);