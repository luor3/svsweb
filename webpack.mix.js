const mix = require('laravel-mix');
var path = require('path');
var vtkRules = require('@kitware/vtk.js/Utilities/config/dependency.js').webpack.core.rules;
var entry = path.join(__dirname, './resources/js/app.js');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ]);
    // tinymce editor dependencies
    mix.copyDirectory('node_modules/tinymce/icons', 'public/node_modules/tinymce/icons');
    mix.copyDirectory('node_modules/tinymce/plugins', 'public/node_modules/tinymce/plugins');
    mix.copyDirectory('node_modules/tinymce/skins', 'public/node_modules/tinymce/skins');
    mix.copyDirectory('node_modules/tinymce/themes', 'public/node_modules/tinymce/themes');
    mix.copy('node_modules/tinymce/jquery.tinymce.js', 'public/node_modules/tinymce/jquery.tinymce.js');
    mix.copy('node_modules/tinymce/jquery.tinymce.min.js', 'public/node_modules/tinymce/jquery.tinymce.min.js');
    mix.copy('node_modules/tinymce/tinymce.js', 'public/node_modules/tinymce/tinymce.js');
    mix.copy('node_modules/tinymce/tinymce.min.js', 'public/node_modules/tinymce/tinymce.min.js');


mix.webpackConfig({
     module: {
        rules: [
            { 
                test: entry, 
                loader: 'expose-loader',
                options: {
                  exposes: {
                    globalName: 'MyWebApp',
                    override: true
                  },
                }
            },
            { 
                test: /\.html$/, 
                use: ['html-loader']
            },
            { 
                test: /\.glsl$/i, 
                use: ['shader-loader']
            },
            { 
                test: /\.jsx?$/, 
                use: ['babel-loader'], 
            },
            {
                test: /\.worker\.js$/,
                use: { loader: "worker-loader" },
              },
        ].concat(vtkRules),
    },
});


if (mix.inProduction()) {
    mix.version();
}
