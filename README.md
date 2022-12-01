# EasyModeration 

A plugin to facilitate the moderation of your server that allows you to ban, unban, silence and unmute users and see the user command by users! 
- - - -
## Compatibility 
This plugin is meant to be used on servers made only in the software **[PocketMine-MP](https://github.com/pmmp/PocketMine-MP)**, it may not work perfectly in variants of it.

## Commands:
 **Punish:**
- /punish [args...]
- /absolve [args...]

 **Mute:**
- /mute [args...] 
- /unmute [args...] 

 **Spy:**
- /spy [args...] 

## Usage commands:
- punish: /punish [player] [reason]
- absolve: /absolve [player]
- mute: /mute [player] [reason] [time]
- unmute: /unmute [player]
- spy: /spy [value]

## EasyModeration API
```php 
use ImperaZim\EasyModeration\Functions\Punishment\Punishment as API;
API::isMuted(String $username); // example return true
API::isBanned(String $username); // example return true
```
