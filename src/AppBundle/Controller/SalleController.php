<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Salle;
use AppBundle\Entity\TypeSalle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


/**
 * Salle controller.
 *
 * @Route("salle")
 */
class SalleController extends Controller
{
    /**
     * Lists all salle entities.
     *
     * @Route("/", name="salle_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $query = 'SELECT s.id, s.numero, s.capacite, t.nom typeSalle FROM SALLE s
        JOIN  TYPESALLE t ON s.typeSalleId = t.id';

        $statement= $em->getConnection()->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $salles = $result;

        return $this->render('salle/index.html.twig', array(
            'salles' => $salles,
            'user' => $this->get('session')->get('user'),
        ));
    }


    /**
     * Lists all salle statistics.
     *
     * @Route("/statistics", name="statistique")
     * @Method("GET")
     */
    public function statistics()
    {
        $em = $this->getDoctrine()->getManager();
        $query = 'SELECT s.numero AS SALLE, t.nom as TYPESALLE, R.YEAR, R.MONTH, R.WEEK, SUM(R.duree) as TEMPS, SUM(R.duree)/27 as STAT FROM SALLE s
                    JOIN  TYPESALLE t ON s.typeSalleId = t.id 
                    JOIN (select r.salleId as SALLEID, 
                      r.duree as DUREE, 
                      extract(year from r.dateDebut) as YEAR, 
                      extract(month from r.dateDebut) as MONTH, 
                      extract(week from r.dateDebut) AS WEEK 
                      from reserve r) R ON R.SALLEID = s.id 
                    group by s.numero,t.nom,R.YEAR,R.MONTH,R.WEEK
                    order by s.numero, R.YEAR,R.MONTH,R.WEEK';

        $statement= $em->getConnection()->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();

        return $this->render('salle/statistique.html.twig', array(
            'results' => $results,
            'user' => $this->get('session')->get('user'),
        ));
    }

    /**
     * Creates a new salle entity.
     *
     * @Route("/new", name="salle_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $salle = new Salle();
        $em = $this->getDoctrine()->getManager();
        $typesSalles = $em->getRepository('AppBundle:TypeSalle')->findAll();
        $arraySuggestion=[];
        foreach ($typesSalles as $typeSalle)
        {
            $arraySuggestion[$typeSalle->getNom()] = $typeSalle->getId();
        }

        $form = $form = $this->createFormBuilder()
            ->add('type_de_salle', ChoiceType::class, array(
                'choices'  =>$arraySuggestion))
            ->add('numero_de_salle', TextType::class)
            ->add('capacite', IntegerType::class)
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle->setTypeSalleId($form->getData()['type_de_salle']);
            $salle->setCapacite($form->getData()['capacite']);
            $salle->setNumero($form->getData()['numero_de_salle']);
            $em->persist($salle);
            $em->flush();

            return $this->redirectToRoute('salle_show', array('id' => $salle->getId()));
        }

        return $this->render('salle/new.html.twig', array(
            'salle' => $salle,
            'form' => $form->createView(),
            'user' => $this->get('session')->get('user'),
        ));
    }

    /**
     * Finds and displays a salle entity.
     *
     * @Route("/{id}", name="salle_show")
     * @Method("GET")
     */
    public function showAction(Salle $salle)
    {
        $deleteForm = $this->createDeleteForm($salle);

        $em = $this->getDoctrine()->getManager();
        $query = 'SELECT s.id, s.numero, s.capacite, t.nom typeSalle FROM SALLE s
        JOIN  TYPESALLE t ON s.typeSalleId = t.id where s.id = '. $salle -> getId();
        $statement= $em->getConnection()->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $full_salle = $result;
        return $this->render('salle/show.html.twig', array(
            'salle' => $full_salle,
            'delete_form' => $deleteForm->createView(),
            'user' => $this->get('session')->get('user'),
        ));
    }

    /**
     * Displays a form to edit an existing salle entity.
     *
     * @Route("/{id}/edit", name="salle_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Salle $salle)
    {
        $em=$this->getDoctrine()->getManager();
        $deleteForm = $this->createDeleteForm($salle);

        $typesSalles = $em->getRepository('AppBundle:TypeSalle')->findAll();
        $arraySuggestion=[];
        foreach ($typesSalles as $typeSalle)
        {
            $arraySuggestion[$typeSalle->getNom()] = $typeSalle->getId();
            if($salle->getTypeSalleId() == $typeSalle -> getId()){
                $selectedType = $typeSalle -> getId();
            }
        }

        $form = $this->createFormBuilder()
            ->add('type_de_salle', ChoiceType::class, array(
                'choices'  =>$arraySuggestion,
                'data' => $selectedType))
            ->add('numero_de_salle', TextType::class,array('data'=>$salle->getNumero()))
            ->add('capacite', IntegerType::class,array('data'=>$salle->getCapacite()))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $salle->setTypeSalleId($form->getData()['type_de_salle']);
            $salle->setCapacite($form->getData()['capacite']);
            $salle->setNumero($form->getData()['numero_de_salle']);
            $em->flush();
            return $this->redirectToRoute('salle_edit', array('id' => $salle->getId()));
        }

        return $this->render('salle/edit.html.twig', array(
            'salle' => $salle,
            'edit_form' => $form->createView(),
            'delete_form' => $deleteForm->createView(),
            'user' => $this->get('session')->get('user'),
        ));
    }

    /**
     * Deletes a salle entity.
     *
     * @Route("/{id}", name="salle_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Salle $salle)
    {
        $form = $this->createDeleteForm($salle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($salle);
            $em->flush();
        }

        return $this->redirectToRoute('salle_index');
    }

    /**
     * Creates a form to delete a salle entity.
     *
     * @param Salle $salle The salle entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Salle $salle)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('salle_delete', array('id' => $salle->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
