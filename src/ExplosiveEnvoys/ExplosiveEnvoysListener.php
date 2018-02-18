<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * @author GiantQuartz
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