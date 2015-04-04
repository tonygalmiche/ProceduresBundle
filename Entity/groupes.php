<?php

namespace OVE\ProceduresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * groupes
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OVE\ProceduresBundle\Entity\groupesRepository")
 */
class groupes
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="membres", type="text")
     */
    private $membres;

    /**
     * @var string
     *
     * @ORM\Column(name="createur", type="string", length=255)
     */
    private $createur;


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
     * Set nom
     *
     * @param string $nom
     * @return groupes
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set membres
     *
     * @param string $membres
     * @return groupes
     */
    public function setMembres($membres)
    {
        $this->membres = $membres;

        return $this;
    }

    /**
     * Get membres
     *
     * @return string 
     */
    public function getMembres()
    {
        return $this->membres;
    }

    /**
     * Set createur
     *
     * @param string $createur
     * @return groupes
     */
    public function setCreateur($createur)
    {
        $this->createur = $createur;

        return $this;
    }

    /**
     * Get createur
     *
     * @return string 
     */
    public function getCreateur()
    {
        return $this->createur;
    }
}
