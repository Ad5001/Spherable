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
namespace Ad5001\Spherable\commands;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\entity\Effect;

use Ad5001\Spherable\Main;
use Ad5001\Spherable\generators\spheres\SpheresGenerator;

class sphgenCommand extends Command{

    /**
     * Constructs the class
     *
     * @param Main $main
     */
    public function __construct(Main $main){
        $this->main = $main;
        parent::__construct("sphgen", "Spheres games base command", "/sphgen <createworld|join|tp> <world name> [player]");
        $this->setPermission("sphereable.cmd.join");
        $this->setUsage("§4§o[§r§4Usage§o§4]§r§4 /sphgen <createworld|join|tp> <world name>[player]");
    }

    /**
     * When the command is executed
     *
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if(count($args) < 2){
            $this->setUsage("§4§o[§r§4Usage§o§4]§r§4 /sphgen <createworld|join|tp> <world name> [player]");
            return true;
        }
        switch($args[0]){
            case "createworld":
            case "cw":
            if(!$sender->hasPermission("sphereable.cmd.createworld")){
                $sender->sendMessage("§4§o[§r§4Permission§o§4]§r§4 You don't have the permission to execute this command. If you believe this is an error, please contact the server administrator.");
                return true;
            }
            Server::getInstance()->generateLevel($args[1], 
            (int) round(rand(0, (int) (round(time() / memory_get_usage(true)) * (int) str_shuffle("127469453645108") / (int) str_shuffle("12746945364"))))
            , SpheresGenerator::class , []);
            Server::getInstance()->loadLevel($args[1]);
            $sender->sendMessage("§a§o[§r§aSuccess§o§a]§r§a Generating world $args[1]...");
            if(Server::getInstance()->getPluginManager()->getPlugin("PSMCore") !== null){
                \Ad5001\PSMCore\API::addPlayerAction('Tp to challenge world "' . $args[1] . '"', "sphgen tp {$args[1]} %p");
            }
            break;
            case "join":
            case "j":
            if(file_exists(Server::getInstance()->getDataPath() . "worlds/{$args[1]}/level.dat")){
                if(Server::getInstance()->getLevelByName($args[1]) == null){
                    Server::getInstance()->loadLevel($args[1]);
                }
                if(Server::getInstance()->getLevelByName($args[1])->getProvider()->getGenerator() == "spheres"){
                    $sender->teleport(new Position(264, 256, 264,Server::getInstance()->getLevelByName($args[1])));
                    $effect = Effect::getEffectByName("resistance");
                    $effect->setDuration(600);
                    $effect->setAmplifier(99);
					$effect->setVisible(false);
                    $sender->addEffect($effect);
                    $sender->sendMessage("§a§o[§r§aSuccess§o§a]§r§a Teleporting to challenge world $args[1]...");
                } else {
                    $sender->sendMessage("§4§o[§r§4Error§o§4]§r§4 Spheres world $args[1] doesn't exist. Generate a world using /sphgen cw <world name>.");                
                }
            } else {
                $sender->sendMessage("§4§o[§r§4Error§o§4]§r§4 Spheres world $args[1] doesn't exist. Generate a world using /sphgen cw <world name>.");
            }
            break;
            case "tp":
            if(!$sender->hasPermission("sphereable.cmd.tp")){
                $sender->sendMessage("§4§o[§r§4Permission§o§4]§r§4 You don't have the permission to execute this command. If you believe this is an error, please contact the server administrator.");
                return true;
            }
            if(count($args) < 3){
                $sender->sendMessage("§4§o[§r§4Usage§o§4]§r§4 /sphgen $args[0] <world name> <player>");
                return true;
            }
            if(file_exists(Server::getInstance()->getDataPath() . "worlds/{$args[1]}/level.dat")){
                if(Server::getInstance()->getLevelByName($args[1]) == null){
                    Server::getInstance()->loadLevel($args[1]);
                }
                if(Server::getInstance()->getLevelByName($args[1])->getProvider()->getGenerator() == "spheres"){
                    if(Server::getInstance()->getPlayer($args[2]) == null) {
                        $sender->sendMessage("§4§o[§r§4Error§o§4]§r§4 Player $args[2] doesn't exists or isn't connected.");
                        return true;
                    }
                    $player = Server::getInstance()->getPlayer($args[2]);
                    $player->teleport(new Position(264, 256, 264,Server::getInstance()->getLevelByName($args[1])));
                    $effect = Effect::getEffectByName("resistance");
                    $effect->setDuration(600);
                    $effect->setAmplifier(99);
					$effect->setVisible(false);
                    $player->addEffect($effect);
                    $player->sendMessage("§a§o[§r§aSuccess§o§a]§r§a {$sender->getName()} teleported you to to challenge world $args[1]...");
                    $sender->sendMessage("§a§o[§r§aSuccess§o§a]§r§a Teleporting $args[2] to challenge world $args[1]...");
                } else {
                    $sender->sendMessage("§4§o[§r§4Error§o§4]§r§4 Spheres world $args[1] doesn't exist. Generate a world using /sphgen cw <world name>.");                
                }
            } else {
                $sender->sendMessage("§4§o[§r§4Error§o§4]§r§4 Spheres world $args[1] doesn't exist. Generate a world using /sphgen cw <world name>.");
            }
            break;
        }
        return true;
    }

    /**
     * Generates custom data for command
     *
     * @param Player $player
     * @return array
     */
    public function generateCustomCommandData(Player $player): array {
        $cmdData = parent::generateCustomCommandData($player);
        $cmdData["permission"] = "sphereable.cmd.join";
        $cmdData["aliases"] = [];
        $cmdData["overloads"]["default"]["input"]["parameters"] = [
            0 => [
                "type" => "stringenum",
                "name" => "subcmd",
                "optional" => false,
                "enum_values" => [
                    "createworld",
                    "cw",
                    "join",
                    "j",
                ]
            ],
            1 => [
                "type" => "string",
                "name" => "world",
                "optional" => false
            ],
            2 => [
                "type" => "string",
                "name" => "player",
                "optional" => true
            ]
        ];
        return $cmdData;
    }
}
