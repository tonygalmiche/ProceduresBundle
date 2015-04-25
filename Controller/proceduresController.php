<?php

namespace OVE\ProceduresBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OVE\ProceduresBundle\Entity\procedures;
use OVE\ProceduresBundle\Form\proceduresType;

use OVE\ProceduresBundle\Entity\statistiques;

use OVE\ProceduresBundle\Controller\ldap;

//use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * procedures controller.
 *
 * @Route("/procedures")
 */
class proceduresController extends Controller
{





    /**
     * Lists all procedures entities.
     *
     * @Route("/rss/{etat}", name="procedures_rss")
     * @Method("GET")
     * @Template()
     */
    public function rssAction($etat)
    {

        //Retrouver les choix d'une liste de choix ****************************
        $form = $this->createForm(new proceduresType());
        $types = $form->get('type')->getConfig()->getOption('choices');
        //*********************************************************************



        $em = $this->getDoctrine()->getManager();
        $SQL="SELECT e FROM OVEProceduresBundle:procedures e 
              WHERE e.etat='$etat' 
              ORDER BY e.date_mise_a_jour DESC ";
        $entities=$em->createQuery($SQL)->setMaxResults(30)->getResult();
        $results=array();



        foreach($entities as $v) {
          $url="https://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."/procedures/".$v->getId();

          $type=$v->getType();
          if(array_key_exists($type, $types)) $type=$types[$type];


          $results[]=array(
            "id"    => $v->getId(),
            "fiche" => $v->getFiche(),
            "version" => $v->getVersion(),
            "url"   => $url, 
            "type"  => $type,
          );
        }
        $title="Manuel des procédures";
        if($etat=="verification") $title="Manuel des procédures - Les procédures en attente de vérification";
        if($etat=="approbation")  $title="Manuel des procédures - Les procédures en attente d'approbation";
        if($etat=="valide")       $title="Manuel des procédures - Les dernières procédures validées";

        return $this->render('OVEProceduresBundle:procedures:index.rss.twig', 
          array(
            "entities" => $results,
            "etat"     => $etat,
            "title"    => $title,
          )
        );
    }






    /**
     * Lists all procedures entities.
     *
     * @Route("/", name="procedures")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $login = $user->getUserName();
        $roles=$user->getRoles();

        //print_r($roles);

        $droits=$this->getDroits();
        //print_r($droits);


        //Retrouver les choix d'une liste de choix ****************************
        $form = $this->createForm(new proceduresType());
        $types = $form->get('type')->getConfig()->getOption('choices');
        $etats = $form->get('etat')->getConfig()->getOption('choices');
        $etats["tous"]="(Tous)";
        //*********************************************************************


        $f=array(); //f=formulaire
        $f["fiche"]     = $this->getRequest()->query->get('fiche');
        $f["nom"]       = $this->getRequest()->query->get('nom');
        $f["type"]      = $this->getRequest()->query->get('type');
        $f["domaine"]   = $this->getRequest()->query->get('domaine');
        $f["mots_cles"] = $this->getRequest()->query->get('mots_cles');
        $f["etat"]      = $this->getRequest()->query->get('etat');
        $filtre         = $this->getRequest()->query->get('filtre');



        if($f["etat"]=="") $f["etat"]="valide";
        $tab_etats=array();
        foreach($etats as $k=>$v) {
          if($k==$f["etat"]) $selected="selected"; else $selected="";
          $tab_etats[]=array("id"=>$k, "title"=>$v, "selected"=>$selected);
        }




        $SQL="SELECT e FROM OVEProceduresBundle:procedures e WHERE e.id>0 ";

        if($filtre=="procedures_a_verifier")  {
          $SQL.=" AND e.verificateursid like '%$login%'  ";
          $f["etat"]="verification";
        }

        if($filtre=="procedures_a_approuver")  {
          $f["etat"]="approbation";
        }

        if($filtre=="procedures_a_lire")  {
          $SQL.=" AND e.etat='valide' and e.lecteursid like '%$login%' and  (e.lecteursaccuse not like '%$login%' or e.lecteursaccuse is null) ";
          $x=$em->createQuery($SQL)->getResult();
          $nb=count($x);
          $alertes["procedures_a_lire"]=$nb;
          $f["etat"]="valide";
        }

        if($filtre=="nouvelles_procedures")  {
          $SQL2="SELECT p.id from procedures p left outer join statistiques s on p.id=s.idprocedure and s.login='$login'
                WHERE p.etat='valide' and s.id is null ";
          $req = $em->getConnection()->prepare($SQL2);
          $req->execute();
          $entities=$req->fetchAll();
          $ids=array();
          foreach ($entities as $v) {
            $ids[]="'".$v["id"]."'";
          }
          if(count($ids)>0) {
            $SQL.=" AND e.id IN (".implode(",",$ids).") ";
          }
          $f["etat"]="valide";
        }










        if($f["fiche"]<>"")     $SQL.=" AND e.fiche     like '%".$f["fiche"]."%' ";
        if($f["nom"]<>"")       $SQL.=" AND e.nom       like '%".$f["nom"]."%' ";
        if($f["type"]<>"")      $SQL.=" AND e.type      like '%".$f["type"]."%' ";
        if($f["domaine"]<>"")   $SQL.=" AND e.domaine   like '%".$f["domaine"]."%' ";
        if($f["mots_cles"]<>"") $SQL.=" AND e.mots_cles like '%".$f["mots_cles"]."%' ";
        if($f["etat"]<>"" and $f["etat"]<>"tous")      $SQL.=" AND e.etat      like '%".$f["etat"]."%' ";








        $SQL.=" ORDER BY e.fiche ASC";

        $entities=$em->createQuery($SQL)->getResult();
        $results=array();
        foreach($entities as $v) {


          $etat=$v->getEtat();
          if(array_key_exists($etat, $etats)) $etat=$etats[$etat];
          $etat_color=$v->getEtatColor($v->getEtat());

          $type=$v->getType();
          if(array_key_exists($type, $types)) $type=$types[$type];


          $results[]=array(
            "id"                 => $v->getId(),
            "initid"             => $v->getInitid(),
            "fiche"              => $v->getFiche(),
            "nom"                => $v->getNom(),
            "type"               => $type,
            "etat"               => $etat,
            "etat_color"         => $etat_color,
            "domaine"            => $v->getDomaine(),
            "motscles"           => $v->getMotsCles(),
            "version"            => $v->getVersion(),
            "dateredaction"      => $v->getDateRedaction(),
            "dateverifie"        => $v->getDateVerifie(),
            "dateapprouve"       => $v->getDateApprouve(),
            "dateapplication"    => $v->getDateApplication(),
            "datemiseajour"      => $v->getDateMiseAJour(),
            "objet_modification" => $v->getObjetModification(),

          );
        }


        //** Alertes **************************************
        $alertes=array();


        //** Alerte : Procedures à vérifier ****************
        $nb=0;
        //if(in_array ("ROLE_VERIFICATION", $roles)) {
          $SQL="SELECT e FROM OVEProceduresBundle:procedures e WHERE e.etat='verification' and e.verificateursid like '%$login%'  ";
          $x=$em->createQuery($SQL)->getResult();
          $nb=count($x);
        //}
        $alertes["procedures_a_verifier"]=$nb;
        //*************************************************


        //** Alerte : Procedures à approuver **************
        $nb=0;
        if(in_array ("ROLE_APPROBATION", $roles)) {
          $SQL="SELECT e FROM OVEProceduresBundle:procedures e WHERE e.etat='approbation' ";
          $x=$em->createQuery($SQL)->getResult();
          $nb=count($x);
        }
        $alertes["procedures_a_approuver"]=$nb;
        //*************************************************


        //** Alerte : Procedures à lire *******************
        $SQL="SELECT e FROM OVEProceduresBundle:procedures e 
              WHERE e.etat='valide' and e.lecteursid like '%$login%' and  ( e.lecteursaccuse not like '%$login%' or e.lecteursaccuse is null) ";
        $x=$em->createQuery($SQL)->getResult();
        $nb=count($x);
        $alertes["procedures_a_lire"]=$nb;
        //*************************************************


        //** Alerte : Nouvelles procedures ****************
        $SQL="SELECT p.id from procedures p left outer join statistiques s on p.id=s.idprocedure and s.login='$login'
              WHERE p.etat='valide' and s.id is null ";
        $req = $em->getConnection()->prepare($SQL);
        $req->execute();
        $entities=$req->fetchAll();
        $Nb=count($entities);
        $alertes["nouvelles_procedures"]=$Nb;
        //*************************************************



        return array(
            'entities' => $results,
            'f'        => $f,
            "etats"    => $tab_etats,
            "alertes"  => $alertes,
            'droits'   => $droits,
        );
    }
    /**
     * Creates a new procedures entity.
     *
     * @Route("/", name="procedures_create")
     * @Method("POST")
     * @Template("OVEProceduresBundle:procedures:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new procedures();
        $form = $this->createForm(new proceduresType(), $entity);
        //$form = $this->createCreateForm($entity);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('procedures', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a procedures entity.
    *
    * @param procedures $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(procedures $entity)
    {
        $form = $this->createForm(new proceduresType(), $entity, array(
            'action' => $this->generateUrl('procedures_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new procedures entity.
     *
     * @Route("/new", name="procedures_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {


        $p = new Procedures;
        $p->setFiche(" ");
        $p->setNom(" ");
        $p->setEtat("redaction");
        $em = $this->getDoctrine()->getManager();
        $em->persist($p);
        $em->flush();
        $id=$p->getId();
        //echo"id=$id";
        return $this->redirect($this->generateUrl('procedures_edit', array('id' => $id)));



        $entity = new procedures();
        $form   = $this->createForm(new proceduresType(), $entity);


        //$entity = new procedures();
        //$form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }



    private function getDroits($entity="") {
      $user = $this->getUser();
      $login = $user->getUserName();

      $roles=$user->getRoles();

      $droits["minimum"]=true;
      if(in_array("ROLE_USER", $roles) and count($roles)>1) $droits["minimum"]=false;
      if(in_array("ROLE_ADMIN", $roles)) $droits["minimum"]=false;

      $etat="";
      if(is_object($entity)) {
        $etat=$entity->getEtat();
      }
      //print_r($roles);


      //** Droit de verificateur / vers_approbation *******
      $droits["vers_approbation"]  = false;
      if(in_array ("ROLE_VERIFICATION", $roles) and $etat=="verification") $droits["vers_approbation"]=true;
      if(in_array ("ROLE_ADMIN", $roles)        and $etat=="verification") $droits["vers_approbation"]=true;

      if(is_object($entity)) {
        $ids=$entity->getVerificateursid();
        if($ids<>"") $ids=explode("\n", $ids); else $ids=array();
        if($etat=="verification") {
          if(in_array ($login, $ids)) {
            $droits["vers_approbation"]=true;
          }
        }
      }
      //***************************************************




      //** Droit de modifier ******************************
      $droits["modifier"]=false;
      if(is_object($entity)) {
        $ids=$entity->getRedacteursid();
        if($ids<>"") $ids=explode("\n", $ids); else $ids=array();
        if($etat=="redaction") {
          if(in_array($login, $ids)) $droits["modifier"]=true;
          //if(in_array("ROLE_REDACTION", $roles)) $droits["modifier"]=true;
        }

        if($etat=="verification") {
          if ($droits["vers_approbation"]==true) $droits["modifier"]=true;
          //$ids=$this->role2login("ROLE_VERIFICATION");
          //if(in_array ($login, $ids))         $droits["modifier"]=true;
        }

        if($etat=="approbation") {
          $ids=$this->role2login("ROLE_APPROBATION");
          if(in_array ($login, $ids))         $droits["modifier"]=true;
        }

        if(in_array ("ROLE_ADMIN", $roles)) $droits["modifier"]=true;
      }
      //***************************************************



      //** Droit de lecteur ******************************
      $droits["lecteur"]=false;
      if(is_object($entity)) {
        $ids=$entity->getLecteursid();
        if($ids<>"") $ids=explode(",", $ids); else $ids=array();
        $accuse=$entity->getLecteursaccuse();
        if($accuse<>"") $accuse=explode(",", $accuse); else $accuse=array();
        if($etat=="valide") {
          if(in_array ($login, $ids)) {
            if(!in_array ($login, $accuse)) {
              $droits["lecteur"]=true;
            }
          }
        }
      }
      //***************************************************





      //** Droits changement d'état ***********************
      $droits["vers_verification"] = $droits["modifier"];
      if($etat=="verification") $droits["vers_verification"]=false;


      $droits["vers_valide"]  = false;
      if(in_array ("ROLE_APPROBATION", $roles) and $etat=="approbation") $droits["vers_valide"]=true;
      if(in_array ("ROLE_ADMIN", $roles)       and $etat=="approbation") $droits["vers_valide"]=true;

      $droits["vers_redaction"]   = false;
      if(in_array ("ROLE_ADMIN", $roles)  and $etat!="redaction") $droits["vers_redaction"]=true;


      //print_r($droits);

      //$droits["lecteur"]   = true;
      //if(in_array ("ROLE_ADMIN", $roles)  and $etat!="redaction") $droits["vers_redaction"]=true;

      //***************************************************









      //** Droit de révision ******************************
      $droits["revision"]=false;
      if($etat=="valide" and $droits["modifier"]) $droits["revision"]=true;
      //***************************************************


      //** Droit de supprimer définitivement **************
      $droits["supprimer"]=false;
      if(in_array("ROLE_ADMIN", $roles)) $droits["supprimer"]=true;
      //***************************************************


      //** Droit de supprimer définitivement **************
      $droits["admin"]=false;
      if(in_array("ROLE_ADMIN", $roles)) $droits["admin"]=true;
      //***************************************************



      //print_r($droits);

      return $droits;
    }



    /**
     * Finds and displays a procedures entity.
     *
     * @Route("/{id}", name="procedures_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $repository=$em->getRepository('OVEProceduresBundle:procedures');
        $entity = $repository->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find procedures entity.');
        }



        //** filigrane **********************************************
        $repository=$em->getRepository('OVEProceduresBundle:parametres');
        $parametres = $repository->findBy(array('code' => 'filigrane'));
        $filigrane="";
        foreach ($parametres as $parametre) {
          $filigrane=$parametre->getValeur();
        }
        $filigrane=str_replace ("[date]", date("d/m/Y"), $filigrane);
        $url="https://".$_SERVER["HTTP_HOST"];
        $filigrane=str_replace ("[url]", $url, $filigrane);

        $parametres = $repository->findBy(array('code' => 'filigrane_couleur'));
        $filigrane_couleur="";
        foreach ($parametres as $parametre) {
          $filigrane_couleur=$parametre->getValeur();
        }

        $parametres = $repository->findBy(array('code' => 'filigrane_top'));
        $filigrane_top="";
        foreach ($parametres as $parametre) {
          $filigrane_top=$parametre->getValeur();
        }
        $parametres = $repository->findBy(array('code' => 'filigrane_left'));
        $filigrane_left="";
        foreach ($parametres as $parametre) {
          $filigrane_left=$parametre->getValeur();
        }


        //***********************************************************




        //** statistiques *******************************************
        $date1 = date("Y-m-d");
        $date2 = date("Y-m-d",time()+3600*24);
        $user = $this->getUser();
        $login = $user->getUserName(); //login
        $SQL="SELECT e 
              FROM OVEProceduresBundle:statistiques e 
              WHERE e.idprocedure='$id' and e.login='$login' and e.date>='$date1' and e.date<'$date2'  ";
        $entities=$em->createQuery($SQL)->getResult();
        if(count($entities)==0) {
          $date = new \DateTime();
          $e = new Statistiques;
          $e->setDate($date);
          $e->setLogin($login);
          $e->setIdprocedure($id);
          $em->persist($e);
          $em->flush();
        }
        //***********************************************************


        $etat_color=$entity->getEtatColor($entity->getEtat());

        $deleteForm = $this->createDeleteForm($id);

        //Retrouver le label d'une liste de choix a partir de son id **********
        $form = $this->createForm(new proceduresType(), $entity);

        $types = $form->get('type')->getConfig()->getOption('choices');
        $type=$entity->getType();
        if(array_key_exists($type, $types)) $type=$types[$type];

        $etats = $form->get('etat')->getConfig()->getOption('choices');
        $etat=$entity->getEtat();
        if(array_key_exists($etat, $etats)) $etat=$etats[$etat];




        //*********************************************************************

        $editId = $id."-diagramme";
        $existingFiles = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'attachments/' . $editId));

        $url="https://".$_SERVER["HTTP_HOST"];

        $diagramme="";
        if(count($existingFiles)>0) {
          //$diagramme=$existingFiles[0];
          foreach($existingFiles as $v) {
            //$diagramme="https://".$_SERVER["HTTP_HOST"]."/uploads/attachments/$id-diagramme/originals/$v";
            $diagramme="$url/uploads/attachments/$id-diagramme/originals/$v";
          }
        }





        $editId = $id."-pieces-jointes";
        $existingFiles = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'attachments/' . $editId));
        $pieces_jointes="";
        if(count($existingFiles)>0) $pieces_jointes=$existingFiles;


        $editId = $id."-modeles";
        $existingFiles = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'attachments/' . $editId));
        $modeles="";
        if(count($existingFiles)>0) $modeles=$existingFiles;





        $droits=$this->getDroits($entity);

        //print_r($droits);

        $lecteurstitle  = explode(",",$entity->getLecteurs());
        $lecteursid     = explode(",",$entity->getLecteursid());
        $lecteursaccuse = explode(",",$entity->getLecteursaccuse());

        $lecteurs=array();
        for($i=0;$i<count($lecteursid);$i++) {
          $lecteurs[$i]["id"]    = $lecteursid[$i];
          $lecteurs[$i]["title"] = $lecteurstitle[$i];
          $lecteurs[$i]["color"] = "red";
          if(array_key_exists($i,$lecteursid)) {
            if(in_array ($lecteursid[$i], $lecteursaccuse)) $lecteurs[$i]["color"]="green";
          }
        }

        $redacteurs  = $entity->getRedacteurs();
        if($redacteurs!="") $redacteurs = explode("\n",$redacteurs); else $redacteurs=array();
        $html="";
        for($i=0;$i<count($redacteurs);$i++) {
          if($i==0) {
            $html.="<li><b>".$redacteurs[$i]."</b></li>";
            $html.="<ul>";
          } else {
            $html.="<li>".$redacteurs[$i]."</li>";
          }
          if($i==count($redacteurs)-1) $html.="</ul>";
        }
        $redacteurs=$html;

        $verificateurs  = $entity->getVerificateurs();
        if($verificateurs!="") $verificateurs = explode("\n",$verificateurs); else $verificateurs=array();

        $approbateurs  = $entity->getApprobateurs();
        if($approbateurs!="") $approbateurs = explode("\n",$approbateurs); else $approbateurs=array();

        $liens_html  = $entity->getLiensHtml();
        if($liens_html<>"") $liens_html  = explode("\n",$entity->getLiensHtml()); else $liens_html=array();

        return array(
            'type'             => $type,
            'etat'             => $etat,
            'etat_color'       => $etat_color,
            'entity'           => $entity,
            'diagramme'        => $diagramme,
            'pieces_jointes'   => $pieces_jointes,
            'modeles'          => $modeles,
            'delete_form'      => $deleteForm->createView(),
            'droits'           => $droits,
            'redacteurs'       => $redacteurs,
            'lecteurs'         => $lecteurs,
            'verificateurs'    => $verificateurs,
            'approbateurs'     => $approbateurs,
            'liens_html'       => $liens_html,
            'filigrane'        => $filigrane,
            'filigrane_couleur'=> $filigrane_couleur,
            'filigrane_top'    => $filigrane_top,
            'filigrane_left'   => $filigrane_left,
            'diagramme_width'  => '',
            'diagramme_height' => '',
            'url'              => $url
        );
    }





    /**
     *
     * @Route("/upload_diagramme", name="upload_diagramme")
     * @Template()
     */
    public function uploadDiagrammeAction()
    {
        $editId = $this->getRequest()->get('editId');
        $max_number_of_files=1;
        $allowed_extensions=array("png","jpg","jpeg");
        $this->get('punk_ave.file_uploader')->handleFileUpload(
          array(
            'folder' => "/tmp/attachments/$editId", 
            'max_number_of_files' => $max_number_of_files,
            'allowed_extensions'  => $allowed_extensions,
          ));
    }


    /**
     *
     * @Route("/upload_pieces_jointes", name="upload_pieces_jointes")
     * @Template()
     */
    public function uploadPiecesJointesAction()
    {
        $editId = $this->getRequest()->get('editId');
        $max_number_of_files=10;
        $allowed_extensions=array("pdf");
        $this->get('punk_ave.file_uploader')->handleFileUpload(
          array(
            'folder'              => "/tmp/attachments/$editId", 
            'max_number_of_files' => $max_number_of_files,
            'allowed_extensions'  => $allowed_extensions,
          ));
    }



    /**
     *
     * @Route("/upload_modeles", name="upload_modeles")
     * @Template()
     */
    public function uploadModelesAction()
    {
        $editId = $this->getRequest()->get('editId');
        $max_number_of_files=10;
        $allowed_extensions=array("pdf","odt","ods","odp","doc","dot","docx","dotx","xls","xlt","xlsx","xltx");
        $this->get('punk_ave.file_uploader')->handleFileUpload(
          array(
            'folder'              => "/tmp/attachments/$editId", 
            'max_number_of_files' => $max_number_of_files,
            'allowed_extensions'  => $allowed_extensions,
          ));
    }







    /**
     * Displays a form to edit an existing procedures entity.
     *
     * @Route("/{id}/edit", name="procedures_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {



        $droits=$this->getDroits();

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('OVEProceduresBundle:procedures')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find procedures entity.');
        }

        /*
        $request = $this->getRequest();
        $editId = $this->getRequest()->get('editId');
        if (!preg_match('/^\d+$/', $editId))
        {
            $editId = sprintf('%09d', mt_rand(0, 1999999999));
            //if ($posting->getId())
            if ($entity->getId())
            {
                $this->get('punk_ave.file_uploader')->syncFiles(
                    array(
                      'from_folder' => 'attachments/' . $entity->getId(),
                      'to_folder' => 'tmp/attachments/' . $editId,
                      'create_to_folder' => true
                    )
                );
            }
        }
        */

        $editId1 = $id."-diagramme";
        $this->get('punk_ave.file_uploader')->syncFiles(
            array(
              'from_folder' => 'attachments/' . $editId1,
              'to_folder' => 'tmp/attachments/' . $editId1,
              'create_to_folder' => true
            )
        );

        $editId2 = $id."-pieces-jointes";
        $this->get('punk_ave.file_uploader')->syncFiles(
            array(
              'from_folder' => 'attachments/' . $editId2,
              'to_folder' => 'tmp/attachments/' . $editId2,
              'create_to_folder' => true
            )
        );


        $editId3 = $id."-modeles";
        $this->get('punk_ave.file_uploader')->syncFiles(
            array(
              'from_folder' => 'attachments/' . $editId2,
              'to_folder' => 'tmp/attachments/' . $editId2,
              'create_to_folder' => true
            )
        );

        $existingFiles1 = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'attachments/' . $editId1));
        $existingFiles2 = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'attachments/' . $editId2));
        $existingFiles3 = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'attachments/' . $editId3));


        $session = $this->getRequest()->getSession();

        //** Thésaurus des domaines *********************************
        $thesaurus=$this->container->getParameter('thesaurus');
        $url=$thesaurus["domaine"];
        $domaines_json=file_get_contents ($url);
        $domaines=json_decode($domaines_json,true);
        $tab=array(); $x=array();
        foreach($domaines as $k=>$v) {
          $key=$v["id"];
          $val=$v["text"];
          $tab["id_$key"]=array("id"=>$key,"text"=>$val);
          $x[$key]=$val;
        }
        $session->set('domaine'      , $x);
        $session->set('domaines_json', $domaines_json);
        ksort($tab);
        $domaine=array(); 
        foreach($tab as $k=>$v) {
          $domaine[]=$v;
        }

        $domaine=json_encode($domaine);
        //$domaine=str_replace("'","\'",$domaine);
        //$domaine=str_replace('"','\"',$domaine);
        //***********************************************************


        //** Thésaurus des mots clés ********************************
        $thesaurus=$this->container->getParameter('thesaurus');
        $url=$thesaurus["mot_cle"];
        $json=file_get_contents ($url);
        $json=json_decode($json,true);
        $tab=array(); $x=array();
        foreach($json as $k=>$v) {
          $key=$k;
          $val=$v["text"];
          $tab[$val]=array("id"=>$key,"text"=>$val);
          $x[$key]=$val;
        }
        $session->set('mots_cles', $x);
        ksort($tab);
        $mots_cles=array();
        foreach($tab as $k=>$v) {
          $mots_cles[]=$v;
        }
        $mots_cles=json_encode($mots_cles);
        //$mots_cles=str_replace("'","\'",$mots_cles);
        //$mots_cles=str_replace('"','\"',$mots_cles);
        //***********************************************************


        //** Redacteurs *********************************************
        $redacteurs=array();
        $x=$entity->getRedacteursid();
        if($x!="") {
          $x=explode("\n",$x);
          $ldap = new ldap($em);
          foreach($x as $v) {
            $redacteurs[]=array("id"=>$v,"text"=>$ldap->getName($v));
          }
        }
        $redacteurs = json_encode($redacteurs);
        //***********************************************************


        //** Lecteurs ***********************************************
        $lecteurs=array();
        $x=$entity->getLecteursid();
        if($x!="") {
          $x=explode(",",$x);
          $ldap = new ldap($em);
          foreach($x as $v) {
            $lecteurs[]=array("id"=>$v,"text"=>$ldap->getName($v));
          }
        }
        $lecteurs = json_encode($lecteurs);
        //***********************************************************



        $editForm = $this->createForm(new proceduresType(), $entity);
        $deleteForm = $this->createDeleteForm($id);




        return array(
            'entity'         => $entity,
            'delete_form'    => $deleteForm->createView(),
            'form'           => $editForm->createView(),
            'editId1'        => $editId1,
            'editId2'        => $editId2,
            'editId3'        => $editId3,
            'domaine'        => $domaine,
            'domaines_json'  => $domaines_json,
            'mots_cles'      => $mots_cles,
            'redacteurs'     => $redacteurs,
            'lecteurs'       => $lecteurs,
            'existingFiles1' => $existingFiles1,
            'existingFiles2' => $existingFiles2,
            'droits'         => $droits,
        );
    }

    /**
    * Creates a form to edit a procedures entity.
    *
    * @param procedures $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(procedures $entity)
    {
        $form = $this->createForm(new proceduresType(), $entity, array(
            'action' => $this->generateUrl('procedures_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing procedures entity.
     *
     * @Route("/{id}", name="procedures_update")
     * @Method("PUT")
     * @Template("OVEProceduresBundle:procedures:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OVEProceduresBundle:procedures')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find procedures entity.');
        }

        $deleteForm = $this->createDeleteForm($id);


        
        $from_folder = "/tmp/attachments/$id-diagramme";
        $to_folder   = "/attachments/$id-diagramme";
        $fileUploader = $this->get('punk_ave.file_uploader');
        $fileUploader->syncFiles(array(
            'from_folder'        =>  $from_folder,
            'to_folder'          => $to_folder,
            'remove_from_folder' => true,
            'create_to_folder'   => true)
        );


        $from_folder = "/tmp/attachments/$id-pieces-jointes";
        $to_folder   = "/attachments/$id-pieces-jointes";
        $fileUploader = $this->get('punk_ave.file_uploader');
        $fileUploader->syncFiles(array(
            'from_folder'        =>  $from_folder,
            'to_folder'          => $to_folder,
            'remove_from_folder' => true,
            'create_to_folder'   => true)
        );

        $from_folder = "/tmp/attachments/$id-modeles";
        $to_folder   = "/attachments/$id-modeles";
        $fileUploader = $this->get('punk_ave.file_uploader');
        $fileUploader->syncFiles(array(
            'from_folder'        =>  $from_folder,
            'to_folder'          => $to_folder,
            'remove_from_folder' => true,
            'create_to_folder'   => true)
        );




        


        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);


        $date = new \DateTime();
        //$entity->setDateMiseAJour();

        //$entity->setDateRedaction(new \DateTime("- 4 days - 1 hours"));
        //$entity->setDateVerifie (new \DateTime("- 3 days - 10 minutes"));
        //$entity->setDateApprouve(new \DateTime("- 2 days"));
        //$entity->setDateApplication(new \DateTime("- 1 days"));

        //Nouvelle demande de modification du 05/03/2015
        if($entity->getEtat()=="redaction" and $entity->getVersion()==1) {
            $entity->setDateRedaction($date);
            //$entity->setNomRedaction($name);
        }



        $user = $this->getUser();
        $login = $user->getUserName(); //login
        if(method_exists ($user, "getName" )) $name = $user->getName(); else $name=$login; //Nom complet

        //$entity->setNomRedaction($name);
        //$entity->setNomVerifie($name);
        //$entity->setNomApprouve($name);







        //** Sauvegarde des domaines ********************************
        $session = $this->getRequest()->getSession();
        $domaines_json   = $session->get('domaines_json');
        $domaines=json_decode($domaines_json,true);
        $tab=array();
        foreach($domaines as $domaine) {
          $k=$domaine["id"];
          $tab[$k]=$domaine;
        }
        $ids=explode(",", $entity->getDomaineid());
        $x1=array(); $x2=array();
        foreach($ids as $v) {
          if(array_key_exists($v, $tab)) {
            $x1[]=$v;
            $parent=$tab[$v]["parent"];
            $prefix="";
            if(array_key_exists($parent, $tab)) $prefix=$tab[$parent]["text"]." / ";
            $x2[]=$prefix.$tab[$v]["text"];
          }
        }
        $entity->setDomaineId(implode(",",$x1));
        $entity->setDomaine(implode("<br>",$x2));
        //***********************************************************


        //** Sauvegarde de l'intitulé des mots-clés *****************
        $tab = $session->get('mots_cles');
        $ids = explode(",", $entity->getMotsClesid());
        $x=array();
        foreach($ids as $v) {
          if(array_key_exists($v, $tab)) $x[]=$tab[$v];
        }
        //$entity->setMotsCles(implode("<br>",$x));
        $entity->setMotsCles(implode(", ",$x));
        //***********************************************************



        //** Sauvegarde de l'intitulé des Approbateurs **************
        $noms=$this->role2nom("ROLE_APPROBATION");
        $entity->setApprobateurs(implode("\n",$noms));
        //***********************************************************


        //** Utilisateurs ayant le rôle de vérificateur *************
        //$noms=$this->role2nom("ROLE_VERIFICATION");
        //$entity->setVerificateurs(implode("\n",$noms));
        $verificateurs=array();
        //$verificateurs=$this->role2login("ROLE_VERIFICATION");
        //***********************************************************


        //** Ajout des vérificateurs des domaines *******************
        $ids=explode(",", $entity->getDomaineid());

        foreach($ids as $v) {
          $obj = $em->getRepository('OVEProceduresBundle:domaines')->findOneBy(array("termeid"=>$v));
          if(is_object($obj)) {
            $verificateurs[]=$obj->getResponsable();
          }
        }
        $verificateurs=array_unique ($verificateurs);
        asort($verificateurs);
        $entity->setVerificateursId(implode("\n",$verificateurs));
        //***********************************************************


        //** Sauvegarde de l'intitulé des vérificateurs *************
        $x=array();
        $ldap = new ldap($em);
        foreach($verificateurs as $v) {
          $x[]=$ldap->getName($v);
        }
        $entity->setVerificateurs(implode("\n",$x));
        //***********************************************************






        //** Sauvegarde de l'intitulé des Rédacteurs ****************
        $ids=$entity->getRedacteursid();
        if($ids<>"") $ids=explode(",", $ids); else $ids=array();
        if(!in_array ($login, $ids)) $ids[]=$login; //Ajout de la personne qui éffectue la modification
        $x=array();
        $ldap = new ldap($em);
        foreach($ids as $v) {
          $x[]=$ldap->getName($v);
        }
        $entity->setRedacteursid(implode("\n",$ids));
        $entity->setRedacteurs(implode("\n",$x));


        //Demande d'Antoine du 02/03/2015 => Le nom du rédacteur est le premier rédacteur de la liste
        if(count($x)>0) $entity->setNomRedaction($x[0]);

        //***********************************************************


        //** Sauvegarde de l'intitulé des Lecteurs ****************
        $tab = $session->get('mots_cles');
        $ids = explode(",", $entity->getLecteursid());
        $x=array();
        foreach($ids as $v) {
          $x[]=$ldap->getName($v);
        }
        $entity->setLecteurs(implode(",",$x));
        //***********************************************************


        //** Version ************************************************
        $version=$entity->getVersion();
        if($version=="") $version="001";
        $entity->setVersion($version);
        //***********************************************************

        //** initid *************************************************
        $initid=$entity->getInitid();
        if($initid==0 or $initid=="") $entity->setInitid($id);
        //***********************************************************


        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('procedures_show', array('id' => $id)));
        }
        return $this->redirect($this->generateUrl('procedures_edit', array('id' => $id)));
    }





    /**
     * Deletes a procedures entity.
     *
     * @Route("/{id}", name="procedures_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OVEProceduresBundle:procedures')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find procedures entity.');
            }


            $folder   = "tmp/attachments/$id-diagramme";
            $this->get('punk_ave.file_uploader')->removeFiles(array('folder' => $folder));

            $folder   = "tmp/attachments/$id-pieces-jointes";
            $this->get('punk_ave.file_uploader')->removeFiles(array('folder' => $folder));

            $folder   = "attachments/$id-diagramme";
            $this->get('punk_ave.file_uploader')->removeFiles(array('folder' => $folder));

            $folder   = "attachments/$id-pieces-jointes";
            $this->get('punk_ave.file_uploader')->removeFiles(array('folder' => $folder));


            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('procedures'));
    }

    /**
     * Creates a form to delete a procedures entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('procedures_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    /**
     * @Route("/lecteur/{id}", name="procedures_lecteur")
     * @Method("GET")
     * @Template()
     */
    public function lecteurAction($id)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('OVEProceduresBundle:procedures')->find($id);
      $ids=$entity->getLecteursaccuse();
      $user = $this->getUser();
      $login = $user->getUserName();
      if($ids<>"") $ids=explode(",", $ids); else $ids=array();
      if(!in_array ($login, $ids)) $ids[]=$login;
      $entity->setLecteursaccuse(implode(",",$ids));
      $em->flush();
      return $this->redirect($this->generateUrl('procedures_show', array('id' => $id)));
    }



    /**
     * @Route("/etat/{etat}/{id}", name="procedures_etat")
     * @Method("GET")
     * @Template()
     */
    public function etatAction($id,$etat)
    {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('OVEProceduresBundle:procedures')->find($id);
      $entity->setEtat($etat);

      $user = $this->getUser();
      $login = $user->getUserName(); //login
      if(method_exists ($user, "getName" )) $name = $user->getName(); else $name=$login;

      //** Envoi des mails ********************************
      // Doc : http://swiftmailer.org/docs/messages.html
      if($etat=="verification") {
        $to=$this->role2mail("ROLE_VERIFICATION");
        //$sujet = "[Procédure][Vérification] ".
        $tab=array();
        $Verificateursid=$entity->getVerificateursid();
        if($Verificateursid!="") $tab=explode("\n",$Verificateursid);
        $to=array();
        foreach($tab as $v) {
          $to[]=$v."@fondation-ove.fr";
        }
        $sujet = "Une procédure ou règle de gestion n°".$entity->getFiche()." intitulée '".$entity->getNom()."' est en attente de vérification";
        //$sujet=$sujet."( ".implode(",",$to).")";
        //$to="tony.galmiche@gmail.com";
        $layout="mail_verification.html.twig";
        $this->sendMail($sujet, $to, $layout, $entity, $etat);
      }
      if($etat=="approbation") {
        $to=$this->role2mail("ROLE_APPROBATION");
        //$sujet = "[Procédure][Approbation] ".$entity->getFiche();
        $sujet = "Une procédure ou règle de gestion n°".$entity->getFiche()." intitulée '".$entity->getNom()."' est en attente d'approbation";
        $layout="mail_approbation.html.twig";
        $this->sendMail($sujet, $to, $layout, $entity, $etat);
      }

      if($etat=="archive") {
        $to=$this->role2mail("ROLE_APPROBATION");
        $sujet = "[Procédure][Archivage] ".$entity->getFiche();
        $layout="mail_archive.html.twig";
        $this->sendMail($sujet, $to, $layout, $entity, $etat);
      }
      if($etat=="valide") {

        //** Archivage de l'ancienne procédure ************
        $SQL="SELECT e FROM OVEProceduresBundle:procedures e 
              WHERE e.etat='valide' and e.initid='".$entity->getInitid()."' and e.id<>'$id' ";
        $entities=$em->createQuery($SQL)->getResult();
        foreach($entities as $e) {
          $e->setEtat("archive");
          $em->persist($e);
        }
        //*************************************************

        $tab=array();
        $lecteurs=$entity->getLecteursaccuse();
        if($lecteurs!="") $tab=explode(",",$lecteurs);
        $to=array();
        foreach($tab as $v) {
          $to[]=$v."@ove.asso.fr";
        }

        //$sujet = "[Procédure][Lecture] ".$entity->getFiche();
        $sujet = "Une nouvelle procédure ou règle de gestion n°".$entity->getFiche()." intitulée '".$entity->getNom()."' entre en application !";


        $layout="mail_lecture.html.twig";
        $this->sendMail($sujet, $to, $layout, $entity, $etat);
      }
      //***************************************************


      //** Gestion des dates ******************************
      $version=$entity->getVersion();

      $date = new \DateTime();
      /*
      if($etat=="valide" and $version==1) {
        $entity->setDateRedaction($date);
        //$entity->setNomRedaction($name);
      }
      */

      if($etat=="approbation") {
        $entity->setDateVerifie($date);
        $entity->setNomVerifie($name);
      }

      if($etat=="valide") {
        $entity->setDateApprouve($date);
        $entity->setNomApprouve($name);
        //Si DateApplication < Date de validation ou si DateApplication est vide, il faut mettre la date de validation
        $DateApplication=$entity->getDateApplication();
        if($DateApplication<$date or $DateApplication=="") $DateApplication=$date;
        $entity->setDateApplication($DateApplication);

        if($entity->getVersion()>1) {
            $entity->setDateMiseAJour($date);
            //$entity->setObjetModification("version=".$entity->getVersion());
            
        }
      }
      /*
      if($etat=="verification") {
        $entity->setDateRedaction($date);
        $entity->setNomRedaction($name);
      }
      */
      //***************************************************



      $em->flush();

      if($etat=="valide") {
        return $this->redirect($this->generateUrl('manuel_html'));
      } else {
        if($etat!="poubelle") {
          return $this->redirect($this->generateUrl('procedures_show', array('id' => $id)));
        } else {
          return $this->redirect($this->generateUrl('procedures'));
        }
      }
    }


    private function sendMail($sujet, $to, $layout, $entity, $etat) {

      //print_r($_SERVER);
      //[HTTP_HOST] => procedures-demo.fondation-ove.fr
      //[SCRIPT_NAME] => /app_dev.php
      $url="https://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"]."/procedures/".$entity->getId();
      $user = $this->getUser();
      $login = $user->getUserName(); //login
      $from  = $login."@ove.asso.fr";
      $body=$this->renderView("OVEProceduresBundle:procedures:$layout", array("entity"=>$entity,"url"=>$url)
      );

      $cc=array("anneclaire.duchon@fondation-ove.fr","bastien.gonzalez@fondation-ove.fr");

      //$to=array("tony.galmiche@gmail.com");
      //$cc=array("tony.galmiche@gmail.com");
      //$cc=array();

      $message = \Swift_Message::newInstance()
        ->setSubject($sujet)
        ->setFrom($from)
        ->setTo($to)
        ->setCc($cc) 
        ->setBody($body, 'text/html');
      $this->get('mailer')->send($message);
    }



    private function role2login($role) {
      $em = $this->getDoctrine()->getManager();
      $SQL="SELECT r.id, ru.utilisateur_id, u.login
            FROM role r, role_utilisateur ru, utilisateur u
            WHERE r.id=ru.role_id and ru.utilisateur_id=u.id and  r.role='$role' ";
      $req = $em->getConnection()->prepare($SQL);
      $req->execute();
      $entities=$req->fetchAll();
      $logins=array();
      foreach ($entities as $v) {
        $logins[]=$v["login"];
      }
      return $logins;
    }



    private function role2mail($role) {
      $em = $this->getDoctrine()->getManager();
      $SQL="SELECT r.id, ru.utilisateur_id, u.login
            FROM role r, role_utilisateur ru, utilisateur u
            WHERE r.id=ru.role_id and ru.utilisateur_id=u.id and  r.role='$role' ";
      $req = $em->getConnection()->prepare($SQL);
      $req->execute();
      $entities=$req->fetchAll();
      $mails=array();
      foreach ($entities as $v) {
        $mails[]=$v["login"]."@ove.asso.fr";
      }
      return $mails;
    }


    private function role2nom($role) {
      $em = $this->getDoctrine()->getManager();
      $SQL="SELECT r.id, ru.utilisateur_id, u.login
            FROM role r, role_utilisateur ru, utilisateur u
            WHERE r.id=ru.role_id and ru.utilisateur_id=u.id and  r.role='$role' ";
      $req = $em->getConnection()->prepare($SQL);
      $req->execute();
      $entities=$req->fetchAll();
      $ldap = new ldap($em);
      $noms=array();
      foreach ($entities as $v) {
        $noms[]=$ldap->getName($v["login"]);
      }
      return $noms;
    }










    /**
     * @Route("/revision/{id}", name="procedures_revision")
     * @Method("GET")
     * @Template()
     */
    public function revisionAction($id)
    {


      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('OVEProceduresBundle:procedures')->find($id);

      $copy = clone $entity;

      //** Version ************************************************
      $version=$entity->getVersion();
      $version=$version/1+1;
      $version=substr("000".$version,-3);
      $copy->setVersion($version);
      //***********************************************************

      //** Dates **************************************************
      //$copy->setDateRedaction(null);
      //$copy->setNomRedaction("");
      $copy->setDateApprouve(null);
      $copy->setNomApprouve("");
      $copy->setDateApplication(null);
      $copy->setNomApplication("");
      //***********************************************************


      $copy->setEtat("redaction");
      $em->persist($copy);

      //$entity->setEtat("archive");
      //$em->persist($entity);

      $em->flush();
      $newid=$copy->getId();

      //** Copie des pieces jointes *******************************
      $path=$_SERVER["DOCUMENT_ROOT"];// => /var/web/procedures/symfony_demo/web
      exec("cp -Rf $path/uploads/attachments/$id-diagramme $path/uploads/attachments/$newid-diagramme           2>/tmp/diagramme_err.log");
      exec("cp -Rf $path/uploads/attachments/$id-pieces-jointes $path/uploads/attachments/$newid-pieces-jointes 2>/tmp/pieces_jointes_err.log");
      exec("cp -Rf $path/uploads/attachments/$id-modeles $path/uploads/attachments/$newid-modeles               2>/tmp/modeles_err.log");
      //***********************************************************


      return $this->redirect($this->generateUrl('procedures_show', array('id' => $newid)));
    }





}
