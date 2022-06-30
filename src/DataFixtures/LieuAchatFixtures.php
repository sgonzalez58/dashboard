<?php

namespace App\DataFixtures;

use App\Entity\LieuAchat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LieuAchatFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        $type=['a distance', 'sur place'];
        for ($i = 0; $i < 20; $i++) {
            $product = new LieuAchat();
            $product->setNom('LieuAchat n°'.$i);
            $product->setType($type[mt_rand(0,1)]);
            $product->setAdresse('Adresse n°'.$i);
            $manager->persist($product);
        }

        $manager->flush();
    }
}