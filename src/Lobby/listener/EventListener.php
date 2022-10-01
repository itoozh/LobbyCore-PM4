<?php

namespace Lobby\listener;

use Lobby\Main;
use Lobby\session\SessionFactory;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\particle\FloatingTextParticle;
use skymin\bossbar\BossBarAPI;

class EventListener implements Listener
{

    public function onJoin(PlayerJoinEvent $event): void
    {
        $config = Main::getInstance()->getConfig();
        $session = SessionFactory::getSession($player = $event->getPlayer());
        if (is_null($session)) {
            return;
        }

        $session->initScoreboard();
        $session->sendWelcomeMessages();
        $session->setup();
        $session->teleportToLobbyWorld();

        foreach (SessionFactory::getSessions() as $session) {
            $session->update();
        }
        # Welcome message
        $event->setJoinMessage(TextFormat::colorize("&7[&2+&7] " . $player->getName()));
        $pos = explode(':', $config->get('floating'));
        $player->getServer()->getWorldManager()->getDefaultWorld()?->addParticle(new Vector3((int)$pos[0], (int)$pos[1], (int)$pos[2]), new FloatingTextParticle("", "§r§eWelcome to " . $config->get("server-name")), [$player]);
        $player->getServer()->getWorldManager()->getDefaultWorld()?->addParticle(new Vector3((int)$pos[0], (int)$pos[1] - 0.50, (int)$pos[2]), new FloatingTextParticle("", "§r§fUsa el selector para explorar las §r§emodalidades§r§f."), [$player]);
        $player->getServer()->getWorldManager()->getDefaultWorld()?->addParticle(new Vector3((int)$pos[0], (int)$pos[1] - 1, (int)$pos[2]), new FloatingTextParticle("", "§r§a§l25% OFF SALE"), [$player]);
        $player->getServer()->getWorldManager()->getDefaultWorld()?->addParticle(new Vector3((int)$pos[0], (int)$pos[1] - 1.25, (int)$pos[2]), new FloatingTextParticle("", "§r§6Activo por tiempo limitado!"), [$player]);
        $player->getServer()->getWorldManager()->getDefaultWorld()?->addParticle(new Vector3((int)$pos[0], (int)$pos[1] - 1.75, (int)$pos[2]), new FloatingTextParticle("", $config->get("server-storelink")), [$player]);
        //$bossbar = new BossBarAPI();
        //$bossbar->sendBossBar($player, TextFormat::colorize($config->get("scoreboard.title")), 0, "0", 0);
    }

    public function onQuit(PlayerQuitEvent $event): void
    {
        # Leave message
        $event->setQuitMessage(TextFormat::colorize("&7[&c-&7] " . $event->getPlayer()->getName()));
    }

    public function onExhaust(PlayerExhaustEvent $event): void
    {
        # Cancel hunger update
        $event->cancel();
    }

    public function onDamage(EntityDamageEvent $event): void
    {
        # Cancel fall damage
        if ($event->getCause() === EntityDamageEvent::CAUSE_FALL) {
            $event->cancel();
            return;
        }

        if ($event->getCause() === EntityDamageEvent::CAUSE_VOID) {
            $player = $event->getEntity();

            if ($player instanceof Player) {
                $event->cancel();
                SessionFactory::getSession($player)?->teleportToLobbyWorld();
            }
            return;
        }

        if ($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
            $event->cancel();
        }
    }
}

