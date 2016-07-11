// Matt Moehr
// 2016-05-31

/**********************************************************/

// "Global" Vars

/**********************************************************/

var autosave_timeout, table_data, table_handsontable, table_data_key_field;


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
  console.log($(this).parents('.record').data('entityName'));
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
  // this requires the html to be marked up in a specific way
  req.params.push($(this).closest('.record').find('#grant_id').val());

  // field name and new value;
  req.params.push($(this).attr('id'));
  req.params.push($(this).val());

  console.log(req);

  $.post('/lib/ajax_handler.php', 
         { "request": req },
         "json"
         )
         .done(function(d){
                 console.log('ajaxChange post done');
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
  td.innerHTML = '<a href="/views/' + entity + '.php?id=' + id + '">' + id + '</a>';
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
  // console.log(e);
  // console.log(this);
  // console.log($(this));

  // TODO change the action and table parameters to dynamically use the entity name, e.g. 'grants', 'people'
  var req = {};
  req.target = 'entity';
  req.action = 'addGrant';
  req.table = 'grants';
  // adding a blank title makes a new blank record
  req.params = ['title', ''];

  console.log(req);

  $.post('/lib/ajax_handler.php',
         { "request": req },
         "json"
         )
         .done( function(d) {
                  console.log('ajax post done');
                  console.log(d);
                  // TODO update table_data and use HoT .render()
                  // table_data = getTableData();
                  // console.log(table_data);
                  // why doesn't this work?
                  // table_handsontable.render();
                  location.reload();
               })
         .fail( function(d){
                  console.log('ajax post fail');
                  console.log(d);
                })
         ;
}



