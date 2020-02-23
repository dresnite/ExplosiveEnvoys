<?php


namespace ExplosiveEnvoys\utils;


use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;

class Utils {

    public static function printSeconds(int $seconds): string {
        return gmdate("i:s", $seconds);
    }

    public static function parseItem(string $itemString): ?Item {
        $array = explode(",", $itemString);
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

    public static function parseItems(array $itemStrings): array {
        $resultItems = [];
        foreach($itemStrings as $item) {
            $item = self::parseItem($item);
            if($item instanceof Item) {
                $resultItems[] = $item;
            }
        }
        return $resultItems;
    }

}