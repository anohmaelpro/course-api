<?php

namespace App\Doctrine ;

use Doctrine\ORM\QueryBuilder;
use App\Repository\InvoiceRepository;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
     /**
      * get User linked to the current event
      *
      * @var Security
      */
     private $security ;

     private $auth;

 
     public function __construct(Security $security,AuthorizationCheckerInterface $checker) {
          $this->security = $security;
          $this->auth = $checker;
     }


     private function addWhere(QueryBuilder $queryBuilder, string $resourceClass){

          // get User connect and send the request
          $currentEventUser = $this->security->getUser();
          if ((($resourceClass === Invoice::class) || ($resourceClass === Customer::class)) && (!$this->auth->isGranted('ROLE_ADMIN')) && ($currentEventUser instanceof User)) {
               $aliasCurrentRequest =  $queryBuilder->getRootAliases()[0];
          
               if ($resourceClass === Customer::class) {
                    $queryBuilder->andWhere("$aliasCurrentRequest.userCustomer = :user") ;
                                                  
               } else if ($resourceClass === Invoice::class) {
                    $queryBuilder->join("$aliasCurrentRequest.customer", "c")
                                                  ->andWhere("c.userCustomer = :user") ;
               }
               
               $queryBuilder->setParameter(":user", $currentEventUser);
          }
     }



     /**
      *this function is used to get Request sent when the user try to get all invoices or all customers, and then we can update the request as we want to be able to return a better response from the DB
      *
      * @param QueryBuilder $queryBuilder => this parameter is used to get the content of the query
      * @param QueryNameGeneratorInterface $queryNameGenerator
      * @param string $resourceClass => this parameter allows the entity or class to be assigned to current request
      * @param string|null $operationName
      * @return void
      */
     public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
     {
          $this->addWhere($queryBuilder, $resourceClass);
     }

     public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
     {
          $this->addWhere($queryBuilder, $resourceClass);
     }
}