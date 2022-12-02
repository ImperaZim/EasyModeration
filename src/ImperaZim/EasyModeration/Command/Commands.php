<?php

namespace ImperaZim\EasyModeration\Command;

use pocketmine\Server;
use ImperaZim\EasyModeration\Command\Mute\MuteCommand;
use ImperaZim\EasyModeration\Command\Mute\UnmuteCommand;
use ImperaZim\EasyModeration\Command\Punish\PunishCommand;
use ImperaZim\EasyModeration\Command\Punish\AbsolveCommand;

class Commands extends Server {
 
 public static function registerAll() : void {
   $commands = [
    "Mute" => new MuteCommand(), 
    "Unmute" => new UnmuteCommand(), 
    "Punish" => new PunishCommand(), 
    "Absolve" => new AbsolveCommand()
   ];
   foreach ($commands as $name => $command) {
    self::getInstance()->getCommandMap()->register($name, $command);
   }
  } 
  
} 
