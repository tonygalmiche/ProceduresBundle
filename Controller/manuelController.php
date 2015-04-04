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



/**
 * manuel des procedures controller.
 *
 * @Route("/manuel")
 */
class manuelController extends Controller
{

    /**
     * Lists all procedures entities.
     *
     * @Route("/html", name="manuel_html")
     * @Method("GET")
     * @Template()
     */


    public function htmlAction() {
        $em = $this->getDoctrine()->getManager();
        $SQL="SELECT p.id from procedures p
              WHERE p.id>0 and etat='valide' 
              ORDER BY p.fiche, p.version ";

        // WHERE p.id>0 and id in(116,64,73)

        $req = $em->getConnection()->prepare($SQL);
        $req->execute();
        $entities=$req->fetchAll();
        $procedures=array();
        foreach($entities as $v) {
          $id=$v["id"];
          $entity = $em->getRepository('OVEProceduresBundle:procedures')->find($id);

          //Retrouver le label d'une liste de choix a partir de son id **********
          $form = $this->createForm(new proceduresType(), $entity);
          $types = $form->get('type')->getConfig()->getOption('choices');
          $type=$entity->getType();
          if(array_key_exists($type, $types)) $type=$types[$type];
          //*********************************************************************


          $url="https://".$_SERVER["HTTP_HOST"];

          //** Diagramme ********************************************************
          $editId = $id."-diagramme";
          $existingFiles = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'attachments/' . $editId));
          $diagramme=""; $diagramme_width=""; $diagramme_height="";
          foreach($existingFiles as $v) {
            //$path=$_SERVER["DOCUMENT_ROOT"]."/uploads/attachments/$id-diagramme/small/$v";
            $path=$_SERVER["DOCUMENT_ROOT"]."/uploads/attachments/$id-diagramme/originals/$v";
            if(file_exists ($path)) {
              $diagramme_size=getimagesize ($path);

              $diagramme_width  = $diagramme_size[0]*0.0352777777777778;
              $diagramme_height = $diagramme_size[1]*0.0352777777777778;

              //Limitation à 18cm, car il y a 3cm de marge sur la page ODT
              if($diagramme_width>18) {
                $coef=18/$diagramme_width;
                $diagramme_width=$diagramme_width*$coef;
                $diagramme_height=$diagramme_height*$coef;
              }

              if($diagramme_height>24) {
                $coef=24/$diagramme_height;
                $diagramme_width=$diagramme_width*$coef;
                $diagramme_height=$diagramme_height*$coef;
              }



              $diagramme_width  = round($diagramme_width,3);
              $diagramme_height = round($diagramme_height,3);

              //$diagramme="https://".$_SERVER["HTTP_HOST"]."/uploads/attachments/$id-diagramme/small/$v";
              //$diagramme="https://".$_SERVER["HTTP_HOST"]."/uploads/attachments/$id-diagramme/originals/$v";
              $diagramme="$url/uploads/attachments/$id-diagramme/originals/$v";



            }
          }
          //*********************************************************************




          //** Documentations ***************************************************
          $editId = $id."-pieces-jointes";
          $existingFiles = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'attachments/' . $editId));
          $pieces_jointes="";
          if(count($existingFiles)>0) $pieces_jointes=$existingFiles;
          //*********************************************************************

          //** Modèles **********************************************************
          $editId = $id."-modeles";
          $existingFiles = $this->get('punk_ave.file_uploader')->getFiles(array('folder' => 'attachments/' . $editId));
          $modeles="";
          if(count($existingFiles)>0) $modeles=$existingFiles;
          //*********************************************************************




          $lecteurstitle  = explode(",",$entity->getLecteurs());
          $lecteursid     = explode(",",$entity->getLecteursid());
          $lecteursaccuse = explode(",",$entity->getLecteursaccuse());

          $lecteurs=array();
          for($i=0;$i<count($lecteursid);$i++) {
            $lecteurs[$i]["id"]    = $lecteursid[$i];
            $lecteurs[$i]["title"] = trim($lecteurstitle[$i]);
            $lecteurs[$i]["color"] = "red";
            if(array_key_exists($i,$lecteursid)) {
              if(in_array ($lecteursid[$i], $lecteursaccuse)) $lecteurs[$i]["color"]="green";
            }
          }

          $redacteurs  = $entity->getRedacteurs();
          if($redacteurs!="") $redacteurs = explode("\n",$redacteurs); else $redacteurs=array();

          $verificateurs  = $entity->getVerificateurs();
          if($verificateurs!="") $verificateurs = explode("\n",$verificateurs); else $verificateurs=array();

          $approbateurs  = $entity->getVerificateurs();
          if($approbateurs!="") $approbateurs = explode("\n",$approbateurs); else $approbateurs=array();

          $liens_html  = $entity->getLiensHtml();
          if($liens_html<>"") $liens_html  = explode("\n",$entity->getLiensHtml()); else $liens_html=array();


          //$repository=$em->getRepository('OVEProceduresBundle:parametres');
          //$parametres = $repository->findBy(array('code' => 'filigrane'));
          $filigrane="";
          //foreach ($parametres as $parametre) {
          //  $filigrane=$parametre->getValeur();
          //}

          $form = $this->createForm(new proceduresType(), $entity);
          $etats = $form->get('etat')->getConfig()->getOption('choices');
          $etat=$entity->getEtat();
          if(array_key_exists($etat, $etats)) $etat=$etats[$etat];


          $etat_color=$entity->getEtatColor($entity->getEtat());

          $procedures[]=array(
              'type'             => $type,
              'etat'             => $etat,
              'etat_color'       => $etat_color,
              'entity'           => $entity,
              'diagramme'        => $diagramme,
              'diagramme_width'  => $diagramme_width,
              'diagramme_height' => $diagramme_height,
              'pieces_jointes'   => $pieces_jointes,
              'modeles'          => $modeles,
              'redacteurs'       => $redacteurs,
              'lecteurs'         => $lecteurs,
              'verificateurs'    => $verificateurs,
              'approbateurs'     => $approbateurs,
              'liens_html'       => $liens_html,
              'filigrane'        => "",
              'filigrane_couleur'=> "",
              'filigrane_top'    => "",
              'filigrane_left'   => "",

              'url'              => $url
          );
        }



        //** Version HTML *****************************************************
        $path=$_SERVER["DOCUMENT_ROOT"]; ///var/web/procedures/symfony_demo/web
        $data=$this->renderView('OVEProceduresBundle:manuel:html.html.twig', array("procedures"=>$procedures));
        $fp = fopen("$path/uploads/manuel/manuel.html", 'w');
        fwrite($fp, $data);
        fclose($fp);

        //** Convertion HTML en ODT *******************************************
        shell_exec("python /var/web/gestform2/DocumentConverter.py $path/uploads/manuel/manuel.html $path/uploads/manuel/manuel.odt");


        //** Mise en place trame ODT ******************************************
        shell_exec("rm -Rf $path/uploads/manuel/manuel");
        shell_exec("unzip $path/uploads/manuel/manuel.odt -d $path/uploads/manuel/manuel");

        shell_exec("rm -Rf $path/uploads/manuel/trame");
        shell_exec("unzip $path/uploads/manuel/trame.odt -d $path/uploads/manuel/trame");


        //** Remplace les styles de puces par ceux de la trame ****************
        // Doc http://php.net/manual/fr/function.preg-match-all.php et http://php.net/manual/fr/reference.pcre.pattern.modifiers.php 
        $content=file_get_contents("$path/uploads/manuel/trame/content.xml");
        $pattern='/<text:list-style style:name="L1">.*<\/text:list-style>/si';
        preg_match_all ($pattern, $content, $matches);
        if(count($matches)>0) {
          $styles=$matches[0][0];
          $content=file_get_contents("$path/uploads/manuel/manuel/content.xml");
          $content = preg_replace('/<text:list-style style:name="L1">.*<\/text:list-style>/si', $styles, $content);
          $content = preg_replace('/text:style-name="L.*"/U', 'text:style-name="L1"', $content);
          $fp = fopen("$path/uploads/manuel/trame/content.xml", 'w');
          fwrite($fp, $content);
          fclose($fp);
        }
        //copy("$path/uploads/manuel/manuel/content.xml","$path/uploads/manuel/trame/content.xml");



        //** Reconsctrution du fichier ODT ************************************
        shell_exec("rm -f $path/uploads/manuel/manuel.odt");
        shell_exec("cd $path/uploads/manuel/trame && zip -r ../manuel.odt *");


        //** Transformation en PDF ********************************************
        shell_exec("python /var/web/gestform2/DocumentConverter.py $path/uploads/manuel/manuel.odt $path/uploads/manuel/manuel.pdf");
        $this->sendMail();
        return $this->redirect($_SERVER["HTTP_REFERER"]);

    }


    private function sendMail() {
      $em = $this->getDoctrine()->getManager();
      $repository=$em->getRepository('OVEProceduresBundle:parametres');
      $obj = $repository->findOneBy(array('code' => 'mail_manuel'));
      $to=array("tony.galmiche.div@free.fr");
      if(is_object($obj)) {
        $to=explode("\r\n",$obj->getValeur());
      }
      $sujet = "[Manuel]Nouvelle version";
      $user = $this->getUser();
      $login = $user->getUserName(); //login
      $from  = $login."@ove.asso.fr";

      $url="https://".$_SERVER["HTTP_HOST"];
      $body=$this->renderView("OVEProceduresBundle:manuel:mail_manuel.html.twig", array('url' => $url));

      $cc=array("tony.galmiche@gmail.com","anneclaire.duchon@fondation-ove.fr","bastien.gonzalez@fondation-ove.fr");

      $message = \Swift_Message::newInstance()
        ->setSubject($sujet)
        ->setFrom($from)
        ->setTo($to)
        ->setCc($cc) 
        ->setBody($body, 'text/html');
      $this->get('mailer')->send($message);
    }



}
