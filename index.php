<?php 

 
function writeMsg() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       validateRecaptcha();
    } else {
        $myObj = new stdClass();
        $myObj-> message = "Not Post Request";
        $myObj-> sucess = false;
        http_response_code(404);
        echo json_encode($myObj);
        exit();
    }
  }
  
writeMsg(); 

function validateRecaptcha() {
    $secretKey = "6LfmNKMZAAAAAM50bkrw3wtl_2C6wZJI987Tso3Z";
    $data = $_POST;
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
        $responseData = json_decode($verifyResponse);
        $replyObject = new stdClass();
        if($responseData->success) {
           
            sendMail();
           exit();
         } else {
            $replyObject-> message = "Robot Verification Falied :(";
            $replyObject-> sucess = false;
            http_response_code(401);
            echo json_encode($replyObject);
            exit();
        }
}

function sendMail() {

         $to   = "heer.jtdev@gmail.com";
		//$to      = "dipu961988@gmail.com";
		$subject = "Jyoti Technosoft Contact mail from " . $_POST['name'] . ", e-mail: " .$_POST['email']. "";
		$message = $_POST['message'];

		$headers = 'From: poojan.jtdev@gmail.com' . "\r\n" .
			'Reply-To: poojan.jtdev@gmail.com,=' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();

		$success = mail($to, $subject, $message, $headers);

		//Sending email to user
		$to      = $_POST['email'];
        $subject = "Thankyou for contact Javee Infotech";
        $message = "Hello " . $_POST['name'] . "\n\n Thank you for contact Javee Infotech. We will contact you very soon. \n\n Thanks,\n Javee Team" ;

        $headers = 'From: ' ."info@jyotitechnosoft.com". "\r\n" .
        			'Reply-To: '."info@gmail.com" . "\r\n" ;

        $success = mail($to, $subject, $message, $headers);
	
		if($success){
            $replyObject-> message = "Thank you for connecting Us will get back to you";
            $replyObject-> sucess = true;
            http_response_code(201);
            echo json_encode($replyObject);
		}else{
            $replyObject-> message = "Error while sending mail";
            $replyObject-> sucess = false;
            http_response_code(400);
            echo json_encode($replyObject);
		}
    }

?>
