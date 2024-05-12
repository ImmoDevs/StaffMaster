<?php

namespace ImmoDev\Staff\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use pocketmine\network\mcpe\protocol\types\DeviceOS;

use ImmoDev\Staff\Loader;
use ImmoDev\Staff\Managers\FreezeManager;

class StaffToolsCommand extends Command implements Listener
{
    public function __construct(Loader $plugin)
    {
        parent::__construct("stafftools", "Gives staff tools", null, [
            "stools",
        ]);
        $this->setPermission("stafftools.use");
    }

    public function execute(
        CommandSender $sender,
        string $commandLabel,
        array $args
    ): bool {
        if (!$sender instanceof Player) {
            if (!$sender->hasPermission("stafftools.use")) {
                $sender->sendMessage(
                    TextFormat::RED .
                        "You do not have permission to use this command."
                );
            } else {
                $sender->sendMessage(
                    TextFormat::RED . "This command can only be used in-game."
                );
            }
            return true;
        }
        $this->StaffTools($sender);
        return true;
    }

    public function StaffTools(Player $player)
    {
        $compass = VanillaItems::COMPASS()->setCustomName(
            TextFormat::RESET . TextFormat::AQUA . "Staff Tools - Player Freeze"
        );
        $bone = VanillaItems::BONE()->setCustomName(
            TextFormat::RESET . TextFormat::AQUA . "Staff Tools - Player Info"
        );
        $brick = VanillaItems::BRICK()->setCustomName(
            TextFormat::RESET . TextFormat::AQUA . "Staff Tools - Player Kick"
        );

        $player->getInventory()->sddItem($compass);
        $player->getInventory()->sddItem($bone);
        $player->getInventory()->sddItem($brick);
    }

    public function onItemUse(PlayerItemUseEvent $event)
    {
    }

    public function onEntityDamage(EntityDamageByEntityEvent $event)
    {
        $entity = $event->getEntity();
        $damager = $event->getDamager();
        if ($entity instanceof Player and $damager instanceof Player) {
            $item = $damager->getInventory()->getItemInHand();
            if (
                $item->getCustomName() ===
                TextFormat::RESET .
                    TextFormat::AQUA .
                    "Staff Tools - Player Freeze"
            ) {
                if (!FreezeManager::isPlayerFrozen($entity)) {
                    FreezeManager::freezePlayer($entity);
                    $damager->sendMessage(
                        TextFormat::GOLD .
                            "Freezing Player: " .
                            TextFormat::WHITE .
                            $entity->getName()
                    );
                    $damager->sendMessage(
                        TextFormat::GREEN .
                            "Player " .
                            TextFormat::WHITE .
                            $entity->getName() .
                            TextFormat::GREEN .
                            " has been frozen."
                    );
                } else {
                    FreezeManager::unfreezePlayer($entity);
                    $damager->sendMessage(
                        TextFormat::GOLD .
                            "UnFreezing Player: " .
                            TextFormat::WHITE .
                            $entity->getName()
                    );
                    $damager->sendMessage(
                        TextFormat::GREEN .
                            "Player " .
                            TextFormat::WHITE .
                            $entity->getName() .
                            TextFormat::GREEN .
                            " has been unfrozen."
                    );
                }
                $event->cancel();
            }

            if (
                $item->getCustomName() ===
                TextFormat::RESET .
                    TextFormat::AQUA .
                    "Staff Tools - Player Info"
            ) {
                $name = $entity->getName();
                $ping = $entity->getNetWorkSession()->getPing();
                $health = $entity->getHealth();
                $ipAddress = $entity->getNetWorkSession()->getIp();
                $device = $this->getPlayerPlatForm($entity);

                $damager->sendMessage(
                    TextFormat::GOLD .
                        "Player Info:" .
                        "\n" .
                        TextFormat::GREEN .
                        "Name: " .
                        TextFormat::WHITE .
                        $name .
                        "\n" .
                        TextFormat::GREEN .
                        "Ping: " .
                        TextFormat::WHITE .
                        $ping .
                        "\n" .
                        TextFormat::GREEN .
                        "Health: " .
                        TextFormat::WHITE .
                        $health .
                        "\n" .
                        TextFormat::GREEN .
                        "IP Address: " .
                        TextFormat::WHITE .
                        $ipAddress .
                        "\n" .
                        TextFormat::GREEN .
                        "Device: " .
                        TextFormat::WHITE .
                        $device
                );
                $event->cancel();
            }

            if (
                $item->getCustomName() ===
                TextFormat::RESET .
                    TextFormat::AQUA .
                    "Staff Tools - Player Kick"
            ) {
                $name = $entity->getName();
                $damager->sendMessage(
                    TextFormat::GOLD .
                        "Kicking Player: " .
                        TextFormat::WHITE .
                        $name
                );
                $entity->kick(
                    TextFormat::RED . "You have been kicked by a staff member."
                );
                $event->cancel();
            }
        }
    }

    public function getPlayerPlatForm(Player $player): string
    {
        $data = $player->getPlayerInfo()->getExtraData();
        if (
            $data["DeviceOS"] === DeviceOS::ANDROID &&
            $data["DeviceOS"] === "Not Registered"
        ) {
            return "Linux";
        }

        return match ($data["DeviceOS"]) {
            DeviceOS::ANDROID => "Android",
            DeviceOS::IOS => "iOS",
            DeviceOS::OSX => "Mac",
            DeviceOS::XBOX => "Xbox",
            DeviceOS::PLAYSTATION => "PlayStation",
            DeviceOS::WINDOWS_10 => "Windows 10",
            DeviceOS::WIN32 => "Windows 32",
            DeviceOS::NINTENDO => "Nintendo",
            default => "Unknown",
        };
    }
}
