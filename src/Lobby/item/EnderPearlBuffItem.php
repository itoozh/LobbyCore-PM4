<?php

declare(strict_types=1);


namespace Lobby\item;


use Lobby\form\ServersForm;

use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\sound\EndermanTeleportSound;

class EnderPearlBuffItem extends LobbyItem {

    public function __construct() {
        parent::__construct(new ItemIdentifier(ItemIds::ENDER_PEARL, 0), "§r§6EnderPearl §r§7(Right Click)");
    }

    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult {
        $world = $player->getWorld();
        $player->setMotion($player->getDirectionVector()->multiply(1.6));
        $world->addSound($player->getPosition(), new EndermanTeleportSound(), [$player]);
        return ItemUseResult::FAIL();
    }

}
