<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class HelloController
{
    protected $MyLogger;
    protected $calculator;

    public function __construct(LoggerInterface $logger, Calculator $calculator)
    {
        $this->MyLogger = $logger;
        $this->calculator = $calculator;
    }
    /**
     * @Route("/hello/{prenom<\w+>?world}", name="hello")
     */
    public function hello($prenom, Slugify $slug, Environment $twig) : Response
    {
        dump($twig);
        dump($slug->slugify("Aurélien Biguet"));
        $this->MyLogger->info("Mon log");
        dump($tva = $this->calculator->calcul(100));
        return new Response("Hello $prenom");
    }
}