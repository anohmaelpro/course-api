<?php

namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;


class JwtCreatedSubscriber
{
     public function updateJwtData(JWTCreatedEvent $event){
          // get user who start the event
          $userEvent = $event->getUser();

          // data get on the variable $event for add new dataUser like his firstname and lastname
          $dataEventUpdate = $event->getData();

          // VsCode doesn't that the $userEvent is instance of entity user that's why, those function are underlined in red color
          $dataEventUpdate['firstName'] = $userEvent->getFirstName();
          $dataEventUpdate['lastName'] = $userEvent->getLastName();

          $event->setData($dataEventUpdate) ;
     }
}