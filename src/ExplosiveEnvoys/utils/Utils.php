<?php


namespace ExplosiveEnvoys\utils;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\level\Position;

class Utils {

    /**
     * @param $seconds
     * @return string
     */
    public static function printSeconds($seconds): string {
        return ((($m = floor($seconds / 60)) < 10 ? "0" : "") . $m . ":" . (($s = floor($seconds % 60)) < 10 ? "0" : "") . (string) $s);
    }

    /**
     * Return true if both positions are equal, false if not
     *
     * @param Position $position
     * @param Position $position2
     * @return bool
     */
    public static function comparePositions(Position $position, Position $position2): bool {
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

    /**
     * Parse items
     *
     * @param array $array
     * @return []Item
     */
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