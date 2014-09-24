<?php

Header("Content-Type: application/json");

$accounts = array(
  "user@email" => "password");

$user_to_session = array(
  "user@email" => md5("user@email password"));

$session_to_user = array_flip($user_to_session);

function auth() {
  global $_REQUEST, $accounts, $user_to_session;

  $email = @$_REQUEST['email'];
  $password = @$_REQUEST['password'];

  if(isset($accounts[$email]) && $accounts[$email] == $password)
    return $user_to_session[$email];

  header("Status: 401 Not authorized");

  echo json_encode(
    array(
      "status" => "error", 
      "message" => "invalid email or password"));
  die;
}

function session() {
  global $_REQUEST, $session_to_user;

  $session = @$_REQUEST['session'];

  if(isset($session_to_user[$session]))
    return $session_to_user[$session];

  header("Status: 401 Not authorized");

  echo json_encode(
    array(
      "status" => "error", 
      "message" => "invalid session"));
  die;
}

switch($_SERVER["PATH_INFO"]) {
  case '/auth/login':
    $session = auth();

    echo json_encode(
      array(
        "status" => "ok",
        "session" => $session));
    die;
    break;

  case '/user/items':
    $user = session();

    echo json_encode(
      array(
        "status" => "ok",
        "email" => $user,
        "items" => array(
                  "Android List View",
                  "Adapter implementation",
                  "Simple List View In Android",
                  "Create List View Android",
                  "Android Example",
                  "List View Source Code",
                  "List View Array Adapter",
                  "Android Example List View"
          )));
    die;
    break;

  case '/php/info':
    Header("Content-Type: text/html");
    phpinfo();
    break;

  default:
    header("Status: 404 Not Found");
    echo json_encode(
      array(
        "status" => "error", 
        "message" => "not found"));
    die;
    break;
}
