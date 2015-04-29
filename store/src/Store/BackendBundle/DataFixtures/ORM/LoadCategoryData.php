<?php

namespace Store\BackendBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Store\BackendBundle\Entity\Category;
use Store\BackendBundle\Entity\Product;
use Store\BackendBundle\Entity\Jeweler;


/**
 * Cette classe me permettra de charger des catégories en base de données
 * Class LoadCategoryData
 * @package Store\BackendBundle\DataFixtures\ORM
 */
class LoadCategoryData implements FixtureInterface
{
    /**
     * Cette méthode me permettra de charger mes données (catégories)
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {

        $jeweler = new Jeweler();

        $manager->persist($jeweler);

        // Ma 1ère catégorie
        $categorie = new Category();
        $categorie->setTitle('Colliers magnifiques');
        $categorie->setDescription('Jolie description de vos magnifiques colliers');

        $manager->persist($categorie);

        // Ma 2ème catégorie
        $categorie2 = new Category();
        $categorie2->setTitle('Bracelets glamours');
        $categorie2->setDescription('Jolie description complète de vos bracelets glamours');

        $manager->persist($categorie2);

        // Mon 1er produit
        $product = new Product();
        $product->addCategory($categorie);
        $product->setTitle('Collier Azur Été');
        $product->setDescription('Collier composé de perles nacrées avec vernissage et finition de pierres Swarovski');
        $product->setComposition('Perles nacrées, pierres précieuses');
        $product->setActive(true);
        $product->setCover(true);
        $product->setJeweler($jeweler);

        $manager->persist($product);

        $manager->flush();
    }

}