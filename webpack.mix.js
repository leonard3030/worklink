let mix = require("laravel-mix");
require("./webpack-custom-config");
mix
    .options({
        postCss: [
            require('autoprefixer'),
        ],
    })
    /*
     |--------------------------------------------------------------------------
     | Mix Asset Management
     |--------------------------------------------------------------------------
     */

mix
    .js(
        ["scheme/vue/main.js"],
        "assets/js/vue_main.js"
    ).extract().setPublicPath('assets/js/')
    .disableNotifications();
mix
    .js(
        ["scheme/angular/polyfills.ts", "scheme/angular/main.ts"],
        "assets/js/angular_main.js"
    ).extract().setPublicPath('assets/js/')
    .disableNotifications();

mix
    .js(
        ["scheme/react/main.js"],
        "assets/js/react_main.js"
    ).extract().setPublicPath('assets/js/')
    .disableNotifications();