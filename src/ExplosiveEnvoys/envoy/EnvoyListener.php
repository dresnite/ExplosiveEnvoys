<?php


namespace ExplosiveEnvoys\envoy;


use ExplosiveEnvoys\utils\Utils;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class EnvoyListener implements Listener {

    /** @var EnvoyManager */
    private $manager;

    public function __construct(EnvoyManager $manager) {
        $this->manager = $manager;
    }

    public function onBreak(BlockBreakEvent $event) {
        foreach($this->manager->getEnvoys() as $envoy) {
            if(Utils::comparePositions($envoy->getPosition(), $event->getBlock())) {
                $this->manager->despawnEnvoy($envoy);
            }
        }
    }

}