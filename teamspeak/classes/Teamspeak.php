<?php
class Teamspeak {
  private $ts = null;
  public $clients = array();


  public function __construct($tspw) {
    $this->ts = new ts3admin('127.0.0.1', '10011');
    if($this->ts->getElement('success', $this->ts->connect())) {
      $login = $this->ts->login('serveradmin', $tspw);
      if($login['success'] == 1) {
        $this->ts->selectServer(9987);
        $this->ts->clientUpdate(
          array(
            'client_nickname' => 'VATeir\sTS\s(BOT)'
          )
        );
      } else {
        throw new Exception ("Cannot connect to the server");
      }

    }
  }

  public function channelList() {
    return $this->ts->channelList();
  }

  public function clients() {
    $clients = $this->ts->clientList("-uid -times");
    if($clients['success'] == 1) {
      foreach($clients['data'] as $client) {
        if($client['client_type'] == 0) { //if they are a normal client, IE not a server query client
          $this->clients[] = $client;
        }
      }
      return $this->clients;
    } else {
      throw new Exception("Could not get client list");
    }
  }

  public function moveAway($clid) {
    return $this->ts->clientMove($clid, 19, null);
  }

  public function kick($client_id, $message) {
    return $this->ts->clientkick($client_id, 'server', $message);
  }

  public function makeKey($vatsim_id) {
    $key = $this->ts->privilegekeyAdd(0, 7, null, null, ['CID' => $vatsim_id]);
    if($key['success'] == 1) {
      return $key['data']['token'];
    } else {
      throw new Exception("Error generating privilege key");
    }
  }
  public function deleteKey($token) {
    $delete = $this->ts->privilegekeyDelete($token);
    if($delete == true) {
      return true;
    }
    throw new Exception("Error deleting privilege key");
  }

  public function listKeys(){
    $list = $this->ts->privilegekeyList();
    if($list['success'] == 1) {
      return $list['data'];
    }
    throw new Exception("Error getting privilege keys");
  }

  public function clientMessage($client_id, $message) {
    $send = $this->ts->sendMessage(1, $client_id, $message);
    if($send==true) {
      return true;
    }
    return false;
  }
  public function serverMessage($message) {
    $send = $this->ts->sendMessage(3, 1, $message);
    if($send==true) {
      return true;
    }
    return false;
  }

  public function ban($clientID, $duration, $reason) {
    $banID = $this->ts->banAddByUid($clientID, $duration, $reason);
    if($banID['success'] == 1) {
      return $banID['data']['banid'];
    }
    return false;
  }

  public function unban($banid) {
    return $this->ts->banDelete($banid);
  }

  public function getPrivilegeKeys() {
    $keys = $this->ts->privilegekeyList();
    if($keys['data'] != null) {
      return $keys['data'];
    }
    return false;
  }
  public function deletePrivilegeKey($key) {
    return $this->ts->privilegekeyDelete($key);
  }
}
