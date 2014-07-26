<?php
// currently not supported
if(php_sapi_name() == 'cli')
{
    $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['HTTP_HOST'] = 'localhost';
    $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
}

xhprof_enable(XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_CPU);

register_shutdown_function(function(){
    // by registering register_shutdown_function at the end of the file
    // I make sure that all execution data, including that of the earlier
    // registered register_shutdown_function, is collected.

    $xhprof_data	= xhprof_disable();

    if(function_exists('fastcgi_finish_request'))
    {
        fastcgi_finish_request();
    }

    $config			= require __DIR__ . '/../xhprof/includes/config.inc.php';

    require_once __DIR__ . '/../xhprof/classes/data.php';

    $xhprof_data_obj	= new \ay\xhprof\Data($config['pdo']);
    $xhprof_data_obj->save($xhprof_data);
});