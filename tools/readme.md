Development tools for the shots project.

If you are deploying shots onto a server, you don't need to worry about anything in this folder. (Nor do you need to worry about anything in the `source` folder for that matter.)

## Dependencies

1. php >= 5.4.0 on the dev machine. This provides a "built-in sever":
    * `php -S 127.0.0.1:8080`
2. node on the dev machine
2. npm on the dev machine
    * Suggested: I made this on a windows machine so I used babun for all of my *nix terminal emmulation needs.
3. Browsersync
    * Install it in this directory.
    
        > $ cd .../shots/tools
        > $ npm install browser-sync
    * Run it: `browser-sync ... `

## Run the system in dev mode

> $ cd .../shots/tools
> $ node << filename of js file goes here >>


