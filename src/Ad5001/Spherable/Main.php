<?php

/***
*      ____          _                        _      _       
 *     / ___|  _ __  | |__    ___  _ __  __ _ | |__  | |  ___ 
 *     \___ \ | '_ \ | '_ \  / _ \| '__|/ _` || '_ \ | | / _ \
 *      ___) || |_) || | | ||  __/| |  | (_| || |_) || ||  __/
 *     |____/ | .__/ |_| |_| \___||_|   \__,_||_.__/ |_| \___|
 *            |_|                                             
 * 
 * Spheres world generator. A new survival challenge.
 * @author Ad5001 <mail@ad5001.eu>
 * @copyright (C) 2017 Ad5001
 * @license NTOSL (View LICENSE.md)
 * @package Spherical
 * @version 1.0.0
 * @link https://download.ad5001.eu/en/view.php?name=Spherable&src=github
 */
declare(strict_types = 1);

namespace Ad5001\Spherable;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\level\generator\Generator;
use pocketmine\level\generator\GeneratorManager;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\level\Position;
use pocketmine\entity\Effect;

use Ad5001\Spherable\generators\spheres\SpheresGenerator;
use Ad5001\Spherable\commands\sphgenCommand;



class Main extends PluginBase implements Listener{

    public $playersResist = [];
	
	
	/**
	 * When the plugin enables
	 *
	 * @return void
	 */
	public function onEnable(){
		GeneratorManager::addGenerator(SpheresGenerator::class, "spheres");
		$this->getServer()->getCommandMap()->register("sphgen", new sphgenCommand($this));
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	
	/**
	 */
	public function onEntityLevelChange(\pocketmine\event\entity\EntityLevelChangeEvent $event){
		if($event->getTarget()->getProvider()->getGenerator() == "spheres" && $event->getEntity() instanceof Player){
			$event->getEntity()->setSpawn(new Position(264, 255, 264, $event->getTarget()));
		}
	}
	
	
	/**
	 */
	public function onRespawn(\pocketmine\event\player\PlayerRespawnEvent $event){
		if($event->getPlayer()->getLevel()->getProvider()->getGenerator() == "spheres"){
            $this->playersResist[$event->getPlayer()->getName()] = time();
            $event->getPlayer()->sendMessage("You are resistant for 30 seconds. Profit to go back to your last death point.");
		}
	}
	
	
	/**
	 */
	public function onEntityDamage(\pocketmine\event\entity\EntityDamageEvent $event){
        if($event->getEntity()->getLevel()->getProvider()->getGenerator() == "spheres" && 
        $event->getEntity() instanceof Player && 
        isset($this->playersResist[$event->getEntity()->getName()]) &&
        $this->playersResist[$event->getEntity()->getName()] > time() - 30){
            $event->setCancelled();
		}
	}
	
	
	/**
	 */
	public function onBlockBreak(\pocketmine\event\block\BlockBreakEvent $event){
        if($event->getBlock()->getLevel()->getProvider()->getGenerator() == "spheres"){
            if($event->getBlock()->getId() == 56){
                $diamonds_count = 1;
                foreach($event->getPlayer()->getInventory()->getContents() as $item){
                    $diamonds_count += $item->getCount();
                }
                if($diamonds_count % 64 == 0 && $this->getServer()->getPluginManager()->getPlugin("PSMCore") !== null){
                    \Ad5001\PSMCore\API::displayNotification("Diamonds !", $event->getPlayer()->getName() . " has mined " . ($diamonds_count / 64) . " stacks of diamond!", [], "none");
                }
            }
		}
	}
	
	
	/**
	 */
	public function onJoin(\pocketmine\event\player\PlayerJoinEvent $event){
		if($event->getPlayer()->getLevel()->getProvider()->getGenerator() == "spheres"){
			$event->getPlayer()->setSpawn(new Position(264, 255, 264, $event->getPlayer()->getLevel()));
		}
	}
}
