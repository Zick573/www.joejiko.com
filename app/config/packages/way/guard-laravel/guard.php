<?php

return array(

  /*
  |--------------------------------------------------------------------------
  | The JavaScripts Path
  |--------------------------------------------------------------------------
  |
  | This is where you can specify a custom path to your JavaScripts
  | directory. We've set a sensible default, but feel free to update it.
  |
  */
  'js_path' => 'public/js/app',

  /*
  |--------------------------------------------------------------------------
  | The Stylesheets Path
  |--------------------------------------------------------------------------
  |
  | This is where you can specify a custom path to your Stylesheets
  | directory. We've set a sensible default, but feel free to update it.
  |
  */
  'css_path' => 'public/css',

  /*
  |--------------------------------------------------------------------------
  | Base Path for Compile Assets
  |--------------------------------------------------------------------------
  |
  | If you need to specify a custom directory for where assets should be
  | compiled, then update this setting, as needed. If you're unsure, just
  | leave this as it is.
  |
  */
  'compile_path' => 'public',

  /*
  |--------------------------------------------------------------------------
  | The Path To Your Assets Directory
  |--------------------------------------------------------------------------
  |
  | Here, you'll specify where you want your assets directory to go.
  | This will be the base directory, where sass, less, and coffee directories
  | will be inserted.
  |
  */
  'assets_path' => 'app/assets',

  /*
  |--------------------------------------------------------------------------
  | JavaScript Concatenation
  |--------------------------------------------------------------------------
  |
  | By default, we're going to concat all files from your JavaScript directory,
  | but that's probably not what you want. When you need to set a specific order
  | for concatenation, set this value to an array of paths that are relative
  | to what you have set in the `js_path` option, above. So, for `public/js/main.js`,
  | you'd simply add `array('main')` (the extension may be left off).
  |
  */
  'js_concat' => array(
    'main'
  ),

  /*
  |--------------------------------------------------------------------------
  | CSS Concatenation
  |--------------------------------------------------------------------------
  |
  | By default, we're going to concat all files from your CSS directory,
  | but that's probably not what you want. When you need to set a specific order
  | for concatenation, set this value to an array of paths that are relative
  | to what you have set in the `css_path` option, above. So, for `public/css/buttons.css`,
  | you'd simply add `array('buttons')` (the extension may be left off).
  |
  */
  'css_concat' => array('styles'),
  'guard_options' => array(
      'sass' => array(
          'compass' => true
      )
  )
);