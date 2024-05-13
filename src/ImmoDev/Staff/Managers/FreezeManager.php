<?php

namespace Yookou\Freeze\managers;

use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class FreezeManager {
	use SingletonTrait;
	public array $frozenPlayers = [];

	protected function __construct() {
		self::setInstance($this);
	}

	public function freezePlayer(Player $player) : void {
		$playerName = $player->getName();
		$this->frozenPlayers[$playerName] = $playerName;
		$player->setNoClientPredictions();
	}

	public function unfreezePlayer(Player $player) : void {
		unset($this->frozenPlayers[$player->getName()]);
		$player->setNoClientPredictions(false);
	}

	public function isFreeze(Player $player) : bool {
		return isset($this->frozenPlayers[$player->getName()]);
	}
}
