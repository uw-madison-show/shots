// Matt Moehr
// 2016-05-31

/**********************************************************/

// "Global" Vars

/**********************************************************/

var autosave_timeout, table_data, 
    table_handsontable, table_data_key_field,
    auth2, new_user;

var key_field_mapping = { 
                          "events":        "event_id",
                          "grants":        "grant_id",
                          "people":        "person_id",
                          "documents":     "document_id",
                          "outreach":      "outreach_id",
                          "lookup_values": "lookup_value_id",
                        }

// these fields are set to empty for the addRow function
// just need to be string field with no constraints
var empty_field_mapping = {
                            "events":        "title",
                            "grants":        "title",
                            "people":        "name",
                            "documents":     "title",
                            "outreach":      "primary_contact",
                            "lookup_values": "lookup_value", 
                          }            

// these are the fields that most humans would want to read
// when they want to see a "list" of the entities
// i'm calling these fields, "titles" but i am not married to that term
// must be an array of variable names. array of 1 is ok.
var title_field_mapping = {
                            "events":       ["datetime_start", "title"],
                            "grants":       ["title", "grant_body"],
                            "people":       ["name", "affiliation"],
                            "documents":    ["name", "extension"],
                            "outreach":     ["primary_contact"],
                            "lookup_value": ["column_name", "label"],
                          }

/**********************************************************/

// On page load

/**********************************************************/

jQuery(document).ready(function(){
  // initialize the sign in buttons
  googleAuthInit();
  // handle an error message coming from the server/db
  if( $('.server-side-error-message').length ){
    $('.server-side-error-message').each(function(){
      $('footer .message-holder').append(this);
      $(this).show();
    });
  }
});

/**********************************************************/

// Functions

/**********************************************************/

function googleAuthInit() {
  console.log('googleAuthInit');
  console.log('username = ' + username);

  // attach the click handler to the logout button
  $('#google-auth-logout-button').click(googleAuthLogout);

  if (username.length > 0) {
    googleAuthButtons('cache', username);
  } else {
    gapi.load('auth2', function(){
      // retrieve the GoogleAuth library and set up client
      auth2 = gapi.auth2.init({
        client_id: google_auth_client_id
      });

      // attach the click handler to the sign-in button
      $('#google-auth-button').click(function() {
        auth2.grantOfflineAccess({"redirect_uri": "postmessage"})
          .then(googleAuthCallback);
      });
    });
  } // end if username exists
}

function googleAuthCallback(authResult) {
  console.log('googleAuthCallback');
  console.log(authResult);

  if (authResult['code']) {

    // clear out the UI elements
    $('#google-auth-username').empty();
    $('#google-auth-button').hide();

    new_user = auth2.currentUser.get();

    if (new_user) {
      authResult['Id'] = new_user.getBasicProfile().getId();
      authResult['Email'] = new_user.getBasicProfile().getEmail();

      $.post(app_root + '/lib/shots/internals/authenticate.php',
             authResult,
             'json'
             )
             .done( googleAuthSuccess )
             .fail( ajaxFailed )
             ;
    } else {
      googleAuthFailure(authResult);
    }   
  } else {
    googleAuthFailure(authResult);
  }
}

function googleAuthSuccess(r) {
  console.log('googleAuthSuccess');
  console.log(r);

  var data = JSON.parse(r);

  if ( typeof data == 'object' && data.error !== "true" ){

    googleAuthButtons('new', data);

  } else {
    $('footer .message-holder').append('Not a valid response from authenticate.php');
    $('footer .message-holder').append(r);
    $('.server-side-error-message').show();
  }
}

function googleAuthFailure(authResult) {
  console.log('googleAuthFailure');
  console.log(authResult);

  $.each(authResult, function(index, msg){
    $('footer .message-holder').append(msg);
  });
  $('.server-side-error-message').show();

  // TODO at this point the UI elements should be updated with something?
}

function googleAuthButtons(source, data) {
  console.log('googleAuthButtons');
  console.log(data);

  if (source === 'new') {
    username = new_user.getBasicProfile().getEmail();
    // update the UI elements
    var username_to_display = username.length > 0 ? username : 'login failed';
    $('#google-auth-username').append(username_to_display);
    $('#google-auth-logout-button').show();
    if (username.length > 0) {
      window.location.replace(app_root + '/');
    }
  } else if (source === 'cache') {
    $('#google-auth-username').append(username);
    $('#google-auth-button').hide();
    $('#google-auth-logout-button').show();
  }
}

function googleAuthLogout() {
  console.log('googleAuthLogout');

  username = "";
  new_user = {};

  window.location.replace(sign_out_page);
}

// function showErrorMessageVisiblity(message){
//   // console.log(message);
//   $(message).show();

// }

function ajaxFailed(d) {
  console.log('Ajax data load has failed.');
  console.log(d);
  console.log(d.hasOwnProperty("responseText"));
  if (d.hasOwnProperty("responseText")) {
    $('footer .message-holder').append(d.responseText);
  } else if (d.hasOwnProperty("error_messages")) {
    $.each(d.error_messages, function(index, msg){
        // TODO append the rest of the html markup so it looks nice.
        $('footer .message-holder').append(msg);
    }); 
  }
  $('.server-side-error-message').show();
}

function ajaxChange(e){
  console.log(this);
  var html_input_type = $(this).attr('type');
  var entity_name = $(this).parents('.record').data('entityName');
  // the ajax handler is going to include the file /lib/shots/entities/{table}.php
  // then it will use call_user_func() to pass the {params}
  // into the function named by {action}
  var req = {};
  req.target   = 'entity';
  req.action   = entity_name + 'Update';
  req.table    = entity_name;
  req.params   = [];

  // get record id
  // this requires knowing the key_field
  var key_field = '#' + key_field_mapping[entity_name];
  var key_value = $(this).closest('.record').find(key_field).val();
  console.log(key_field + ' = ' + key_value);
  req.params.push(key_value);

  // field name
  req.params.push($(this).attr('id'));

  // get the field value 
  var html_input = getFormInputValue($(this));
  req.params.push(html_input.value);

  console.log(req);

  $.post(app_root + '/lib/ajax_handler.php', 
         { "request": req },
         "json"
         )
         .done() 
         .fail( ajaxFailed )
         .always(function(r) {
                   if (r.error === false){
                    console.log(r);
                   } else {
                    ajaxFailed(r);
                   }
                 })
         ;
}

// opens an empty modal dialog
// returns the jQuery object of the modal
// sets default behavior so that any modal hide action will completely destory the html element
function openModal(){
  $('body').append('<div id="basic-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="basic-modal-label" aria-hidden="true">' +
                   '<div class="modal-dialog">' +
                   '  <div class="modal-content">' +
                   '  <form id="modal-form">'+
                   '    <div class="modal-header">' +
                   '      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>' +
                   '      <h3 id="basic-modal-label"></h3>' +
                   '    </div>'+
                   '    <div class="modal-body">' +
                   '      <div></div>' +
                   '    </div>' +
                   '    <div class="modal-footer">'+
                   '    </div>' +
                   '  </form>'+
                   '  </div>'+
                   '</div>' +
                   '</div>'
                   );
  var m = $('#basic-modal');
  m.modal('show');
  // on any type of modal close, remove modal from html
  m.on('hidden.bs.modal', function(){
    $(this).remove();
    $('.modal-backdrop').remove();
  });
  console.log(m);
  return m;
}

// opens the modal dialog box to confirm deletions
function openDeleteModal(e){
  console.log('openDeleteModal');
  console.log($(this));

  var modal_message_html = '';
  var delete_array = [];

  var entity_name = $(this).closest('.record').data('entityName');
  var key_field = key_field_mapping[entity_name];
  var key_html_id = '#' + key_field;
  var key_value = $(this).closest('.record').find(key_html_id).val();

  // search the entity table
  // do an ajax data request to get the data
  // prepar the request object
  var req = {};
  req.target   = 'entity';
  req.action   = entity_name + 'Fetch';
  req.table    = entity_name;
  req.params   = [];
  // entity id to return
  req.params.push(key_value);
  // set return format to json
  req.params.push('json');
  console.log(req);

  // do the request
  // use the handlers to finish the UI updates
  $.post(app_root + '/lib/ajax_handler.php', 
         { 
          "request": req 
         },
         "json"
         )
         .done() 
         .fail( ajaxFailed )
         .always(function(r){ 
                   console.log(r);
                   if (r.error === false) {
                     openDeleteModal_f1(r) 
                   } else {
                     ajaxFailed(r);
                   }
                }) 
         ;

  // search the relationship table
  function openDeleteModal_f1(data){
    var entity_result = $.parseJSON(data.results[0])[key_value];
    console.log(entity_result);

    // search the relationship table
    var req = {};
    req.target   = 'relationships';
    req.action   = 'relationshipsFetch';
    req.table    = 'relationships';
    req.params   = [];
    // entity type and id to return
    req.params.push(entity_name);
    req.params.push(key_value);
    // set return format to json
    req.params.push('json');
    console.log(req);

  // do the request
  // use the handlers to finish the UI updates
  $.post(app_root + '/lib/ajax_handler.php', 
         { 
          "request": req 
         },
         "json"
         )
         .done() 
         .fail( ajaxFailed )
         .always(function(r){
                   console.log(r);
                   if (r.error === false) {
                     openDeleteModal_f2(r, entity_result);
                   } else {
                     ajaxFailed(r);
                   }
                 }) 
         ;

  }

  function openDeleteModal_f2(data, entity_result){
    var rel_results = data.results[0];
    console.log(rel_results);
    console.log(entity_result);

    delete_array = [];

    delete_array.push([ entity_name, key_field, entity_result[key_field] ]);
    modal_message_html += '<p>Delete this entity? (' + formatEntityResultAsShortText(entity_name, entity_result) + ')</p>';

    if ( $.isEmptyObject(rel_results) === false ){
      modal_message_html += '<p>Also remove the relationships to these other things?</p>';
      modal_message_html += '<ul>';
      $.each(rel_results, function(rel_entity_type, relationships){
        $.each(relationships, function(index, obj){
          var rel_key_field = key_field_mapping[rel_entity_type];
          delete_array.push( [rel_entity_type, rel_key_field, obj.id] );
          modal_message_html += '<li><strong>' + rel_entity_type + '</strong> (id #' + obj.id + ') related as ' + obj.type + '</li>';
        });
      });
      modal_message_html += '</ul>';
    }

    var delete_modal = openModal();
    
    delete_modal.find('#basic-modal-label').html('Confirm Delete');
    delete_modal.find('#modal-form').attr('action', app_root + '/delete.php');
    delete_modal.find('#modal-form').attr('method', 'post');

    // add the delete_array as a hidden object
    delete_encoded = encodeURIComponent(JSON.stringify(delete_array));
    console.log(delete_encoded);
    delete_modal.find('#modal-form').append('<input type="hidden" name="delete-array" id="delete-array" value ="'+ delete_encoded +'"/>');

    delete_modal.find('.modal-body').append(modal_message_html);
    // add buttons
    delete_modal.find('.modal-footer').append('<button id="delete-modal-cancel-button" class="btn btn-default" data-dismiss="modal">< Oops, go back</button>');
    delete_modal.find('.modal-footer').append('<button type="submit" id="delete-modal-confirm-button" class="btn btn-primary">Confirm delete ></button>');

    // console.log(delete_modal);

    /*
    // attach listener for the delete confirmation button
    $('#delete-modal-confirm-button').on('click', function(){
      var successful_deletes = ajaxDelete(delete_array);
      $.when(successful_deletes).done(function(){
        console.log(successful_deletes);
        if (successful_deletes) {
          if (delete_array[0][0]) {
            window.location = '/views/table_all_' + delete_array[0][0] + '.php';
          } else {
            window.location = '/';
          }
        }
      });
    });
    */

  } 
}


// opens the modal dialog box to confirm deletions
function openUploadModal(e){
  console.log('openUploadModal');

  var modal_message_html = '';

  // find the 'parent' entity for the document
  var entity_name = $(this).closest('.record').data('entityName');
  var key_field = key_field_mapping[entity_name];
  var key_html_id = '#' + key_field;
  var key_value = $(this).closest('.record').find(key_html_id).val();

  // write out the html input form elements
  // TODO refactor this so the input fields are added programatically?
  modal_message_html += '<div id="file-upload-modal-message">' +
      '<div class="form-horizontal">' +
        '<div class="form-group">' +
          '<label class="control-label col-xs-4" for="title">Title</label>' +
          '<div class="col-xs-8">' +
            '<input id="title" name="title" class="form-control" type="text" placeholder="optional" />' +
          '</div>' +
        '</div>' +
        '<div class="form-group">' +
          '<label class="control-label col-xs-4" for="description">Description</label>' +
          '<div class="col-xs-8">' +
            '<input id="description" name="description" class="form-control" type="text" placeholder="optional" />' +
          '</div>' +
        '</div>' +
        '<input type="hidden" name="from_entity_type" id="from_entity_type" value="' + entity_name + '"/>' +
        '<input type="hidden" name="from_entity_id" id="from_entity_id" value="' + key_value + '"/>' +
        '<div class="form-group">' +
          '<div class="col-xs-4 col-xs-offset-4">' +
            '<label for="file-upload-button" class="btn btn-default">Pick file<input type="file" id="file-upload-button" name="files[]" data-url="' + app_root + '/lib/file_handler.php"  style="display: none;"/>' +
            '</label>' +
          '</div>' +
        '</div>' +
        '<div class="form-group">' +
          '<div class="col-xs-8 col-xs-offset-4">' +
            '<div id="file-upload-progress" class="progress">' +
              '<div class="progress-bar progress-bar-default"></div>' +
            '</div>' +
          '</div>' +
        '</div>' +
      '</div>' +
    '</div>' 
    ;

    var upload_modal = openModal();
    upload_modal.find('#basic-modal-label').html('Upload File');
    upload_modal.find('.modal-body').append(modal_message_html);
    
    $('#file-upload-button').fileupload({
      // dataType: 'json',
      done: function (e, data) {
        console.log("done!");
        // console.log(e);
        // console.log(data);
        // foobar = data;
        try {
          var result = JSON.parse(data.result);
          if (result && typeof result === 'object'){
            // console.log(result);
            if (result.files[0].error) {
              var e = {};
              e.error_messages = [ result.files[0].error ];
              ajaxFailed(e);
              $('#basic-modal').modal('hide');
            }

            // we have a good result
            location.reload();
          }
        } catch (js_err) {
          var e = {};
          e.error_messages = [ js_err, data.result ];
          ajaxFailed(e);
          $('#basic-modal').modal('hide');
        }
        
      },
      progressall: function (e, data) {
        // console.log("progressall!");
        // disable the modal inputs while the upload is working
        $('#file-upload-modal-message input').prop('disabled', true);
        $('#file-upload-button').prop('disabled', true);
        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#file-upload-progress .progress-bar').css(
            'width',
            progress + '%'
        );

      }
    }); 
}

// open a modal dialog to connect two entities
function openAttachModal(ego_entity_name, ego_id, alter_entity_name){
  console.log('openAttachModal');

  var modal_message_html = '';

  // write out the html input form elements
  // TODO refactor this so the input fields are added programatically?
  modal_message_html += '<div id="related-entity-modal-message">'+
    '<div class="form-horizontal">' +
      '<div class="form-group">'+
        '<label class="control-label col-xs-4" for="existing-entity">Pick an existing record:</label>'+
        '<div class="col-xs-8">'+
          '<select id="existing-entity" name="existing-entity" class="form-control" disabled>'+
            '<option class="drop-down-option-default" value=""></option>'+
          '</select>'+
        '</div>'+
      '</div>'+
      '<p class="col-xs-12 centering">~ or ~</p>'+
      '<div class="form-group">'+
        '<label class="control-label col-xs-4" for="new-entity">Create new record:</label>'+
        '<div class="col-xs-8">'+
          '<input id="new-entity" name="new-entity" class="form-control" type="text" placeholder="" />'+
        '</div>'+
      '</div>'+
    '</div>'+
    '</div>';

  var attach_modal = openModal();
  attach_modal.find('#basic-modal-label').html('Connect to ' + alter_entity_name);
  attach_modal.find('.modal-body').append(modal_message_html);

  attach_modal.find('.modal-footer').append('<button type="submit" id="attach-modal-confirm-button" class="btn btn-primary">Attach ></button>');

  $('#attach-modal-confirm-button').on('click', function (e) {
    // console.log('click');
    e.preventDefault();
    $('#new-entity').prop('disabled', true);
    $('#existing-entity').prop('disabled', true);
    $('#attach-modal-confirm-button').prop('disabled', true);

    // check that we have either existing record or a new record
    var new_val         = $('#new-entity').val();
    var alter_entity_id = $('#existing-entity').val();
    console.log(new_val);
    console.log(alter_entity_id);
    // prefer the existing record if we have both
    if (alter_entity_id){
      var rel = {};
      rel.target = 'relationships';
      rel.action = 'relationshipsAdd';
      rel.table  = 'relationships';
      rel.params = [ego_entity_name,
                    ego_id,
                    alter_entity_name,
                    alter_entity_id
                    ];
      $.post(app_root + '/lib/ajax_handler.php', 
             {"request": rel},
             "json"
             )
             .done() 
             .fail( ajaxFailed )
             .always(function(r){
                       if (r.error === false) {
                         // console.log(r);
                         location.reload();
                       } else {
                         ajaxFailed(r);
                       }
                    })
             ;

    } else if (new_val) {
      // create a new entity

      // we assume that the typed in value will go into empty_field_mapping
      // TODO double check that the new val may already exist in the alter table
      var new_entity = {};
      new_entity.target = 'entity';
      new_entity.action = alter_entity_name + 'Add';
      new_entity.table  = alter_entity_name;
      new_entity.params = [empty_field_mapping[alter_entity_name],
                           new_val
                           ];
      $.post(app_root + '/lib/ajax_handler.php', 
             {"request": new_entity},
             "json"
             )
             .done() 
             .fail( ajaxFailed )
             .always(function(r){
                       if (r.error === false) {
                         // console.log(r);

                         // add a relationship from ego to the new entity
                         openAttachModal_f1(r);

                         
                       } else {
                         ajaxFailed(r);
                       }
                    })
             ;


    } // end if existing value or new value
    
    // private function to use for ajax post callback
    // get the id of the newly created entity
    function openAttachModal_f1(data){
      console.log(data);
      var req = {};
      req.target = 'entity';
      req.action = data.request_params.table + 'Search';
      req.table  = data.request_params.table;
      req.params = [data.request_params.params[0],
                    data.request_params.params[1]
                    ];
      $.post(app_root + '/lib/ajax_handler.php', 
             {"request": req},
             "json"
             )
             .done() 
             .fail( ajaxFailed )
             .always(function(r){
                       if (r.error === false) {
                         // console.log(r);

                         // add a relationship from ego to the new entity
                         openAttachModal_f2(r);

                         
                       } else {
                         ajaxFailed(r);
                       }
                    })
             ;
    }
    
    // private function to use for ajax post callback
    // add a relationship
    function openAttachModal_f2(data){
      console.log(data);
      var alter_entity_name = data.request_params.table;
      var alter_key_field   = key_field_mapping[alter_entity_name];
      var alter_key_value   = data.results[0][0][alter_key_field];

      var rel = {};
      rel.target = 'relationships';
      rel.action = 'relationshipsAdd';
      rel.table  = 'relationships';
      rel.params = [ego_entity_name,
                    ego_id,
                    alter_entity_name,
                    alter_key_value
                    ];
      $.post(app_root + '/lib/ajax_handler.php', 
             {"request": rel},
             "json"
             )
             .done() 
             .fail( ajaxFailed )
             .always(function(r){
                       if (r.error === false) {
                         // console.log(r);
                         location.reload();
                       } else {
                         ajaxFailed(r);
                       }
                    })
             ;
    }
  }); // end listener for click on the confirm attach button
  return attach_modal;  
}


function ajaxDelete(a){
  console.log(a);
  var all_reqs = [];
  var first_entity = a[0][0];
  // do the deletes
  $.each(a, function(index, this_delete){
    var req = {};
    req.target = 'entity';
    req.action = this_delete[0] + 'Delete';
    req.table  = this_delete[0];
    req.params = [];
    req.params.push(this_delete[2]);
  
    console.log(req);

    var this_req = $.post(app_root + '/lib/ajax_handler.php', 
                          { "request": req },
                          "json"
                          );
    all_reqs.push(this_req);

            /*
             .done() 
             .fail( ajaxFailed )
             .always(function(r) {
                       if (r.error === false){
                         successful_delete = true;
                         ajax_deletes.push(r);
                         console.log(r);
                       } else {
                         ajaxFailed(r);
                       }
                     })
             ;
             */
  });

  $.when.apply($, all_reqs).done(function(){
    console.log(all_reqs);
    // if delete is successful then redirect the window.location
    $.each(all_reqs, function(index, resp){
      if (resp.responseJSON.error === false) {
        return true;
      }
    });
    return false;
  });
}

function getTableData(target, entity) {
  var return_data = {};
  if (!target || !entity) { return false; }
  
  var req = {};
  req.target = target;
  req.action = entity + 'FetchAll';
  req.table  = entity;
  req.params = [ 'json' ];

  console.log(req);

  $.post({url: app_root + '/lib/ajax_handler.php',
          data: { "request": req },
          dataType: "json",
          async: false
         })
         .done() 
         .fail( ajaxFailed )
         .always(function(r) {
                   console.log('getTableData post result = ');
                   console.log(r);
                   if (r.error === false){
                     return_data = JSON.parse(r.results[0]);
                   } else {
                     ajaxFailed(r);
                   }
                 })
         ;

  return return_data;

}


// change array is defined by handsontable
function saveHandsonChange(change, entity, target) {
  // TODO make the autosave message thing work
  clearTimeout(autosave_timeout);

  console.log(change);

  var row      = change[0];
  var col      = change[1];
  var old      = change[2];
  var current  = change[3];

  var database_id = table_handsontable.getDataAtCell(row, 0);

  // object for request is defined by grants.php functions
  var req = {};
  req.target = target;
  req.action = entity + 'Update';
  req.table  = entity;
  req.params = [];

  req.params.push(database_id);
  req.params.push(col);
  req.params.push(current);

  console.log(req);

  $.post(app_root + '/lib/ajax_handler.php', 
           { "request": req },
           "json"
           )
           .done() 
           .fail( ajaxFailed )
           .always(function(r) {
                     if (r.error === false){
                       console.log(r);
                     } else {
                       ajaxFailed(r);
                     }
                   })
           ;

}

// all these params except entity are defined by handsontable
function keyFieldRenderer(instance, td, row, col, prop, value, cellProperties){
  // console.log('inside renderer:');
  // console.log(instance.rootElement.getAttribute('data-entity-name'));
  var entity = instance.rootElement.getAttribute('data-entity-name');
  var id = Handsontable.helper.stringify(value);
  td.innerHTML = '<a href="' + app_root + '/views/one_' + entity + '.php?id=' + id + '">' + id + '</a>';
  return td;
}

function initializeTable( target, entity, key_field ) {
  if (!target || !entity || !key_field) {
    console.log('initializeTable needs the target, entity and key_field');
    return false;
  }

  table_data = getTableData(target, entity);

  if ( typeof table_data !== 'object' || $.isEmptyObject(table_data) ) {
    console.log('initializeTable could not find data for ' + target + ': ' + entity);
    return false;
  }

  var table_data_fields = Object.keys(table_data[0]);

  // console.log(example_data_fields);

  var table_columns = [];

  table_data_fields.forEach(function (field) {
    // console.log(field);
    if ( field === key_field ){
      table_columns.push({data: field,
                          type: 'numeric',
                          editor: false,
                          renderer: keyFieldRenderer
                          });
    } else {
      table_columns.push({data: field});
    }
  })

  var table_element = document.querySelector('#table-holder');
  var table_parent_element = table_element.parentNode;

  var table_settings = {
    data: table_data,
    columns: table_columns,
    colHeaders: table_data_fields,
    rowHeaders: true,
    columnSorting: true,
    sortIndicator: true,
    fixedColumnsLeft: 1,
    maxRows: table_data.length,
    afterChange: function (changes, source) {
      // console.log(source);
      // console.log(changes);
      var edit_types = [ 'alter', 'empty', 'edit', 'autofill', 'paste', 'external' ];
      if ( edit_types.indexOf(source) > -1 ) {
        for ( var i = 0; i < changes.length; i++ ){
          saveHandsonChange(changes[i], entity, target);
        }
      } else {
        console.log('ignoring table change: ' + changes);
        // TODO should reset the table cell to the original value
      }
    }
  }

  table_handsontable = new Handsontable(table_element, table_settings);
}

function addRow(e) {
  // console.log('addRow has begun');
  // console.log(e);       // the event
  // console.log(this);    // the html object
  // console.log($(this)); // the jQuery object

  // get the table name from the html data- attribute
  var this_table_data = $(this).parent('div').data();
  var target = this_table_data.target;
  var tbl = this_table_data.entityName;
  var empty_field = empty_field_mapping[tbl];
  if ( !target || !tbl || !empty_field ){
    console.log('addRow function can not get the meta-data out of the table-holder div');
    console.log('this = ' + this);
    console.log('$(this).parent(\'div\') = ' + $(this).parent('div'));
    console.log('target = ' + target);
    console.log('tbl = ' + tbl);
    console.log('empty_field = ' + empty_field)
    return false;
  }

  // make the request object
  var req = {};
  req.target = target;
  req.action = tbl + 'Add';
  req.table  = tbl;
  // adding a blank field makes a new blank record
  req.params = [empty_field, ''];

  console.log(req);

  $.post(app_root + '/lib/ajax_handler.php',
         { "request": req },
         "json"
         )
         .done( function(d) {
                  console.log('ajax post done');
                  console.log(d);
                  if (d.error === false){
                    location.reload();
                  } else {
                    // TODO display PHP/db error message
                  }
               })
         .fail( function(d){
                  console.log('ajax post fail');
                  console.log(d);
                  // TODO display jQuery/js/http error message
                })
         ;
}

/**
 * I got tired or writing code for checkboxes so I made this function to standardize the values of html form elements.
 *
 * @param object a jQuery object, usually the result of calling it with $(this) from an event handler.
 *
 * @return object with type, key, and value properties.
 */
function getFormInputValue(object) {
  // e.preventDefault();
  console.log(object.is('select'));
  // foobar = object;
  var input = {};
  var type  = '';
  var key   = '';

  if (object.is('select')) {
    // deal with drop down select elements
    input.type  = 'select';
    input.key   = object.attr('name');
    input.value = object.val();
  } else if (object.is('textarea')) {
    // deal with textareas
    input.type  = 'textarea';
    input.key   = object.attr('name');
    input.value = object.val();
  } else {
    // deal with all the input and checkbox elements

    type = object.attr('type');
    key = object.attr('name') ? object.attr('name') : object.attr('id');
  
    // console.log(type);
    // console.log($(this).prop('name'));
    // console.log($(this).prop('id'));
    // console.log($(this).prop('checked'));
    // console.log($(this).prop('value'));

    if (!key || !type) {
      // TODO return something?
      console.log('getFormInputValue is missing key or type.');
    } else {
      input.type = type;
      input.key = key;
    
      // switch statement to get the value;
      switch(type){
        case 'hidden':
        case 'text':
        case 'search':
        case 'tel':
        case 'url':
        case 'email':
        case 'password':
        case 'number':
        case 'range':
        case 'color':
        case 'date':
        case 'datetime-local':
        case 'time':
        case 'file':
        case 'radio':
        case 'image':
          input.value = object.prop('value');
          break;
        case 'checkbox':
          input.value = object.prop('checked') ? 1 : 0;
          break;
        case 'submit':
        // case 'reset':
        case 'button':
          input.value = 1;
          break;
        default:
          input.value = object.prop('value');
          break;
      } // end switch

      // TODO capture other stuff, e.g. html classes or data-* attributes
      // TODO expand to deal with textarea, select, and button elements
    } // end if key and type exists
  } // end if for is select?
  // console.log(input);
  return input;
}

/*
 * Take a result object from ajax_handler.php and return a short text "list" for the entity.
 *
 * Used the most for making human-readable lists in the related entity sidebar.
 * 
 * @param string entity_name The name of the table.
 * @param object data The data object for one entity. Expected to be in the format of field_name: field_value.
 *
 * @return string.
 */
function formatEntityResultAsShortText(entity_name, data){
  console.log(data);
  var return_array = [];
  var title_vars = title_field_mapping[entity_name];
  for (var ii = 0; ii < title_vars.length; ii++) {
    var this_title_var = title_vars[ii];
    var this_string = data[this_title_var];
    console.log(this_string);
    if ( this_string && this_string.length > 18 ){
      this_string = this_string.substr(0,16) + '...';
    }
    return_array.push(this_string);
  }
  return return_array.join(', ');
}

function revealRelatedEntities(e) {
  // console.log($(this));

  // TODO throbber

  var list_items = $(this).find('.related-list-item');

  list_items.each( function(id, this_li){
    console.log( $(this_li).children('a').first() );
    var this_li_data = $(this_li).data()
    console.log(this_li_data);

    // do an ajax data request to get the data
    // prepar the request object
    var req = {};
    req.target   = 'entity';
    req.action   = this_li_data.entity + 'Fetch';
    req.table    = this_li_data.entity;
    req.params   = [];
    // entity id to return
    req.params.push(this_li_data.entityId);
    // set return format to json
    req.params.push('json');
    console.log(req);

    // do the request
    // use the handlers to finish the UI updates
    $.post(app_root + '/lib/ajax_handler.php', 
           { "request": req },
           "json"
           )
           .done(function(d){
                   // console.log('getData post done');
                   var return_data = $.parseJSON(d['results'][0]);
                   console.log(return_data);
                   var new_text = formatEntityResultAsShortText(this_li_data.entity, return_data[this_li_data.entityId]);
                   $(this_li).children('a').first().html(new_text);
                 }) 
           .fail(function(d){
                   console.log('Ajax data load has failed.');
                   console.log(d);
                   $('footer .message-holder').append(d.responseText);
                   $('.server-side-error-message').show();
                   // TODO handle error message
                 })
           .always(function(d) {
                     // console.log('getData always');
                     // TODO update the ui with the best available info
                     // TODO remove throbber
                   })
           ;
    return true;   
  });


}
