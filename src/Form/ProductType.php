<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\DataTransformer\CentimesTransformer;
use App\Form\Type\PriceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => ['placeholder' => 'Tapez le nom du produit']
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Courte description du produit',
                'attr' => [
                    'placeholder' => 'Tapez une description courte du produit'
                ]
            ])
            ->add('price', MoneyType::class, [
                'divisor' => 100,
                'label' => 'Prix du produit ',
                'attr' => [
                    'placeholder' => 'Tapez le prix du produit'
                ]
            ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'URL de l\'image ',
                'attr' => [
                    'placeholder' => 'URL de l\'image'
                ]
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return strtoupper($category->getName());
                },
                'choice_value' => 'id'
            ]);


        /**
         * Pour l'exemple
         * 
         */
        // $builder->get('price')->addModelTransformer(new CentimesTransformer);
        // $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
        //     /** @var Product */
        //     $product = $event->getData();
        //     dump($product->getPrice());
        //     if ($product->getPrice() !== null) {
        //         $product->setPrice($product->getPrice() * 100);
        //     }
        //     dd($product);
        // });
        // $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
        //     $form = $event->getForm();

        //     /** @var Product */
        //     $product = $event->getData();
        //     if ($product->getPrice() !== null) {
        //         $product->setPrice($product->getPrice() / 100);
        //     }

        //     // if ($product->getId() === NULL) {

        //     //     $form->add('category', EntityType::class, [
        //     //         'label' => 'Catégorie',
        //     //         'placeholder' => '-- Choisir une catégorie --',
        //     //         'class' => Category::class,
        //     //         'choice_label' => function (Category $category) {
        //     //             return strtoupper($category->getName());
        //     //         },
        //     //         'choice_value' => 'id'
        //     //     ]);
        //     // }
        // });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
