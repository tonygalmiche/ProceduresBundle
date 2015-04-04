<?php

namespace OVE\ProceduresBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * procedures controller.
 *
 * @Route("/")
 */
class contactController extends Controller
{

    /**
     * Formulaire de contact
     *
     * @Route("/contact", name="contact")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
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
      ksort($tab);
      $domaine=array(); 
      foreach($tab as $k=>$v) {
        $domaine[]=$v;
      }
      $domaine=json_encode($domaine);
      //***********************************************************

      return array(
          'test' => "toto et tutu",
          'domaine'        => $domaine,
          'domaines_json'  => $domaines_json,
      );
    }



    /**
     * Formulaire de contact
     *
     * @Route("/contact", name="contact_send_mail")
     * @Method("PUT")
     * @Template()
     */
    public function sendMailAction()
    {
      $em = $this->getDoctrine()->getManager();

      $sujet=$_POST["sujet"];
      $contenu=$_POST["contenu"];
      $contenu=str_replace("\n","<br>",$contenu);

      $url="https://".$_SERVER["HTTP_HOST"].$_SERVER["SCRIPT_NAME"];
      $contenu="$contenu</p><p><small>Mail envoyé depuis l'application <a href='$url'>'Manuel de procédures'</a></small></p>";

      $user = $this->getUser();
      $login = $user->getUserName(); //login
      $from  = $login."@ove.asso.fr";


      //** Recherche des destinataires à partir des domaines ******************
      $domaines=$_POST["domaines"];
      $ids=explode(",", $domaines);
      $to=array();
      foreach($ids as $v) {
        $obj = $em->getRepository('OVEProceduresBundle:domaines')->findOneBy(array("termeid"=>$v));
        if(is_object($obj)) {
          $to[]=$obj->getResponsable()."@ove.asso.fr";
        }
      }
      $to=array_unique ($to);
      asort($to);
      //***********************************************************************


      //** Envoie du mail *****************************************************
      //$to=array("tony.galmiche@gmail.com");
      $cc=array("tony.galmiche@gmail.com");
      //$cc=array();

      $message = \Swift_Message::newInstance()
        ->setSubject($sujet)
        ->setFrom($from)
        ->setTo($to)
        ->setCc($cc) 
        ->setBody($contenu, 'text/html');
      $this->get('mailer')->send($message);
      //***********************************************************************


      return array(
          'from'          => $from,
          'to'            => implode(", ",$to),
          'domaines'      => $domaines,
          'sujet'         => $sujet,
          'contenu'       => $contenu,
      );


    }
}
