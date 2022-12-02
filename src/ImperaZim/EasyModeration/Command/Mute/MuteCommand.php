<?php

namespace ImperaZim\EasyModeration\Command\Mute;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use ImperaZim\EasyModeration\Loader;
use pocketmine\command\CommandSender;
use ImperaZim\EasyModeration\Functions\Punishment\Mute;

class MuteCommand extends Command implements PluginOwned {

 public function __construct() {
  parent::__construct("mute", "§7Mute players!", null, ["mutar", "sileciar"]);
 }

 public function execute(CommandSender $player, String $commandLabel, array $args) : bool {
  if (!$this->testPermission($player, "easymoderation.mute.command")) {
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.no-permission"))); 
   return true;
  } 
  if (isset($args[0])) {
   $target = $args[0];
   $punish = new Mute($target);
   if ($punish->isMuted($target)) {
    $player->sendMessage(Loader::getProcessedTags(["{mute.player}"], [$target], Loader::get()->getConfig()->getNested("messages.mute.is-muted")));
    return true;
   }  
   if (!Server::getInstance()->getPlayerExact($target) instanceof Player) {
    $player->sendMessage(Loader::getProcessedTags(["{mute.player}"], [$target], Loader::get()->getConfig()->getNested("messages.muted.offline"))); 
    return true;
   }
   $date = date("d/m/y");
   $hours = date("H:i:s");
   $author = "CONSOLE_MUTE";
   $time = $args[2] ?? "undefined";
   $reason = $args[1] ?? "undefined";
   $fulltime = "[{$date}], {$hours}";
   if ($player instanceof Player) {
    $author = isset($args[3]) ? $args[3] : ":a";
    $author = $author != ":a" ? $player->getName() : "anonymous";
   }
   $mute = new Mute($target);
   $mute->setTime($time);
   $mute->setReason($reason);
   $mute->setDate($fulltime);
   $mute->setAuthor($author);
   if ($mute->execute()) {
    $player->sendMessage(Loader::getProcessedTags(["{mute.player}"], [$target], Loader::get()->getConfig()->getNested("messages.muted.to-sender")));
    return true;
   }
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.error")));
   return true;
  }
  $player->sendMessage(Loader::getProcessedTags([], [], "{prefix} §7Use: /mute (name) \"reason between quotes\" (time = undefined) (mode = default)"));
  return true;
 }

 public function getOwningPlugin() : Loader {
  return Loader::getInstance();
 }

 public static function getServer() : Server {
  return Server::getInstance();
 }

}
