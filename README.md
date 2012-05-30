# Media Tasks

*Tasks that work with my [kohana-media](http://github.com/Zeelot/kohana-media) module*

- **Module Version:** 0.0.0
- **Module URL:** <https://github.com/Zeelot/kohana-media>
- **Compatible Kohana Version(s):** 3.2.x

## Description
Currently provides `media:compile` and `media:watch` tasks. `media:compile` will gather all the JS and SCSS files in your Cascading Filesystem and compile them into files to serve using the [kohana-media](http://github.com/Zeelot/kohana-media) module. `media:watch` will watch your media files for changes and automatically trigger `media:compile` for you.

The module will also allow you to configure additional compilers with a simple config file and interface class.

## Requirements

- **uglifyjs** - required to minify the JS
- **compass** - required to compile and minify the SCSS files into CSS files
- **coffee** - required to compile coffee files. This can be installed through npm.

The tasks have only been tested on Ubuntu servers for now and I anticipate issues on Windows systems. Let me know if you test the tasks in any other OS and run into any issues.