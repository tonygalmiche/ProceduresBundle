<?php

namespace OVE\ProceduresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * domaines
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OVE\ProceduresBundle\Entity\domainesRepository")
 */
class domaines
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
     * @ORM\Column(name="termeid", type="integer")
     */
    private $termeid;

    /**
     * @var string
     *
     * @ORM\Column(name="responsable", type="string", length=255)
     */
    private $responsable;


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
     * Set termeid
     *
     * @param integer $termeid
     * @return domaines
     */
    public function setTermeid($termeid)
    {
        $this->termeid = $termeid;

        return $this;
    }

    /**
     * Get termeid
     *
     * @return integer 
     */
    public function getTermeid()
    {
        return $this->termeid;
    }

    /**
     * Set responsable
     *
     * @param string $responsable
     * @return domaines
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;

        return $this;
    }

    /**
     * Get responsable
     *
     * @return string 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }
}
