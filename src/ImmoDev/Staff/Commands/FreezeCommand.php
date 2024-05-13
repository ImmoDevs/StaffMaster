<?php 

namespace ImmoDev\Staff\Commands;

use pocketmine\command\{Command, CommandSender};
use pocketmine\player\Player;

use ImmoDev\Staff\Loader;
use ImmoDev\Staff\Managers\FreezeManager;

class FreezeCommand extends Command {

	private Loader $plugin;

	public function __construct(Loader $plugin) {
        parent::__construct("freeze", "freeze player", "/freeze </player>");
        $this->setPermission("staff.freeze.use");
        $this->plugin = $plugin;
    }

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if (!$sender instanceof Player) {
			$this->plugin->getLogger()->info("This command can only be used in-game.");
			return true;
		}
		
		if (!$sender->hasPermission("staff.freeze.use")) {
			$sender->sendMessage(TextFormat::RED . "You do not have permission to use this command.");
			return true;
		}
		
		if (empty($args)) {
			$sender->sendMessage(TextFormat::RED . "Usage: /freeze <player>");
			return true;
		}
		
		$playerName = array_shift($args);
		$player = $sender->getServer()->getPlayerByPrefix($playerName);
		
		if (!$player instanceof Player) {
			$sender->sendMessage(TextFormat::RED . "Player not found.");
			return true;
		}
		
		if (FreezeManager::isPlayerFrozen($player)) {
			$sender->sendMessage(TextFormat::RED . "Player " . $player->getName() . " is already frozen.");
		} else {
			FreezeManager::freezePlayer($player);
			$sender->sendMessage(TextFormat::RED . "Player " . $player->getName() . " has been frozen.");
			$player->sendMessage(TextFormat::GREEN . "You have been frozen.");
		}
		return true;
	}
}
