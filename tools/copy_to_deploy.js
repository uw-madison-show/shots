// Matt Moehr
// 2016-04-27
console.log('Remember: close all files before running this.');

var fs = require('fs');

// going to use the path module to make this Win/*nix transferable
var path = require('path');
var copy = require('cpr').cpr;
var rm   = require('remove');

// set up cpr
var copy_options = {};
copy_options.filter = filenameFilter;
copy_options.overwrite = true;

// function for ignoring files; defined globally across the whole project
// cpr documentation: "f you give it a RegExp, it will use that to test the filename as they are being gathered. If it passes, it will be removed. If you give it a function, it will use that with Array.filter on the list of files.""
function filenameFilter (filename){
  // TODO make this filename filter for git stuff, and *foo* files
  return true;
}


// TODO make the root folder a CLI parameter
// root folder for the project
var root_folder = path.normalize('/Users/Moehr/Documents/GitHub/shots/');
// input/output folders
var destination_folder = path.join(root_folder, 'deploy');
var source_folder      = path.join(root_folder, 'source');


// clean out the deploy folder
try {
  rm.removeSync(destination_folder);
  console.log('removed ' + destination_folder);
} catch (err) {
  console.log(err);
}



try {
  // make an empty deploy folder
  fs.mkdirSync(destination_folder);
  // make an empty database & file folders
  var empty_folders = [];
  empty_folders.push(path.join(destination_folder, 'database'));
  empty_folders.push(path.join(destination_folder, 'database/files'));
  for (var i = 0; i < empty_folders.length; i++) {
    var this_empty = empty_folders[i];
    // console.log(this_empty);
    fs.mkdirSync(this_empty);
  }
  console.log('created empty folders');
} catch (err) {
  console.log(err);
}

// make an array of the folders in `source` that will be copied
var source_folders = ['includes',
                      'lib',
                      'views',
                      ];


// do the copying of the folders
for (var i = 0; i < source_folders.length; i++) {
  var this_folder = source_folders[i];
  var src = path.join(source_folder, this_folder);
  var des = path.join(destination_folder, this_folder);
  copy(src, des, function(err){
    if (err) { return console.error(err); }
  });
}
console.log('done cloning folders');


// copy the top level files
source_files = ['sign_in.php',
                'delete.php',
                'index.php',
                'manage_database.php',
                '.htaccess'
                ];

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
console.log('done copying files');






