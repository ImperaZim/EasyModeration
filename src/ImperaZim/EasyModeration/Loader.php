<?php

namespace ImperaZim\EasyModeration;

use pocketmine\plugin\PluginBase;
use ImperaZim\EasyModeration\Event\Events;
use ImperaZim\EasyModeration\Task\TimerTask;
use ImperaZim\EasyModeration\Command\Commands;

class Loader extends PluginBase {
 
 public $spy = []; 
 
 public static Loader $instance;

 public static function get() : Loader {
  return self::$instance;
 }

 public function onEnable() : void {
  self::$instance = $this; 
  $this->loadAll();
 }
 
 public function loadAll() : void {
  self::$instance = $this; 
  Events::registerAll();
  Commands::registerAll();
  TimerTask::register($this, new TimerTask());
  $data = new \SQLite3($this->getDataFolder() . "database.db");
  $data->query("CREATE TABLE IF NOT EXISTS muted(name TEXT, time INT, reason TEXT, date TEXT, author TEXT)");
  $data->query("CREATE TABLE IF NOT EXISTS banned(name TEXT, time INT, reason TEXT, date TEXT, author TEXT)"); 
 }
 
 public static function getFullTime(Int $time) {
  $secounds = $time;
  $minutes = round($secounds / 60);
  $minutes = $minutes < 0 ? 0 : $minutes;
  $hours = round($minutes / 60);
  $hours = $hours < 0 ? 0 : $hours;
  $days = round($hours / 24);
  $days = $days < 0 ? 0 : $days;
  $week = round($days / 7);
  $week = $week < 0 ? 0 : $week;
  $month = round($days / 30);
  $month = $month < 0 ? 0 : $month;
  $years = round($month / 12);
  $years = $years < 0 ? 0 : $years;
  
  # ================ #
  $y = round($years) < 0 ? 0 : round($years); 
  $y = round($years) > 999 ? "+999" : round($years); 
  $mon = round($month - ($years * 60)) < 0 ? 0 : round($month - ($years * 60)); 
  $d = round($days - ($month * 60)) < 0 ? 0 : round($days - ($month * 60)); 
  $h = round($hours - ($days * 60)) < 0 ? 0 : round($hours - ($days * 60)); 
  $m = round($minutes - ($hours * 60)) < 0 ? 0 : round($minutes - ($hours * 60)); 
  $s = round($secounds - ($minutes * 60)) < 0 ? 0 : round($secounds - ($minutes * 60)); 
  # ================ #
  
  
  $y = $y <= 0 ? "" : $y. "y, ";
  $mon = $mon <= 0 ? "" : $mon. "mon, ";
  $d = $d <= 0 ? "" : $d. "d, ";
  $h = $h <= 0 ? "" : $h. "y, ";
  $m = $m <= 0 ? "" : $m. "m, ";
  $s = $s . "s.";
  $fulltime = $y . $mon . $d . $h . $m . $s;
  return ["secounds" => $secounds, "minutes" => $minutes, "hours" => $hours, "days" => $days, "weeks" => $week, "months" => $month, "years" => $years, "fulltime" => $fulltime];
 }  
 
 public static function getProcessedTags($tags, $processeds, $message) {
  $message = str_replace(
   ["{prefix}"], 
   [self::$instance->getConfig()->get("prefix")], 
   $message);
  return str_replace($tags, $processeds, $message);
 }
 
}