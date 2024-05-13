<?php

namespace ImmoDev\Staff;

use pocketmine\plugin\PluginBase;
use ImmoDev\Staff\Commands\{StaffChatCommand, FreezeCommand, UnfreezeCommand};
use ImmoDev\Staff\Listeners\FreezeListener;
use pocketmine\event\Listener;

class Loader extends PluginBase {

	public function onEnable(): void {
		$this->getLogger()->info("Plugin Activated");
		$this->registerCommands();
		$this->registerEvents();
	}

	public function onDisable():void {
		$this->getLogger()->info("Plugin Deactivated");
	}

	public function registerCommands() {
		$commands = [
			new StaffChatCommand($this),
			new FreezeCommand($this),
			new UnfreezeCommand($this)
		];

		foreach ($commands as $command) {
			$this->getServer()->getCommandMap()->register($command->getName(), $command);
		}
	}

	public function registerEvents(): void {
		$listeners  = [
			new StaffChatCommand($this),
			new FreezeListener($this)
		];

		foreach ($listeners  as $listener) {
			$this->getServer()->getPluginManager()->registerEvents($listener, $this);
		}
	}
}
