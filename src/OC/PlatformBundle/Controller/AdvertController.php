<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller {

    /**
     * Index page : Advert list
     */
    public function indexAction($page) {
        if(page < 1) {
            // TODO edit custom 404 page
            throw new NotFoundHttpException('Page n°"'.$page.'" inexistante.');
        }

        // TODO fetch adverts

        return this->render('OCPlatformBundle:Advert:index.html.twig');
	}

    /**
     *
     * Display advert based on id
     */
    public function viewAction($id) {

        //TODO fetch advert at id $id

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'id' => $id
        ));
    }

    /**
     * Add new advert
     */
    public function addAction(Request $request) {

        //TODO display form template

        if($request->isMethod('POST')) {
            // TODO add advert and get new advert id
            $newId = 1;

            //TODO update view

            $request
                ->getSession()
                ->getFlashBag()
                ->add('notice', 'Annonce bien enregistrée');

            return $this->redirectToRoute('OCPlatformBundle:Advert:view.html.twig', array(
                'id' => $newId
            ));
        }
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
            'id' => $id
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

}