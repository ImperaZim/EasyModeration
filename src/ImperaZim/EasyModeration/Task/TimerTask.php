<?php

namespace ImperaZim\EasyModeration\Task;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use ImperaZim\EasyModeration\Loader;
use ImperaZim\EasyModeration\Functions\Punishment\Mute; 
use ImperaZim\EasyModeration\Functions\DataBase\SQLite3; 
use ImperaZim\EasyModeration\Functions\Punishment\Punishment; 

class TimerTask extends Task {
 
 public static function register($loader, TimerTask $task) : void {
  $loader->getScheduler()->scheduleRepeatingTask($task, 10);
 }
  
 public function unregister() : void {
  $this->shutdown();
 }

 public function onRun() : void {
  $plugin = Loader::get();
  $server = Server::getInstance();
  
  $this->refresh();
  
  foreach ($server->getOnlinePlayers() as $player) {
   $punish = new Punishment(); 
   if ($punish->isMuted($player->getName())) {
    $mute = new Mute($player->getName());
    $time = $mute->getTime();
    if ($time <= 0) {  
     if ($mute->absolve()) {
      $message = explode(":", Loader::get()->getConfig()->getNested("messages.muted.absolve.notice"));
      if ($message[0] == "true") { 
       $notice = Loader::getProcessedTags([], [], $message[1]);
       $player->sendMessage($notice);

      }
     }
    } 
   }
  }
 }

 public function refresh() : void {
  SQLite3::table()->query("UPDATE muted SET time=time-1 WHERE time > 0");
  SQLite3::table()->query("UPDATE banned SET time=time-1 WHERE time > 0");
 }
 
}
