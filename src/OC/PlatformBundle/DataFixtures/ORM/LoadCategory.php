<?php

namespace OC\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Category;

class LoadCategory implements FixtureInterface
{
	// In the load method parameter, the $manager object is our EntityManager
	public function load(ObjectManager $manager)
	{
		// List of categories to add
		$names = array(
			'Développement web',
			'Développement mobile',
			'Graphisme',
			'Intégration',
			'Réseau'
		);

		foreach ($names as $name) {
			// Creating Category
			$category = new Category();
			$category -> setName($name);

			//persist it
			$manager->persist($category);
		}

		// Save changes
		$manager->flush();
	}



}



?>