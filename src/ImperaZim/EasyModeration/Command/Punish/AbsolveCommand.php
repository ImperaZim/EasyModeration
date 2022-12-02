<?php

namespace ImperaZim\EasyModeration\Command\Punish;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use ImperaZim\EasyModeration\Loader;
use pocketmine\command\CommandSender;
use ImperaZim\EasyModeration\Functions\Punishment\Punish;

class AbsolveCommand extends Command implements PluginOwned {

 public function __construct() {
  parent::__construct("absolve", "§7Absolve player!", null, ["perdoar", "desbanir"]);
 }

 public function execute(CommandSender $player, String $commandLabel, array $args) : bool {
  if (!$this->testPermission($player, "easymoderation.absolve.command")) {
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.no-permission"))); 
   return true;
  }
  if (isset($args[0])) {
   $target = $args[0];
   $punish = new Punish($target);
   if (!$punish->isBanned($target)) {
    $player->sendMessage(Loader::getProcessedTags(["{absolve.player}"], [$target], Loader::get()->getConfig()->getNested("messages.banned.absolve.no-banned")));
    return true;
   }
   if ($punish->absolve()) {
    $player->sendMessage(Loader::getProcessedTags(["{absolve.player}"], [$target], Loader::get()->getConfig()->getNested("messages.banned.absolve.to-sender")));
    return true;
   }
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.error")));
   return true;
  }
  $player->sendMessage(Loader::getProcessedTags([], [], "{prefix} §7Use: /absolve (name)"));
  return true;
 }

 public function getOwningPlugin() : Loader {
  return Loader::getInstance();
 }

 public static function getServer() : Server {
  return Server::getInstance();
 }

}
