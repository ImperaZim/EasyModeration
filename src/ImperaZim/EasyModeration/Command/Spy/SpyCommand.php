<?php

namespace ImperaZim\EasyModeration\Command\Spy;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use ImperaZim\EasyModeration\Loader;
use pocketmine\command\CommandSender;

class SpyCommand extends Command implements PluginOwned {

 public function __construct() {
  parent::__construct("spy", "§7Show and hide command logs!", null, ["log"]);
 }

 public function execute(CommandSender $player, String $commandLabel, array $args) : bool {
  if (!$player instanceof Player) {
   self::getServer()->getLogger()->error("This command can only be used in the game");
   return true;
  }
  if (!$this->testPermission($player, "easymoderation.spy.usage")) {
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.no-permission"))); 
   return true;
  }
  $hasEnable = isset(Loader::get()->spy[$player->getName()]);
  if (!isset($args[0])) {
   if ($hasEnable) {
    unset(Loader::get()->spy[$player->getName()]);
    $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.spy.disabled")));
   }else{
    Loader::get()->spy[$player->getName()] = $player->getName();
    $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.spy.activated")));
   }
   return true;
  }
  if (in_array(strtolower($args[0]), ["on", "ativar", "enable"])) {
   if (!$hasEnable) {
    Loader::get()->spy[$player->getName()] = $player->getName();
    $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.spy.activated")));
    return true;
   }
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.spy.has-activated")));
   return true;
  }
  if (in_array(strtolower($args[0]), ["off", "desativar", "disable"])) {
   if ($hasEnable) {
    unset(Loader::get()->spy[$player->getName()]);
    $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.spy.disabled"))); 
    return true;
   }
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.spy.has-disabled")));
   return true;
  }
  $player->sendMessage(Loader::getProcessedTags([], [], "{prefix} §7Use: /spy (on / off)"));
  return true;
 }

 public function getOwningPlugin() : Loader {
  return Loader::get();
 }

 public static function getServer() : Server {
  return Server::getInstance();
 }

}
