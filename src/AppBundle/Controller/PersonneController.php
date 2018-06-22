<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Personne;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Personne controller.
 *
 * @Route("personne")
 */
class PersonneController extends Controller
{
    /**
     * Lists all personne entities.
     *
     * @Route("/", name="personne_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $personnes = $em->getRepository('AppBundle:Personne')->findAll();

        return $this->render('personne/index.html.twig', array(
            'personnes' => $personnes,
            'user' => $this->get('session')->get('user'),
        ));
    }

    /**
     * Creates a new personne entity.
     *
     * @Route("/new", name="personne_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $personne = new Personne();
        $form = $this->createForm('AppBundle\Form\PersonneType', $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $personne->setPwd(md5($personne->getPwd()));
            $personne->setIsAdmin(false);
            $em->persist($personne);
            $em->flush();

            if ($this->get('session')->get('user')["isAdmin"]==1){
                return $this->redirectToRoute('personne_show', array('id' => $personne->getId()));
            }else{
                $this->get('session')->set('creationUser',1);
                return $this->redirectToRoute('personne_connexion');
            }
        }

        return $this->render('personne/new.html.twig', array(
            'personne' => $personne,
            'form' => $form->createView(),
            'user' => $this->get('session')->get('user'),
        ));
    }
    /**
     * Connection to admin or user
     *
     * @Route("/connect",name="personne_connexion")
     *
     */
    public function connectAction(Request $request)
    {
        $personne = new Personne();
        $form = $this->createFormBuilder()
            ->add('login', TextType::class)
            ->add('pwd', PasswordType::class)
            ->add('validate', SubmitType::class, array('label' => 'Se connecter'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $personne->setLogin($form->getData()['login']);
            $personne->setPwd(md5($form->getData()['pwd']));

            return $this->validateFormUser($personne);
        }

        return $this->render("personne/connexion.html.twig", array(
            'form' => $form->createView(),
            'user' => $this->get('session')->get('user'),
            'errorLogin'=> $this->get('session')->get('errorLogin'),
            "creationUser"=> $this->get('session')->get('creationUser')
        ));
    }

    /**
     * deconnection to admin or user
     *
     * @Route("/deconnect",name="personne_deconnexion")
     *
     */
    public function deconnectAction(Request $request)
    {
        $this->get('session')->set('user',null);

        return $this->redirectToRoute("personne_connexion");
    }

    /**
     * validation of connection
     */
    public function validateFormUser(Personne $personne)
    {
        $repository = $this->getDoctrine()->getRepository(Personne::class);
        $databasePerson= $repository->findOneBy(['login'=>$personne->getLogin()]);
        if ($databasePerson->getPwd()==$personne->getPwd()){
            $this->get('session')->set('user',array(
                "id"=>$databasePerson->getId(),
                "prenom"=>$databasePerson->getNom(),
                "isAdmin"=>$databasePerson->getIsAdmin(),
                "isConnected"=>1));
            $this->get('session')->remove('errorLogin');
            $this->get('session')->remove('creationUser');
        }else{
            $this->get('session')->set('errorLogin',1);
           return $this->redirectToRoute("personne_connexion");
        }
        if ($databasePerson->getIsAdmin()){
            return $this->redirectToRoute("personne_index");
        }else{
            return $this->redirectToRoute("reserve_index");
        }

    }




    /**
     * Finds and displays a personne entity.
     *
     * @Route("/display-{id}", name="personne_show")
     * @Method("GET")
     */
    public function showAction(Personne $personne)
    {
        $deleteForm = $this->createDeleteForm($personne);

        return $this->render('personne/show.html.twig', array(
            'personne' => $personne,
            'delete_form' => $deleteForm->createView(),
            'user' => $this->get('session')->get('user'),
        ));
    }

    /**
     * Displays a form to edit an existing personne entity.
     *
     * @Route("/edit/{id}", name="personne_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Personne $personne)
    {
        $deleteForm = $this->createDeleteForm($personne);
        $editForm = $this->createForm('AppBundle\Form\PersonneType', $personne);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('personne_edit', array('id' => $personne->getId()));
        }

        return $this->render('personne/edit.html.twig', array(
            'personne' => $personne,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'user' => $this->get('session')->get('user'),
        ));
    }

    /**
     * Deletes a personne entity.
     *
     * @Route("/delete/{id}", name="personne_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Personne $personne)
    {
        $form = $this->createDeleteForm($personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($personne);
            $em->flush();
        }

        return $this->redirectToRoute('personne_index');
    }

    /**
     * Creates a form to delete a personne entity.
     *
     * @param Personne $personne The personne entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Personne $personne)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('personne_delete', array('id' => $personne->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
