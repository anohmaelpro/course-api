<?php

namespace App\Controller;

use App\Entity\Invoice;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class invoiceIncrementationController
{
     /**
      * permet de faire le rajout dans la BDD (persister les données et les ajouter dans la BDD)
      *
      * @var EntityManagerInterface
      */
     private $manager;

     public function __construct(EntityManagerInterface $manager) {
          $this->manager = $manager;
     }

     public function __invoke(Invoice $data)
     {
          $data->setChrono($data->getChrono()+1);
          
          // $this->manager->persist($data) ;

          $this->manager->flush() ;
          
          return $data;
     }
}