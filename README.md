# Media

*Tasks that work with my [http://github.com/Zeelot/kohana-media](kohana-media) module*

- **Module Version:** 1.1.0
- **Module URL:** <https://github.com/Zeelot/kohana-media>
- **Compatible Kohana Version(s):** 3.2.x

## Description
Currently provides `media:compile` and `media:watch` tasks. `media:compile` will gather all the JS and SCSS files in your Cascading Filesystem and compile them into files to server using the [http://github.com/Zeelot/kohana-media](kohana-media) module. `media:watch` will watch your media files for changes and automatically trigger `media:compile` for you.

The module will also allow you to configure additional compilers with a simple config file and interface class.

## Requirements

- **uglifyjs** - required to minify the JS
- **compass** - required to compile and minify the SCSS files into CSS files

The tasks have only been tested on Ubuntu servers for now and I anticipage issues on Windows systems. Let me know if you test the tasks in any other OS and run into any issues.