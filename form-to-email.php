<?php
  $name = $_POST['name'];
  $rsvp = $_POST['rsvp'];
  $dinner = $_POST['dinner'];
  $email_from = 'rmota29619@gmail.com';
  $email_subject = "Wedding Reservation";
  $email_body = "You have received an rsvp from $name.\n";
  $to = "rmota29619@gmail.com";

//Injection prevention handler
  function IsInjected($str)
  {
    $injections = array('(\n+)',
           '(\r+)',
           '(\t+)',
           '(%0A+)',
           '(%0D+)',
           '(%08+)',
           '(%09+)'
           );
               
    $inject = join('|', $injections);
    $inject = "/$inject/i";
    
    if(preg_match($inject,$str))
    {
      return true;
    }
    else
    {
      return false;
    }
}

if(IsInjected($name))
{
    echo "Bad name value!";
    exit;
}
//Injection prevention handler

//php mail handler through heroku trustifi
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $_ENV['TRUSTIFI_URL'] . "/api/i/v1/email",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS =>"{\"recipients\":[{\"email\":\"rmota29619@gmail.com\"}],\"title\":\"Title\",\"html\":\"Body" . $email_body . "\"}",
    CURLOPT_HTTPHEADER => array(
        "x-trustifi-key: " . $_ENV['TRUSTIFI_KEY'],
        "x-trustifi-secret: " . $_ENV['TRUSTIFI_SECRET'],
        "content-type: application/json"
    )
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}
//end of heroku handler

?>
