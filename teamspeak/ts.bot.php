<?php
require_once('init.php');
$ts = new Teamspeak($tspw);
$exempt = [];
//Get connected clients
$clients = $ts->clients();
// echo '<pre>';
// print_r($clients);
// echo '</pre>';

foreach($clients as $client) {
  // echo $client['client_nickname'];
  //registered with database
  $check = $conn->prepare("SELECT c.*, u.*, a.fname as alias_fname, a.lname as alias_lname FROM clients c
      LEFT JOIN users u ON u.cid = c.cid
      LEFT JOIN aliases a on a.cid = c.cid
     WHERE c.uid = :uid
     LIMIT 1");
  $check->bindParam(':uid', $client['client_unique_identifier']);
  $check->execute();

  $logPut = $conn->prepare("INSERT INTO log (uid, category, date_log) VALUES (:uid, :category, NOW())");

  if($check->rowCount() == 0) { // for those without accounts registered
    $logGet = $conn->prepare("SELECT * from log where uid = :uid AND category = 10
      ORDER BY date_log ASC
      LIMIT 1
    ");
    $logGet->bindParam(':uid', $client['client_unique_identifier']);
    $logGet->execute();


    if($logGet->rowCount() == 0) {
      $ts->clientMessage($client['clid'], '[color=red]You have 30 minutes to register[/color]');
      $ts->clientMessage($client['clid'], '[color=red]your TS client with the VATeir website.[/color]');
      $ts->clientMessage($client['clid'], '[color=red]If your client has not been registered[/color]');
      $ts->clientMessage($client['clid'], '[color=red]by this time, you will be removed[/color]');
      $ts->clientMessage($client['clid'], '[color=red]from the server.[/color]');
      $ts->clientMessage($client['clid'], '[color=red]Go to the [url=http://vateir.org/teamspeak/]Teamspeak Portal[/url] to register your client.[/color]');
      $cat = '10'; //warning about no account
      $logPut->bindParam(':uid', $client['client_unique_identifier']);
      $logPut->bindParam(':category', $cat);
      $logPut->execute();
    } else {
      $logWarn = $logGet->fetchAll(PDO::FETCH_ASSOC)[0];
      if(time() > strtotime($logWarn['date_log'] . '+ 30 minutes')) { //if log is > 30 minutes old
        $ts->clientMessage($client['clid'], '[color=red]You were given 30 minutes to register[/color]');
        $ts->clientMessage($client['clid'], '[color=red]your TS client with the VATeir website[/color]');
        $ts->clientMessage($client['clid'], '[color=red]on ' . date(" j M Y H:i:s", strtotime($logWarn['date_log'])) . ' IST. As you have not done so,[/color]');
        $ts->clientMessage($client['clid'], '[color=red] you are being removed from the server.[/color]');
        $ts->clientMessage($client['clid'], '[color=red]Go to the [url=http://vateir.org/teamspeak/]Teamspeak Portal[/url] to register your client.[/color]');
        $cat = '20'; //kick no account
        $logPut->bindParam(':uid', $client['client_unique_identifier']);
        $logPut->bindParam(':category', $cat);
        $logPut->execute();
        $ts->kick($client['clid'], 'TS client not registered.');
      }
    }

  } else { //for those with accounts registered in the database we're going to perform some checks
    $dbres = $check->fetchAll(PDO::FETCH_ASSOC)[0];
    // make CIDs in array above exempt from checks
      if(in_array($dbres['cid'], $exempt) == false) {
        ///Ban them if they have 3 kicks in the last 24 hours
        //if they were banned don't check this, even if their ban was removed because they'll still get kicked by having too many kicks logged.
        $banNo = $conn->prepare("SELECT count(l.id) as number, (SELECT b.ban_id FROM bans b WHERE
        UNIX_TIMESTAMP(b.time_banned)+b.duration >= UNIX_TIMESTAMP(NOW())
        AND b.cid = c.cid LIMIT 1) as banned
        FROM log l
          LEFT JOIN clients c ON c.uid  = l.uid
          LEFT JOIN bans b ON b.cid = c.cid
          WHERE c.cid = :cid
            AND l.date_log between now() - interval 1 hour AND now()
            AND l.category between 20 and 29
          ");
          $banNo->bindParam(':cid', $dbres['cid']);
          $banNo->execute();

          if($banNo->rowCount()!= 0) {
            $noLogs = $banNo->fetchAll(PDO::FETCH_ASSOC)[0];
            if($noLogs['banned'] == null) {



              if($noLogs['number'] >= 3) {
                  $duration = 86400;
                  $reason = "Kicked three times in 24 hours.";
                  //get all their client uids
                  $allclients = $conn->prepare("SELECT * FROM clients WHERE cid = :cid");
                  $allclients->bindParam(':cid', $dbres['cid']);
                  $allclients->execute();
                  $uids = $allclients->fetchAll(PDO::FETCH_ASSOC);
                  //bans - prepare query
                  $ban = $conn->prepare("INSERT INTO bans (cid, uid, ban_id, time_banned, duration, reason) VALUES (:cid, :uid, :ban_id, NOW(), :duration, :reason)");
                  $ban->bindParam(':cid', $dbres['cid']);
                  $ban->bindParam(':duration', $duration);
                  $ban->bindParam(':reason', $reason);
                  echo 'ban<br>';
                  foreach($uids as $uid) {
                    $banID = $ts->ban($uid['uid'], $duration, $reason); //ban their clients from teamspeak
                    if($banID != false) {
                      $ban->bindParam(':uid', $uid['uid']);
                      $ban->bindParam(':ban_id', $banID);
                      $ban->execute();//record in bans table
                    }
                  }
                continue;
              }
            }
          }

          //move to away channel if they are idle for more than 30 minutes
        $idleTime = $client['client_idle_time']/1000;
        if($idleTime > 1800) {
          $ts->moveAway($client['clid']);
        }

        //Name check
        if(($client['client_nickname'] != $dbres['first_name'] . ' ' . $dbres['last_name']) && ($client['client_nickname'] != $dbres['alias_fname'] . ' ' . $dbres['alias_lname'])) { //checking name to make sure name is same as cert; cross checking alias
          $cat = '21'; //Kick name different
          $logPut->bindParam(':uid', $client['client_unique_identifier']);
          $logPut->bindParam(':category', $cat);
          $logPut->execute();
          $ts->clientMessage($client['clid'], '[color=red]We require that all clients connect to the[/color]');
          $ts->clientMessage($client['clid'], '[color=red]VATeir teamspeak using either their CERT[/color]');
          $ts->clientMessage($client['clid'], '[color=red]name or an approved alias.[/color]');
          $ts->clientMessage($client['clid'], '[color=red]Go to the [url=http://vateir.org/teamspeak/]Teamspeak Portal[/url] on the website to register an alias.[/color]');
          $ts->kick($client['clid'], 'TS name does not match CERT name or alias.');
          continue;
        }

        if($dbres['rating'] == 0) {
          //checking name to make sure they are not suspended
          $cat = '22'; //Kick cert suspended
          $logPut->bindParam(':uid', $client['client_unique_identifier']);
          $logPut->bindParam(':category', $cat);
          $logPut->execute();
          $ts->kick($client['clid'], 'Your VATSIM CERT is suspended.');
          continue;
        }
        if($dbres['rating'] == -1) {
          //checking to make sure they are not inactive
          $cat = '23'; //Kick cert inactive
          $logPut->bindParam(':uid', $client['client_unique_identifier']);
          $logPut->bindParam(':category', $cat);
          $logPut->execute();
          $ts->kick($client['clid'], 'ina', $dbres['cid'], 'Your VATSIM CERT is inactive.');
          continue;
        }
    }
    //banned from vatsim
  }
}
//house keeping
$keyGet = $conn->prepare("SELECT * from priv_keys WHERE registered < now() - interval 1 day");
$keyGet->execute();
if($keyGet->rowCount()) {
  $keys = $keyGet->fetchAll(PDO::FETCH_ASSOC);
  $delKey = $conn->prepare("DELETE FROM priv_keys WHERE token = :token AND cid = :cid LIMIT 1");
  foreach($keys as $key) {
    $ts->deletePrivilegeKey($key['token']);
    $delKey->bindParam(':token', $key['token']);
    $delKey->bindParam(':cid', $key['cid']);
    $delKey->execute();
  }
}
