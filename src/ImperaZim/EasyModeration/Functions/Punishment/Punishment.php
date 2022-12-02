<?php

namespace ImperaZim\EasyModeration\Functions\Punishment;

use ImperaZim\EasyModeration\Functions\DataBase\SQLite3;

class Punishment {
 
 public $KICK = "KICK_DATA";
 public $MUTE = "MUTE_DATA";
 public $PUNISH = "PUNISH_DATA";
 
 public function get() : Punishment { 
  return $this;
 }
 
 public function punish(String $type, String $player, String $reason, Int $time, String $date, String $author) : void {
  $data = SQLite3::table();
  if ($type == $this->PUNISH) {
   $perfil = $data->prepare("INSERT INTO banned(name, time, reason, date, author) VALUES (:name, :time, :reason, :date, :author)");
   $perfil->bindValue(":name", $player); 
   $perfil->bindValue(":time", $time);
   $perfil->bindValue(":date", $date);
   $perfil->bindValue(":reason", $reason);
   $perfil->bindValue(":author", $author);
   if (!$this->isBanned($player)) { $perfil->execute(); }
  }
  if ($type == $this->MUTE) {
   $perfil = $data->prepare("INSERT INTO muted(name, time, reason, date, author) VALUES (:name, :time, :reason, :date, :author)");
   $perfil->bindValue(":name", $player); 
   $perfil->bindValue(":time", $time);
   $perfil->bindValue(":date", $date);
   $perfil->bindValue(":reason", $reason);
   $perfil->bindValue(":author", $author);
   if (!$this->isMuted($player)) { $perfil->execute(); }
  }
 }
 
 public function isMuted(String $player) : bool {
  $query = SQLite3::table()->query("SELECT * FROM muted WHERE name='" . $player . "';");
  $data = $query->fetchArray(SQLITE3_ASSOC);
  return isset($data['name']);
 }
 
 public function isBanned(String $player) : bool {
  $query = SQLite3::table()->query("SELECT * FROM banned WHERE name='" . $player . "';");
  $data = $query->fetchArray(SQLITE3_ASSOC);
  return isset($data['name']);
 }
 
}