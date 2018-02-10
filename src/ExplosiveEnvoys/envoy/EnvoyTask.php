<?php
/**
 * Copyright (C) A. Andrés R. Arias - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 */

namespace ExplosiveEnvoys\envoy;


use ExplosiveEnvoys\ExplosiveEnvoys;
use pocketmine\scheduler\PluginTask;

class EnvoyTask extends PluginTask {
    
    /** @var ExplosiveEnvoys */
    protected $owner;
    
    /**
     * @param int $currentTick
     */
    public function onRun(int $currentTick) {
        $manager = $this->owner->getEnvoyManager();
        $manager->update();
        foreach($manager->getEnvoys() as $envoy) {
            $envoy->update();
        }
    }
    
}