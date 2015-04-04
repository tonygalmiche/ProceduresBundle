<?php

namespace OVE\ProceduresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * statistiques
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OVE\ProceduresBundle\Entity\statistiquesRepository")
 */
class statistiques
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="idprocedure", type="integer")
     */
    private $idprocedure;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;


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
     * Set idprocedure
     *
     * @param integer $idprocedure
     * @return statistiques
     */
    public function setIdprocedure($idprocedure)
    {
        $this->idprocedure = $idprocedure;

        return $this;
    }

    /**
     * Get idprocedure
     *
     * @return integer 
     */
    public function getIdprocedure()
    {
        return $this->idprocedure;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return statistiques
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return statistiques
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }
}
