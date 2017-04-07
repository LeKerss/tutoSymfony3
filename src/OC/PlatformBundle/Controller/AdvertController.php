<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\PlatformBundle\Entity\Advert;

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

        //TODO fetch advert at id $id
        $advert = array(
            'title'     => 'Recherche développeur Symfony2',
            'id'        => $id,
            'author'    => 'Alexandre',
            'content'   => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla...',
            'date'      => new \DateTime()
        );

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert'    => $advert
        ));
    }

    /**
     * Add new advert
     */
    public function addAction(Request $request) {

    //TODO display form template
    //
        //Creating advert object
        $advert = new Advert();
        $advert->setTitle('Recherche développeur Symfony.');
        $advert->setAuthor("Jean-Louis");
        $advert->setContent("Nous recherchons un développeur Symfony débutant sur Paname. bla bla..");

        // Getting entity manager
        $em = $this->getDoctrine()->getManager();

        // persisting our advert object
        $em->persist($advert);

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