<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Users
 *
 * @ORM\Table(name="fos_users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsersRepository")
 */
class Users extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Parties", mappedBy="users1")
     */
    private $parties_1;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Parties", mappedBy="users2")
     */
    private $parties_2;

    private $parties;


    /**
     * @var int
     *
     * @ORM\Column(name="partie_gagnee", type="smallint", nullable=true)
     */
    private $partie_gagnee;

    /**
     * @var int
     *
     * @ORM\Column(name="partie_perdue", type="smallint", nullable=true)
     */
    private $partie_perdue;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="bigint", nullable=true)
     */
    private $score;

    public function __construct()
    {
        parent::__construct();
        $this->parties_1 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parties_2 = new \Doctrine\Common\Collections\ArrayCollection();
        $this->parties = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getParties()
    {
        if (!is_null($this->parties_1) || !is_null($this->parties_2)){
            if (!is_null($this->parties_1)){
                $this->parties[] = $this->parties_1;
            }
            if (!is_null($this->parties_2)){
                $this->parties[] = $this->parties_2;
            }
            return $this->parties;
        }

    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add party
     *
     * @param \AppBundle\Entity\Parties $party
     *
     * @return Users
     */
    public function addParty(\AppBundle\Entity\Parties $party)
    {
        $this->parties[] = $party;

        return $this;
    }

    /**
     * Remove party
     *
     * @param \AppBundle\Entity\Parties $party
     */
    public function removeParty(\AppBundle\Entity\Parties $party)
    {
        $this->parties->removeElement($party);
    }

    /**
     * Add parties1
     *
     * @param \AppBundle\Entity\Parties $parties1
     *
     * @return Users
     */
    public function addParties1(\AppBundle\Entity\Parties $parties1)
    {
        $this->parties_1[] = $parties1;

        return $this;
    }

    /**
     * Remove parties1
     *
     * @param \AppBundle\Entity\Parties $parties1
     */
    public function removeParties1(\AppBundle\Entity\Parties $parties1)
    {
        $this->parties_1->removeElement($parties1);
    }

    /**
     * Get parties1
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParties1()
    {
        return $this->parties_1;
    }

    /**
     * Add parties2
     *
     * @param \AppBundle\Entity\Parties $parties2
     *
     * @return Users
     */
    public function addParties2(\AppBundle\Entity\Parties $parties2)
    {
        $this->parties_2[] = $parties2;

        return $this;
    }

    /**
     * Remove parties2
     *
     * @param \AppBundle\Entity\Parties $parties2
     */
    public function removeParties2(\AppBundle\Entity\Parties $parties2)
    {
        $this->parties_2->removeElement($parties2);
    }

    /**
     * Get parties2
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParties2()
    {
        return $this->parties_2;
    }

    /**
     * Add chat
     *
     * @param \AppBundle\Entity\Chats $chat
     *
     * @return Users
     */
    public function addChat(\AppBundle\Entity\Chats $chat)
    {
        $this->chats[] = $chat;

        return $this;
    }

    /**
     * Remove chat
     *
     * @param \AppBundle\Entity\Chats $chat
     */
    public function removeChat(\AppBundle\Entity\Chats $chat)
    {
        $this->chats->removeElement($chat);
    }

    /**
     * Get chats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChats()
    {
        return $this->chats;
    }

    /**
     * Set partieGagnee
     *
     * @param integer $partieGagnee
     *
     * @return Users
     */
    public function setPartieGagnee($partieGagnee)
    {
        $this->partie_gagnee = $partieGagnee;

        return $this;
    }

    /**
     * Get partieGagnee
     *
     * @return integer
     */
    public function getPartieGagnee()
    {
        return $this->partie_gagnee;
    }

    /**
     * Set partiePerdue
     *
     * @param integer $partiePerdue
     *
     * @return Users
     */
    public function setPartiePerdue($partiePerdue)
    {
        $this->partie_perdue = $partiePerdue;

        return $this;
    }

    /**
     * Get partiePerdue
     *
     * @return integer
     */
    public function getPartiePerdue()
    {
        return $this->partie_perdue;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return Users
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return integer
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Add tour
     *
     * @param \AppBundle\Entity\Parties $tour
     *
     * @return Users
     */
    public function addTour(\AppBundle\Entity\Parties $tour)
    {
        $this->tour[] = $tour;

        return $this;
    }

    /**
     * Remove tour
     *
     * @param \AppBundle\Entity\Parties $tour
     */
    public function removeTour(\AppBundle\Entity\Parties $tour)
    {
        $this->tour->removeElement($tour);
    }

    /**
     * Get tour
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTour()
    {
        return $this->tour;
    }
}
