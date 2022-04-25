<?php

namespace Slime;

class x {

	// return user's ip address
	public static function client_ip(){
	  if (!empty($_SERVER['HTTP_CLIENT_IP'])){
	    $ip = $_SERVER['HTTP_CLIENT_IP'];
	  }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
	    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	  }else{
	    $ip = $_SERVER['REMOTE_ADDR'];
	  }
	  return $ip;
	}

	// send an email (with mailgun api, if available, otherwise use php mail)
	public static function email_send($input){
		$message_html = false;
		if ($input['template'] || $input['html']){
      // fixit make this work
			// if ($input['template']){
			// 	$rendered_template = $GLOBALS['engine']->render($input['template'], $input['data']);
			// 	if ($input['layout']){
			// 		$layout = explode('[[outlet]]', $GLOBALS['engine']->render('_layouts/' . $input['layout'], $input['data']));
			// 		$message_html = $layout[0];
			// 		if (count($layout) > 1){
			// 			$message_html .= $rendered_template;
			// 		}
			// 		$message_html .= $layout[1];
			// 	}else{
			// 		$message_html = $rendered_template;
			// 	}
			// }
			if ($input['html']){
				$message_html = $input['message'];					
			}	
			$message_text = strip_tags(x::br2nl($message_html));	
		}

		if ($GLOBALS['settings']['mailgun']['api_key']){
			$message = array(
				'from' => $input['from'],
				'to' => $input['to'],
				'subject' => $input['subject']
			);
			if ($input['cc']){
				$message['cc'] = $input['cc'];
			}	
			if ($input['reply_to']){
				$message['h:Reply-To'] = $input['reply_to'];
			}
			if ($input['bcc']){
				$message['bcc'] = $input['bcc'];
			}	
			if ($message_html){
				$message['html'] = $message_html;
				$message['text'] = $message_text;
			}else{
				$message['text'] = $input['message'];
			}
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.mailgun.net/v3/".$GLOBALS['settings']['mailgun']['domain']."/messages");
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "api:".$GLOBALS['settings']['mailgun']['api_key']."");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_POST, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
			$result = curl_exec($ch);
			curl_close($ch);
			return $result;
		}
		else{
			if ($message_html){
				$boundary = uniqid('st');
				$headers .= "MIME-Version: 1.0\r\n" . "Content-type: multipart/alternative; boundary=" . $boundary . "; charset=utf-8\r\n";
				$message = $message_text;
				$message .= "\r\n\r\n--" . $boundary . "\r\n";
				$message .= "Content-type: text/plain;charset=utf-8\r\n\r\n" . $message_text;
				$message .= "\r\n\r\n--" . $boundary . "\r\n";
				$message .= "Content-type: text/html;charset=utf-8\r\n\r\n" . $message_html;
				$message .= "\r\n\r\n--" . $boundary . "--";

			}else{
				$message = $input['message'];
			}
			if ($input['from']){
				$headers 	.= "From: " . $input['from'] . "\r\n";	
			}		
			if ($input['cc']){
				$headers 	.= "Cc: " . $input['cc'] . "\r\n";	
			}		
			if ($input['bcc']){
				$headers 	.= "Bcc: " . $input['bcc'] . "\r\n";	
			}		
			if ($input['reply_to']){
				$headers 	.= "Reply-To: " . $input['reply_to'] . "\r\n";	
			}
			if ($input['preview']){
				echo $message;
			}
			if ($input['debug']){
				echo '<pre class="bg-black white">';
				print_r($input);
				echo "<hr />";
				echo $headers;
				echo "<hr />";
				echo $message;
				echo '</pre>';
			}
			if ($input['to'] && !$input['preview']&& !$input['debug']){
				mail($input['to'], $input['subject'], $message, $headers);
				return true;
			}else{
				return false;
			}
		}
	}

	// return string as url-safe slug
	public static function url_slug($string){
		// unicode-compatible chars only (i think)
		return strtolower(preg_replace('#[^\pL\pN./-]+#', "-", $string)); 
	}

	// return just the domain of a given url (remove "http://","https://","www.")
	public static function url_strip($input){
		if (!preg_match('#^http(s)?://#', $input)) {
	    $input = 'http://' . $input;
		}	
		$url_parts = parse_url($input);
		$domain = preg_replace('/^www\./', '', $url_parts['host']);
		return $domain;
	}

	// add http to url, if needed
	public static function url_validate($url){
		if (!preg_match('#^http(s)?://#', $url)) {
	    $o = 'http://' . $url;
		}
		else{
			$o = $url;
		}
		return $o;
	}

	// replace "<br />" with "\n" (the opposite of nl2br)
	public static function br2nl($string) {
	  return preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string);
	} 

	// encode an array (of strings) for storage in mysql (turn it into a string)
	public static function array_encode($array){
		$o = "";
		foreach ($array as $ar){
			$o .= "|" . $ar;
		}
		return $o;
	}	

	// decode an encoded string and return an array
	public static function array_decode($string){
		return explode("|", $string);
	}	

}

?>