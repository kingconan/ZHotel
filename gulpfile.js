var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');

    //copy files
    mix.copy('vendor/almasaeed2010/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css',
        'public/css/libs/bootstrap.css');
    mix.copy('vendor/almasaeed2010/adminlte/dist/css/AdminLTE.min.css',
        'public/css/libs/admin-lte.css');
    mix.copy('vendor/almasaeed2010/adminlte/dist/css/skins/_all-skins.css',
        'public/css/libs/admin-lte-skin.css');
    mix.copy('vendor/almasaeed2010/adminlte/dist/js/adminlte.min.js',
        'public/js/libs/admin-lte.js');
    mix.copy('vendor/almasaeed2010/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js',
        'public/js/libs/bootstrap.js');

    mix.copy('vendor/almasaeed2010/adminlte/bower_components/jquery/dist/jquery.min.js',
        'public/js/libs/jquery.js');

    mix.copy('node_modules/axios/dist/axios.min.js',
        'public/js/libs/axios.js');

    mix.copy('vendor/almasaeed2010/adminlte/bower_components/bootstrap/fonts',
        'public/fonts');

    // Font Awesome
    mix.copy('vendor/almasaeed2010/adminlte/bower_components/font-awesome/css/font-awesome.css',
        'public/css/libs/font-awesome.css');
    mix.copy('vendor/almasaeed2010/adminlte/bower_components/font-awesome/fonts',
        'public/fonts');


    // iCheck
    mix.copy('vendor/almasaeed2010/adminlte/plugins/iCheck/square/blue.css',
        'public/css/libs/i-check.css');
    mix.copy('vendor/almasaeed2010/adminlte/plugins/iCheck/square/blue.png',
        'public/css/blue.png');
    mix.copy('vendor/almasaeed2010/adminlte/plugins/iCheck/icheck.js',
        'public/js/libs/i-check.js');

    // Merge all CSS files in one file.
    mix.styles([
        '/libs/bootstrap.css',
        '/libs/admin-lte.css',
        'libs/admin-lte-skin.css',
        'libs/font-awesome.css',
        '/libs/i-check.css',
        //'/libs/custom.css',
    ], './public/css/min.css', './public/css');
    mix.styles([
        '/libs/bootstrap.css',
    ], './public/css/min_2.css', './public/css');

    // Merge all JS  files in one file.
    mix.scripts([
        '/libs/jquery.js',
        '/libs/axios.js',
        '/libs/bootstrap.js',
        '/libs/admin-lte.js',
        //'/libs/i-check.js',
    ], './public/js/min.js', './public/js');

    // Merge all JS  files in one file.
    mix.scripts([
        '/libs/jquery.js',
        '/libs/axios.js',
        '/libs/bootstrap.js',
    ], './public/js/min_2.js', './public/js');

    // Merge all JS  files in one file.
    mix.scripts([
        '/libs/jquery.js',
        '/libs/axios.js',
    ], './public/js/min_3.js', './public/js');
});
