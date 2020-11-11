<?php

namespace App\Events;


use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Customer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class CustomerUserSubscriber implements EventSubscriberInterface
{
     /**
      * Cette variable va nous permetre de récupérer le bon User lié à notre requete de création de Customer
      *
      * @var Security
      */
     private $security;

     public function __construct(Security $security) {
          $this->security = $security;
     }

     public static function getSubscribedEvents(){
          return [
               KernelEvents::VIEW => ['setUserForCustomer', EventPriorities::PRE_VALIDATE]
          ];
     }

     public function setUserForCustomer(ViewEvent $event){
         
          // cette fonction permet de savoir l'entity lié à l'évènement reçu (USER, CUSTOMER, INVOICE) ici on s'interesse au Customer
          $resultEvent = $event->getControllerResult() ; // should be Customer

          // le type de method qui est utilisé pour lancer la méthod, comme il s'agit d'une création logique ici on doit avoir un post
          $methodEvent = $event->getRequest()->getMethod() ; // should be POST

          if (($resultEvent instanceof Customer) && ($methodEvent === "POST")){
               // on récupère le bon User qui fait la requete de creation d'un Costumer
               $userCustomer = $this->security->getUser();

               // on assigne l'User au costumer automatiquement avant l'ajout dans la BDD
               $resultEvent->setUserCustomer($userCustomer) ;
          }


     }

}