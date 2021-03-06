<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\FAQ;
use App\Entity\FAQCategory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private const CATEGORY_NUM = 8;

    private $passwordEncoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadFAQCategories($manager);
        $this->loadFAQs($manager);
    }

    private function loadUsers(ObjectManager $em): void
    {
        for($i = 1; $i <= 9; $i++) {
            $user = new User();
            $user->setUsername('user_name' . $i);
            $user->setName($this->faker->firstName);
            $user->setSurname($this->faker->lastName);
            $user->setEmail('user' . $i . '@gmail.com');
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'));

            $em->persist($user);
        }

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setName($this->faker->firstName);
        $admin->setSurname($this->faker->lastName);
        $admin->setEmail('admin@gmail.com');
        $admin->setPassword($this->passwordEncoder->encodePassword($user, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);

        $em->persist($admin);

        $em->flush();
    }

    private function loadFAQCategories(ObjectManager $em): void
    {
        $position = 0;
        for($i = 0; $i < static::CATEGORY_NUM; $i++) {
            $faqCategory = new FAQCategory();
            $faqCategory->setName($this->faker->city);
            $faqCategory->setPosition($position);
            $position++;

            $this->addReference('faqCategory' . $i, $faqCategory);

            $em->persist($faqCategory);
        }

        $em->flush();
    }

    private function loadFAQs(ObjectManager $em): void
    {
        $position = 0;
        for($i = 0; $i < static::CATEGORY_NUM; $i++) {
            for($j = 0; $j < random_int(5, 20); $j++) {
                $faq = new FAQ();
                $faq->setQuestion($this->faker->text(150));
                $faq->setAnswer($this->faker->text(250));
                $faq->setPosition($position);
                $position++;

                $faq->setCategory($this->getReference('faqCategory' . $i));

                $em->persist($faq);
            }
        }

        $em->flush();
    }
}
