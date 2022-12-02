<?php

namespace ImperaZim\EasyModeration\Event;

use ImperaZim\EasyModeration\Loader;
use ImperaZim\EasyModeration\Event\PlayerEvent\ChatEvent;
use ImperaZim\EasyModeration\Event\PlayerEvent\JoinEvent;
use ImperaZim\EasyModeration\Event\ServerEvent\CommandEvent;

class Events extends Loader {
 
 public static function registerAll() : void {
   $events = [
    JoinEvent::class, 
    ChatEvent::class, 
    CommandEvent::class
   ];
   foreach ($events as $event) {
    self::get()->getServer()->getPluginManager()->registerEvents(new $event(), self::get());
   }
  } 
 
} 
