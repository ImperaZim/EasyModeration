<?php

namespace ImperaZim\EasyModeration\Event\PlayerEvent;

use pocketmine\event\Listener;
use ImperaZim\EasyModeration\Loader;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use ImperaZim\EasyModeration\Functions\Punishment\Punish;
use ImperaZim\EasyModeration\Functions\Punishment\Punishment;

class JoinEvent implements Listener {

 public function onPreLogin(PlayerPreLoginEvent $event): void {
  $punishment = new Punishment(); 
  $player = $event->getPlayerInfo();
  if ($punishment->isBanned($player->getUsername())) {
   $punish = new Punish($player->getUsername());
   $time = $punish->getTime();
   if ($time >= 1) {
    $date = $punish->getDate();
    $author = $punish->getAuthor();
    $reason = $punish->getReason();
    $time = Loader::getFullTime($time)["fulltime"]; 
    $kick_reason = Loader::getProcessedTags(["{ban.duration}", "{ban.reason}", "{ban.date}", "{ban.author}"], [$time, $reason, $date, $author], Loader::get()->getConfig()->getNested("messages.banned.to-banned")); 
    $event->setKickReason(PlayerPreLoginEvent::KICK_REASON_BANNED, $kick_reason);
   }else{
    $punish = new Punish($player->getUsername());
    $punish->absolve();
   }
  }
 }

 public function onLogin(PlayerLoginEvent $event): void {
  $punishment = new Punishment(); 
  $player = $event->getPlayer();
  if ($punishment->isBanned($player->getName())) {
   $punish = new Punish($player->getName());
   $time = $punish->getTime();
   if ($time >= 1) { 
    $date = $punish->getDate();
    $author = $punish->getAuthor();
    $reason = $punish->getReason();
    $time = Loader::getFullTime($time)["fulltime"]; 
    $kick_reason = Loader::getProcessedTags(["{ban.duration}", "{ban.reason}", "{ban.date}", "{ban.author}"], [$time, $reason, $date, $author], Loader::get()->getConfig()->getNested("messages.banned.to-banned")); 
    $player->kick($kick_reason);
    $event->cancel();
   }else{
    $punish = new Punish($player->getName());
    $punish->absolve();
    $message = explode(":", Loader::get()->getConfig()->getNested("messages.banned.absolve.notice"));
    if ($message[0] == "true") { 
     $absolve_notice = Loader::getProcessedTags([], [], $message[1]);
     $player->sendMessage($absolve_notice);
    }
   }
  }
 }

}

