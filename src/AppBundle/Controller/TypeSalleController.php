<?php

namespace AppBundle\Controller;

use AppBundle\Entity\TypeSalle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Typesalle controller.
 *
 * @Route("typesalle")
 */
class TypeSalleController extends Controller
{
    /**
     * Lists all typeSalle entities.
     *
     * @Route("/", name="typesalle_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeSalles = $em->getRepository('AppBundle:TypeSalle')->findAll();

        return $this->render('typesalle/index.html.twig', array(
            'typeSalles' => $typeSalles,
            'isConnected'=>$this->get('session')->get('isConnected'),
            'isAdmin'=>$this->get('session')->get('isAdmin'),
        ));
    }

    /**
     * Creates a new typeSalle entity.
     *
     * @Route("/new", name="typesalle_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeSalle = new Typesalle();
        $form = $this->createForm('AppBundle\Form\TypeSalleType', $typeSalle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeSalle);
            $em->flush();

            return $this->redirectToRoute('typesalle_show', array('id' => $typeSalle->getId()));
        }

        return $this->render('typesalle/new.html.twig', array(
            'typeSalle' => $typeSalle,
            'form' => $form->createView(),
            'isConnected'=>$this->get('session')->get('isConnected'),
            'isAdmin'=>$this->get('session')->get('isAdmin'),
        ));
    }

    /**
     * Finds and displays a typeSalle entity.
     *
     * @Route("/{id}", name="typesalle_show")
     * @Method("GET")
     */
    public function showAction(TypeSalle $typeSalle)
    {
        $deleteForm = $this->createDeleteForm($typeSalle);

        return $this->render('typesalle/show.html.twig', array(
            'typeSalle' => $typeSalle,
            'delete_form' => $deleteForm->createView(),
            'isConnected'=>$this->get('session')->get('isConnected'),
            'isAdmin'=>$this->get('session')->get('isAdmin'),
        ));
    }

    /**
     * Displays a form to edit an existing typeSalle entity.
     *
     * @Route("/{id}/edit", name="typesalle_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeSalle $typeSalle)
    {
        $deleteForm = $this->createDeleteForm($typeSalle);
        $editForm = $this->createForm('AppBundle\Form\TypeSalleType', $typeSalle);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('typesalle_edit', array('id' => $typeSalle->getId()));
        }

        return $this->render('typesalle/edit.html.twig', array(
            'typeSalle' => $typeSalle,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'isConnected'=>$this->get('session')->get('isConnected'),
            'isAdmin'=>$this->get('session')->get('isAdmin'),
        ));
    }

    /**
     * Deletes a typeSalle entity.
     *
     * @Route("/{id}", name="typesalle_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeSalle $typeSalle)
    {
        $form = $this->createDeleteForm($typeSalle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeSalle);
            $em->flush();
        }

        return $this->redirectToRoute('typesalle_index');
    }

    /**
     * Creates a form to delete a typeSalle entity.
     *
     * @param TypeSalle $typeSalle The typeSalle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeSalle $typeSalle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typesalle_delete', array('id' => $typeSalle->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
