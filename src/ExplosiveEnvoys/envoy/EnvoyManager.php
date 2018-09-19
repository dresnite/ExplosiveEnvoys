<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * @author GiantQuartz
*/

namespace ExplosiveEnvoys\envoy;


use ExplosiveEnvoys\ExplosiveEnvoys;
use pocketmine\block\Block;
use pocketmine\level\Explosion;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\tile\Chest;
use pocketmine\tile\Tile;

class EnvoyManager {
    
    /** @var ExplosiveEnvoys */
    private $plugin;
    
    /** @var Envoy[] */
    private $envoys = [];
    
    /** @var int */
    private $timer;
    
    /** @var Level[] */
    private $worlds = [];
    
    /**
     * EnvoyManager constructor.
     * @param ExplosiveEnvoys $plugin
     */
    public function __construct(ExplosiveEnvoys $plugin) {
        $this->plugin = $plugin;
        $this->timer = $plugin->getSettings()["spawnTime"];
        foreach($plugin->getSettings()["worlds"] as $world) {
            $server = $plugin->getServer();
            if($server->isLevelLoaded($world)) {
                $this->worlds[] = $server->getLevelByName($world);
            }
        }
        if(empty($this->worlds)) {
            $this->plugin->getLogger()->warning("ExplosiveEnvoys failed because there are no worlds available!");
            $this->plugin->getServer()->getPluginManager()->disablePlugin($this->plugin);
        }
        $plugin->getScheduler()->scheduleRepeatingTask(new EnvoyTask($plugin), 20);
    }
    
    /**
     * @return ExplosiveEnvoys
     */
    public function getPlugin(): ExplosiveEnvoys {
        return $this->plugin;
    }
    
    /**
     * @return Envoy[]
     */
    public function getEnvoys(): array {
        return $this->envoys;
    }
    
    /**
     * @param Position $position
     */
    public function spawnEnvoy(Position $position) {
        $nbt = new CompoundTag("", [
            new ListTag("Items", []),
            new StringTag("id", Tile::CHEST),
            new IntTag("x", $position->x),
            new IntTag("y", $position->y),
            new IntTag("z", $position->z)
        ]);
        $nbt->getListTag("Items")->setTagType(NBT::TAG_Compound);
        $level = $position->getLevel();
        $level->setBlock($position, Block::get(Block::CHEST));
        /** @var Chest $chest */
        $chest = Tile::createTile("Chest", $level, $nbt);
        $level->addTile($chest);
    
        $items = $this->plugin->getSettings()["content"];
        $items = ExplosiveEnvoys::parseItems($items);
        for($i = 0; $i < rand(3, 20); $i++) {
            $chest->getInventory()->addItem($items[array_rand($items)]);
        }
        
        $this->envoys[] = new Envoy($this, $position, $this->plugin->getSettings());
    }
    
    /**
     * @param Envoy $envoy
     * @param bool $forceDisappear
     */
    public function despawnEnvoy(Envoy $envoy, bool $forceDisappear = false) {
        $position = $envoy->getPosition();
        $envoy->getParticle()->setInvisible();
        $envoy->updateParticle();
        if($envoy->isExplode() and !($forceDisappear)) {
            $explosion = new Explosion($position, $envoy->getRadius());
            $explosion->explodeA();
            $explosion->explodeB();
        } else {
            $position->getLevel()->setBlock($position, Block::get(Block::AIR));
        }
        unset($this->envoys[array_search($envoy, $this->envoys)]);
    }
    
    public function update() {
        if($this->timer > 0) {
            $this->timer--;
        } else {
            $settings = $this->plugin->getSettings();
            for($i = 1; $i <= $settings["envoysToSpawn"]; $i++) {
                $level = $this->worlds[array_rand($this->worlds)];
                $position = $level->getSafeSpawn(new Vector3(rand(201, 1500), rand(1, 100), rand(201, 1500)));
                $this->spawnEnvoy($position);
                $this->plugin->getServer()->broadcastMessage($this->plugin->getMessage("ENVOY_SPAWNED", [
                    "num" => $i,
                    "level" => $position->getLevel()->getName(),
                    "x" => $position->getFloorX(),
                    "y" => $position->getFloorY(),
                    "z" => $position->getFloorZ()
                ]));
                $this->timer = $settings["spawnTime"];
            }
        }
    }
    
}