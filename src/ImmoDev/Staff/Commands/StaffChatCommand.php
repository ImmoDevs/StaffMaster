<?php 

namespace ImmoDev\Staff\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

use ImmoDev\Staff\Loader;

class StaffChatCommand extends Command implements Listener {

    private Loader $plugin;
    private $staffChat = [];

    public function __construct(Loader $plugin) {
        parent::__construct("staffchat", "Send private message to a staff", null, ["schat"]);
        $this->setPermission("staffchat.use");
        $this->plugin = $plugin;
    }

	public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
		if ($sender instanceof Player) {
			if ($sender->hasPermission("staffchat.use")) {
				if (empty($args)) {
					if (isset($this->staffChat[$sender->getName()])) {
						unset($this->staffChat[$sender->getName()]);
						$sender->sendMessage(TextFormat::GREEN . "StaffChat disabled.");
					} else {
						$this->staffChat[$sender->getName()] = true;
						$sender->sendMessage(TextFormat::GREEN . "StaffChat enabled. All your messages will now be sent to StaffChat.");
					}
					return true;
				}
				$message = implode(" ", $args);
				$this->sendStaffMessage($sender, $message);
			} else {
				$sender->sendMessage(TextFormat::RED . "You do not have permission to use this command.");
			}
		} else {
			$this->plugin->getLogger()->info("This command can only be used in-game.");
		}
		return true;
    }

    public function onPlayerChat(PlayerChatEvent $event): void {
        $player = $event->getPlayer();
        if (isset($this->staffChat[$player->getName()])) {
            $event->cancel();
            $this->sendStaffMessage($player, $event->getMessage());
        }
    }

    private function sendStaffMessage(Player $sender, string $message): void {
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if ($player->hasPermission("staffchat.view") || $player->hasPermission("staffchat.use")) {
                $player->sendMessage(TextFormat::GOLD . "[StaffChat] " . TextFormat::WHITE . $sender->getName() . ": " . $message);
            }
        }
    }
}