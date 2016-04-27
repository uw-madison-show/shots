// Matt Moehr
// 2016-04-27
console.log('Remember: close all files before running this.');

var fs = require('fs');

// going to use the path module to make this Win/*nix transferable
var path = require('path');

var ncp = require('ncp').ncp;

ncp.limit = 8;

// root folder for the project
var root_folder = path.normalize('/Users/Moehr/Documents/GitHub/shots/');
// input/output folders
var destination_folder = path.join(root_folder, 'deploy');
var source_folder      = path.join(root_folder, 'source');

// make an array of the folders in `source` that will be copied
var source_folders = [];

source_folders.push( 'includes' );
source_folders.push( 'lib' );
source_folders.push( 'setup' );


// do the copying of the folders
for (var i = 0; i < source_folders.length; i++) {
  var this_folder = source_folders[i];
  var src = path.join(source_folder, this_folder);
  var des = path.join(destination_folder, this_folder);
  ncp(src, des, function(err){
    if (err) { return console.error(err); }
  });
}

// copy the top level files
source_files = [];
source_files.push('grant.php');
source_files.push('index.html');

for (var i = 0; i < source_files.length; i++) {
  var this_file = source_files[i];
  var source = path.join(root_folder, 'source', this_file);
  var destination = path.join(destination_folder, this_file);
  try {
    fs.createReadStream(source).pipe(fs.createWriteStream(destination));
  } catch (err) {
    return console.log(err);
  }
}



// make empty folders as necessary
var empty_folders = [];
empty_folders.push(path.join(destination_folder, 'database'));

for (var i = 0; i < empty_folders; i++) {
  var this_empty = empty_folders[i];
  fs.mkdir(this_empty, function(err){
    if (err) { return console.log(err); }
  });
}



