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
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\level\Position;
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
        $this->getLogger()->info("ExplosiveEnvoys by @GiantQuartz was enabled");
    }
    
    public function onDisable() {
        $this->getLogger()->info("ExplosiveEnvoys by @GiantQuartz was disabled");
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
    
    /**
     * @param $seconds
     * @return string
     */
    public static function printSeconds($seconds): string {
        $m = floor($seconds / 60);
        $s = floor($seconds % 60);
        return (($m < 10 ? "0" : "") . $m . ":" . ($s < 10 ? "0" : "") . (string) $s);
    }
    
    /**
     * Return true if both positions are equal, false if not
     *
     * @param Position $position
     * @param Position $position2
     * @return bool
     */
    public static function comparePositions(Position $position, Position $position2) {
        if($position->getFloorX() == $position2->getFloorX() and $position->getFloorY() == $position2->getFloorY() and
            $position->getFloorZ() == $position2->getFloorZ() and $position2->getLevel() === $position->getLevel()) {
            return true;
        }
        else {
            return false;
        }
    }
    
    /**
     * Parse an Item
     *
     * @param string $string
     * @return null|Item
     */
    public static function parseItem($string) {
        $array = explode(",", $string);
        foreach($array as $key => $value) {
            $array[$key] = (int) $value;
        }
        if(isset($array[1])) {
            $item = Item::get($array[0], 0, $array[1]);
            if(isset($array[3])) {
                $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment($array[2]), $array[3]));
            }
            return $item;
        }
        else {
            return null;
        }
    }
    
    /**
     * Parse items
     *
     * @param array $array
     * @return array
     */
    public static function parseItems($array) {
        $items = [];
        foreach($array as $item) {
            $item = self::parseItem($item);
            if($item instanceof Item) {
                $items[] = $item;
            }
        }
        return $items;
    }
    
}