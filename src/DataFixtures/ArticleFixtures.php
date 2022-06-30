<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $product = new Article();
            $product->setNom('product '.$i);
            $product->setLieuAchat(mt_rand(1, 10));
            $product->setCategorie(mt_rand(1, 10));
            $product->setDateAchat(new \DateTime());
            $product->setDateGarantie(new \DateTime('@'.strtotime('+1 year')));
            $product->setPrix(mt_rand(10, 100));
            $product->setPhoto('ticket.jpg');
            $manager->persist($product);
        }

        $manager->flush();
    }
}