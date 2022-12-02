<?php

namespace ImperaZim\EasyModeration\Event\ServerEvent;

use pocketmine\Server;
use pocketmine\player\player;
use pocketmine\event\Listener;
use ImperaZim\EasyModeration\Loader;
use pocketmine\event\server\CommandEvent as UsageEvent;

class CommandEvent implements Listener { 
 
 public function Event(UsageEvent $event) : void {
  $sender = $event->getSender();
  $command = $event->getCommand();
  foreach (Server::getInstance()->getOnlinePlayers() as $player) {
   $name = $sender instanceof Player ? $sender->getName() : "";
   if ($player->hasPermission("easymoderation.logger.usage")) {
    if (!isset(Loader::get()->spy[$player->getName()])) {
     $player->sendMessage(Loader::getProcessedTags(["{logger.sender}", "{logger.command}"], [$name, $command], Loader::get()->getConfig()->getNested("messages.logger.notice")));  
     Server::getInstance()->getLogger()->notice(Loader::getProcessedTags(["{logger.sender}", "{logger.command}"], [$name, $command], Loader::get()->getConfig()->getNested("messages.logger.notice")));  
    }
   }
  }
 }
 
} 
