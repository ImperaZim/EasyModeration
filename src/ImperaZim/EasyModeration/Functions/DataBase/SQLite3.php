<?php

namespace ImperaZim\EasyModeration\Functions\DataBase;

use ImperaZim\EasyModeration\Loader;

class SQLite3 extends Loader {
 
 public static function table() {
  return new \SQLite3(self::get()->getDataFolder() . "database.db");
 }
 
}
