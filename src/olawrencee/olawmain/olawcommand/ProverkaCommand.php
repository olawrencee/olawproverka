<?php

namespace olawrencee\olawmain\olawcommand;

use olawrencee\olawmain\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;

class ProverkaCommand extends Command implements PluginOwned
{

    public function __construct()
    {
        parent::__construct("proverka", "Вызвать игрока на проверку", "/proverka", ["ss"]);
        $this->setPermission("olawproverka.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) {
            return;
        }
        if (!$sender instanceof Player) {
            return;
        }
        $this->getOwningPlugin()->getForm()->Menu($sender);
    }

    public function getOwningPlugin(): Main
    {
        return Main::getInstance();
    }
}
