<?php

namespace OVE\ProceduresBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OVE\ProceduresBundle\Entity\groupes;
use OVE\ProceduresBundle\Form\groupesType;

/**
 * groupes controller.
 *
 * @Route("/groupes")
 */
class groupesController extends Controller
{

    /**
     * Lists all groupes entities.
     *
     * @Route("/", name="groupes")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user  = $this->getUser();
        $roles = $user->getRoles();
        if(array_key_exists("ROLE_ADMIN",$roles)) {
          $entities = $em->getRepository('OVEProceduresBundle:groupes')->findAll();
        } else {
          $login = $user->getUserName();
          $entities = $em->getRepository('OVEProceduresBundle:groupes')->findby(array("createur"=>$login));
        }
        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new groupes entity.
     *
     * @Route("/", name="groupes_create")
     * @Method("POST")
     * @Template("OVEProceduresBundle:groupes:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new groupes();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groupes_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a groupes entity.
     *
     * @param groupes $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(groupes $entity)
    {
        $form = $this->createForm(new groupesType(), $entity, array(
            'action' => $this->generateUrl('groupes_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new groupes entity.
     *
     * @Route("/new", name="groupes_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new groupes();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a groupes entity.
     *
     * @Route("/{id}", name="groupes_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OVEProceduresBundle:groupes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find groupes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing groupes entity.
     *
     * @Route("/{id}/edit", name="groupes_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OVEProceduresBundle:groupes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find groupes entity.');
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
    * Creates a form to edit a groupes entity.
    *
    * @param groupes $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(groupes $entity)
    {
        $form = $this->createForm(new groupesType(), $entity, array(
            'action' => $this->generateUrl('groupes_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing groupes entity.
     *
     * @Route("/{id}", name="groupes_update")
     * @Method("PUT")
     * @Template("OVEProceduresBundle:groupes:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OVEProceduresBundle:groupes')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find groupes entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('groupes'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a groupes entity.
     *
     * @Route("/{id}", name="groupes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OVEProceduresBundle:groupes')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find groupes entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('groupes'));
    }

    /**
     * Creates a form to delete a groupes entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('groupes_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
