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
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

class Envoy {
    
    /** @var EnvoyManager */
    private $manager;
    
    /** @var Position */
    private $position;
    
    /** @var bool */
    private $explode;
    
    /** @var int */
    private $timer;
    
    /** @var int */
    private $radius;
    
    /** @var FloatingTextParticle */
    private $particle;
    
    /**
     * Envoy constructor.
     * @param EnvoyManager $manager
     * @param Position $position
     * @param array $settings
     */
    public function __construct(EnvoyManager $manager, Position $position, array $settings) {
        $this->manager = $manager;
        $this->position = $position;
        $this->explode = $settings["explode"];
        $this->timer = $settings["seconds"];
        $this->radius = $settings["explosion-radius"];
        $vector = new Vector3($position->x + 0.5, $position->y + 1, $position->z + 0.5);
        $this->particle = new FloatingTextParticle($vector, "");
        $position->getLevel()->addParticle($this->particle);
    }
    
    /**
     * @return bool
     */
    public function isExplode(): bool {
        return $this->explode;
    }
    
    /**
     * @return Position
     */
    public function getPosition(): Position {
        return $this->position;
    }
    
    /**
     * @return int
     */
    public function getRadius(): int {
        return $this->radius;
    }
    
    /**
     * @return FloatingTextParticle
     */
    public function getParticle(): FloatingTextParticle {
        return $this->particle;
    }
    
    public function update() {
        if($this->timer > 0) {
            $this->timer--;
            $this->updateParticle();
        } else {
            $this->manager->despawnEnvoy($this);
        }
    }
    
    public function updateParticle() {
        $plugin = $this->manager->getPlugin();
        $this->particle->setTitle($plugin->getMessage("TIMER_MESSAGE", [
            "time" => ExplosiveEnvoys::printSeconds($this->timer)
        ]));
        foreach($plugin->getServer()->getOnlinePlayers() as $player) {
            foreach($this->particle->encode() as $packet) {
                $player->sendDataPacket($packet);
            }
        }
    }
    
}