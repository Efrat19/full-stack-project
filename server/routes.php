<?php
include($_SERVER['DOCUMENT_ROOT'] . '/server/controllers/BaseController.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server/controllers/CreateDBController.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server/controllers/HandleFormController.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server/controllers/Response.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server/controllers/Service.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server/controllers/ShowDBController.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server/controllers/SelectFromDBController.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server/controllers/LogRow.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server/controllers/User.php');
include($_SERVER['DOCUMENT_ROOT'] . '/server/Router.php');


$router = new Router();

/**
 * Allowed url pattern prefixes
 */
$router->set_prefix( array( 'default'   => '' ) );
$router->set_prefix( array( 'admin'     => 'admin' ) );


/**
 * Set up allowed URLs
 */
//$router->set( array ( '/\/api/postLead/'    => 'BaseController@route' ) );
$router->set( array ( '/\/api\/db/'    => 'CreateDBController@index' ) );
$router->set( array ( '/\/api\/postLead/'    => 'HandleFormController@index' ) );
$router->set( array ( '/\/api\/showDB/'    => 'ShowDBController@index' ) );
$router->set( array ( '/\/api\/select/'    => 'SelectFromDBController@index' ) );

$router->run();