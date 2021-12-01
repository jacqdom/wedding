<?php
 echo "beginning";
  $name = $_POST['name'];
  $visitor_email = $_POST['email'];
  $message = $_POST['message'];

	 $email_from = 'rmota29619@gmail.com';

 	$email_subject = "New Form submission";

 	$email_body = "You have received a new message from the user $name.\n";
    
  $to = "rmota29619@gmail.com";

  $headers = "From: $email_from \r\n";

  $headers = "Reply-To: $visitor_email \r\n";

//mail($to,$email_subject,$email_body,$headers);
 echo "mailed to";
 echo $to;

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
    CURLOPT_POSTFIELDS =>"{\"recipients\":[{\"email\":\"rmota29619@gmail.com\"}],\"title\":\"Title\",\"html\":\"Body\"}",
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

if(IsInjected($visitor_email))
{
    echo "Bad email value!";
    exit;
}

?>
