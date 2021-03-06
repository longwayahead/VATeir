<?php
class Twitch {

  private function getFromTwitch() {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.twitch.tv/kraken/streams/122441256'); //Set the URL here
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);


    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Accept: application/vnd.twitchtv.v5+json',
      'Client-ID: '.Config::get('twitch/apitoken')
    ));


    $get = curl_exec($curl); //Get the data
    curl_close($curl);
    return $get;
  }

  public function johnStream() {
    $details = json_decode($this->getFromTwitch(), false);

    if($details->stream == null) {
      return false;
    } else {
      return $details;
    }
  }

}
