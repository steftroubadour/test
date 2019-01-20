<?php
// src/AppBundle/DataFixtures/LoadProduct.php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadProduct extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $names = array(
            'cat',
            'crow',
            'dog',
            'dove',
            'dragon',
            'fish',
            'frog',
            'hippo',
            'horse',
            );

        foreach ($names as $name) {

            $image = new Image();
            $image->setUrl('bundles/app/images/' . $name . '-solid.svg');
            $image->setAlt($name);

            $product = new Product();
            $product->setImage($image);
            $product->setTitle( "Icône '" . $name . "'" );
            $product->setPrice(round(rand(200, 1000)/100,2));
            $product->setDescription(
                "Cet icône '". $name . "' est unique, il faut l'acheter!\r\n"
                ."Cum autem commodis intervallata temporibus convivia longa et noxia coeperint apparari vel distributio sollemnium sportularum, anxia deliberatione tractatur an exceptis his quibus vicissitudo debetur, peregrinum invitari conveniet, et si digesto plene consilio id placuerit fieri, is adhibetur qui pro domibus excubat aurigarum aut artem tesserariam profitetur aut secretiora quaedam se nosse confingit."
            );

            $manager->persist($product);
        }

        $manager->flush();
    }
}