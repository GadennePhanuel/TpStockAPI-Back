<?php


namespace App\Events;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber
{
    public function updateJwtData(JWTCreatedEvent $event)
    {
        //récupérer l'utilisateur
        $user = $event->getUser();

        //enrichir les data pour quelle contiennent ses données
        $data = $event->getData();         //ici effete on récupère le payload du token JWT
        $data['firstName'] = $user->getFirstName();
        $data['lastName'] = $user->getLastName();

        $event->setData($data);
    }
}