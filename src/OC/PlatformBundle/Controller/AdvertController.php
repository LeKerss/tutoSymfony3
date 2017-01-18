<?php


namespace OC\PlatformBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdvertController extends Controller{

    public function viewSlugAction($slug, $year, $_format) {
        return new Response(
            "L'annonce correspondant au slug '".$slug."', créée en ".$year." et au format ".$_format."."
        );
    }

    public function viewAction($id, Request $request) {
        //Generating redirection url
        return $this->redirectToRoute('oc_platform_home');
/*        //Get 'tag' parameter from the request
        $tag = $request->query->get('tag');

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'id'=>$id,
            'tag'=>$tag
        ));*/
    }

	public function indexAction()	{
		$content = $this
		->get('templating')
		->render('OCPlatformBundle:Advert:index.html.twig', array('nom' => 'Kersa'));
		return new response($content);
	}

}