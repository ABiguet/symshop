<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Bezhanov\Faker\Provider\Commerce;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Liior\Faker\Prices;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $encoder;
    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $encoder)
    {
        $this->slugger = $slugger;
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Prices($faker));
        $faker->addProvider(new Commerce($faker));
        $faker->addProvider(new PicsumPhotosProvider($faker));

        for ($c = 0; $c < 4; $c++) {
            $category = new Category;
            $category->setName($faker->department(2))
                ->setSlug(strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);

            for ($p = 0; $p < mt_rand(15, 20); $p++) {
                $product = new Product;
                $product->setName($faker->productName())
                    ->setPrice($faker->price(1000, 20000))
                    ->setSlug(strtolower($this->slugger->slug($product->getName())))
                    ->setCategory($category)
                    ->setShortDescription($faker->paragraph())
                    ->setMainPicture($faker->imageUrl(400, 400, true));

                $manager->persist($product);
            }
        }

        $admin = new User;

        $admin->setEmail('admin@gmail.com')
            ->setFirstName('Aurélien')
            ->setLastName('Biguet')
            ->setPassword($this->encoder->encodePassword($admin, '031284'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $user = new User;
        $user->setEmail('user@gmail.com')
            ->setFirstName('Aurélien')
            ->setLastName('Biguet')
            ->setPassword($this->encoder->encodePassword($admin, '031284'));

        $manager->persist($user);

        for ($u = 0; $u < 10; $u++) {
            $user = new User;
            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setPassword($this->encoder->encodePassword($user, 'password'));

            $email = strtolower($this->slugger->slug($user->getFirstName()) . '.' . $this->slugger->slug($user->getLastName()) . '@' . $faker->freeEmailDomain());
            $user->setEmail($email);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
