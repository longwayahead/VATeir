<?php
class OAD {

  private function getter($link) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $link); //Set the URL here
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLINFO_HEADER_OUT, true);


    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    	'accept: application/json'
    ));


    $get = curl_exec($curl); //Get the data
    curl_close($curl);
    return $get;
  }
  private function getData($url) {
    return json_decode($this->getter($url));
  }

  public function metar($icao) {

    return $this->getData("https://api.openaviationdata.com/v4/Airport/$icao/Metar");
  }
   public function taf($icao) {
     return $this->getData("https://api.openaviationdata.com/v4/Airport/$icao/Taf");
   }

  public function icaoInfo($icao) {
    return $this->getData("https://api.openaviationdata.com/v4/Airport/$icao/Data");
  }

  public function nats(){
      return $this->getData("https://api.openaviationdata.com/v4/Routing/Oceanic/NATS");
  }

}
