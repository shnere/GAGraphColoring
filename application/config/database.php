<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = 'online_externa';
$active_record = TRUE;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = 'root';
$db['default']['database'] = 'clasificador';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


$db['online_externa']['hostname'] = '149.47.143.251:3306';
$db['online_externa']['username'] = 'votainfo_analiza';
$db['online_externa']['password'] = 'anal1z4d0r';
$db['online_externa']['database'] = 'votainfo_analizador';
$db['online_externa']['dbdriver'] = 'mysql';
$db['online_externa']['dbprefix'] = '';
$db['online_externa']['pconnect'] = TRUE;
$db['online_externa']['db_debug'] = TRUE;
$db['online_externa']['cache_on'] = FALSE;
$db['online_externa']['cachedir'] = '';
$db['online_externa']['char_set'] = 'utf8';
$db['online_externa']['dbcollat'] = 'utf8_general_ci';
$db['online_externa']['swap_pre'] = '';
$db['online_externa']['autoinit'] = TRUE;
$db['online_externa']['stricton'] = FALSE;

$db['online']['hostname'] = 'localhost';
$db['online']['username'] = 'votainfo_analiza';
$db['online']['password'] = 'anal1z4d0r';
$db['online']['database'] = 'votainfo_analizador';
$db['online']['dbdriver'] = 'mysql';
$db['online']['dbprefix'] = '';
$db['online']['pconnect'] = TRUE;
$db['online']['db_debug'] = TRUE;
$db['online']['cache_on'] = FALSE;
$db['online']['cachedir'] = '';
$db['online']['char_set'] = 'utf8';
$db['online']['dbcollat'] = 'utf8_general_ci';
$db['online']['swap_pre'] = '';
$db['online']['autoinit'] = TRUE;
$db['online']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */