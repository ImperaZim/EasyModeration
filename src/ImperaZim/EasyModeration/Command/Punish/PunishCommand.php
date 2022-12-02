<?php

namespace ImperaZim\EasyModeration\Command\Punish;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use ImperaZim\EasyModeration\Loader;
use pocketmine\command\CommandSender;
use ImperaZim\EasyModeration\Functions\Punishment\Punish;

class PunishCommand extends Command implements PluginOwned {

 public function __construct() {
  parent::__construct("punish", "§7Punish players!", null, ["punir", "banir"]);
 }

 public function execute(CommandSender $player, String $commandLabel, array $args) : bool {
  if (!$this->testPermission($player, "easymoderation.punish.command")) {
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.no-permission"))); 
   return true;
  }
  if (isset($args[0])) {
   $target = $args[0];
   if ((new Punish($target))->isBanned($target)) {
    $player->sendMessage(Loader::getProcessedTags(["{ban.player}"], [$target], Loader::get()->getConfig()->getNested("messages.banned.is-banned")));
    return true;
   } 
   $date = date("d/m/y");
   $hours = date("H:i:s");
   $author = "CONSOLE_BAN";
   $time = $args[2] ?? "undefined";
   $reason = $args[1] ?? "undefined";
   $fulltime = "[{$date}], {$hours}";
   if ($player instanceof Player) {
    $author = isset($args[3]) ? $args[3] : ":a";
    $author = $author != ":a" ? $player->getName() : "anonymous";
   }
   $punish = new Punish($target);
   $punish->setTime($time);
   $punish->setReason($reason);
   $punish->setDate($fulltime);
   $punish->setAuthor($author);
   if ($punish->execute()) {
    $message = explode(":", Loader::get()->getConfig()->getNested("messages.banned.notice"));
    if ($message[0] == "true") {
     $duration = new Punish($target);
     $duration = $duration->getTime();
     $duration = Loader::getFullTime($duration)["fulltime"];
     $punish_notice = Loader::getProcessedTags(["{ban.duration}", "{ban.reason}", "{ban.date}", "{ban.author}", "{ban.player}"], [$duration, $reason, $duration, $author, $target], $message[1]);
     self::getServer()->broadcastMessage($punish_notice);
    }
    return true;
   }
   $player->sendMessage(Loader::getProcessedTags([], [], Loader::get()->getConfig()->getNested("messages.error")));
   return true;
  }
  $player->sendMessage(Loader::getProcessedTags([], [], "{prefix} §7Use: /punish (name) \"reason between quotes\" (time = undefined) (mode = default)"));
  return true;
 }

 public function getOwningPlugin() : Loader {
  return Loader::getInstance();
 }

 public static function getServer() : Server {
  return Server::getInstance();
 }

}