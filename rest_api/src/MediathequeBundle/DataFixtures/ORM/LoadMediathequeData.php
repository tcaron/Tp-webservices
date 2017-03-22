<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use MediathequeBundle\Entity\Members;
use MediathequeBundle\Entity\Books;
use MediathequeBundle\Entity\Borrowing;

class LoadMediathequeData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = new Members();
        $user->setName('Thomas');
        $manager->persist($user);

        $user1 = new Members();
        $user1->setName('Jean');
        $manager->persist($user1);

        $user2 = new Members();
        $user2->setName('Marie');
        $manager->persist($user2);

        $user3 = new Members();
        $user3->setName('Anne');
        $manager->persist($user3);

        $user4 = new Members();
        $user4->setName('Jeanne');
        $manager->persist($user4);

        $user5 = new Members();
        $user5->setName('Robert');
        $manager->persist($user5);

        $book = new Books();
        $book->setName('le seigneur des anneaux, le retour du roi');
        $book->setCategory('fantastique');
        $manager->persist($book);

        $book1 = new Books();
        $book1->setName('metro 2033');
        $book1->setCategory('science fiction');
        $manager->persist($book1);

        $book2 = new Books();
        $book2->setName('Bel ami');
        $book2->setCategory('literature');
        $manager->persist($book2);

        $book3 = new Books();
        $book3->setName('Père Goriot');
        $book3->setCategory('literature');
        $manager->persist($book3);

        $book4 = new Books();
        $book4->setName('Candide');
        $book4->setCategory('literature');
        $manager->persist($book4);

        $book5 = new Books();
        $book5->setName('Berserk');
        $book5->setCategory('Manga');
        $manager->persist($book5);

        $manager->flush();

        $borrowing = new Borrowing();
        $borrowing->setMember($user);
        $borrowing->setBook($book);
        $manager->persist($borrowing);


        $borrowing = new Borrowing();
        $borrowing->setMember($user);
        $borrowing->setBook($book5);
        $manager->persist($borrowing);

        $borrowing1 = new Borrowing();
        $borrowing1->setMember($user2);
        $borrowing1->setBook($book3);
        $manager->persist($borrowing1);

        $manager->flush();

    }
}