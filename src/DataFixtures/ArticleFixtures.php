<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\LieuAchat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 300; $i++) {
            $product = new Article();
            $product->setNom('product n°'.$i);
            $product->setLieuAchat($manager->find(LieuAchat::class, mt_rand(161, 220)));
            $product->setCategorie($manager->find(Categorie::class, mt_rand(150, 189)));
            $product->setDateAchat(new \DateTime());
            $product->setDateGarantie(new \DateTime('@'.strtotime('+1 year')));
            $product->setPrix(mt_rand(100, 10000));
            $product->setPhoto('ticket.jpg');
            $manager->persist($product);
        }

        $manager->flush();
    }
}