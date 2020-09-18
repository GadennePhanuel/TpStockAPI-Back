<?php


namespace App\Events;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Stock;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class StockUserSubscriber implements EventSubscriberInterface
{

    /**
     * @var Security
     */
    private $security;

    /**
     * StockUserSubscriber constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUserForStock', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setUserForStock(ViewEvent $event)
    {
        $stock = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($stock instanceof Stock && $method === "POST")
        {
            //Choper l'utilisateur actuellement connecté
            $user = $this->security->getUser();

            //Assigner cet utilisateur au stock qu'on est entrain de créer
            $stock->setUser($user);
        }
    }
}