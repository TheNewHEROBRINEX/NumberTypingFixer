<?php

namespace TheNewHEROBRINE\NumberTypingFixer;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {
    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onChat(PlayerChatEvent $event) {
        $message = $event->getMessage();
        if ($message{0} !== "/" and !ctype_digit($message) and preg_match_all("/\d+/", $message, $matches, PREG_OFFSET_CAPTURE)) {
            foreach ($matches[0] as $match) {
                $number = $match[0];
                $offset = $match[1];
                $endOffset = $offset + strlen($number) - 1;
                if ($offset == 0) {
                    if (!ctype_alpha($message{$endOffset + 1})) {
                        continue;
                    }
                } elseif ($endOffset == (strlen($message) - 1)) {
                    if (!ctype_alpha($message{$offset - 1})) {
                        continue;
                    }
                } elseif (!ctype_alpha($message{$endOffset + 1}) and !ctype_alpha($message{$offset - 1})) {
                        continue;
                }
                $number = str_replace([1, 2, 3, 4, 5, 6, 7, 8, 9, 0], ["q", "w", "e", "r", "t", "y", "u", "i", "o", "p"], $number);
                $message = substr_replace($message, $number, $offset, strlen($number));
            }
            $event->setMessage($message);
        }
    }
}