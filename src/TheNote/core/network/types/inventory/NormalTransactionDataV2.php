<?php
//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//
namespace TheNote\core\inventar\action;

use pocketmine\network\mcpe\NetworkBinaryStream as PacketSerializer;
use pocketmine\network\mcpe\protocol\types\inventory\NormalTransactionData;

class NormalTransactionDataV2 extends NormalTransactionData {

    protected function decodeData(PacketSerializer $stream) : void{
        $stream->offset = $stream->preDecodeOffset;
        $this->actions = [];
        $actionCount = $stream->getUnsignedVarInt();
        for($i = 0; $i < $actionCount; ++$i){
            $this->actions[] = (new NetworkInventoryActionV2())->read($stream);
        }
    }
}