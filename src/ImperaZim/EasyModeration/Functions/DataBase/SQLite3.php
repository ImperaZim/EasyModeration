<?php

namespace ImperaZim\EasyModeration\Functions\DataBase;

use ImperaZim\EasyModeration\Loader;

class SQLite3 extends Loader {
 
 public static function table() {
  return new \SQLite3(self::get()->getDataFolder() . "database.db");
 }
  
 public static function createTable() : void {
  self::table()->exec("CREATE TABLE IF NOT EXISTS muted(name TEXT, time INT, reason TEXT, date TEXT, author TEXT)");
  self::table()->exec("CREATE TABLE IF NOT EXISTS banned(name TEXT, time INT, reason TEXT, date TEXT, author TEXT)");
 } 
 
}
