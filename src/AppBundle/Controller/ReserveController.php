<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reserve;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $salles = $em->getRepository('AppBundle:Salle')->findAll();
        $personnes = $em->getRepository('AppBundle:Personne')->findAll();

        return $this->render('reserve/index.html.twig', array(
            'reserves' => $reserves,
            'salles' => $salles,
            'personnes' => $personnes,
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
        $em = $this->getDoctrine()->getManager();

        $deleteForm = $this->createDeleteForm($reserve);
        $salles = $em->getRepository('AppBundle:Salle')->findAll();
        $personnes = $em->getRepository('AppBundle:Personne')->findAll();

        return $this->render('reserve/show.html.twig', array(
            'reserve' => $reserve,
            'salles' => $salles,
            'personnes' => $personnes,
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
        $query = "Select s.id,s.capacite,s.numero, t.nom from SALLE s 
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
            $htmlToRender = $this->render("void.html.twig");
        }
        return new Response($htmlToRender->getContent());
    }

    /**
     * get hours disponibilities in ajax
     * @Route("/new/hoursDispo", name="hours_dispo")
     * @Method ("GET")
     * @return  Response
     */
    public function ajaxHoursDispo(Request $request)
    {
        $params = array(
            "dateSelected"=>$request->get('dateSelected'),
            "idSalleToCheck"=>$request->get('idSalleToCheck')
        );

        $em= $this->getDoctrine()->getManager();
        $ReserveRepo = $em->getRepository("AppBundle:Reserve");
        $ArrayInitDispoHours=[
            0=>"08:00 - 08:30",
            1=>"08:30 - 09:00",
            2=>"09:00 - 09:30",
            3=>"09:30 - 10:00",
            4=>"10:00 - 10:30",
            5=>"10:30 - 11:00",
            6=>"11:00 - 11:30",
            7=>"11:30 - 12:00",
            8=>"12:00 - 12:30",
            9=>"12:30 - 13:00",
            10=>"13:00 - 13:30",
            11=>"13:30 - 14:00",
            12=>"14:00 - 14:30",
            13=>"14:30 - 15:00",
            14=>"15:00 - 15:30",
            15=>"15:30 - 16:00",
            16=>"16:30 - 17:00",
            17=>"17:00 - 17:30",
            18=>"17:30 - 18:00"
        ];
        $OccupiedHours=$ReserveRepo->getOccupiedHoursByIdSalle($params["dateSelected"],$params["idSalleToCheck"]);
        $occupiedCrenau = [];
        foreach ($OccupiedHours as $occHour)
        {
            $nbCrenau=$occHour["duree"]/30;
            $heureDebut = date("H:i",strtotime($occHour["datedebut"]));
            for ($i=1;$i<=$nbCrenau;$i++)
            {
                $heureFin= date("H:i",strtotime("2000-01-01 ".$heureDebut)+60*30);
                $occupiedCrenau[] = $heureDebut." - ".$heureFin;
                $heureDebut=$heureFin;
            }
        }

        foreach($occupiedCrenau as $crenau)
        {

            if (in_array($crenau,$ArrayInitDispoHours))
            {
                $index = array_search($crenau,$ArrayInitDispoHours);
                unset($ArrayInitDispoHours[$index]);
            }
        }

        $firstArray = [];
        $secondArray = [];
        $thirdArray=[];
        $count=0;
        foreach ($ArrayInitDispoHours as $c)
        {
            if ($count<=6)
            {
                $firstArray [array_search($c,$ArrayInitDispoHours)]= $c;
                $count++;
            }elseif ($count>6 && $count<=12)
            {
                $secondArray [array_search($c,$ArrayInitDispoHours)] = $c;
                $count++;
            }else{
                $thirdArray[array_search($c,$ArrayInitDispoHours)]=$c;
                $count++;
            }
        }


        $htmlToRender = $this->render("/reserve/hours.html.twig",
            array("hoursFirst"=>$firstArray,
            "hoursSecond"=>$secondArray,
                "hoursThird"=>$thirdArray));
        return new Response($htmlToRender->getContent());
    }

    /**
     * create reservation ajax
     * @Route("/new/create", name="create_reservation")
     * @Method ("GET")
     *
     */
    public function ajaxCreateReservation(Request $request){
        $em = $this->getDoctrine()->getManager();
        $reservation = new Reserve();
        $repository = $em->getRepository("AppBundle:Reserve");
        $params = array(
            "dateDebut"=>$request->get('dateDebut'),
            "dateFin"=>$request->get("dateFin"),
            "idSalle"=>$request->get('idSalle'),
            "duree"=>$request->get("duree")
        );
        $digits = 4;
        $code = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        $userId=$this->get("session")->get("user")["id"];
        $dateDeb= new \DateTime(date("Y-m-d H:i:s", strtotime($params["dateDebut"])));
        $dateFin= new \DateTime(date("Y-m-d H:i:s", strtotime($params["dateFin"])));
        $reservation->setPersonneId($userId);
        $reservation->setDateDebut($dateDeb);
        $reservation->setDateFin($dateFin);
        $reservation->setDuree(intval($params["duree"]));
        $reservation->setSalleId(intval($params["idSalle"]));
        $reservation->setCode($code);
        $reservation->setStatus(1);
        $em->persist($reservation);
        $em->flush();
        return new JsonResponse(json_encode(["id"=>$reservation->getId()]));
    }


}
