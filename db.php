<?php

/**
 * Configuration file for: Database Connection
 * This is the place where your database login constants are saved
 *
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/q/2447791/1114320
 */


/**
 * database host, usually it's "127.0.0.1" or "localhost", some servers also need port info, like "127.0.0.1:8080"
 */
define("DB_HOST", "127.0.0.1");

/**
 * name of the database. please note: database and database table are not the same thing!
 */
define("DB_NAME", "sixtylime");

/**
 * user for your database. the user needs to have rights for SELECT, UPDATE, DELETE and INSERT.
 * by the way, it's bad style to use "root". In a real application you should create a database user
 * that fits your needs.
 */
define("DB_USER", "root");

/**
 * The password of the above user
 */
define("DB_PASS", "vrcko787");


// Cookie 

define('COOKIE_RUNTIME', 1209600); // 1209600 seconds = 2 weeks
define('COOKIE_DOMAIN', '.127.0.0.1'); // the domain where the cookie is valid for, like '.mydomain.com'
define('COOKIE_SECRET_KEY', 'lololololol'); // use to salt cookie content and when changed, can invalidate all databases users cookies
