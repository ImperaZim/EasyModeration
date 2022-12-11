<?php

namespace ImperaZim\EasyModeration\Event\ServerEvent;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use ImperaZim\EasyModeration\Loader;
use pocketmine\event\server\CommandEvent as UsageEvent;

class CommandEvent implements Listener { 
 
 public function Event(UsageEvent $event) : void {
  $sender = $event->getSender();
  $command = $event->getCommand();
  foreach (Server::getInstance()->getOnlinePlayers() as $player) {
   $name = $sender instanceof Player ? $sender->getName() : "";
   if ($player->hasPermission("easymoderation.spy.usage")) {
    if (isset(Loader::get()->spy[$player->getName()])) {
     $player->sendMessage(Loader::getProcessedTags(["{spy.sender}", "{spy.command}"], [$name, $command], Loader::get()->getConfig()->getNested("messages.spy.notice")));  
    }
   }
  }
 }
 
} 
