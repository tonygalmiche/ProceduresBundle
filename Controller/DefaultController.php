<?php

namespace OVE\ProceduresBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use OVE\ProceduresBundle\Entity\procedures;
use OVE\ProceduresBundle\Form\proceduresType;


/**
 * Default controller.
 *
 * @Route("/")
 */
class DefaultController extends Controller
{


    /**
     * @Route("/", name="default_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        /*
        $user = $this->getUser();
        print_r($user);
        echo $user->getUserName();
        if(method_exists ($user, "getName" )) echo $user->getName();
        return array();
        */

        return $this->redirect($this->generateUrl('procedures'));

        /*
        $thesaurus=$this->container->getParameter('thesaurus');
        $url=$thesaurus["domaine"];
        $json=file_get_contents ($url);
        $json=json_decode($json,true);
        $tab=array();
        foreach($json as $k=>$v) {
          $key=$v["text"];
          $val=$v["description"];
          $tab["id_$key"]=array("id"=>$key,"text"=>$key."-".$val);
        }
        ksort($tab);
        $domaine=array();
        foreach($tab as $k=>$v) {
          $domaine[]=$v;
        }
        $domaine=json_encode($domaine);
        $domaine=str_replace("'","\'",$domaine);
        $domaine=str_replace('"','\"',$domaine);
        $id     = $this->getRequest()->query->get('id');
        $action = $this->getRequest()->query->get('action');
        $user  = $this->getUser();
        $roles = $user->getRoles();
        if(in_array("ROLE_ADMIN", $roles) or in_array("ROLE_PARAM",$roles)) $ROLE_PARAM=true;
        $em = $this->getDoctrine()->getManager();
        if($action=="creer") {
          $p = new Procedures;
          $p->setFiche("Nouveau");
          $em = $this->getDoctrine()->getManager();
          $em->persist($p);
          $em->flush();
        }
        $p="";
        if($id>0) {
          $p = $em->getRepository('OVEProceduresBundle:procedures')->findOneBy(array("id"=>$id));
        }
        $form="";
        if(is_object($p)) {
          $form = $this->createForm(new proceduresType(), $p);
          $form = $form->createView();
        }
        $entities = $em->getRepository('OVEProceduresBundle:procedures')->findBy(array(), array('fiche' => 'ASC'));
        return array(
            'entities' => $entities,
            'form'     => $form,
            'p'        => $p,
            'domaine'  => $domaine
        );
        */
    }


    /**
     * @Route("/parametrage", name="default_parametrage")
     * @Method("GET")
     * @Template()
     */
    public function parametrageAction() 
    {
        return array();
    }

    /**
     * @Route("/administration", name="default_administration")
     * @Method("GET")
     * @Template()
     */
    public function administrationAction() 
    {
        return array();
    }



}
