<?php
/**
 * Created by PhpStorm.
 * User: Thomas
 * Date: 13/03/2017
 * Time: 13:19
 */

namespace MediathequeBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * Borrowing
 *
 * @ORM\Table(name="borrowing",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="borrowing_book_unique",columns={"book_id"})}
 *     )
 * @ORM\Entity(repositoryClass="MediathequeBundle\Repository\BorrowingRepository")
 */
class Borrowing
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Members")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     *
     * @var member
     */
    private $member;

    /**
     * @ORM\ManyToOne(targetEntity="Books")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     *
     * @var book
     */
    private $book;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set member
     *
     * @param \MediathequeBundle\Entity\Members $member
     *
     * @return Borrowing
     */
    public function setMember(\MediathequeBundle\Entity\Members $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \MediathequeBundle\Entity\Members
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * Set book
     *
     * @param \MediathequeBundle\Entity\Books $book
     *
     * @return Borrowing
     */
    public function setBook(\MediathequeBundle\Entity\Books $book = null)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \MediathequeBundle\Entity\Books
     */
    public function getBook()
    {
        return $this->book;
    }
}
