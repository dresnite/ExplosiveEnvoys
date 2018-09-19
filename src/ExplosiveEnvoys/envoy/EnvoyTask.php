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
use pocketmine\scheduler\Task;

class EnvoyTask extends Task {
    
    /** @var ExplosiveEnvoys */
    protected $owner;
    
    /**
     * EnvoyTask constructor.
     * @param ExplosiveEnvoys $owner
     */
    public function __construct(ExplosiveEnvoys $owner) {
        $this->owner = $owner;
    }
    
    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick): void {
        $manager = $this->owner->getEnvoyManager();
        $manager->update();
        foreach($manager->getEnvoys() as $envoy) {
            $envoy->update();
        }
    }
    
}