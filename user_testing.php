<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// Allow cross origin request from Qualtrics/user
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

//connect to DB
require_once 'utils_testing.php';
require_once 'f_connect_testing.php';

// get all of the "posted" and "get" vars
$variables = array('id', 'survey_id', 'piece_rate_schedule', 'bonus_schedule', 'visibility', 'vision', 'mturk_code', 'secret');
foreach ($variables as $var) {
    $$var = $cid->real_escape_string($_REQUEST[$var]);
}

$server_secret = ''; # Fill in your own
if ($secret == $server_secret) {
    // Strip non-alphanumeric characters
    $id = preg_replace("/[^[:alnum:]]/u", '', $id);

    // Generate secure token for image retrieval
    $image_token = random_str(16);

    insertIfNew($cid, $id, $survey_id, $piece_rate_schedule, $bonus_schedule, $visibility, $vision, $image_token, $mturk_code);
    $user = userInfo($cid, $id, $survey_id);
    $survey = surveyInfo($cid, $survey_id);
    unset($survey['id']);

    echo json_encode(array_merge($user, $survey));

    // Use this opportunity to do regular database update
    updateDatabase($cid, $survey_id);
}
