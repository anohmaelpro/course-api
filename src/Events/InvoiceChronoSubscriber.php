<?php

namespace App\Events;

use App\Entity\Invoice;
use App\Repository\InvoiceRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class InvoiceChronoSubscriber implements EventSubscriberInterface
{

     /**
      * get User linked to the current event
      *
      * @var Security
      */
     private $security ;

     /**
      * All invoice request is there
      *
      * @var InvoiceRepository
      */
     private $invoiceRepository ;

     public function __construct(Security $security, InvoiceRepository $invoiceRepository) {
          $this->security = $security;
          $this->invoiceRepository = $invoiceRepository;
     }

     public static function getSubscribedEvents(){
          
          return [
               KernelEvents::VIEW => ['setChronoForInvoice', EventPriorities::PRE_VALIDATE]
          ];
     }

     public function setChronoForInvoice(ViewEvent $events){
          // on recupere le type d'entité lié au lancement de l'évènement
          $resultEvent = $events->getControllerResult() ; // should be Invoices

          // get method used for call event
          $methodEvent = $events->getRequest()->getMethod();  // should be Post from POST

          
          if (($resultEvent instanceof Invoice)&&($methodEvent === "POST")){
               $invoiceEvent = $resultEvent;

               // get users who call the event and linked to the customer who wants create new invoice
               $userEvent = $this->security->getUser();

               // get next value of invoice chrono
               $nextInvoiceChrono = $this->invoiceRepository->findLastChrono($userEvent) + 1;

               $invoiceEvent->setChrono($nextInvoiceChrono) ;

               // set a new dateTime if the user didn't
               if (empty($invoiceEvent->getSentAt())) {
                    $invoiceEvent->setSentAt(new \DateTime()) ;
               }
          }
     }
}