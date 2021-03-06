<?php

/*
 * jQuery File Upload Plugin PHP Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

// error_reporting(E_ALL | E_STRICT);
require_once('all_pages.php');
require_once('shots/entities/documents.php');

// $upload_handler = new UploadHandler( 
//   array('upload_dir' => "C:\\Users\\moehr\\Documents\\GitHub\\shots\\source\\database\\files\\",
//         'upload_url' => "localhost:3000/database/files/")
//   );

// this is the name of the folder relative to the web root for creating the download url
// TODO figure out how to make this part of the options array and use it like a real $this->options['option_name'] style variable.
$server_file_storage_root = '/database/files';


   
$upload_handler = new ShotsUploadHandler( $file_upload_options );

?>