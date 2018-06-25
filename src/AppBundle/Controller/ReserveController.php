<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reserve;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Reserve controller.
 *
 * @Route("reserve")
 */
class ReserveController extends Controller
{
    /**
     * Lists all reserve entities.
     *
     * @Route("/", name="reserve_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reserves = $em->getRepository('AppBundle:Reserve')->findAll();

        return $this->render('reserve/index.html.twig', array(
            'reserves' => $reserves,
            'user' => $this->get('session')->get('user'),
        ));
    }

    /**
     * Creates a new reserve entity.
     *
     * @Route("/new", name="reserve_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $reserve = new Reserve();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('AppBundle\Form\ReserveType', $reserve);
        $typesSalles = $em->getRepository('AppBundle:TypeSalle')->findAll();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reserve);
            $em->flush();

            return $this->redirectToRoute('reserve_show', array('id' => $reserve->getId()));
        }

        return $this->render('reserve/new.html.twig', array(
            'reserve' => $reserve,
            'typeSalle'=> $typesSalles,
            'form' => $form->createView(),
            'user' => $this->get('session')->get('user'),
        ));
    }

    /**
     * Finds and displays a reserve entity.
     *
     * @Route("/{id}", name="reserve_show")
     * @Method("GET")
     */
    public function showAction(Reserve $reserve)
    {
        $deleteForm = $this->createDeleteForm($reserve);

        return $this->render('reserve/show.html.twig', array(
            'reserve' => $reserve,
            'delete_form' => $deleteForm->createView(),
            'user' => $this->get('session')->get('user'),
        ));
    }

    /**
     * Displays a form to edit an existing reserve entity.
     *
     * @Route("/{id}/edit", name="reserve_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Reserve $reserve)
    {
        $deleteForm = $this->createDeleteForm($reserve);
        $editForm = $this->createForm('AppBundle\Form\ReserveType', $reserve);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reserve_edit', array('id' => $reserve->getId()));
        }

        return $this->render('reserve/edit.html.twig', array(
            'reserve' => $reserve,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'user' => $this->get('session')->get('user'),
        ));
    }

    /**
     * Deletes a reserve entity.
     *
     * @Route("/{id}", name="reserve_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Reserve $reserve)
    {
        $form = $this->createDeleteForm($reserve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reserve);
            $em->flush();
        }

        return $this->redirectToRoute('reserve_index');
    }

    /**
     * Creates a form to delete a reserve entity.
     *
     * @param Reserve $reserve The reserve entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Reserve $reserve)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reserve_delete', array('id' => $reserve->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * get salles disponibilities in ajax
     * @Route("/new/disponibilities", name="reserve_dispo")
     * @Method ("GET")
     * @return  Response
     */
    public function ajaxGetDisponibilities(Request $request)
    {
        $params = array("typeSalle"=>$request->get('typeSelected'),
            "dateSelected"=>$request->get('dateSelected'),
            "capaciteSelected"=>$request->get('capaciteSelected'));
        $em=$this->getDoctrine()->getManager();
        $query = "Select s.capacite,s.numero, t.nom from SALLE s 
                  JOIN TYPESALLE t ON s.typeSalleid = t.id 
                   AND s.id not in 
                  (select r.salleId from RESERVE r where r.dateDebut = '".$params['dateSelected']."' group by r.salleId HAVING(SUM(r.duree)>=9*60))";
        if($params["typeSalle"]){
            $query.=' AND s.typeSalleId ='.$params["typeSalle"];
        }
        if($params["capaciteSelected"]){
            $query.=' AND s.capacite >='.$params["capaciteSelected"];
        }
        $query.=';';

        $statement = $em->getConnection()->prepare($query);
        $statement->execute();

        $result = $statement->fetchAll();
        if (count($result)){
            $htmlToRender = $this->render("/reserve/dispo.html.twig",array("salles"=>$result));
        }else{
            $htmlToRender = "";
        }
        return new Response($htmlToRender->getContent());
    }

    /**
     * get salles disponibilities in ajax
     * @Route("/new/hoursDispo", name="hours_dispo")
     * @Method ("GET")
     * @return  Response
     */
    public function ajaxHoursDispo(Request $request)
    {
        $htmlToRender = $this->render("/reserve/hours.html.twig");
        return new Response($htmlToRender->getContent());
    }
}
