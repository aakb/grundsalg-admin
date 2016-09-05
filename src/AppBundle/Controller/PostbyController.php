<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\Postby;
use AppBundle\Form\PostbyType;
use AppBundle\Controller\BaseController;

/**
 * Postby controller.
 *
 * @Route("/postby")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class PostbyController extends BaseController
{

  public function init(Request $request) {
    parent::init($request);
    $this->breadcrumbs->addItem('postby.labels.singular', $this->generateUrl('postby'));
}


    /**
     * Lists all Postby entities.
     *
     * @Route("/", name="postby")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Postby')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Postby entity.
     *
     * @Route("/", name="postby_create")
     * @Method("POST")
     * @Template("AppBundle:Postby:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Postby();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('postby'));

        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Postby entity.
     *
     * @param Postby $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Postby $entity)
    {
        $form = $this->createForm('AppBundle\Form\PostbyType', $entity, array(
            'action' => $this->generateUrl('postby_create'),
            'method' => 'POST',
        ));

        $this->addUpdate($form, $this->generateUrl('postby'));

        return $form;
    }

    /**
     * Displays a form to create a new Postby entity.
     *
     * @Route("/new", name="postby_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('postby'));

        $entity = new Postby();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Postby entity.
     *
     * @Route("/{id}", name="postby_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Postby')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('postby_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Postby entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Postby entity.
     *
     * @Route("/{id}/edit", name="postby_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Postby $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('postby_show', array('id' => $entity->getId())));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('postby_show', array('id' => $entity->getId())));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Postby entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity->getId());

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Postby entity.
    *
    * @param Postby $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Postby $entity)
    {
        $form = $this->createForm('AppBundle\Form\PostbyType', $entity, array(
            'action' => $this->generateUrl('postby_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $this->addUpdate($form, $this->generateUrl('postby_show', array('id' => $entity->getId())));

        return $form;
    }
    /**
     * Edits an existing Postby entity.
     *
     * @Route("/{id}", name="postby_update")
     * @Method("PUT")
     * @Template("AppBundle:Postby:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Postby')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Postby entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('postby'));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Postby entity.
     *
     * @Route("/{id}", name="postby_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Postby')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Postby entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('postby'));
    }

    /**
     * Creates a form to delete a Postby entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('postby_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
