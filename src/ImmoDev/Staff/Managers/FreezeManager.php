<?php 

namespace ImmoDev\Staff\Managers;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class FreezeManager {

    private static $frozenPlayers = [];

    public static function freezePlayer(Player $player): void {
        self::$frozenPlayers[$player->getName()] = true;
		$player->sendMessage(TextFormat::GREEN. "You have been frozen!");
    }

    public static function unfreezePlayer(Player $player): void {
        unset(self::$frozenPlayers[$player->getName()]);
		$player->sendMessage(TextFormat::GREEN. "You have been unfrozen!");
    }

    public static function isPlayerFrozen(Player $player): bool {
        return isset(self::$frozenPlayers[$player->getName()]);
    }
}
