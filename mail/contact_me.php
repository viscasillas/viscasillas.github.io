<?php

function NEW_LEAD($database,$json){
    // set couchdb configuration
  $CouchDBHost = '192.34.59.51';
  $CouchDBPort = '5984';
    //first get a UUID
          // Get cURL resource
          $ch = curl_init();

          // Set url
          curl_setopt($ch, CURLOPT_URL, 'http://'.$CouchDBHost.':'.$CouchDBPort.'/_uuids');

          // Set method
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

          // Set options
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);



          // Send the request & save response to $resp
          $resp = curl_exec($ch);

          if(!$resp) {
            die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
          } else {
            echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
            echo "\nResponse HTTP Body : " . $resp;
          }
          $uuids_to_parse = json_decode($resp, true);
          $uuid = $uuids_to_parse['uuids'];
          // Close request to clear up some resources
          curl_close($ch);
    // now we can use the UUID to create a document
          // Get cURL resource
          $ch = curl_init();

          $json_needs = json_decode($json, true);
          $json_id = $json_needs['_id'];

          // Set url
          curl_setopt($ch, CURLOPT_URL, 'http://'.$CouchDBHost.':'.$CouchDBPort.'/'.$database.'/'.implode("",$uuid));

          // Set method
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

          // Set options
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

          $body = json_encode($json_needs);

          // Set body
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

          // Send the request & save response to $resp
          $resp = curl_exec($ch);

          if(!$resp) {
            die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
          } else {
            echo "Response HTTP Status Code : " . curl_getinfo($ch, CURLINFO_HTTP_CODE);
            echo "\nResponse HTTP Body : " . $resp;
          }

          // Close request to clear up some resources
          curl_close($ch);
  }

// Check for empty fields
if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['phone']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "No arguments Provided!";
	return false;
   }

$name = $_POST['name'];
$email_address = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];

// lets clean up the contents of textarea/$_POST['message'] to remove line breaks and spaces
$output = str_replace(array("\r\n", "\r"), "\n", $message);
$lines = explode("\n", $output);
$new_lines = array();
foreach ($lines as $i => $line) {
    if(!empty($line))
        $new_lines[] = trim($line);
}
$message_clean = implode($new_lines);

// add lead to fortag.crativo.com
NEW_LEAD('leads','{
   "accountTitle": "",
   "fullName": "'.$name.'",
   "emailAddress": "'.$email_address.'",
   "phoneNumber": "'.$phone.'",
   "physicalAddress": "",
   "websiteURL": "",
   "notes": "'.$message_clean.'",
   "referralSource": "Crativo.com",
   "accountType": "",
   "industry": null
}');
//
// // Create the email and send the message to me so i can reply
// $to = 'viscasillas@me.com'; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
// $email_subject = "Crativo - Website Contact Form:  $name";
// $email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nPhone: $phone\n\nMessage:\n$message";
// $headers = "From: noreply@yourdomain.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
// $headers .= "Reply-To: $email_address";
// mail($to,$email_subject,$email_body,$headers);

return true;
?>
