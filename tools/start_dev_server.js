// Matt Moehr
// 2016-04-07

// require Browsersync
var Browsersync = require('browser-sync').create();

// run the server config
Browsersync.init({files: ["../source/*",
                          "../source/lib/*",
                          "../source/lib/js/*",
                          "../source/lib/css/*",
                          "../source/lib/shots/entities/*",
                          "../source/views/*"
                          ],
                  proxy: "127.0.0.1:8080"
                  });