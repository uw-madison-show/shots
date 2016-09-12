Development tools for the shots project.

If you are deploying shots onto a server, you don't need to worry about anything in this folder. (Nor do you need to worry about anything in the `source` folder for that matter.)

## Dependencies

1. php >= 5.4.0 on the dev machine. This provides a "built-in sever":
    * `php -S 127.0.0.1:8080`
    * I made a ini file for the built in server on my dev, but hopefully it will not be necessary when loading into test/prod. See `...\shots\source\php\built_in_server.ini`.
2. node on the dev machine
3. npm on the dev machine
    * Suggested: I made this on a windows machine so I used babun for all of my *nix terminal emmulation needs.
4. Browsersync
    * Install it in this directory.
        > $ cd .../shots/tools
        > $ npm install browser-sync
    * Run it: `browser-sync ... `
5. Node modules used for deploying and moving files:
    * ~~ncp -- recursive copy~~ cpr -- recursive copy
    * base packages: path, fs

## Run the system in dev mode

In the windows command prompt:

    > cd C:\...\shots
    > php -S 127.0.0.1:8080 -c .\tools\php\built_in_server.ini -t .\source

Then in the babun prompt:

    $ cd .../shots/tools
    $ node start_dev_server.js &

You now have two command prompts watching for changes. Sigh. I know. I wasted 90 minutes of my life trying to make this better but there seems to be some weird bugginess in cygwin and php command line, so whatevs. This works.





