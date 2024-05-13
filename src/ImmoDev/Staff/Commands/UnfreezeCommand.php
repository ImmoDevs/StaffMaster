<?php

namespace ImmoDev\Staff\Commands;

use pocketmine\player\Player;
use pocketmine\command\{Command, CommandSender};

use ImmoDev\Staff\Loader;
use ImmoDev\Staff\Managers\FreezeManager;

class UnfreezeCommand extends Command {

	private Loader $plugin;

	public function __construct(Loader $plugin) {
        parent::__construct("unfreeze", "Unfreeze a player", "/unfreeze </player>");
		$this->setPermission("staff.unfreeze.use");
        $this->plugin = $plugin;
    }

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if (!$sender instanceof Player) {
			$this->plugin->getLogger()->info("This command can only be used in-game.");
			return true;
		}
		
		if (!$sender->hasPermission("staff.unfreeze.use")) {
			$sender->sendMessage(TextFormat::RED . "You do not have permission to use this command.");
			return true;
		}
		
		if (empty($args)) {
			$sender->sendMessage(TextFormat::RED . "Usage: /unfreeze <player>");
			return true;
		}
		
		$playerName = array_shift($args);
		$player = $sender->getServer()->getPlayerByPrefix($playerName);
		
		if (!$player instanceof Player) {
			$sender->sendMessage(TextFormat::RED . "Player not found.");
			return true;
		}
		
		if (!FreezeManager::isPlayerFrozen($player)) {
			$sender->sendMessage(TextFormat::RED . "Player is not frozen.");return true;
		}

		FreezeManager::unfreezePlayer($player);
		$sender->sendMessage(TextFormat::GREEN . "Player has been unfrozen.");
		$player->sendMessage(TextFormat::GREEN . "You have been unfrozen.");
		return true;
	}
}
