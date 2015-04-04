<?php

namespace OVE\ProceduresBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use \Symfony\Component\HttpFoundation\Response;
use \Symfony\Component\HttpFoundation\JsonResponse;

use OVE\ProceduresBundle\Controller\ldap;

use OVE\ProceduresBundle\Entity\groupes;
use OVE\ProceduresBundle\Entity\domaines;


/**
 * webservice controller.
 *
 * @Route("/ajax")
 */
class ajaxController extends Controller
{


    /**
     * Set 
     *
     * @Route("/select_ldap", name="select_ldap")
     * @Method("GET")
     * @Template()
     */
    public function SelectLdapAction() {
      $q = $this->getRequest()->query->get('q');
      $em = $this->getDoctrine()->getManager();
      $ldap = new ldap($em);
      $results=$ldap->getNames($q);
      $r=array(
        "more"    => false,
        "results" => $results,
        "err"     => "",
      );
      $r = json_encode($r);        
      return new Response($r);
    }



    /**
     * Set 
     *
     * @Route("/select_trame", name="select_trame")
     * @Method("GET")
     * @Template()
     */
    public function SelectTrameAction() {
      $q = $this->getRequest()->query->get('q');
      $em = $this->getDoctrine()->getManager();
      $trames=$em->createQuery("SELECT t FROM OVEProceduresBundle:trames t WHERE t.nom like '%".$q."%' ORDER BY t.nom ASC")->getResult();
      $results=array();
      foreach($trames as $v) {
        $results[]=array("id"=>$v->getId(),"text"=>$v->getNom());
      }
      $r=array(
        "more"    => false,
        "results" => $results,
        "err"     => "",
      );
      $r = json_encode($r);        
      return new Response($r);
    }





    /**
     * Set 
     *
     * @Route("/ajout_groupe", name="ajout_groupe")
     * @Method("GET")
     * @Template()
     */
    public function AjoutGroupeAction() {
      $q = $this->getRequest()->query->get('q');
      $em = $this->getDoctrine()->getManager();

      //$repository = $em->getRepository('OVEProceduresBundle:Groupes');

      $groupes=$em->createQuery("SELECT g FROM OVEProceduresBundle:groupes g WHERE g.nom like '%".$q."%' ORDER BY g.nom ASC")->getResult();
      $results=array();
      foreach($groupes as $v) {
        $results[]=array("id"=>$v->getId(),"text"=>$v->getNom());
      }

      if(strlen($q)>4) $results[]=array("id"=>0,"text"=>"Créer '$q'");


      $r=array(
        "more"    => false,
        "results" => $results,
        "err"     => "",
      );
      $r = json_encode($r);        
      return new Response($r);
    }




    /**
     * Set 
     *
     * @Route("/creer_groupe", name="creer_groupe")
     * @Method("GET")
     * @Template()
     */
    public function CreerGroupeAction() {
      $groupe  = $this->getRequest()->query->get('groupe');
      $membres = $this->getRequest()->query->get('membres');
      $groupe=substr($groupe, 8,strlen($groupe)-9);
      $em = $this->getDoctrine()->getManager();
      $entity = new groupes;
      $entity->setNom($groupe);
      $entity->setMembres($membres);
      $user = $this->getUser();
      $entity->setCreateur($user->getUserName());
      $em->persist($entity);
      $em->flush();
      $r=array(
        "err"     => "",
      );
      $r = json_encode($r);        
      return new Response($r);
    }



    /**
     * Set 
     *
     * @Route("/get_groupe", name="get_groupe")
     * @Method("GET")
     * @Template()
     */
    public function GetGroupeAction() {
      $id  = $this->getRequest()->query->get('id');
      $membres = $this->getRequest()->query->get('membres');
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('OVEProceduresBundle:groupes')->find($id);
      $membres=$membres.",".$entity->getMembres($membres);
      $tab=array();
      $membres=explode(",",$membres);
      $ldap = new ldap($em);
      foreach($membres as $v) {
        if($v<>"") $tab[$v]=array("id"=>$v,"text"=>$ldap->getName($v));
      }
      ksort($tab);
      $results=array();
      foreach($tab as $v) {
        $results[]=$v;
      }
      $r=array(
        "membres" => $membres,
        "results" => $results,
        "err"     => "",
      );
      $r = json_encode($r);        
      return new Response($r);
    }




    /**
     * Set 
     *
     * @Route("/get_trame", name="get_trame")
     * @Method("GET")
     * @Template()
     */
    public function GetTrameAction() {
      $id  = $this->getRequest()->query->get('id');
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('OVEProceduresBundle:trames')->find($id);
      $contenu=$entity->getContenu();
      $r=array(
        "contenu" => $contenu,
        "err"     => "",
      );
      $r = json_encode($r);        
      return new Response($r);
    }





    /**
     * Set 
     *
     * @Route("/set", name="ajax_set")
     * @Method("GET")
     * @Template()
     */
    public function setAction() {

      $id     = $this->getRequest()->query->get('id');
      $champ  = $this->getRequest()->query->get('champ');
      $value  = $this->getRequest()->query->get('value');

      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('OVEProceduresBundle:procedures')->findOneBy(array("id"=>$id));

      $err="";
      if(is_object($entity)) {


        //$entity->setFiche($value);
        $entity->set($champ, $value);


        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
      } else {
        $err="Impossible de modifier le champ $id !";
      }

      $r=array("err"=>$err);
      $r = json_encode($r);        
      return new Response($r);
    }



    /**
     * Set 
     *
     * @Route("/diagramme_upload", name="ajax_diagramme_upload")
     * @Method("POST")
     * @Template()
     */
    public function diagrammeUploadAction() {
      $err="";
      $r=array("err"=>$err);
      $r = json_encode($r);        
      return new Response($r);
    }




    /**
     * Get Verificateur
     *
     * @Route("/get_verificateur", name="get_verificateur")
     * @Method("GET")
     * @Template()
     */
    public function getVerificateurAction() {
      $id = $this->getRequest()->query->get('id');
      $em = $this->getDoctrine()->getManager();
      //$entity = $em->getRepository('OVEThesaurusBundle:terme')->findOneBy(array("id"=>$id));
      $entity = $em->getRepository('OVEProceduresBundle:domaines')->findOneBy(array("termeid"=>$id));
      $err=""; $verificateur=""; $nom="";
      if(is_object($entity)) {
        $verificateur=$entity->getResponsable();
        //** Recherche du nom dans le LDAP ****************
        $ldap = new ldap($em);
        $results=$ldap->getNames($verificateur);
        $nom=$verificateur;
        foreach($results as $v) {
          if(array_key_exists("text", $v)) $nom=$v["text"];
        }
        //*************************************************
      } 
      $r=array("err"=>$err);
      $r["json"]=array("id"=>$verificateur,"text"=>$nom);
      $r = json_encode($r);        
      return new Response($r);
    }



    /**
     * Set Verificateur
     *
     * @Route("/set_verificateur", name="set_verificateur")
     * @Method("GET")
     * @Template()
     */
    public function setVerificateurAction() {
      $id               = $this->getRequest()->query->get('id');
      $verificateur     = $this->getRequest()->query->get('verificateur');
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('OVEProceduresBundle:domaines')->findOneBy(array("termeid"=>$id));
      if(is_object($entity)) {
        $entity->setResponsable($verificateur);
        $em = $this->getDoctrine()->getManager();
        $em->persist($entity);
        $em->flush();
      } else {
        $entity = new Domaines;
        $entity->setTermeid($id);
        $entity->setResponsable($verificateur);
        $em->persist($entity);
        $em->flush();
      }
      $r=array("err"=>"");
      $r = json_encode($r);        
      return new Response($r);
    }
}





