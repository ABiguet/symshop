<?php

namespace App\Controller;

use App\Taxes\Calculator;
use App\Taxes\Detector;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController extends AbstractController
{
    protected $MyLogger;
    protected $calculator;

    public function __construct(LoggerInterface $logger, Calculator $calculator)
    {
        $this->MyLogger = $logger;
        $this->calculator = $calculator;
    }

    /**
     * @Route(
     *     "/hello/{prenom<\w+>?world}",
     *     name="hello")
     */
    public function hello($prenom, Slugify $slug, Environment $twig, Detector $detector): Response
    {
        dump($detector->detect(101));
        dump($detector->detect(10));

        dump($twig);

        dump($slug->slugify("Aurélien Biguet"));

        $this->MyLogger->info("Mon message de log");
        dump($tva = $this->calculator->calcul(100));
        return new Response("Hello $prenom");
    }

    /**
     * @Route(
     *     "/twig/{prenom<\w+>?world}",
     *     name="twig")
     */
    public function twig($prenom)
    {
        return $this->render('hello.html.twig', ['prenom' => $prenom]);
    }

    /**
     * @Route(
     *     "example/",
     *     name="example"
     * )
     */
    public function example()
    {
        return $this->render("example.html.twig", ['age' => 36]);
    }
}
