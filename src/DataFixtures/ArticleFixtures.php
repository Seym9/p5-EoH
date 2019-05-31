<?php

namespace App\DataFixtures;

use App\Entity\Articles;
use App\Entity\ArticlesCategories;
use App\Entity\ArticlesComments;
use App\Entity\ForumCategories;
use App\Entity\TopicLike;
use App\Entity\Topics;
use App\Entity\TopicsComments;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Migrations\Version\Factory;

class ArticleFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create("fr_FR");
        $users = [];
        $user = new Users();
        $user->setCreatedAt(new \DateTime())
                        ->setEmail('test@test.com')
                        ->setPassword('test')
                        ->setUsername('Aymeric');
        $users[] = $user;

        for ($h = 0; $h < 20; $h++){
            $user = new Users();
            $user->setCreatedAt(new \DateTime())
                ->setEmail($faker->email)
                ->setPassword('test')
                ->setUsername($faker->name);

            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 1; $i <=3; $i++){
            $category = new ArticlesCategories();
            $category->setName($faker->sentence());

            $manager->persist($category);

            for ($j = 1; $j <= mt_rand(4, 6); $j++){
                $article = new Articles();

                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';
                $article->setName($faker->sentence())
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween('-months'))
                        ->setCategory($category)
                        ->setAuthor($user);
                $manager->persist($article);

                for ($k = 1; $k <= mt_rand(4, 10); $k++){
                    $articleComments = new ArticlesComments();

                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

                    $now = new \DateTime();
                    $days = $now->diff($article->getCreatedAt())->days;

                    $articleComments->setAuthor($user)
                                    ->setArticle($article)
                                    ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                                    ->setContent($content);
                    $manager->persist($articleComments);
                }
            }
        }
        for ($i = 1; $i <=3; $i++){
            $category = new ForumCategories();
            $category->setName($faker->sentence());

            $manager->persist($category);

            for ($j = 1; $j <= mt_rand(4, 6); $j++){
                $article = new Topics();

                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';
                $article->setName($faker->sentence())
                    ->setContent($content)
                    ->setCreatedAt($faker->dateTimeBetween('-months'))
                    ->setCategory($category)
                    ->setAuthor($user);
                $manager->persist($article);

                for ($k = 1; $k <= mt_rand(4, 10); $k++){
                    $articleComments = new TopicsComments();

                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

                    $now = new \DateTime();
                    $days = $now->diff($article->getCreatedAt())->days;

                    $articleComments->setAuthor($user)
                        ->setTopic($article)
                        ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                        ->setContent($content);
                    $manager->persist($articleComments);
                }
                for ($g = 1; $g <= mt_rand(4, 10); $g++){
                    $like = new TopicLike();
                    $like
                        ->setTopic($article)
                        ->setUser($faker->randomElement($users))
                        ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'));
                    $manager->persist($like);
                }
            }
        }

        $manager->flush();
    }
}
