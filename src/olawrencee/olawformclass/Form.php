<?php

namespace olawrencee\olawformclass;

use olawrencee\olawmain\Main;
use olawrencee\olawlibs\jojoe77777\FormAPI\CustomForm;
use olawrencee\olawlibs\jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TF;

class Form
{

    public function Menu(Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) {
                return true;
            }
            switch ($data) {
                case 0:
                    $this->FREEZE($player);
                    break;

                case 1:
                    if (!Main::getInstance()->freezeplayers) {
                        $player->sendMessage(Main::getInstance()->prefix . TF::YELLOW . "§c§lНет игроков которые вызваны на проверку!");
                    } else {
                        $this->UNFREEZE($player);
                    }
                    break;
            }
        });
        $form->setTitle("§f§lМенеджер §cпроверок");
        $form->addButton("§f§lВызвать на §cпроверку", 0, "textures/ui/icon_winter");
        $form->addButton("§f§lОтпустить с §cпроверки", 0, "textures/ui/redX1");
        $player->sendForm($form);
        return $form;
    }

    public function FREEZE(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data = null) {
            if ($data === null) {
                return true;
            }
            $playerName = [];
            foreach (Server::getInstance()->getOnlinePlayers() as $players) {
                $playerName[] = $players->getName();
            }
            $target = Main::getInstance()->getServer()->getPlayerExact($playerName[$data[0]]);
            $targetName = $target->getName();
            if ($targetName == $player->getName()) {
                $player->sendMessage(Main::getInstance()->prefix . TF::YELLOW . "§f§lНельзя вызвать себя на проверку!");
                return true;
            }
            if (!in_array($targetName, Main::getInstance()->freezeplayers)) {
                array_push(Main::getInstance()->freezeplayers, $targetName);
                $player->sendMessage(Main::getInstance()->prefix . TF::GREEN . "§l§cВнимание вы были вызваны на проверку! \n§l§cВы были заподозрены в использовании стороннего §fПО. \n§l§cВыход с сервера = бан. Пожалуйста дайте свой §9Discord§b либо же §fAnyDesk. На все у вас есть 5 минут. \n§f§cВызвал на проверку: " . TF::YELLOW . $targetName);
                $target->sendMessage(Main::getInstance()->prefix . TF::GREEN . "§c§lВы вызваны на проверку!");
            } else {
                $player->sendMessage(Main::getInstance()->prefix . TF::RED . $targetName . TF::YELLOW . " §lуже на проверке!");
            }
        });
        $form->setTitle("§f§lВызвать на §cпроверку");
        $playerName = [];
        foreach (Server::getInstance()->getOnlinePlayers() as $players) {
            $playerName[] = $players->getName();
        }
        $form->addDropdown("Выберите игрока:", $playerName);
        $player->sendForm($form);
        return $form;
    }

    public function UNFREEZE(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data = null) {
            if ($data === null) {
                return true;
            }
            $playerName = [];
            foreach (Main::getInstance()->freezeplayers as $players) {
                $playerName[] = $players;
            }
            $target = Main::getInstance()->getServer()->getPlayerExact($playerName[$data[0]]);
            if(!$target){
                $player->sendMessage(Main::getInstance()->prefix . TF::RED . "§lИгрок не в сети!");
            }else {
                $targetName = $target->getName();
                array_splice(Main::getInstance()->freezeplayers, array_search($targetName, Main::getInstance()->freezeplayers), 1);
                $player->sendMessage(Main::getInstance()->prefix . TF::YELLOW . $targetName . TF::GREEN . " §f§lбольше не на проверке!");
                $target->sendMessage(Main::getInstance()->prefix . TF::GREEN . "§f§lВас отпустили с проверки! ");
            }
        });
        $form->setTitle("§l§fОтпустить с §cпроверки");
        $form->addDropdown("Выберите игрока:", Main::getInstance()->freezeplayers);
        $player->sendForm($form);
        return $form;
    }
}
