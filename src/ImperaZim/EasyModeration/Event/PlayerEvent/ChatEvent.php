<?php

namespace ImperaZim\EasyModeration\Event\PlayerEvent;

use pocketmine\event\Listener;
use ImperaZim\EasyModeration\Loader;
use pocketmine\event\player\PlayerChatEvent;
use ImperaZim\EasyModeration\Functions\Punishment\Mute;
use ImperaZim\EasyModeration\Functions\Punishment\Punishment;

class ChatEvent implements Listener { 
 
 public function Event(PlayerChatEvent $event) : void {
  $player = $event->getPlayer();
  $punishment = new Punishment(); 
  if ($punishment->isMuted($player->getName())) {
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.muted.alert")));
   $event->cancel();
  }
 }
 
}
