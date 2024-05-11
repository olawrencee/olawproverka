<?php

namespace olawrencee\olawmain;

use olawrencee\olawformclass\Form;
use pocketmine\plugin\PluginBase;
use olawrencee\EventListener;
use olawrencee\olawmain\olawcommand\ProverkaCommand;
use pocketmine\utils\TextFormat as TF;
use olawrencee\olawlibs\jojoe77777\FormAPI\Form as FormAPI;

class Main extends PluginBase
{

    public static $instance;

    private $form;

    public $freezeplayers = [];

    public $prefix = TF::BOLD . TF::DARK_GRAY ."[".TF::AQUA ."ПРОВЕРКА".TF::DARK_GRAY ."] ". TF::RESET;

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register($this->getName(), new ProverkaCommand());
        $this->form = new Form();

        if(!class_exists(FormAPI::class)){
            $this->getLogger()->error("FormAPI не найден.");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    public function onLoad(): void
    {
        self::$instance = $this;
    }

    public function getForm(): Form
    {
        return $this->form;
    }
}