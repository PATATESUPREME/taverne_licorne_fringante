<?php

namespace RestaurantBundle\Controller;

use RestaurantBundle\Entity\Booking;
use RestaurantBundle\Entity\Dish;
use RestaurantBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * Main page
     *
     * @return Response
     *
     * @Route("/", name="default_index")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * Lists all valid menu.
     *
     * @return Response
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
     * @return Response
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
     * @param Request $request
     *
     * @return Response
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
            $em->flush();

            $subject =
                '[' . $this->get('translator')->trans('website_name', array(), 'general') . ']' .
                $this->get('translator')->trans('booking_confirmation_subject', array(), 'mail');
            $mail = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('taverne.licorne.fringante@gmail.com')
                ->setTo($booking->getEmail())
                ->setBody(
                    $this->renderView(
                        'email/booking_confirmation.html.twig',
                        array(
                            'booking' => $booking)
                    ),
                    'text/html'
                )
            ;
            $this->get('mailer')->send($mail);

            return $this->redirectToRoute('default_index');
        }

        return $this->render('booking/front_new.html.twig', array(
            'booking' => $booking,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a menu entity on front.
     *
     * @param Menu $menu
     *
     * @return Response
     *
     * @Route("/front/menu/{id}", name="menu_front_show")
     * @Method("GET")
     */
    public function menuFrontShowAction(Menu $menu)
    {
        return $this->render('menu/front_show.html.twig', array(
            'menu' => $menu,
        ));
    }

    /**
     * Finds and displays a dish entity on front.
     *
     * @param Dish $dish
     *
     * @return Response
     *
     * @Route("/front/dish/{id}", name="dish_front_show")
     * @Method("GET")
     */
    public function dishFrontShowAction(Dish $dish)
    {
        return $this->render('dish/front_show.html.twig', array(
            'dish' => $dish,
        ));
    }
}
