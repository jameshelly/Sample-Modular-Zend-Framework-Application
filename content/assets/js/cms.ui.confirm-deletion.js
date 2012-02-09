window.CMS = window.CMS || {};

var DeleteConfirmationDialogue = UI.extend
(
{
    initialize: function()
    {
        console.info('Initialising: Delete Confirmation Dialogue');

        var that = this;

        var target = this.get('target'); //needs to be passed to the constructor
		
        var buttonSingle = $('button#actions-confirmdeletionbtn, button#entityform-actions-confirmdeletionbtn');
        var itemHasChildren = $('div.content > article > form').attr('has_children');
                
        //if the item has children, then disable the delete button
        if (itemHasChildren == 1) {
            buttonSingle.attr('disabled', 'disabled');
            buttonSingle.after('<a class="notice jquery-ui" data-content="You can\'t delete an item that contains children items" data-original-title="Menu items editor" rel="popover" href="#" data-placement="above"><span class="ui-icon ui-icon-info">Information</span>');
        }
        
        var buttonMultiple = $('button#confirm-deletion-btn');
        var confirmSingleDeletion = $('div#confirm-deletion-single .modal-footer .modal-confirm');
        var cancelSingleDeletion = $('div#confirm-deletion-single .modal-footer .modal-exit');
        var confirmReset = $('button#actions-reset');

        var confirmDeletion = $('div#confirm-deletion .modal-footer .modal-confirm');
	var cancelDeletion = $('div#confirm-deletion .modal-footer .modal-exit');
	var checkedElements = new Array();
	var hashElements = new Array();
	var element_id;
	var entity_data;
	var window_location = window.location;


        buttonSingle.bind
        (
            "click",
            function(event)
            {
                console.info("Initialized: Delete Confirmation Dialogue - " + event);

                entity_data = that.getEntityDataFromForm();
                                
                event.preventDefault();
                $('#confirm-deletion-single').modal('show');

                return false;
            }
        );


        buttonMultiple.bind
        (
            "click",
            function(event)
            {
            	checkedElements = new Array();

                console.info("Click Initialized: Delete Confirmation Dialogue - " + event);
                var href = $(buttonMultiple).attr('href');

//                window.location = href;
                //target.data('form', $(that).closest('form'));
                //$('#section1 input:checked').val();
                $('input:checked').each(function(){
                    
                    var element = new Object();
                    element.id = $(this).val();

                    element.entityname = $(this).attr('data-entity');

                    checkedElements.push(element);
                                                            
                });

                //this.jgrowl("Please wait while asset information is loaded.", { header: 'Media Library' });
            }
        );

	confirmSingleDeletion.bind 
	(
			"click",
            function(event)
            {				 

            	var del_status;
                var redir_page;
                var entity_data = that.getEntityDataFromForm();
                
                $('#confirm-deletion-single').modal('hide')


                $.ajax({
                        type: "DELETE",
                        url: window.CMS.config.site.uri + 'api/' + entity_data.name + '/' + entity_data.id + '.json',
                        data: "",
                        async: false,
                        success: function(result){

                                redir_page = result.response.redirect_page;

                                if (del_status == undefined || del_status != 'error') {		
                                del_status = 'success';
                        }
                        },
                        error: function(){
                                del_status = 'error';
                        }
                });
   			
                if (entity_data.name == 'pages') { 
                        window.location = 'http://' + window.location.hostname + '/admin/pages/read';
                }
                else if (entity_data.name == 'tags') {
                        window.location = 'http://' + window.location.hostname + '/manager/tags/read';
                }
                else if (entity_data.name == 'categories') {
                        window.location = 'http://' + window.location.hostname + '/manager/categories/read';
                }
                else if (entity_data.name == 'homepage') {
                        window.location = 'http://' + window.location.hostname + '/admin/home/read';
                }                
                else if (entity_data.name == 'menuitems') {
                        window.location = 'http://' + window.location.hostname + '/admin/menuitems/read';
                }                                
                else {   //if we are deleting a blog entity / user, redirect to previous url (the list page)
                        window.location = 'http://' + window.location.hostname + redir_page;
                }
                
            }
	);
	
	confirmDeletion.bind 
        (
            "click",
            function(event)
            {

            	$('#confirm-deletion').modal('hide')

                    var selected_elements = checkedElements.length;
                    var del_status;
                    var delete_vars; 
                    var entityName; 
				
                    if (selected_elements > 0) {
                        delete_vars = '?';
                    } 	

                    for(var i=0; i<selected_elements; i++) {
				
                        delete_vars += 'ids[]=' + checkedElements[i].id + '&';
    			entityName = checkedElements[i].entityname;	
                    }
				
                    delete_vars = delete_vars.substring(0, delete_vars.length-1);
		
                    //for the translations we not only delete the ID, we also delete the id's with the same name on the table. That's why we use a different call
                    if (entityName == 'translations')
                    {
                        $.ajax({
        			type: "GET",
        			url: window.CMS.config.site.uri + 'admin/translations/delete' + delete_vars,
        			data: '',
        			async: false,
        			success: function(){
                                    window.location = 'http://' + window.location.hostname + '/admin/translations/read';
        			}
    			});
    				
                    }
                    else {
                        $.ajax({
        			type: "DELETE",
        			url: window.CMS.config.site.uri + 'api/' + entityName + '/multiple_ids.json' + delete_vars,
        			data: "",
        			async: false,
        			success: function(){
        				if (del_status == undefined || del_status != 'error') {		
            				del_status = 'success';
            			}
        			},
        			error: function(){
        				del_status = 'error';
        			}
    			});
    				
			//redirect page
                        window.location = window_location;
                    }
				
				
            }
        );
        
         confirmReset.bind
        (
            "click",
            function(event)
            {
                    var entity_data = that.getEntityDataFromForm();

                    window.location = 'http://' + window.location.hostname + '/admin/' + entity_data.name + '/read';

            }
        );
            
        cancelSingleDeletion.bind 
        (
            "click",
            function(event)
            {
            	$('#confirm-deletion-single').modal('hide')

            }
        );
        
        cancelDeletion.bind 
        (
            "click",
            function(event)
            {
            	$('#confirm-deletion').modal('hide')

            }
        );
        
        
    },
		
    show: function()
    {
        console.info('Showing: Delete Confirmation Dialogue');
        //
        this.setupModal(this.setupModalCallback, this);
    },
    
    hide: function()
    {
        console.info('Showing: Delete Confirmation Dialogue');
        //
        this.setupModal(this.setupModalCallback, this);
    },
    
    
    setupModalCallback: function()
    {
        console.info('Initialising: Return of delete to form');
    },
    
    getEntityDataFromForm: function()
    {
        var entity_data = new Array();
        
	if ($('form#posts-edit').attr('element_id') != undefined) {
            entity_data.name = $('form#posts-edit').attr('data-entity');         
            entity_data.id = $('form#posts-edit').attr('element_id');					
	}
	else if ($('form#pages-edit').attr('element_id') != undefined) {
            entity_data.name = $('form#pages-edit').attr('data-entity');     
            entity_data.id = $('form#pages-edit').attr('element_id');					
	}
	else if ($('form#users-edit').attr('element_id') != undefined) {
            entity_data.name = $('form#users-edit').attr('data-entity');     
            entity_data.id = $('form#users-edit').attr('element_id');					
	}
        else if ($('form#usermenu_updateform').attr('element_id') != undefined) {
            entity_data.name = $('form#usermenu_updateform').attr('data-entity');     
            entity_data.id = $('form#usermenu_updateform').attr('element_id');					
	}
        else if ($('form#translations-edit').attr('element_id') != undefined) {
            entity_data.name = $('form#translations-edit').attr('data-entity');     
            entity_data.id = $('form#translations-edit').attr('element_id');					
	}
        else {
            entity_data.name = $('form#entityform').attr('data-entity');
            entity_data.id = $('form#entityform').attr('element_id');
        }
        
        return entity_data;
        
    }
    
}
);