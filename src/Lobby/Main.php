<?php 

namespace Lobby;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use Lobby\session\SessionFactory;

class Main extends PluginBase implements Listener {
    use SingletonTrait;
    
    /** @var SessionFactory */
    private SessionFactory $sessionFactory;
    
    protected function onLoad(): void {
        self::setInstance($this);
    }

    protected function onEnable() : void {
        # Setup config
        $this->saveResource("config.yml");
        # Setup session factory
        $this->sessionFactory = new SessionFactory;
        # Register event handler
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        
        # Logger
        $this->getLogger()->info("LobbyCore Enabled");
    }
    
    /**
     * @return SessionFactory
     */
    public function getSessionFactory(): SessionFactory
    {
        return $this->sessionFactory;
    }
}

