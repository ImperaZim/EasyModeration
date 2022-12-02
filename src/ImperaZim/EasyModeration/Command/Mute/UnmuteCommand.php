<?php

namespace ImperaZim\EasyModeration\Command\Mute;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use ImperaZim\EasyModeration\Loader;
use pocketmine\command\CommandSender;
use ImperaZim\EasyModeration\Functions\Punishment\Mute;

class UnmuteCommand extends Command implements PluginOwned {

 public function __construct() {
  parent::__construct("unmute", "§7Un mute player!", null, ["desmutar"]);
 }

 public function execute(CommandSender $player, String $commandLabel, array $args) : bool {
  if (!$this->testPermission($player, "easymoderation.unmute.command")) {
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.no-permission"))); 
   return true;
  }
  if (isset($args[0])) {
   $target = $args[0];
   $mute = new Mute($target);
   if (!$mute->isMuted($target)) {
    $player->sendMessage(Loader::getProcessedTags(["{absolve.player}"], [$target], Loader::get()->getConfig()->getNested("messages.muted.absolve.no-muted")));
    return true;
   }
   if ($mute->absolve()) {
    $player->sendMessage(Loader::getProcessedTags(["{absolve.player}"], [$target], Loader::get()->getConfig()->getNested("messages.muted.absolve.to-sender")));
    return true;
   }
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.error")));
   return true;
  }
  $player->sendMessage(Loader::getProcessedTags([], [], "{prefix} §7Use: /absolve (name)"));
  return true;
 }

 public function getOwningPlugin() : Loader {
  return Loader::get();
 }

 public static function getServer() : Server {
  return Server::getInstance();
 }

}
