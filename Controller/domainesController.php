<?php

namespace OVE\ProceduresBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OVE\ProceduresBundle\Entity\domaines;
use OVE\ProceduresBundle\Form\domainesType;

/**
 * domaines controller.
 *
 * @Route("/domaines")
 */
class domainesController extends Controller
{

    /**
     * Lists all domaines entities.
     *
     * @Route("/", name="domaines")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OVEProceduresBundle:domaines')->findAll();



        //** Thésaurus des domaines *********************************
        $thesaurus=$this->container->getParameter('thesaurus');
        $url=$thesaurus["domaine"];
        $json=file_get_contents ($url);
        //echo $json;
        /*
        $json=json_decode($json,true);
        $tab=array(); $x=array();
        foreach($json as $k=>$v) {
          $key=$v["text"];
          $val=$key."-".$v["description"];
          $tab["id_$key"]=array("id"=>$key,"text"=>$val);
          $x[$key]=$val;
        }
        */
        //print_r($tab);
        
        //ksort($tab);
        //$domaine=array(); 
        //foreach($tab as $k=>$v) {
        //  $domaine[]=$v;
        //}

        //$domaine=json_encode($domaine);
        //$domaine=str_replace("'","\'",$domaine);
        //$domaine=str_replace('"','\"',$domaine);
        //***********************************************************







        //$json='[{"id":"1","parent":"#","text":"Terme 1"},{"id":"2","parent":"#","text":"Terme 2"},{"id":"3","parent":"#","text":"Terme 3"},{"id":"4","parent":"1","text":"Terme 1a"},{"id":"5","parent":"1","text":"Terme 1b"}]';

          //[{"id":"8","parent":"#","text":"0 - Dispositions g\u00e9n\u00e9rales du manuel","description":"Responsable: responsable du contr\u00f4le interne et responsable qualit\u00e9"},{"id":"9","parent":"8","text":"01 - Conception et r\u00e9alisation des proc\u00e9dures","description":""},{"id":"11","parent":"#","text":"1 - L'usager","description":"Responsable: directeur g\u00e9n\u00e9ral adjoint"},{"id":"12","parent":"11","text":"11 - Accueil et admission","description":""},{"id":"39","parent":"#","text":"3 - Pilotage op\u00e9rationnel","description":"Responsable: ?"},{"id":"40","parent":"#","text":"4 - Gestion des ressources humaines","description":"Responsable: directeur ressources humaines"},{"id":"41","parent":"#","text":"5 - Gestion \u00e9conomique et financi\u00e8re","description":"Responsable: directrice gestion \u00e9conomique et financi\u00e8re"},{"id":"42","parent":"#","text":"6 - Travaux, patrimoine, s\u00e9curit\u00e9","description":"Responsable: responsable travaux, patrimoine, s\u00e9curit\u00e9"},{"id":"43","parent":"#","text":"7 - Restauration, hygi\u00e8ne","description":"Responsable: responsable restauration, hygi\u00e8ne"},{"id":"44","parent":"#","text":"8 - Syst\u00e8mes d'information et de connaissance","description":"Responsable: directeur ressourcial"},{"id":"45","parent":"517","text":"21 - Description et organisation de la Fondation","description":""},{"id":"46","parent":"39","text":"31 - D\u00e9veloppement de l'offre","description":""},{"id":"48","parent":"39","text":"32 - Qualit\u00e9","description":""},{"id":"49","parent":"39","text":"33 - Contr\u00f4le interne","description":""},{"id":"50","parent":"39","text":"34 - Communication","description":""},{"id":"51","parent":"44","text":"81 - Acc\u00e8s au syst\u00e8me d'information","description":""},{"id":"52","parent":"44","text":"82 - Mise \u00e0 disposition de mat\u00e9riel","description":""},{"id":"53","parent":"44","text":"83 - Informatique et libert\u00e9s","description":""},{"id":"54","parent":"44","text":"84 - Pr\u00e9cautions et vigilances","description":""},{"id":"57","parent":"11","text":"12 - Accompagnement","description":""},{"id":"70","parent":"11","text":"13 - Orientation et sortie","description":""},{"id":"72","parent":"11","text":"14 - Participation et droits des usagers","description":""},{"id":"74","parent":"41","text":"52 - Organisation financi\u00e8re d'une structure","description":""},{"id":"75","parent":"41","text":"53 - Fonctionnement","description":""},{"id":"76","parent":"41","text":"54 - Tr\u00e9sorerie","description":""},{"id":"77","parent":"41","text":"55 - Facturation","description":""},{"id":"78","parent":"42","text":"61 - Patrimoine","description":""},{"id":"79","parent":"42","text":"62 - Travaux","description":""},{"id":"80","parent":"42","text":"63 - S\u00e9curit\u00e9","description":""},{"id":"81","parent":"43","text":"71 - Fonctionnement d'une cuisine","description":""},{"id":"82","parent":"43","text":"72 - Maintenance d'une cuisine","description":""},{"id":"83","parent":"40","text":"42 - Paye","description":""},{"id":"84","parent":"40","text":"33 - Instances repr\u00e9sentatives du personnel","description":""},{"id":"85","parent":"40","text":"44 - Formation professionnelle continue","description":""},{"id":"86","parent":"40","text":"45 - Sant\u00e9 au travail","description":""},{"id":"87","parent":"40","text":"46 - Divers","description":""},{"id":"437","parent":"43","text":"73 - Achats","description":""},{"id":"438","parent":"43","text":"74 - Hygi\u00e8ne","description":""},{"id":"517","parent":"#","text":"2 - Fonctionnement des instances de la Fondation","description":"Responsable: secr\u00e9taire g\u00e9n\u00e9ral"},{"id":"520","parent":"39","text":"35 - Recherche","description":""},{"id":"522","parent":"40","text":"43 - Instances repr\u00e9sentatives du personnel","description":""},{"id":"523","parent":"41","text":"51 - Pilotage financier","description":""},{"id":"524","parent":"44","text":"85 - Plan de continuation et de reprise d'activit\u00e9","description":""}]<!DOCTYPE html>


        return array(
            'entities' => $entities,
            'json'     => $json
        );
    }
    /**
     * Creates a new domaines entity.
     *
     * @Route("/", name="domaines_create")
     * @Method("POST")
     * @Template("OVEProceduresBundle:domaines:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new domaines();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('domaines_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a domaines entity.
     *
     * @param domaines $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(domaines $entity)
    {
        $form = $this->createForm(new domainesType(), $entity, array(
            'action' => $this->generateUrl('domaines_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new domaines entity.
     *
     * @Route("/new", name="domaines_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new domaines();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a domaines entity.
     *
     * @Route("/{id}", name="domaines_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OVEProceduresBundle:domaines')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find domaines entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing domaines entity.
     *
     * @Route("/{id}/edit", name="domaines_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OVEProceduresBundle:domaines')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find domaines entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a domaines entity.
    *
    * @param domaines $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(domaines $entity)
    {
        $form = $this->createForm(new domainesType(), $entity, array(
            'action' => $this->generateUrl('domaines_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing domaines entity.
     *
     * @Route("/{id}", name="domaines_update")
     * @Method("PUT")
     * @Template("OVEProceduresBundle:domaines:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OVEProceduresBundle:domaines')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find domaines entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('domaines_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a domaines entity.
     *
     * @Route("/{id}", name="domaines_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OVEProceduresBundle:domaines')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find domaines entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('domaines'));
    }

    /**
     * Creates a form to delete a domaines entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('domaines_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
