<?php
class TgApi {
	private $baseURL = 'https://api.telegram.org/bot';
	protected $token;
	public function __construct($token) {
		$this->token = $token;
		if (is_null($this->token)) {
			throw new \Exception('Required "token" key not supplied');
		}
	}
	public function getMe() {
		$message = $this->sendRequest('getMe');
		return $message;
	}
// Send text messages.
	public function sendMessage($chat_id, $text, $reply_keyboard = null, $inline_keyboard = null) {
		$params = compact('chat_id', 'text');
		if ($reply_keyboard) {
			$params['reply_markup'] = $this->replyKeyboard($reply_keyboard);
		}
		if ($inline_keyboard) {
			$params['reply_markup'] =  $this->inlineKeyboard($inline_keyboard);
		}
		$message = $this->sendRequest('sendMessage', $params);
		return $message ? json_decode($message, true)['result']['message_id'] : false;
	}
	
// Send Location.
	public function sendLocation($chat_id, $latitude, $longitude, $reply_keyboard = null, $inline_keyboard = null) {
		$params = compact('chat_id', 'latitude', 'longitude');
		if ($reply_keyboard) {
			$params['reply_markup'] = $this->replyKeyboard($reply_keyboard);
		}
		if ($inline_keyboard) {
			$params['reply_markup'] =  $this->inlineKeyboard($inline_keyboard);
		}
		$message = $this->sendRequest('sendLocation', $params);
		return $message ? json_decode($message, true)['result']['message_id'] : false;
	}
//Send Contact.
	public function sendContact($chat_id, $phone_number, $first_name, $last_name = null, $reply_keyboard = null, $inline_keyboard = null) {
		$params = compact('chat_id', 'phone_number', 'first_name', 'last_name');
		if ($reply_keyboard) {
			$params['reply_markup'] = $this->replyKeyboard($reply_keyboard);
		}
		if ($inline_keyboard) {
			$params['reply_markup'] =  $this->inlineKeyboard($inline_keyboard);
		}
		$message = $this->sendRequest('sendContact', $params);
		return $message ? json_decode($message, true)['result']['message_id'] : false;
	}
//Send Photo.
	public function sendPhoto($chat_id, $photo, $caption = '', $reply_keyboard = null, $inline_keyboard = null, $upload = null) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $photo);
		finfo_close($finfo);
		$photo =  new \CurlFile($photo,$mime,basename($photo));
		$params = compact('chat_id', 'photo', 'caption');
		if ($reply_keyboard) {
			$params['reply_markup'] = $this->replyKeyboard($reply_keyboard);
		}
		if ($inline_keyboard) {
			$params['reply_markup'] =  $this->inlineKeyboard($inline_keyboard);
		}
		$message = $this->sendRequest('sendPhoto', $params, $upload);
		return $message ? json_decode($message, true)['result']['message_id'] : false;
	}
// Send Audio.
	public function sendAudio($chat_id, $audio, $caption = '', $title='', $reply_keyboard = null, $inline_keyboard = null) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $audio);
		finfo_close($finfo);
		$audio =  new \CurlFile($audio,$mime,basename($audio));
		$params = compact('chat_id', 'audio', 'caption', 'title');
		if ($reply_keyboard) {
			$params['reply_markup'] = $this->replyKeyboard($reply_keyboard);
		}
		if ($inline_keyboard) {
			$params['reply_markup'] =  $this->inlineKeyboard($inline_keyboard);
		}
		$message = $this->sendRequest('sendAudio', $params, $upload);
		return $message ? json_decode($message, true)['result']['message_id'] : false;
	}
//Send Video.
	public function sendVideo($chat_id, $video, $caption = '', $reply_keyboard = null, $inline_keyboard = null) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $video);
		finfo_close($finfo);
		$video =  new \CurlFile($video,$mime,basename($video));
		$params = compact('chat_id', 'video', 'caption');
		if ($reply_keyboard) {
			$params['reply_markup'] = $this->replyKeyboard($reply_keyboard);
		}
		if ($inline_keyboard) {
			$params['reply_markup'] =  $this->inlineKeyboard($inline_keyboard);
		}
		$message = $this->sendRequest('sendVideo', $params, $upload);
		return $message ? json_decode($message, true)['result']['message_id'] : false;
	}
//Send File.
	public function sendDocument($chat_id, $document, $caption = '', $reply_keyboard = null, $inline_keyboard = null) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $document);
		finfo_close($finfo);
		$document =  new \CurlFile($document,$mime,basename($document));
		$params = compact('chat_id', 'document', 'caption');
		if ($reply_keyboard) {
			$params['reply_markup'] = $this->replyKeyboard($reply_keyboard);
		}
		if ($inline_keyboard) {
			$params['reply_markup'] =  $this->inlineKeyboard($inline_keyboard);
		}
		$message = $this->sendRequest('sendDocument', $params, $upload);
		return $message ? json_decode($message, true)['result']['message_id'] : false;
	}
//Send Voice.
	public function sendVoice($chat_id, $voice, $caption = '', $reply_keyboard = null, $inline_keyboard = null) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $voice);
		finfo_close($finfo);
		$voice =  new \CurlFile($voice,$mime,basename($voice));
		$params = compact('chat_id', 'voice', 'caption');
		if ($reply_keyboard) {
			$params['reply_markup'] = $this->replyKeyboard($reply_keyboard);
		}
		if ($inline_keyboard) {
			$params['reply_markup'] =  $this->inlineKeyboard($inline_keyboard);
		}
		$message = $this->sendRequest('sendVoice', $params, $upload);
		return $message ? json_decode($message, true)['result']['message_id'] : false;
	}
//Edit Message.
	public function editMessageText($chat_id, $message_id, $text, $inline_keyboard = null) {
		$params = compact('chat_id', 'message_id', 'text', 'inline_keyboard');
		if ($inline_keyboard) {
			if (!is_array($inline_keyboard)) {
				throw new \Exception("Inline keyboard is invalid");
			}
			$params['inline_keyboard'] = json_encode($inline_keyboard);
		}
		return $this->sendRequest('editMessageText', $params);
	}
//Delete Message.
	public function deleteMessage($chat_id, $message_id) {
		$params = compact('chat_id', 'message_id');
		return $this->sendRequest('deleteMessage', $params );
	}
//Reply keyboard.
	public function replyKeyboard($keyboard, $one_time_keyboard = false, $selective = true, $resize_keyboard = true) {
		if (!is_array($keyboard) && $keyboard!='remove') {
			throw new \Exception("keyboard must be array");
		}
		if($keyboard=='remove'){
			$remove_keyboard = true;
			$replyKeyboard = compact('remove_keyboard');
		}else{
			$replyKeyboard = compact('keyboard', 'one_time_keyboard', 'selective', 'resize_keyboard');
		}
		return json_encode($replyKeyboard);
	}
//Inline keyboard.
	public function inlineKeyboard($inline_keyboard) {
		if (!is_array($inline_keyboard)) {
		  throw new \Exception("Inlinkeyboard must be array");
		}
		$inline_keyboard = compact('inline_keyboard');
		return json_encode($inline_keyboard);
	}
//Send Request.
	private function sendRequest($method, $params = null, $upload = null) {
		$this->baseURL = $this->baseURL . $this->token .'/';
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $this->baseURL . $method);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_POST, 1);
		if(!is_null($upload)){
			curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type:multipart/form-data"));
			curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
		}
		if(!is_null($params) && is_null($upload)){
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_result = curl_exec($curl);
		$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);
		if ($httpcode != 200) {
			if ($curl_result) {
				$curl_result = json_decode($curl_result, true);
				echo $curl_result['description'];
			}
			//throw new \Exception('an error was encountered');
		}
		return $curl_result;
	}
}
?>