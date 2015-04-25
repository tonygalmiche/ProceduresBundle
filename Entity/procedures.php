<?php

namespace OVE\ProceduresBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * procedures
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="OVE\ProceduresBundle\Entity\proceduresRepository")
 */
class procedures
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
     * @ORM\Column(name="initid", type="integer", nullable=true)
     */
    private $initid;



    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=30, nullable=true)
     */
    private $etat;



    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=30, nullable=true)
     */
    private $type;



    /**
     * @var string
     *
     * @ORM\Column(name="domaineid", type="string", length=255, nullable=true)
     */
    private $domaineid;


    /**
     * @var string
     *
     * @ORM\Column(name="domaine", type="text", nullable=true)
     */
    private $domaine;




    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;





    /**
     * @var string
     *
     * @ORM\Column(name="fiche", type="string", length=20)
     */
    private $fiche;

    /**
     * @var string
     *
     * @ORM\Column(name="version", type="string", length=3, nullable=true)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="text", nullable=true)
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="terminologie", type="text", nullable=true)
     */
    private $terminologie;

    /**
     * @var integer
     *
     * @ORM\Column(name="indice", type="integer", nullable=true)
     */
    private $indice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_redaction", type="datetime", nullable=true)
     */
    private $date_redaction;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_verifie", type="datetime", nullable=true)
     */
    private $date_verifie;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_approuve", type="datetime", nullable=true)
     */
    private $date_approuve;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_application", type="date", nullable=true)
     */
    private $date_application;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_mise_a_jour", type="datetime", nullable=true)
     */
    private $date_mise_a_jour;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_redaction", type="string", length=255, nullable=true)
     */
    private $nom_redaction;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_verifie", type="string", length=255, nullable=true)
     */
    private $nom_verifie;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_approuve", type="string", length=255, nullable=true)
     */
    private $nom_approuve;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_application", type="string", length=255, nullable=true)
     */
    private $nom_application;


    /**
     * @var string
     *
     * @ORM\Column(name="objet_modification", type="string", length=255, nullable=true)
     */
    private $objet_modification;







    /**
     * @var string
     *
     * @ORM\Column(name="diagramme", type="string", length=255, nullable=true)
     */
    private $diagramme;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;



    /**
     * @var string
     *
     * @ORM\Column(name="intervenants", type="text", nullable=true)
     */
    private $intervenants;







    /**
     * @var string
     *
     * @ORM\Column(name="redacteurs", type="text", nullable=true)
     */
    private $redacteurs;


    /**
     * @var string
     *
     * @ORM\Column(name="redacteursid", type="text", nullable=true)
     */
    private $redacteursid;





    /**
     * @var string
     *
     * @ORM\Column(name="verificateurs", type="text", nullable=true)
     */
    private $verificateurs;


    /**
     * @var string
     *
     * @ORM\Column(name="verificateursid", type="text", nullable=true)
     */
    private $verificateursid;



    /**
     * @var string
     *
     * @ORM\Column(name="approbateursid", type="text", nullable=true)
     */
    private $approbateursid;


    /**
     * @var string
     *
     * @ORM\Column(name="approbateurs", type="text", nullable=true)
     */
    private $approbateurs;




    /**
     * @var string
     *
     * @ORM\Column(name="lecteurs", type="text", nullable=true)
     */
    private $lecteurs;


    /**
     * @var string
     *
     * @ORM\Column(name="lecteursid", type="text", nullable=true)
     */
    private $lecteursid;


    /**
     * @var string
     *
     * @ORM\Column(name="lecteursaccuse", type="text", nullable=true)
     */
    private $lecteursaccuse;






    /**
     * @var string
     *
     * @ORM\Column(name="mots_cles", type="text", nullable=true)
     */
    private $mots_cles;


    /**
     * @var string
     *
     * @ORM\Column(name="mots_clesid", type="text", nullable=true)
     */
    private $mots_clesid;



    /**
     * @var string
     *
     * @ORM\Column(name="pieces_jointes", type="text", nullable=true)
     */
    private $pieces_jointes;



    /**
     * @var string
     *
     * @ORM\Column(name="liens_html", type="text", nullable=true)
     */
    private $liens_html;





    public function getIntervenantsHTML() {
      $IntervenantsHTML="";
      $Intervenants=$this->getIntervenants();
      if($Intervenants!='') {
        $Intervenants=json_decode($Intervenants);
        $IntervenantsHTML.='<table width="100%" style="border-style:solid;border-width:1px;margin-top:10px">';
        $IntervenantsHTML.='<thead><tr><th width="40%" style="border-style:solid;border-width:1px">Intervenants</th><th style="border-style:solid;border-width:1px">Responsabilités</th></tr></thead>';
        $IntervenantsHTML.="<tbody>";
        foreach($Intervenants as $v) {
          if($v[0]!=NULL) {
            $IntervenantsHTML.='<tr><td style="border-style:solid;border-width:1px">'.$v[0].'</td><td style="border-style:solid;border-width:1px">'.$v[1]."</td></tr>";
          }
        }
        $IntervenantsHTML.="</tbody>";
        $IntervenantsHTML.="</table>";
      }
      return $IntervenantsHTML;
    }




    public function getEtatColor($etat) {
      $color="white";
      if($etat=="redaction")     $color="gray"; 
      //if($etat=="validation")  $color="blue";
      if($etat=="verification")  $color="blue";
      if($etat=="approbation")   $color="orange";
      if($etat=="valide")        $color="green";
      if($etat=="archive")       $color="black";
      return $color;
    }





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
     * Set 
     *
     * @param string $champ
     * @param string $value
     * @return procedures
     */
    public function set($champ, $value)
    {
        if($champ=="fiche")         $this->fiche = $value;
        if($champ=="domaine")       $this->domaine = $value;
        if($champ=="version")       $this->version = $value;
        if($champ=="objet")         $this->objet = $value;
        if($champ=="terminologie")  $this->terminologie = $value;
        if($champ=="indice")        $this->indice = $value;
        if($champ=="diagramme")     $this->diagramme = $value;
        if($champ=="description")   $this->description = $value;
        if($champ=="redacteurs")    $this->redacteurs = $value;
        if($champ=="verificateurs") $this->verificateurs = $value;
        if($champ=="approbateurs")  $this->approbateurs = $value;
        if($champ=="lecteurs")      $this->lecteurs = $value;
        return $this;
    }








    /**
     * Set domaine
     *
     * @param string $domaine
     * @return procedures
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * Get domaine
     *
     * @return string 
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * Set fiche
     *
     * @param string $fiche
     * @return procedures
     */
    public function setFiche($fiche)
    {
        $this->fiche = $fiche;

        return $this;
    }

    /**
     * Get fiche
     *
     * @return string 
     */
    public function getFiche()
    {
        return $this->fiche;
    }

    /**
     * Set version
     *
     * @param string $version
     * @return procedures
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string 
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set objet
     *
     * @param string $objet
     * @return procedures
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet
     *
     * @return string 
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set terminologie
     *
     * @param string $terminologie
     * @return procedures
     */
    public function setTerminologie($terminologie)
    {
        $this->terminologie = $terminologie;

        return $this;
    }

    /**
     * Get terminologie
     *
     * @return string 
     */
    public function getTerminologie()
    {
        return $this->terminologie;
    }

    /**
     * Set indice
     *
     * @param integer $indice
     * @return procedures
     */
    public function setIndice($indice)
    {
        $this->indice = $indice;

        return $this;
    }

    /**
     * Get indice
     *
     * @return integer 
     */
    public function getIndice()
    {
        return $this->indice;
    }

    /**
     * Set date_redaction
     *
     * @param \DateTime $dateRedaction
     * @return procedures
     */
    public function setDateRedaction($dateRedaction)
    {
        $this->date_redaction = $dateRedaction;

        return $this;
    }

    /**
     * Get date_redaction
     *
     * @return \DateTime 
     */
    public function getDateRedaction()
    {
        return $this->date_redaction;
    }

    /**
     * Set date_verifie
     *
     * @param \DateTime $dateVerifie
     * @return procedures
     */
    public function setDateVerifie($dateVerifie)
    {
        $this->date_verifie = $dateVerifie;

        return $this;
    }

    /**
     * Get date_verifie
     *
     * @return \DateTime 
     */
    public function getDateVerifie()
    {
        return $this->date_verifie;
    }

    /**
     * Set date_approuve
     *
     * @param \DateTime $dateApprouve
     * @return procedures
     */
    public function setDateApprouve($dateApprouve)
    {
        $this->date_approuve = $dateApprouve;

        return $this;
    }

    /**
     * Get date_approuve
     *
     * @return \DateTime 
     */
    public function getDateApprouve()
    {
        return $this->date_approuve;
    }

    /**
     * Set date_application
     *
     * @param \DateTime $dateApplication
     * @return procedures
     */
    public function setDateApplication($dateApplication)
    {
        $this->date_application = $dateApplication;

        return $this;
    }

    /**
     * Get date_application
     *
     * @return \DateTime 
     */
    public function getDateApplication()
    {
        return $this->date_application;
    }

    /**
     * Set date_mise_a_jour
     *
     * @param \DateTime $dateMiseAJour
     * @return procedures
     */
    public function setDateMiseAJour($dateMiseAJour)
    {
        $this->date_mise_a_jour = $dateMiseAJour;

        return $this;
    }

    /**
     * Get date_mise_a_jour
     *
     * @return \DateTime 
     */
    public function getDateMiseAJour()
    {
        return $this->date_mise_a_jour;
    }

    /**
     * Set nom_redaction
     *
     * @param string $nomRedaction
     * @return procedures
     */
    public function setNomRedaction($nomRedaction)
    {
        $this->nom_redaction = $nomRedaction;

        return $this;
    }

    /**
     * Get nom_redaction
     *
     * @return string 
     */
    public function getNomRedaction()
    {
        return $this->nom_redaction;
    }

    /**
     * Set nom_verifie
     *
     * @param string $nomVerifie
     * @return procedures
     */
    public function setNomVerifie($nomVerifie)
    {
        $this->nom_verifie = $nomVerifie;

        return $this;
    }

    /**
     * Get nom_verifie
     *
     * @return string 
     */
    public function getNomVerifie()
    {
        return $this->nom_verifie;
    }

    /**
     * Set nom_approuve
     *
     * @param string $nomApprouve
     * @return procedures
     */
    public function setNomApprouve($nomApprouve)
    {
        $this->nom_approuve = $nomApprouve;

        return $this;
    }

    /**
     * Get nom_approuve
     *
     * @return string 
     */
    public function getNomApprouve()
    {
        return $this->nom_approuve;
    }

    /**
     * Set nom_application
     *
     * @param string $nomApplication
     * @return procedures
     */
    public function setNomApplication($nomApplication)
    {
        $this->nom_application = $nomApplication;

        return $this;
    }

    /**
     * Get nom_application
     *
     * @return string 
     */
    public function getNomApplication()
    {
        return $this->nom_application;
    }

    /**
     * Set diagramme
     *
     * @param string $diagramme
     * @return procedures
     */
    public function setDiagramme($diagramme)
    {
        $this->diagramme = $diagramme;

        return $this;
    }

    /**
     * Get diagramme
     *
     * @return string 
     */
    public function getDiagramme()
    {
        return $this->diagramme;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return procedures
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set redacteurs
     *
     * @param string $redacteurs
     * @return procedures
     */
    public function setRedacteurs($redacteurs)
    {
        $this->redacteurs = $redacteurs;

        return $this;
    }

    /**
     * Get redacteurs
     *
     * @return string 
     */
    public function getRedacteurs()
    {
        return $this->redacteurs;
    }

    /**
     * Set verificateurs
     *
     * @param string $verificateurs
     * @return procedures
     */
    public function setVerificateurs($verificateurs)
    {
        $this->verificateurs = $verificateurs;

        return $this;
    }

    /**
     * Get verificateurs
     *
     * @return string 
     */
    public function getVerificateurs()
    {
        return $this->verificateurs;
    }

    /**
     * Set approbateurs
     *
     * @param string $approbateurs
     * @return procedures
     */
    public function setApprobateurs($approbateurs)
    {
        $this->approbateurs = $approbateurs;

        return $this;
    }

    /**
     * Get approbateurs
     *
     * @return string 
     */
    public function getApprobateurs()
    {
        return $this->approbateurs;
    }

    /**
     * Set lecteurs
     *
     * @param string $lecteurs
     * @return procedures
     */
    public function setLecteurs($lecteurs)
    {
        $this->lecteurs = $lecteurs;

        return $this;
    }

    /**
     * Get lecteurs
     *
     * @return string 
     */
    public function getLecteurs()
    {
        return $this->lecteurs;
    }

    /**
     * Set mots_cles
     *
     * @param string $motsCles
     * @return procedures
     */
    public function setMotsCles($motsCles)
    {
        $this->mots_cles = $motsCles;

        return $this;
    }

    /**
     * Get mots_cles
     *
     * @return string 
     */
    public function getMotsCles()
    {
        return $this->mots_cles;
    }

    /**
     * Set pieces_jointes
     *
     * @param string $piecesJointes
     * @return procedures
     */
    public function setPiecesJointes($piecesJointes)
    {
        $this->pieces_jointes = $piecesJointes;

        return $this;
    }

    /**
     * Get pieces_jointes
     *
     * @return string 
     */
    public function getPiecesJointes()
    {
        return $this->pieces_jointes;
    }

    /**
     * Set domaineid
     *
     * @param string $domaineid
     * @return procedures
     */
    public function setDomaineid($domaineid)
    {
        $this->domaineid = $domaineid;

        return $this;
    }

    /**
     * Get domaineid
     *
     * @return string 
     */
    public function getDomaineid()
    {
        return $this->domaineid;
    }

    /**
     * Set redacteursid
     *
     * @param string $redacteursid
     * @return procedures
     */
    public function setRedacteursid($redacteursid)
    {
        $this->redacteursid = $redacteursid;

        return $this;
    }

    /**
     * Get redacteursid
     *
     * @return string 
     */
    public function getRedacteursid()
    {
        return $this->redacteursid;
    }

    /**
     * Set verificateursid
     *
     * @param string $verificateursid
     * @return procedures
     */
    public function setVerificateursid($verificateursid)
    {
        $this->verificateursid = $verificateursid;

        return $this;
    }

    /**
     * Get verificateursid
     *
     * @return string 
     */
    public function getVerificateursid()
    {
        return $this->verificateursid;
    }

    /**
     * Set approbateursid
     *
     * @param string $approbateursid
     * @return procedures
     */
    public function setApprobateursid($approbateursid)
    {
        $this->approbateursid = $approbateursid;

        return $this;
    }

    /**
     * Get approbateursid
     *
     * @return string 
     */
    public function getApprobateursid()
    {
        return $this->approbateursid;
    }

    /**
     * Set lecteursid
     *
     * @param string $lecteursid
     * @return procedures
     */
    public function setLecteursid($lecteursid)
    {
        $this->lecteursid = $lecteursid;

        return $this;
    }

    /**
     * Get lecteursid
     *
     * @return string 
     */
    public function getLecteursid()
    {
        return $this->lecteursid;
    }

    /**
     * Set mots_clesid
     *
     * @param string $motsClesid
     * @return procedures
     */
    public function setMotsClesid($motsClesid)
    {
        $this->mots_clesid = $motsClesid;

        return $this;
    }

    /**
     * Get mots_clesid
     *
     * @return string 
     */
    public function getMotsClesid()
    {
        return $this->mots_clesid;
    }

    /**
     * Set objet_modification
     *
     * @param string $objetModification
     * @return procedures
     */
    public function setObjetModification($objetModification)
    {
        $this->objet_modification = $objetModification;

        return $this;
    }

    /**
     * Get objet_modification
     *
     * @return string 
     */
    public function getObjetModification()
    {
        return $this->objet_modification;
    }

    /**
     * Set initid
     *
     * @param integer $initid
     * @return procedures
     */
    public function setInitid($initid)
    {
        $this->initid = $initid;

        return $this;
    }

    /**
     * Get initid
     *
     * @return integer 
     */
    public function getInitid()
    {
        return $this->initid;
    }

    /**
     * Set etat
     *
     * @param string $etat
     * @return procedures
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string 
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return procedures
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set lecteursaccuse
     *
     * @param string $lecteursaccuse
     * @return procedures
     */
    public function setLecteursaccuse($lecteursaccuse)
    {
        $this->lecteursaccuse = $lecteursaccuse;

        return $this;
    }

    /**
     * Get lecteursaccuse
     *
     * @return string 
     */
    public function getLecteursaccuse()
    {
        return $this->lecteursaccuse;
    }

    /**
     * Set liens_html
     *
     * @param string $liensHtml
     * @return procedures
     */
    public function setLiensHtml($liensHtml)
    {
        $this->liens_html = $liensHtml;

        return $this;
    }

    /**
     * Get liens_html
     *
     * @return string 
     */
    public function getLiensHtml()
    {
        return $this->liens_html;
    }

    /**
     * Set intervenants
     *
     * @param string $intervenants
     * @return procedures
     */
    public function setIntervenants($intervenants)
    {
        $this->intervenants = $intervenants;

        return $this;
    }

    /**
     * Get intervenants
     *
     * @return string 
     */
    public function getIntervenants()
    {
        return $this->intervenants;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return procedures
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
}
