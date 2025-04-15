<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
| You can load these resources automatically:
| 1. Packages
| 2. Libraries
| 3. Drivers
| 4. Helper files
| 5. Custom config files
| 6. Language files
| 7. Models
*/

$autoload['packages'] = array();  // No packages to auto-load

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in system/libraries/ or your
| application/libraries/ directory, with the addition of the
| 'database' library, which is somewhat of a special case.
|
| Example:
|  $autoload['libraries'] = array('database', 'email', 'session');
|
*/
$autoload['libraries'] = array('database', 'session', 'form_validation');  // Add commonly used libraries

/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
| Classes are located in system/libraries/ or application/libraries/
| that extend CI_Driver_Library. These classes offer interchangeable driver options.
|
| Example:
|  $autoload['drivers'] = array('cache');
|
*/
$autoload['drivers'] = array();  // No drivers to auto-load

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Common helper files are automatically loaded here.
|
| Example:
|  $autoload['helper'] = array('url', 'file');
|
*/
$autoload['helper'] = array('url', 'form');  // Auto-load helpers like 'url' and 'form'

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Custom config files can be auto-loaded here.
|
| Example:
|  $autoload['config'] = array('config1', 'config2');
|
*/
$autoload['config'] = array();  // No custom config files to auto-load

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Auto-load language files for localization.
|
| Example:
|  $autoload['language'] = array('lang1', 'lang2');
|
*/
$autoload['language'] = array();  // No language files to auto-load

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Models are automatically loaded here.
|
| Example:
|  $autoload['model'] = array('first_model', 'second_model');
|
*/
$autoload['model'] = array('Barang_model', 'Peminjaman_model');  // Auto-load models related to your application
