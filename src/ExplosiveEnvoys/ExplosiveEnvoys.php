<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * @author GiantQuartz
*/

namespace ExplosiveEnvoys;


use ExplosiveEnvoys\envoy\EnvoyManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class ExplosiveEnvoys extends PluginBase {
    
    /** @var array */
    private $settings;
    
    /** @var array */
    private $messages;
    
    /** @var EnvoyManager */
    private $envoyManager;
    
    public function onLoad() {
        if(!is_dir($this->getDataFolder())) {
            mkdir($this->getDataFolder());
        }
        $this->saveResource("Settings.yml");
        $this->saveResource("Messages.json");
        $this->settings = (new Config($this->getDataFolder() . "Settings.yml"))->getAll();
        $this->messages = (new Config($this->getDataFolder() . "Messages.json"))->getAll();
    }
    
    public function onEnable() {
        $this->envoyManager = new EnvoyManager($this);
        $this->getServer()->getPluginManager()->registerEvents(new ExplosiveEnvoysListener($this), $this);
    }
    
    /**
     * @return array
     */
    public function getSettings(): array {
        return $this->settings;
    }
    
    /**
     * @return EnvoyManager
     */
    public function getEnvoyManager(): EnvoyManager {
        return $this->envoyManager;
    }
    
    /**ç
     * @param string $identifier
     * @param array $args
     * @return null|string
     */
    public function getMessage(string $identifier, array $args): ?string {
        $message = $this->messages[$identifier] ?? "";
        foreach($args as $arg => $value) {
            $message = str_replace("{" . $arg . "}", $value, $message);
        }
        return $message;
    }
    
}
