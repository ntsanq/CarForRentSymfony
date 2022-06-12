<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *      "/default",
 *     name = "default_",
 *     requirements={"language": "en|es|fr"}
 * )
 */
class DefaultController extends AbstractController
{
    /**
     * @Route(
     *     "/{token}",
     *     name = "show_token",
     *     requirements={"token"=".+"},
     *     priority=0
     * )
     */
    public function showToken($token): Response
    {
        //in here your token can include '/'
        return new Response("Token: $token");
    }

    /**
     * @Route (
     *     "/language/{language}",
     *     name="language",
     *     priority=1
     * )
     */
    public function showLanguage($language): Response
    {
        return new Response("Language: $language");
    }

    /**
     * @Route("/attributes",
     *     name="attributes",
     *     defaults={"page": 1, "title": "Hello world!"},
     *     priority=1
     * )
     */
    public function showAttributes(Request $request): Response
    {
        $routeName = $request->attributes->get('_route');
        $allAttributes = $request->attributes->all();
        return new Response("Title attribute: ". $allAttributes['title']);
    }

    /**
     * @Route (
     *     "/generate",
     *     name = "generate",
     *      priority =1
     * )
     */
    public function createUrl()
    {
        $url = $this->generateUrl('default_language', ['language' => 'fr']);
        dd($url);
    }

}