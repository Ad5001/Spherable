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

namespace Ad5001\Spherable\generators\spheres;

use pocketmine\level\generator\Generator;
use pocketmine\level\generator\biome\BiomeSelector;
use pocketmine\level\generator\biome\Biome;
use pocketmine\level\generator\object\OreType;
use pocketmine\level\generator\populator\GroundCover;
use pocketmine\level\generator\populator\Ore;
use pocketmine\level\generator\populator\Populator;
use pocketmine\block\Block;
try {
	if(!class_exists("pocketmine\\block\\BlockFactory")) {
		class_alias("pocketmine\\block\\Block", "pocketmine\\block\\BlockFactory");
	}
} catch(Throwable $e){
	class_alias("pocketmine\\block\\Block", "pocketmine\\block\\BlockFactory");
}
use pocketmine\level\ChunkManager;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class SpheresGenerator extends Generator {
    	
	
	
	/** @var Level */
	protected $level;
	
	
	/** @var Random */
	protected $random;


	/** 
	 * @var array[]
	 * 
	 * An array of planets made of different blocks.
	 **/
	protected $spheresBlocks = [
		[
			[Block::IRON_ORE, 0, 25],
			[Block::STONE, 0, 75], 

		],
		[
			[Block::COAL_ORE, 0, 25],
			[Block::STONE, 0, 75], 

		],
		[
			[Block::GOLD_ORE, 0, 25],
			[Block::STONE, 0, 75], 

		],
		[
			[Block::DIAMOND_ORE, 0, 7],
			[Block::LAPIS_BLOCK, 0, 93],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::REDSTONE_BLOCK, 0, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::WOOD, 12, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::WOOD, 13, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::WOOD, 14, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::WOOD, 15, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::WOOD2, 12, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::WOOD2, 13, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::PLANKS, 0, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::LEAVES, 4, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 7],
			[Block::NOTEBLOCK, 0, 93],
		],
		[
			[Block::DIAMOND_ORE, 0, 3],
			[Block::SNOW_BLOCK, 0, 97],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::COBWEB, 0, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::WOOL, 0, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::WOOL, 1, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::WOOL, 3, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::WOOL, 4, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::WOOL, 0, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 7],
			[Block::BOOKSHELF, 0, 90],
		],
		[
			[Block::DIAMOND_ORE, 0, 30],
			[Block::OBSIDIAN, 0, 70],
		],
		[
			[Block::DIAMOND_ORE, 0, 7],
			[Block::STONE_BRICK, 0, 90],
		],
		[
			[Block::DIAMOND_ORE, 0, 7],
			[Block::GRAVEL, 0, 40],
			[Block::STONE, 0, 50],
		],
		[
			[Block::DIAMOND_ORE, 0, 7],
			[Block::SAND, 0, 40],
			[Block::SANDSTONE, 0, 53],
		],
		[
			[Block::DIAMOND_ORE, 0, 7],
			[Block::PACKED_ICE, 0, 93],
		],
		[
			[Block::DIAMOND_ORE, 0, 3],
			[Block::SLIME_BLOCK, 0, 97],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::QUARTZ_BLOCK, 0, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 2],
			[Block::NETHERRACK, 0, 98],
		],
		[
			[Block::DIAMOND_ORE, 0, 5],
			[Block::EMERALD_ORE, 0, 95],
		],
		[
			[Block::DIAMOND_ORE, 0, 7],
			[Block::REDSTONE_LAMP, 0, 90],
		],
		[
			[Block::DIAMOND_ORE, 0, 10],
			[Block::END_STONE, 0, 90],
		],
		[
			[Block::DIAMOND_ORE, 0, 10],
			[Block::NETHER_BRICK_BLOCK, 0, 90],
		],
		[
			[Block::DIAMOND_ORE, 0, 10],
			[Block::MELON_BLOCK, 0, 90],
		],
		[
			[Block::DIAMOND_ORE, 0, 7],
			[Block::GLOWSTONE, 0, 93],
		],
		[
			[Block::DIAMOND_ORE, 0, 7],
			[Block::PUMPKIN, 0, 93],
		],
		[
			[Block::DIAMOND_ORE, 0, 10],
			[Block::SOUL_SAND, 0, 90],
		],
		[
			[Block::DIAMOND_ORE, 0, 10],
			[Block::SPONGE, 0, 90],
		],
		[
			[Block::DIAMOND_ORE, 0, 10],
			[Block::PRISMARINE, 0, 90],
		],
		[
			[Block::DIAMOND_ORE, 0, 10],
			[Block::SEA_LANTERN, 0, 90],
		],
		[
			[Block::DIAMOND_ORE, 0, 10],
			[Block::NETHER_REACTOR, 0, 90],
		],
	];
	
	public function __construct(array $options = []){}
	
	
	/**
	 * Inits the class for the var
	 * @param		ChunkManager		$level
	 * @param		Random				$random
	 * @return		void
	 */
	public function init(ChunkManager $level, Random $random) {
		$this->level = $level;
		$this->random = $random;
		
	}
	
	
	
	
	/***
	 * Returns the name of the generator
	 *
	 * @return string
	 */
	public function getName() : string{
		return "spheres";
	}
	
	
	/**
	 * Returns the settings of the generator
	 *
	 * @return array
	 */
	public function getSettings() : array{
		return [];
	}
	
	
	/**
	* Generates a chunk
	 *
	 * @param int $chunkX
	 * @param int $chunkZ
	 * @return void
	 */
	public function generateChunk(int $chunkX, int $chunkZ){
		// Leave blank, planets will be generated later
		$chunk = $this->level->getChunk($chunkX, $chunkZ);
		for($x = 0; $x < 16; $x++) {
			for($z = 0; $z < 16; $z++) {
				$chunk->setBiomeId($x, $z, 1);
				if($chunkX == 16 && $chunkZ == 16) $chunk->setBlockId($x, 254, $z, 2);
			}
		}
		$chunk->setGenerated();
	}
	
	
	/**
	* Populates the chunk with planets
	 *
	 * @param int $chunkX
	 * @param int $chunkZ
	 * @return void
	 */
	public function populateChunk(int $chunkX, int $chunkZ){
		$this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
		$chunk = $this->level->getChunk($chunkX, $chunkZ);
		$count = $this->random->nextRange(1, 4);
		for($i = 0; $i <= $count; $i++){
			$y = $this->random->nextRange(17, Level::Y_MAX - 25);
			$maxRadius = $y % 10;
			if($maxRadius < 6) $maxRadius = 6;
			// $maxRadius is situated between 12 and 20 depending on Y choosen
			// Let's add a little bit more random
			$radius = $this->random->nextRange(5, (int) round($maxRadius));
			// Generating planet
			$x = $chunkX * 16 + $this->random->nextRange(0, 15);
			$z = $chunkZ * 16 + $this->random->nextRange(0, 15);
			$center = new Vector3($x, $y, $z);
			$this->generatePlanet($center, $radius);
		}
	}

	/**
	 * Returns the dafault spawn
	 *
	 * @return void
	 */
	public function getSpawn() : Vector3{
		return new Vector3(264, 255, 264);
	}

	/**
	 * Generates a planet 
	 * psmcoreactplugin createlevel4psm Welp spheres 9247603569486
	 *
	 * @param Vector3 $center
	 * @param int $radius
	 * @return void
	 */
	public function generatePlanet(Vector3 $center, int $radius){
		$radiusSquared = $radius ** 2;
		$currentSphereBlocks = $this->spheresBlocks[array_rand($this->spheresBlocks)];
		for ($x = $center->x - $radius; $x <= $center->x + $radius; $x++) {
			$xsquared = ($center->x - $x) * ($center->x - $x);
			for ($y = $center->y - $radius; $y <= $center->y + $radius; $y++) {
				$ysquared = ($center->y - $y) * ($center->y - $y);
				for ($z = $center->z - $radius; $z <= $center->z + $radius; $z++) {
					$zsquared = ($center->z - $z) * ($center->z - $z);
					if($xsquared + $ysquared + $zsquared < $radiusSquared) {
						// Choosing a random block to place
						$rand = $this->random->nextBoundedInt(100) + 1;
						foreach($currentSphereBlocks as $block){
							if($rand > $block[2]) {
								$rand = $block[2];
								continue;
							} else {
								$this->level->setBlockIdAt($x, $y, $z, $block[0], false, false);
								$this->level->setBlockDataAt($x, $y, $z, $block[1], false, false);
								break;
							}
						}
					}
				}
			}
		}
	}
}