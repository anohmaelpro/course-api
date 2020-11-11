<?php

namespace App\Events;

use App\Entity\User;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{
     /**
      * Permet d'encoder le mot de passe d'un utlisateur
      *
      * @var UserPasswordEncoderInterface
      */
     private $encode;

     public function __construct(UserPasswordEncoderInterface $encode) {
          $this->encode = $encode;
     }

     public static function getSubscribedEvents(){
          return [
               KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE]
          ];
     }

     public function encodePassword(ViewEvent $event){
          // on récupère la valeur de l'évènement reçu
          $resultEvent = $event->getControllerResult();
          
          // on récupère la valeur de la method de l'évènement au occurence soit un POST, GET, etc.....Ici c'est dans le cas d'un POST
          $methodEvent = $event->getRequest()->getMethod();
          
          if (($resultEvent instanceof User) && ($methodEvent === "POST")){
               $hash = $this->encode->encodePassword($resultEvent, $resultEvent->getPassword());
               $resultEvent->setPassword($hash);
          }

     }

}