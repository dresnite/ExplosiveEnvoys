<?php
/**
 * Copyright (C) A. Andrés R. Arias - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 */

namespace ExplosiveEnvoys;


use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class ExplosiveEnvoysListener implements Listener {
    
    /** @var ExplosiveEnvoys */
    private $plugin;
    
    /**
     * ExplosiveEnvoysListener constructor.
     * @param ExplosiveEnvoys $plugin
     */
    public function __construct(ExplosiveEnvoys $plugin) {
        $this->plugin = $plugin;
    }
    
    /**
     * @param BlockBreakEvent $event
     */
    public function onBreak(BlockBreakEvent $event) {
        $block = $event->getBlock();
        $manager = $this->plugin->getEnvoyManager();
        foreach($manager->getEnvoys() as $envoy) {
            $position = $envoy->getPosition();
            if(ExplosiveEnvoys::comparePositions($position, $block)) {
                $manager->despawnEnvoy($envoy);
            }
        }
    }
    
}