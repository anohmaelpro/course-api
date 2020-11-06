<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * permet de encoder le mot de passe de chaque USER
     *
     * @var UserPasswordEncoderInterface
     */
    private $encode;

    public function __construct(UserPasswordEncoderInterface $encode) {
        $this->encode = $encode ;
    }



    /**
     * function qui permet de générer de fausse données au sein de notre BDD
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // création des utilisateurs
        for ($usr=0; $usr < 10; $usr++) {
            
            $user = new User();
            $chrono = 1 ; // cette valeur représente le numéro de chaque facture
            $hash = $this->encode->encodePassword($user, "010203");

            $user->setFirstName($faker->firstname())
                 ->setLastName($faker->lastName())
                 ->setEmail($faker->email())
                 ->setPassword($hash);
            
                $manager->persist($user);

                 
                // pour chaque $client(30) on aura 10 facture
            for ($client=0; $client < mt_rand(5,20); $client++) { 
                $customer = new Customer(); // on initialise un nouveau customer
                $customer->setFirstName($faker->firstName())
                            ->setLastName($faker->lastName())
                            ->setCompany($faker->company()) // génère une nom d'entreprise en france
                            ->setEmail($faker->email())
                            ->setUserCustomer($user);
        
                $manager->persist($customer) ;
         
                     // pour chaque facture du client
                    for ($facture=0; $facture < mt_rand(3,10); $facture++) { 
                        $invoicesCustomer = new Invoice() ;
                        $invoicesCustomer->setAmout($faker->randomFloat(2, 250, 5000)) // génère un nombre de type float (2 chiffre après la virgule) entre 250€ et 5000€
                                         ->setSentAt($faker->dateTimeBetween('-6 months')) // une date entre la date actuelle et 6 mois avant
                                         ->setStatus($faker->randomElement(['SENT', 'PAID', 'CANCELLED'])) // Choisi une valeur parmi celle indiqué dans le tableau
                                         ->setCustomer($customer)
                                         ->setChrono($chrono) ;
                            $chrono++;
                         
                        $manager->persist($invoicesCustomer);
                    }
            }
        }

        $manager->flush();
    }
}
