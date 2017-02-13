<?php

namespace RestaurantBundle\Controller;

use RestaurantBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="default_index")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * Lists all valid menu.
     *
     * @Route("/front/menu", name="menu_front_index")
     * @Method("GET")
     */
    public function frontMenuIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $menus = $em->getRepository('RestaurantBundle:Menu')->findValidMenus();

        return $this->render('menu/front_index.html.twig', array(
            'menus' => $menus,
        ));
    }

    /**
     * Lists all valid dish.
     *
     * @Route("/front/dish", name="dish_front_index")
     * @Method("GET")
     */
    public function frontDishIndexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $dishes = $em->getRepository('RestaurantBundle:Dish')->findValidDishes();

        return $this->render('dish/front_index.html.twig', array(
            'dishes' => $dishes,
        ));
    }

    /**
     * Creates a new booking entity from front.
     *
     * @Route("/front/booking/new", name="booking_front_new")
     * @Method({"GET", "POST"})
     */
    public function frontNewAction(Request $request)
    {
        $booking = new Booking();
        $form = $this->createForm('RestaurantBundle\Form\BookingType', $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($booking);
            $em->flush($booking);

            return $this->redirectToRoute('booking_show', array('id' => $booking->getId()));
        }

        return $this->render('booking/front_new.html.twig', array(
            'booking' => $booking,
            'form' => $form->createView(),
        ));
    }
}
