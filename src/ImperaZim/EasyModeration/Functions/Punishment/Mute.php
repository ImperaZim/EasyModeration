<?php

namespace ImperaZim\EasyModeration\Functions\Punishment;

use pocketmine\Server; 
use pocketmine\player\Player; 
use ImperaZim\EasyModeration\Loader; 
use ImperaZim\EasyModeration\Functions\DataBase\SQLite3; 
use ImperaZim\EasyModeration\Functions\Punishment\Punishment;

class Mute extends Punishment {
 
 public $content = [];
 
 public function __construct(String $player) {
  $this->content["type"] = $this->get()->MUTE;
  $this->content["player"] = (string) $player;
 }
 
 public function getTime() : Int {
  $query = SQLite3::table()->query("SELECT * FROM muted WHERE name='" . $this->content["player"] . "';");
  $data = $query->fetchArray(SQLITE3_ASSOC);
  return $data['time'] ?? 0; 
 }
 
 public function getDate() : String {
  $query = SQLite3::table()->query("SELECT * FROM muted WHERE name='" . $this->content["player"] . "';");
  $data = $query->fetchArray(SQLITE3_ASSOC);
  return $data['date'] ?? ""; 
 }
 
 public function getAuthor() : String {
  $query = SQLite3::table()->query("SELECT * FROM muted WHERE name='" . $this->content["player"] . "';");
  $data = $query->fetchArray(SQLITE3_ASSOC);
  return $data['author'] ?? "anonymous"; 
 }
 
 public function getReason() : String {
  $query = SQLite3::table()->query("SELECT * FROM muted WHERE name='" . $this->content["player"] . "';");
  $data = $query->fetchArray(SQLITE3_ASSOC);
  return $data['reason'] ?? ""; 
 }
 
 public function setTime($time = null) : Mute {
  if (!is_numeric($time)) {
   $time = 99999999 * 99999;
  }
  $this->content["time"] = $time;
  return $this;
 }
 
 public function setDate(String $date = null) : Mute {
  $date = $date == null ? "undefined" : $date;
  $this->content["date"] = $date;
  return $this;
 }
 
 public function setAuthor(String $author = null) : Mute {
  $author = $author == null ? "anonymous" : $author;
  $this->content["author"] = $author;
  return $this;
 }
 
 public function setReason(String $reason = null) : Mute {
  $reason = $reason == null ? "undefined" : $reason;
  $this->content["reason"] = $reason;
  return $this;
 }
 
 public function execute() : bool {
  $type = $this->content["type"];
  $time = $this->content["time"];
  $date = $this->content["date"];
  $player = $this->content["player"];
  $reason = $this->content["reason"];
  $author = $this->content["author"];  
  
  $process = 0;
  if (!isset($type)) $process = 1;
  if (!isset($time)) $process = 1;
  if (!isset($date)) $process = 1;
  if (!isset($player)) $process = 1;
  if (!isset($reason)) $process = 1;
  if (!isset($author)) $process = 1;
  
  if ($process <= 0) {
   $this->punish($type, $player, $reason, $time, $date, $author);
  } 
  
  $player = Server::getInstance()->getPlayerExact($player); 
  
  if ($player instanceof Player) {
   $time = Loader::getFullTime($time)["fulltime"];
   $kick_reason = Loader::getProcessedTags(["{mute.duration}", "{mute.reason}", "{mute.date}", "{mute.author}"], [$time, $reason, $date, $author], Loader::get()->getConfig()->getNested("messages.muted.notice")); 
   $player->sendMessage($kick_reason);
  }
  
  return $process <= 0; 
 }
 
 public function absolve() : bool {
  $process = 0;
  if (!isset($this->content["player"])) $process = 1;
  if ($process == 0) {
   if ($this->isMuted($this->content["player"])) {
    SQLite3::table()->query("DELETE FROM muted WHERE name='" . $this->content["player"] . "';");
   }
  }
  return $process == 0;
 }
 
}  
