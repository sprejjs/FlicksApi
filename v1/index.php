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
 * Return the list of the bills
 * url - /bills
 * method - GET
 */
$app->get('/bills/:sessionId', function($sessionId) use ($app){
    $response = array();

    $bills = array();

    $tmp["StartDate"] = "2015-06-01";
    $tmp["EndDate"] = "2015-06-07";
    $tmp["Amount"] = "$12.26";
    $tmp["Url"] = 'bills/url/asdfwebao2124baadf231/bill_06_01.pdf';
    array_push($bills, $tmp);

    $tmp["StartDate"] = "2015-06-08";
    $tmp["EndDate"] = "2015-06-14";
    $tmp["Amount"] = "$34.06";
    $tmp["Url"] = 'bills/url/asdfwebao2124baadf231/bill_06_08.pdf';
    array_push($bills, $tmp);

    $tmp["StartDate"] = "2015-06-15";
    $tmp["EndDate"] = "2015-06-21";
    $tmp["Amount"] = "$28.12";
    $tmp["Url"] = 'bills/url/asdfwebao2124baadf231/bill_06_15.pdf';
    array_push($bills, $tmp);

    $tmp["StartDate"] = "2015-06-22";
    $tmp["EndDate"] = "2015-06-28";
    $tmp["Amount"] = "$29.19";
    $tmp["Url"] = 'bills/url/asdfwebao2124baadf231/bill_06_22.pdf';
    array_push($bills, $tmp);

    $tmp["StartDate"] = "2015-06-29";
    $tmp["EndDate"] = "2015-07-05";
    $tmp["Amount"] = "$20.46";
    $tmp["Url"] = 'bills/url/asdfwebao2124baadf231/bill_06_29.pdf';
    array_push($bills, $tmp);

    $tmp["StartDate"] = "2015-07-06";
    $tmp["EndDate"] = "2015-07-12";
    $tmp["Amount"] = "$15.12";
    $tmp["Url"] = 'bills/url/asdfwebao2124baadf231/bill_07_06.pdf';
    array_push($bills, $tmp);

    $tmp["StartDate"] = "2015-07-13";
    $tmp["EndDate"] = "2015-07-19";
    $tmp["Amount"] = "$19.44";
    $tmp["Url"] = 'bills/url/asdfwebao2124baadf231/bill_07_13.pdf';
    array_push($bills, $tmp);

    $tmp["StartDate"] = "2015-07-20";
    $tmp["EndDate"] = "2015-07-26";
    $tmp["Amount"] = "$23.56";
    $tmp["Url"] = 'bills/url/asdfwebao2124baadf231/bill_07_20.pdf';
    array_push($bills, $tmp);

    $tmp["StartDate"] = "2015-07-27";
    $tmp["EndDate"] = "2015-08-02";
    $tmp["Amount"] = "$28.32";
    $tmp["Url"] = 'bills/url/asdfwebao2124baadf231/bill_07_27.pdf';
    array_push($bills, $tmp);

    $tmp["StartDate"] = "2015-08-03";
    $tmp["EndDate"] = "2015-08-09";
    $tmp["Amount"] = "$19.19";
    $tmp["Url"] = 'bills/url/asdfwebao2124baadf231/bill_08_03.pdf';
    array_push($bills, $tmp);

    $response["bills"] = $bills;

    echoResponse(200, $response);
});

/**
 * Return information relevant to the current moment
 */
$app->get('/now/:sessionId', function($sessionId) use ($app){
    $response = array();

    $response["Spent"] = rand(100,300)/10;
    $response["PriceNow"] = rand(1, 10);
    $response["DaysLeft"] = rand(1, 6);
    $response["AsOf"] = date("Y-m-d");

    echoResponse(200, $response);
});

/**
 * ------------------------------------------------
 * Flick Api ends here
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