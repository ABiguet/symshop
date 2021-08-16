<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function renderMenuList()
    {
        // 1. Aller chercher les categories dans la base de donnees
        $categories = $this->categoryRepository->findAll();

        // 2. Renvoyer le rendu html sous la forme d'une response
        return $this->render('category/_menu.html.twig', ['categories' => $categories]);
    }
    #[Route('/admin/category/create', name: 'category_create')]
    public function create(Request $request, SluggerInterface $slugger): Response
    {
        $em = $this->getDoctrine()->getManager();

        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('product_category', ['slug' => $category->getSlug()]);
        }
        $formView = $form->createView();
        return $this->render('category/create.html.twig', ['formView' => $formView]);
    }

    #[Route('/admin/category/{id}/edit', name: 'category_edit')]
    public function edit($id, Request $request, CategoryRepository $categoryRepository, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $category = $categoryRepository->find($id);
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->flush();
            return $this->redirectToRoute('product_category', ['slug' => $category->getSlug()]);
        }

        return $this->render('category/edit.html.twig', ['formView' => $form->createView()]);
    }
}
