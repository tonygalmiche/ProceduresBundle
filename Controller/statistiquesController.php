<?php

namespace OVE\ProceduresBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OVE\ProceduresBundle\Entity\statistiques;
use OVE\ProceduresBundle\Form\statistiquesType;

/**
 * statistiques controller.
 *
 * @Route("/statistiques")
 */
class statistiquesController extends Controller
{

    /**
     * Lists all statistiques entities.
     *
     * @Route("/", name="statistiques")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $f=array(); //f=formulaire
        $f["idprocedure"]     = $this->getRequest()->query->get('idprocedure');
        $f["login"]      = $this->getRequest()->query->get('login');
        $SQL="SELECT e FROM OVEProceduresBundle:statistiques e WHERE e.id>0 ";
        if($f["idprocedure"]<>"")  $SQL.=" AND e.idprocedure like '%".$f["idprocedure"]."%' ";
        if($f["login"]<>"")        $SQL.=" AND e.login       like '%".$f["login"]."%' ";
        $SQL.=" ORDER BY e.date DESC";
        $entities=$em->createQuery($SQL)->getResult();
        $results=array();
        foreach($entities as $v) {
          $results[]=array(
            "id"          => $v->getId(),
            "idprocedure" => $v->getIdprocedure(),
            "date"        => $v->getDate(),
            "login"       => $v->getLogin(),
          );
        }
        return array(
            'entities' => $results,
            'f'        => $f
        );
    }
}
