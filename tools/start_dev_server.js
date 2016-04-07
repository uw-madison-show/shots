// Matt Moehr
// 2016-04-07

// require Browsersync
var Browsersync = require('browser-sync').create();


// run the server config
Browsersync.init({server: "../source",
                  files: ["../source/*",
                          "../source/includes/*"
                          ]
                  });