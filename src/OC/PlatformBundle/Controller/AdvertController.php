<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;
use OC\PlatformBundle\Entity\Image;
use OC\PlatformBundle\Entity\Application;
use OC\PlatformBundle\Entity\Skill;
use OC\PlatformBundle\Entity\AdvertSkill;

class AdvertController extends Controller {

    /**
     * Index page : Advert list
     */
    public function indexAction($page) {
        if($page < 1) {
            // TODO edit custom 404 page
            $page = 1;
        }

        // TODO fetch adverts from database
        $listAdverts = array(
            array(
                'title'   => 'Recherche développpeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Offre de stage webdesigner',
                'id'      => 3,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date'    => new \Datetime())
        );

        // Et modifiez le 2nd argument pour injecter notre liste
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts
        ));
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => array()
        ));
	}

    /**
     *
     * Display advert based on id
     */
    public function viewAction($id) {

        //Get doctrine entity manager
        $em = $this->getDoctrine()->getManager();

        //Get repository from doctrine manager
        $repository = $em
            ->getRepository('OCPlatformBundle:Advert')
            ;

        //Fetch advert at id $id
        $advert = $repository->find($id);

        //If advert at id $id is not found
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce que vous recherchez (".$id.") n'existe pas.");
        }

        //Fetch applications for this advert
        $listApplications = $em
            ->getRepository('OCPlatformBundle:Application')
            ->findBy(array('advert' => $advert));

        $listAdvertSkills = $em
            ->getRepository('OCPlatformBundle:AdvertSkill')
            ->findBy(array('advert' => $advert));

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert'    => $advert,
            'listApplications' => $listApplications,
            'listAdvertSkills' => $listAdvertSkills
        ));
    }

    /**
     * Add new advert
     */
    public function addAction(Request $request) {

    //TODO display form template


        // Getting entity manager
        $em = $this->getDoctrine()->getManager();

        //Creating advert object
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor("Jean-Louis");
        $advert->setContent("Nous recherchons un développeur Symfony débutant sur Paname. bla bla..");
        
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');

            // On récupère toutes les compétences possibles
        $listSkills = $em->getRepository('OCPlatformBundle:Skill')->findAll();

        // Pour chaque compétence
        foreach ($listSkills as $skill) {
            // On crée une nouvelle « relation entre 1 annonce et 1 compétence »
            $advertSkill = new AdvertSkill();

            // On la lie à l'annonce, qui est ici toujours la même
            $advertSkill->setAdvert($advert);
            // On la lie à la compétence, qui change ici dans la boucle foreach
            $advertSkill->setSkill($skill);

            // Arbitrairement, on dit que chaque compétence est requise au niveau 'Expert'
            $advertSkill->setLevel('Expert');

            // Et bien sûr, on persiste cette entité de relation, propriétaire des deux autres relations
            $em->persist($advertSkill);
        }



        $advert->setImage($image);

        //Creating an application
        $app1 = new Application();
        $app1->setAuthor('Hamid');
        $app1->setContent("J'ai toutes les qualités requises");

        //...And another
        $app2 = new Application();
        $app2->setAuthor('Julouis');
        $app2->setContent("Euh Jamel ?");

        //Binding them to our advert
        $app1->setAdvert($advert);
        $app2->setAdvert($advert);

        // Doctrine ne connait pas encore l'entité $advert. Si vous n'avez pas défini la relation AdvertSkill
        // avec un cascade persist (ce qui est le cas si vous avez utilisé mon code), alors on doit persister $advert
        $em->persist($advert);

        $em->persist($app1);
        $em->persist($app2);

        // Flushing what we have persisted earlier
        $em->flush();

        // Resuming as previous version
        if($request->isMethod('POST')) {
            $request
                ->getSession()
                ->getFlashBag()
                ->add('notice', 'Annonce bien enregistrée');

            return $this->redirectToRoute('oc_platform_view', array(
                'id' => $advert.getId()
            ));
        }

        return $this->render('OCPlatformBundle:Advert:add.html.twig', array('advert' => $advert));
    }

	/**
     * Edit advert at id = $id
     */
	public function editAction($id, Request $request) {

        $em = $this->getDoctrine()->getManager();

        //fetch advert at $id
        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);
        
        if (null === $advert) {
          throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        $listCategories = $em->getRepository('OCPlatformBundle:Category')->findAll();

        foreach($listCategories as $category) {
            $advert -> addCategory ($category);
        }

        $em->flush();

	    if($request->isMethod('POST')) {
	        $request
                ->getSession()
                ->getFlashBag()
                ->add('notice', 'Modifications enregistrées.');
	        return $this->redirectToRoute('oc_platform_view', array(
	            'id' => $request
                    ->query
                    ->get('id')
            ));
        }
        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert' => array(
                'id' => 1
            )
        ));
    }

    /**
     * Delete advert at id = $id
     */
    public function deleteAction($id) {
        // TODO get advert with id = $id

        $em = $this->getDoctrine()->getManager();


        $advert = $em->getRepository('OCPlatformBundle:Advert')->find($id);

        //If advert at id $id is not found
        if (null === $advert) {
            throw new NotFoundHttpException("L'annonce que vous recherchez (".$id.") n'existe pas.");
        }

        foreach($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }

        $em->flush();

        // TODO manage advert deletion

        return $this->render('OCPlatformBundle:Advert:delete.html.twig');
    }

    /**
     * Display advert menu
     */
    public function menuAction() {
        //TODO fetch advert list from database
        $listAdverts = array(
            array('id' => 2, 'title' => 'Recherche développeur Symfony'),
            array('id' => 5, 'title' => 'Mission Webmaster'),
            array('id' => 4, 'title' => 'Offre de stage')
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            'listAdverts' => $listAdverts
        ));
    }

}