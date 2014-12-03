<?php
namespace CommandRepeater;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\RemoteServerCommandEvent;
use pocketmine\event\server\ServerCommandEvent;

class EventHandler implements Listener{
    /** @var Main  */
    public $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event){
        $this->plugin->setLastCommand($event->getPlayer(), false);
    }

    /**
     * @param PlayerQuitEvent $event
     */
    public function onPlayerQuit(PlayerQuitEvent $event){
        $this->plugin->removeCommandSender($event->getPlayer());
    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     */
    public function onPlayerCommand(PlayerCommandPreprocessEvent $event){
        if(substr($event->getMessage(), 0, 1) === "/"){
            $command = explode(" ", substr($event->getMessage(), 1));
            $command[0] = substr($event->getMessage(), 1);
            if($command[0] !== "repeat" && $command[0] !== "repeatcommand" && $command[0] !== "rcmd"){
                $this->plugin->setLastCommand($event->getPlayer(), implode(" ", $command));
            }
        }
    }

    /**
     * @param ServerCommandEvent $event
     */
    public function onConsoleCommand(ServerCommandEvent $event){
        $command = explode(" ", $event->getCommand());
        if($command[0] !== "repeat" && $command[0] !== "repeatcommand" && $command[0] !== "rcmd"){
            $this->plugin->setLastCommand($event->getSender(), $event->getCommand());
        }
    }

    /**
     * @param RemoteServerCommandEvent $event
     */
    public function onRconCommand(RemoteServerCommandEvent $event){
        $command = explode(" ", $event->getCommand());
        if($command[0] !== "repeat" && $command[0] !== "repeatcommand" && $command[0] !== "rcmd"){
            $this->plugin->setLastCommand($event->getSender(), $event->getCommand());
        }
    }
} 