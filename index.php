<?php

/**
 * Index page delegate user actions
 * to corresponding RESTful API
 * requests.
 * 
 */
// reference to the class that defines the request action methods
include_once 'app/RequestAction.php';

// retrieve action value from query string
$action = $_REQUEST['action']??'index';

// create instance 
$requestAction = new RequestAction();
// call corresponding method - expression {$action} resolves to name of method
$requestAction->{$action}();

?>
<br>
<a href="?">Home</a>
<a href="?action=getData">Get Data from REST Server</a>



