<?php

namespace ImmoDev\Staff\Listeners;

use pocketmine\player\Player;
use pocketmine\event\player\{
    PlayerMoveEvent, 
    PlayerDropItemEvent, 
    PlayerInteractEvent,
    PlayerJumpEvent
};
use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\inventory\CraftItemEvent;

use pocketmine\event\Listener;

use ImmoDev\Staff\Loader;
use ImmoDev\Staff\Managers\FreezeManager;

class FreezeListener implements Listener {

    // Player
    public function onMove(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        if (FreezeManager::isPlayerFrozen($player)) {
            // $player->sendMessage("§cYou can`t move, because you have been frozen!");
			// !!!!!! I turned off this message because it's too spammy, I'll fix it as soon as possible
            $event->cancel();
        }
    }

    public function onDrop(PlayerDropItemEvent $event) {
        $player = $event->getPlayer();
        if (FreezeManager::isPlayerFrozen($player)) {
            $player->sendMessage("§cYou can't drop items, because you've been frozen!");
            $event->cancel();
        }
    }

    public function onInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        if (FreezeManager::isPlayerFrozen($player)) {
            $player->sendMessage("§cYou can't interact, because you've been frozen!");
            $event->cancel();
        }
    }

    public function onJump(PlayerJumpEvent $event) {
        $player = $event->getPlayer();
        if (FreezeManager::isPlayerFrozen($player)) {
			$motion = $player->getMotion();
			$player->setMotion($motion->multiply(-1));
            $player->sendMessage("§cYou can't jump, because you've been frozen!");
        }
    }

    // Block
    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        if (FreezeManager::isPlayerFrozen($player)) {
            $player->sendMessage("§cYou can't break the blocks, because you've been frozen!");
            $event->cancel();
        }
    }

    public function onPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        if (FreezeManager::isPlayerFrozen($player)) {
            $player->sendMessage("§cYou can't place blocks, because you've been frozen!");
            $event->cancel();
        }
    }

    // Entity
    public function onPickup(EntityItemPickupEvent $event) {
        $entity = $event->getEntity();
        if ($entity instanceof Player && FreezeManager::isPlayerFrozen($entity)) {
            $entity->sendMessage("§cYou can't pick up items, because you've been frozen!");
            $event->cancel();
        }
    }

    public function onCraft(CraftItemEvent $event) {
        $player = $event->getPlayer();
        if (FreezeManager::isPlayerFrozen($player)) {
            $player->sendMessage("§cYou can't craft items, because you've been frozen!");
            $event->cancel();
        }
    }
}
