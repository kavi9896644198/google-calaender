<?php

class GoogleCalendarApi
{
	public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) {	
		$url = 'https://accounts.google.com/o/oauth2/token';			
		
		$curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code';
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_POST, 1);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);	
		$data = json_decode(curl_exec($ch), true);
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
		if($http_code != 200) 
			throw new Exception('Error : Failed to receieve access token');
			
		return $data;
	}

	public function GetUserCalendarTimezone($access_token) {
		$url_settings = 'https://www.googleapis.com/calendar/v3/users/me/settings/timezone';
		
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url_settings);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);	
		$data = json_decode(curl_exec($ch), true); //echo '<pre>';print_r($data);echo '</pre>';
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
		if($http_code != 200) 
			throw new Exception('Error : Failed to get timezone');

		return $data['value'];
	}

	public function GetCalendarsList($access_token) {
		$url_parameters = array();

		$url_parameters['fields'] = 'items(id,summary,timeZone)';
		$url_parameters['minAccessRole'] = 'owner';

		$url_calendars = 'https://www.googleapis.com/calendar/v3/users/me/calendarList?'. http_build_query($url_parameters);
		
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url_calendars);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);	
		$data = json_decode(curl_exec($ch), true); //echo '<pre>';print_r($data);echo '</pre>';
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
		if($http_code != 200) 
			throw new Exception('Error : Failed to get calendars list');

		return $data['items'];
	}

	// need to add repeat argument here
	public function CreateCalendarEvent($book_slot,$calendar_id,$access_token,$get_time_zone) {
		$url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events';
		$u_data = get_userdata($book_slot['current_user_id']);
		//$curlPost = array('summary' => $summary); // event title
			 $curlPost = array(
                  'summary' => 'Booked Meeting With '.$u_data->display_name, // Replace 'Your Event Summary' with the title of your event
                  'description' => $book_slot['slot_time'],
              );
              // If it's not an all-day event, set the start and end dateTimes
              $curlPost['start'] = array(
                  'dateTime' => ''.$book_slot['slot_date'].'T00:00:00', 
                  'timeZone' => $get_time_zone, // Replace 'America/New_York' with the timeZone of your event
              );
              $curlPost['end'] = array(
                  'dateTime' => ''.$book_slot['slot_date'].'T00:00:00', 
                  'timeZone' => $get_time_zone, // Replace 'America/New_York' with the timeZone of your event
              );

		// if event repeats or not
		if ($recurrence == 1) {
			$curlPost['recurrence'] = array("RRULE:FREQ=WEEKLY;UNTIL=" . str_replace('-', '', $recurrence_end) . ";" );
		}
		
		$ch = curl_init(); // Initializes a new session and return a cURL handle	
		curl_setopt($ch, CURLOPT_URL, $url_events);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return the transfer as a string of the return value of curl_exec() instead of outputting it directly.	
		curl_setopt($ch, CURLOPT_POST, 1); // http post	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // stop cURL from verifying the peer's certificate
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));	
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));	
		$data = json_decode(curl_exec($ch), true);
		//echo "<pre>";print_r($data);die;
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);		
		if($http_code != 200) 
			throw new Exception('Error : Failed to create event');

		return $data;
	}

	public function UpdateCalendarEvent($event_id, $calendar_id, $summary, $all_day, $event_time, $event_timezone, $access_token) {
		$url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events/' . $event_id;

		$curlPost = array('summary' => $summary);
		if($all_day == 1) {
			$curlPost['start'] = array('date' => $event_time['event_date']);
			$curlPost['end'] = array('date' => $event_time['event_date']);
		}
		else {
			$curlPost['start'] = array('dateTime' => $event_time['start_time'], 'timeZone' => $event_timezone);
			$curlPost['end'] = array('dateTime' => $event_time['end_time'], 'timeZone' => $event_timezone);
		}
		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url_events);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));	
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));	
		$data = json_decode(curl_exec($ch), true);
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);		
		if($http_code != 200) 
			throw new Exception('Error : Failed to update event');
	}

	public function DeleteCalendarEvent($event_id, $calendar_id, $access_token) {
		$url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . $calendar_id . '/events/' . $event_id;
		// $url_events = 'https://www.googleapis.com/calendar/v3/calendars/j73873263@gmail.com/events/l56q7db4ktddp2a7nj1f9f2vuo';

		$ch = curl_init();		
		curl_setopt($ch, CURLOPT_URL, $url_events);		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));		
		$data = json_decode(curl_exec($ch), true);
		$http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		if($http_code != 204) 
			throw new Exception('Error : Failed to delete event');
	}

	public function GetEventsList($access_token, $calendar_id) {
    $url_parameters = array();
    $url_parameters['maxResults'] = 10;
    $url_events = 'https://www.googleapis.com/calendar/v3/calendars/' . urlencode($calendar_id) . '/events?' . http_build_query($url_parameters);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url_events);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $data = json_decode(curl_exec($ch), true);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($http_code != 200) {
        throw new Exception('Error: Failed to get events list');
    }
//echo "<pre>";print_r($data);
    return $data['items'];
}

}

?>