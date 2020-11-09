<?php

namespace App\Controller;

use App\Entity\Invoice;
use Symfony\Component\Routing\Annotation\Route;

class invoiceIncrementationController
{
     public function __invoke(Invoice $data)
     {
          dd($data) ;
     }
}