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
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category", priority=-1)
     */
    #[Route('/{slug}', name: 'product_category', priority: -1)]
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
    /**
     * @Route("/{slug_category}/{slug}", name="product_show")
     */
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
    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     */
    public function edit($id, ProductRepository $productRepository, SluggerInterface $slugger, EntityManagerInterface $em, Request $request, ValidatorInterface $validator)
    {
        // /**
        //  * Présentation du validator
        //  * Tout ce qui est commenté est en exemple
        //  */

        // $age = 36;

        // $client = [
        //     'nom' => 'Biguet',
        //     'prenom' => 'Aurélien',
        //     'voiture' => [
        //         'marque' => '',
        //         'couleur' => ''
        //     ]
        // ];
        // $collection = new Collection([
        //     'nom' => new NotBlank(['message' => 'Le nom ne doit pas être vide']),
        //     'prenom' => [
        //         new NotBlank(['message' => 'Le nom ne doit pas être vide']),
        //         new Length(['min' => 3, 'minMessage' => "Le prénom ne doit pas faire moins de 3 caractère"])
        //     ],
        //     'voiture' => new Collection([
        //         'marque' => new NotBlank(['message' => "La marque de la voiture est obligatoire"]),
        //         'couleur' => new NotBlank(['message' => "La couleur de la voiture est obligatoire"]),
        //     ])
        // ]);

        // $product = new Product;

        // $product->setName("to");
        // $product->setPrice(2000);
        // $result = $validator->validate($product);

        // if ($result->count() > 0) dd("Il y a des erreur ", $result);
        // else dd("Tout va bien", $result);

        $product = $productRepository->find($id);
        $form = $this->createForm(ProductType::class, $product);
        //$form->setData($product); -> equivalent a passer $product dans le create form

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $em->flush();

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
    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create(FormFactoryInterface $factory, Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        // $builder = $factory->createBuilder(ProductType::class);
        // $form = $builder->getForm();
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
