<?php


namespace App\Events;



use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Article;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class ArticleUserSubscriber implements EventSubscriberInterface{
    /**
     * @var Security
     */
    private $security;


    /**
     * ArticleUserSubscriber constructor.
     * @param Security $security
     */
    public function __construct(Security $security)
    {

        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUserForArticle', EventPriorities::PRE_VALIDATE]
        ];
    }

    public function setUserForArticle(ViewEvent $event)
    {
        $article = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($article instanceof Article && $method === "POST")
        {
            //Choper l'utilisateur actuellement connecté
            $user = $this->security->getUser();

            //Assigner cet utilisateur à l'article qu'on est entrain de créer
            $article->setUser($user);
        }
    }
}