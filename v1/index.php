<?php

require '.././libs/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/**
 * ------------------------------------------------
 * Flick API starts here
 * ------------------------------------------------
 */

/**
 * User login
 * url - /login
 * method - POST
 * params - email, password
 */
$app->post('/login', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('email', 'pass'));

    $response = array();

    // reading post params
    $email = $app->request->post('email');
    $pass = $app->request->post('pass');

    if($pass == "success") {
        $response["error"] = false;
        $response["sessionId"] = "FakeSessionId";
    } else {
        $response["error"] = true;
        $response["sessionId"] = null;
    }

    // echo json response
    echoResponse(201, $response);
});

/**
 * User login
 * url - /login
 * method - POST
 * params - email, password
 */
$app->get('/bills', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('email', 'pass'));

    $response = array();

    // reading post params
    $email = $app->request->post('email');
    $pass = $app->request->post('pass');

    if($pass == "success") {
        $response["error"] = false;
        $response["sessionId"] = "FakeSessionId";
    } else {
        $response["error"] = true;
        $response["sessionId"] = null;
    }

    // echo json response
    echoResponse(201, $response);
});

/**
 * Access a list of the challenges in a particular group
 * url - /challenges_in_group
 * method - GET
 */
$app->get('/bills', function() use ($app){
    // check for required params
    verifyRequiredParams(array('sessionId'));

    $req = $app->request();
    $sessionId = $req->get('sessionId');
    $response = array();

    $db = new DbHandler();

    $response["challenges"] = $db->getChallengesInGroup($group);

    echoResponse(200, $response);
});

/**
 * ------------------------------------------------
 * Flick Api ends here
 * ------------------------------------------------
 */

/**
 * ------------------------------------------------
 * Hop Balance Reporting API starts here
 * ------------------------------------------------
 */
$app->post('/access', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('email', 'channel', 'success'));

    // reading post params
    $email = $app->request->post('email');
    $channel = $app->request->post('channel');
    $success = $app->request->post('success') === "true" ? 1 : 0;

    //verify channel
    if($channel !== "iPhone" && $channel !== "Android" && $channel !== "iPad"){
        echo "invalid channel";
        return;
    }

    $db = new DbHandler();
    $db->updateLog($email, $channel, $success);
});


/**
 * ------------------------------------------------
 * Hop Balance Reporting API ends here
 * ------------------------------------------------
 */

/**
 * ------------------------------------------------
 * Support functions start here
 * ------------------------------------------------
 */

/**
 * Verifying required params posted or not
 * @param $required_fields array of the fields which are meant to be in the request
 * @throws \Slim\Exception\Stop
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(400, $response);
        $app->stop();
    }
}

/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param [] $response Json response
 */
function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}
/**
 * ------------------------------------------------
 * Support functions end here
 * ------------------------------------------------
 */

$app->run();