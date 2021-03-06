<?php

namespace RestaurantBundle\Controller;

use RestaurantBundle\Entity\Booking;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Booking controller.
 *
 * @Route("booking")
 */
class BookingController extends Controller
{
    /**
     * Lists all booking entities.
     *
     * @Route("/", name="booking_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bookings = $em->getRepository('RestaurantBundle:Booking')->findAll();

        return $this->render('booking/index.html.twig', array(
            'bookings' => $bookings,
        ));
    }

    /**
     * Creates a new booking entity.
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     * @Route("/new", name="booking_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $booking = new Booking();
        $form = $this->createForm('RestaurantBundle\Form\Type\BookingType', $booking);
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

            return $this->redirectToRoute('booking_show', array('id' => $booking->getId()));
        }

        return $this->render('booking/new.html.twig', array(
            'booking' => $booking,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a booking entity.
     *
     * @param Booking $booking
     *
     * @return Response
     *
     * @Route("/{id}", name="booking_show")
     * @Method("GET")
     */
    public function showAction(Booking $booking)
    {
        $deleteForm = $this->createDeleteForm($booking);

        return $this->render('booking/show.html.twig', array(
            'booking' => $booking,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing booking entity.
     *
     * @param Request $request
     * @param Booking $booking
     *
     * @return RedirectResponse|Response
     *
     * @Route("/{id}/edit", name="booking_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Booking $booking)
    {
        $deleteForm = $this->createDeleteForm($booking);
        $editForm = $this->createForm('RestaurantBundle\Form\Type\BookingType', $booking);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('booking_edit', array('id' => $booking->getId()));
        }

        return $this->render('booking/edit.html.twig', array(
            'booking' => $booking,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a booking entity.
     *
     * @param Request $request
     * @param Booking $booking
     *
     * @return RedirectResponse
     *
     * @Route("/{id}", name="booking_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Booking $booking)
    {
        $form = $this->createDeleteForm($booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($booking);
            $em->flush();
        }

        return $this->redirectToRoute('booking_index');
    }

    /**
     * Creates a form to delete a booking entity.
     *
     * @param Booking $booking The booking entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Booking $booking)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('booking_delete', array('id' => $booking->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
