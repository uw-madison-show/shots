// Matt Moehr
// 2016-05-31

/**********************************************************/

// "Global" Vars

/**********************************************************/

var autosave_timeout, table_data, table_handsontable, table_data_key_field;

var key_field_mapping = { 
                          "grants": "grant_id",
                          "people": "person_id"
                        }

// these fields are set to empty for the addRow function
// just need to be string field with no constraints
var empty_field_mapping = {
                            "grants": "title",
                            "people": "name" 
                          }            

// these are the fields that most humans would want to read
// when they want to see a "list" of the entities
// i'm calling these fields, "titles" but i am not married to that term
// must be an array of variable names. array of 1 is ok.
var title_field_mapping = {
                            "grants": ["title", "grant_body"],
                            "people": ["name", "affiliation"]
                          }

/**********************************************************/

// On page load

/**********************************************************/

jQuery(document).ready(function(){
  // handle an error message coming from the server/db
  if( $('.server-side-error-message').length ){
    $('.server-side-error-message').each(function(){
      $('footer .message-holder').append(this);
      toggleErrorMessageVisiblity(this);
    });
  }
});

/**********************************************************/

// Functions

/**********************************************************/

function toggleErrorMessageVisiblity(message){
  // console.log(message);
  $(message).toggle();

}


function ajaxChange(e){
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

  $.post('/lib/ajax_handler.php', 
         { "request": req },
         "json"
         )
         .done(function(d){
                 console.log('ajaxChange post done');
                 console.log(d);
               }) 
         .fail(function(d){
                 console.log('ajaxChange post fail');
                 console.log(d);
                 // TODO handle error message
               })
         .always(function(d) {
                   console.log('ajaxChange always');
                   // TODO check if the result object has error messages from php/db
                 })
         ;
}

function getTableData(entity) {
  var data = {};
  if (!entity) { return data; }
  
  var req = {};
  req.target = 'entity';
  req.action = entity + 'FetchAll';
  req.table  = entity;
  req.params = [ 'json' ];

  console.log(req);

  $.post({url: '/lib/ajax_handler.php',
          data: { "request": req },
          dataType: "json",
          async: false
         })
         .done( function(d) {
                  console.log('ajax post done');
                  console.log(d);
                  // TODO add handling for php/db errors; make sure that the result is parsable json;
                  data = $.parseJSON(d['results'][0]);
               })
         .fail( function(d){
                  console.log('ajax post fail');
                  console.log(d);
                })
         ;

  return data;

}


// change array is defined by handsontable
function saveHandsonChange(change, entity) {
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
  req.target = 'entity';
  req.action = entity + 'update';
  req.table  = entity;
  req.params = [];

  req.params.push(database_id);
  req.params.push(col);
  req.params.push(current);

  console.log(req);

  $.post('/lib/ajax_handler.php', 
           { "request": req },
           "json"
           )
           .done(
                 function(d){
                   console.log('ajax post done');
                   console.log(d);
                 }) 
           .fail(
                 // TODO if there are db/server errors there should be a notification fro the user and the original value should be set
                 function(d){
                   console.log('ajax post fail');
                   console.log(d);
                 })
           .always(
                   function(d) {
                     console.log('ajax always');
                   })
           ;

}

// all these params except entity are defined by handsontable
function keyFieldRenderer(instance, td, row, col, prop, value, cellProperties){
  // console.log('inside renderer:');
  // console.log(instance.rootElement.getAttribute('data-entity-name'));
  var entity = instance.rootElement.getAttribute('data-entity-name');
  var id = Handsontable.helper.stringify(value);
  td.innerHTML = '<a href="/views/one_' + entity + '.php?id=' + id + '">' + id + '</a>';
  return td;
}

function initializeTable( entity, key_field ) {
  if (!entity || !key_field) {
    console.log('initializeTable needs the entity and key_field');
    return false;
  }

  table_data = getTableData(entity);

  // table_data.forEach( function(this_row) {
  //   var original = this_row[table_data_key_field];
  //   this_row[table_data_key_field] = '<strong><a href="/grant.php?id=' + original + '">' + original + '</a></strong>';
  // });

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
          saveHandsonChange(changes[i], entity);
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
  var tbl = this_table_data.entityName;
  var empty_field = empty_field_mapping[tbl];
  if ( !tbl || !empty_field ){
    console.log('addRow function can not get the meta-data out of the table-holder div');
    console.log('this = ' + this);
    console.log('$(this).parent(\'div\') = ' + $(this).parent('div'));
    console.log('tbl = ' + tbl);
    console.log('empty_field = ' + empty_field)
    return false;
  }

  // make the request object
  var req = {};
  req.target = 'entity';
  req.action = tbl + 'Add';
  req.table  = tbl;
  // adding a blank field makes a new blank record
  req.params = [empty_field, ''];

  console.log(req);

  $.post('/lib/ajax_handler.php',
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
  console.log(object);
  var input = {};

  var type = object.attr('type');
  var key = object.attr('name') ? object.attr('name') : object.attr('id');
  
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
    if ( this_string.length > 18 ){
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
    $.post('/lib/ajax_handler.php', 
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
                   $('.server-side-error-message').each(function(){
                     toggleErrorMessageVisiblity(this);
                   });
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
