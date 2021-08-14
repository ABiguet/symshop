<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    #[Route('/{slug}', name: 'product_category')]
    public function category($slug, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            //throw new NotFoundHttpException("La catégorie n'existe pas");
            throw $this->createNotFoundException("La catégorie demandée n'existe pas");
        }

        return $this->render('product/category.html.twig', [
            'category' => $category
        ]);
    }

    #[Route('/{slug_category}/{slug}', name: 'product_show')]
    public function show($slug_category, $slug, ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug_category
        ]);

        $product = $productRepository->findOneBy(
            ['slug' => $slug, 'category' => $category->getId()]
        );

        if (!$product) {
            //throw new NotFoundHttpException("La catégorie n'existe pas");
            throw $this->createNotFoundException("Le produit demandé n'existe pas");
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/admin/product/{id}/edit', name: 'product_edit')]
    public function edit($id, ProductRepository $productRepository, SluggerInterface $slugger, EntityManagerInterface $em, Request $request, UrlGeneratorInterface $urlGeneratorInterface)
    {
        $product = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        //$form->setData($product); -> equivalent a passer $prduct dans le create form

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $em->flush();

            // $url = $urlGeneratorInterface->generate('product_show', [
            //     'slug_category' => $product->getCategory()->getSlug(),
            //     'slug' => $product->getSlug()
            // ]);

            // $response = new RedirectResponse($url);
            // return $response;

            return $this->redirectToRoute('product_show', [
                'slug_category' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();
        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView
        ]);
    }

    #[Route('/admin/product/create', name: 'product_create')]
    public function create(FormFactoryInterface $factory, Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        // $builder = $factory->createBuilder(ProductType::class);
        // $form = $builder->getForm();
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            // $product = new Product;
            // $product->setName($data['name'])
            //     ->setPrice($data['price'])
            //     ->setShortDescription($data['shortDescription'])
            //     ->setCategory($data['category']);
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_show', [
                'slug_category' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }
}
