<?php


namespace ExplosiveEnvoys\utils;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\level\Position;

class Utils {

    public static function printSeconds(int $seconds): string {
        return gmdate("i:s", $seconds);
    }

    public static function comparePositions(Position $position, Position $position2): bool {
        if($position->getFloorX() == $position2->getFloorX() and $position->getFloorY() == $position2->getFloorY() and
            $position->getFloorZ() == $position2->getFloorZ() and $position2->getLevel() === $position->getLevel()) {
            return true;
        }
        else {
            return false;
        }
    }

    public static function parseItem($string): ?Item {
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
    
    public static function parseItems($array): array {
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