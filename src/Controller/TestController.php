<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    /**
     * @Route ("/test", name="index")
     */
    public function index()
    {
        dd("Ca fonctionne");
    }

    /**
     * @Route (
     *     "/test/{age<\d+>?0}",
     *     name="test",
     *     methods={"GET", "POST"},
     *     host="127.0.0.1",
     *     schemes={"https"})
     */
    public function test(Request $request, $age): Response
    {
        dump($request);
        return new Response("Vous avez $age ans");
    }
}
