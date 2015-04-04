<?php

namespace OVE\ProceduresBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use OVE\ProceduresBundle\Entity\parametres;
use OVE\ProceduresBundle\Form\parametresType;

/**
 * parametres controller.
 *
 * @Route("/parametres")
 */
class parametresController extends Controller
{

    /**
     * Lists all parametres entities.
     *
     * @Route("/", name="parametres")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {


        $path=getcwd();
        $path.="/uploads/manuel";
        //echo $path;
        $trames=array();
        foreach (glob("$path/trame-*.odt") as $filename) {
            $trames[]=basename($filename);
            //echo "$filename occupe " . filesize($filename) . "<br>\n";
        }
        arsort($trames);
        array_unshift ( $trames , "trame.odt");
        //print_r($trames);

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('OVEProceduresBundle:parametres')->findAll();

        return array(
            'entities' => $entities,
            'trames'   => $trames,
        );
    }
    /**
     * Creates a new parametres entity.
     *
     * @Route("/", name="parametres_create")
     * @Method("POST")
     * @Template("OVEProceduresBundle:parametres:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new parametres();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('parametres_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a parametres entity.
     *
     * @param parametres $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(parametres $entity)
    {
        $form = $this->createForm(new parametresType(), $entity, array(
            'action' => $this->generateUrl('parametres_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new parametres entity.
     *
     * @Route("/new", name="parametres_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new parametres();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a parametres entity.
     *
     * @Route("/{id}", name="parametres_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OVEProceduresBundle:parametres')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find parametres entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing parametres entity.
     *
     * @Route("/{id}/edit", name="parametres_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OVEProceduresBundle:parametres')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find parametres entity.');
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
    * Creates a form to edit a parametres entity.
    *
    * @param parametres $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(parametres $entity)
    {
        $form = $this->createForm(new parametresType(), $entity, array(
            'action' => $this->generateUrl('parametres_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing parametres entity.
     *
     * @Route("/{id}", name="parametres_update")
     * @Method("PUT")
     * @Template("OVEProceduresBundle:parametres:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('OVEProceduresBundle:parametres')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find parametres entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('parametres', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a parametres entity.
     *
     * @Route("/{id}", name="parametres_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('OVEProceduresBundle:parametres')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find parametres entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('parametres'));
    }

    /**
     * Creates a form to delete a parametres entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('parametres_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }






    /**
     * upload trame
     *
     * @Route("/upload", name="parametres_upload")
     * @Method("POST")
     */
    public function uploadAction(Request $request)
    {
        //echo "test";
        //print_r($_FILES);
        //if($_FILES["trame"]["type"]=="application/vnd.oasis.opendocument.text") {
          $path=getcwd();
          $path.="/uploads/manuel";
          $dest="trame-".date("Ymd-H:i:s").".odt";
          rename("$path/trame.odt","$path/$dest");
          $resultat = move_uploaded_file($_FILES['trame']['tmp_name'],"$path/trame.odt");
          //echo "resultat = $resultat";
        //}
        return $this->redirect($this->generateUrl('parametres'));
    }


    /**
     * upload signature
     *
     * @Route("/uploadsignature", name="parametres_uploadsignature")
     * @Method("POST")
     */
    public function uploadsignatureAction(Request $request)
    {
        $type=$_FILES["signature"]["type"];
        if($type=="image/png") {
          $path=getcwd();
          $path.="/uploads/manuel";
          $resultat = move_uploaded_file($_FILES['signature']['tmp_name'],"$path/signature.png");
        }
        return $this->redirect($this->generateUrl('parametres'));

    }





}
