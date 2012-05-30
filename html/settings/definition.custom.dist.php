<?php
////////////////////////
// from mainfile.php
////////////////////////

// XOOPS Physical Path
// Physical path to your main XOOPS directory WITHOUT trailing slash
// Example: define('XOOPS_ROOT_PATH', '/path/to/xoops/directory');
define('XOOPS_ROOT_PATH', '');

// XOOPS Trusted Path
// This is option. If you need this path, input value. The trusted path
// should be a safety directory which web browsers can't access directly.
define('XOOPS_TRUST_PATH', '');

// XOOPS Virtual Path (URL)
// Virtual path to your main XOOPS directory WITHOUT trailing slash
// Example: define('XOOPS_URL', 'http://url_to_xoops_directory');
define('XOOPS_URL', 'http://');

// XOOPS Cookie Path
// A path for setting cookies. don't terminate it by '/'
// Example: define('XOOPS_COOKIE_PATH', '');
define('XOOPS_COOKIE_PATH','');

// Database
// Choose the database to be used
define('XOOPS_DB_TYPE', 'mysql');

// Table Prefix
// This prefix will be added to all new tables created to avoid name conflict in the database. If you are unsure, just use the default 'xoops'.
define('XOOPS_DB_PREFIX', '');

// SALT
// This plays a supplementary role to generate secret code and token.
define('XOOPS_SALT', '');

// Database Hostname
// Hostname of the database server. If you are unsure, 'localhost' works in most cases.
define('XOOPS_DB_HOST', 'localhost');

// Database Username
// Your database user account on the host
define('XOOPS_DB_USER', '');

// Database Password
// Password for your database user account
define('XOOPS_DB_PASS', '');

// Database Name
// The name of database on the host. The installer will attempt to create the database if not exist
define('XOOPS_DB_NAME', '');

// Use persistent connection? (Yes=1 No=0)
// Default is 'No'. Choose 'No' if you are unsure.
define('XOOPS_DB_PCONNECT', 0);

define('XOOPS_GROUP_ADMIN', '1');
define('XOOPS_GROUP_USERS', '2');
define('XOOPS_GROUP_ANONYMOUS', '3');

// XCL Settings
define('XCL_MEMORY_LIMIT', '32M');
define('LEGACY_INSTALLERCHECKER_ACTIVE', true);

// RYUS extra
define('RYUS_TEMPLATES_SET_PATH', XOOPS_ROOT_PATH . '/ryus_templates_set');
define('RYUS_ADMIN_THEME_PATH', RYUS_TEMPLATES_SET_PATH . '/ryus_admin_theme');
define('RYUS_ADMIN_THEME_URL', XOOPS_URL . '/ryus_templates_set/ryus_admin_theme');
