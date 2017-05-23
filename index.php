<?php
/**
 * Main document for crm framework
 *
 * All includes and main class intialization 
 * are included in this file
 * 
 * PHP VERSION 5
 *
 * @category CRM
 * @package  CRM
 * @author   Rey Lim Jr <junreyjr1029@gmail.com>
 * @license  GPLV2 http:///www.license.com
 * @link     http://nextopics.com
 */

// Start session at the top of the file
// to prevent error.
session_start();

// This file is for the defined variables that are important
// in the entire site.
require 'config/paths.php';

// Define constant variables fo database connection.
require 'config/database.php';

/**
 * Function to automatically load classess
 *
 * Only classes with correct format 
 * are qualified to be autoloaded.
 * 
 * @params $class string classname of the file in a directory
 * 
 * @author Rey Lim Jr <junreyjr1029@gmail.com>
 * @return void
 */


function __autoload($class)
{
    // Generate a file name.
    $file = "libs/$class.php";

    // Check if the filename exist.
    if (file_exists($file) === true) {
        // Include if exist.
        include_once 'libs/'.$class.'.php';
    }

}//end __autoload()


// Instantiate navigation.
$navigation = new Navigation;

// Instantiate bootstrap.
$app = new Bootstrap();