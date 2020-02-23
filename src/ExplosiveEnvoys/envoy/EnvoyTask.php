<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * @author GiantQuartz
*/

namespace ExplosiveEnvoys\envoy;


use pocketmine\scheduler\Task;

class EnvoyTask extends Task {

    /** @var EnvoyManager */
    private $manager;

    public function __construct(EnvoyManager $manager) {
        $this->manager = $manager;
    }
    
    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick): void {
        $this->manager->update();
    }
    
}