<?php

namespace RestaurantBundle\Controller;

use RestaurantBundle\Entity\Dish;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Dish controller.
 *
 * @Route("dish")
 */
class DishController extends Controller
{
    /**
     * Lists all dish entities.
     *
     * @Route("/", name="dish_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $dishes = $em->getRepository('RestaurantBundle:Dish')->findAll();

        return $this->render('dish/index.html.twig', array(
            'dishes' => $dishes,
        ));
    }

    /**
     * Creates a new dish entity.
     *
     * @Route("/new", name="dish_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dish = new Dish();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $dish->setAuthor($user->getUsername());

        $form = $this->createForm('RestaurantBundle\Form\DishType', $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('in_validation')->isClicked()) {
                $status = 'in_validation';
            } elseif ($form->get('refuse')->isClicked()) {
                $status = 'refuse';
            } elseif ($form->get('valid')->isClicked()) {
                $status = 'valid';
            } else {
                $status = 'draft';
            }

            $dish->setStatus($status);

            $em = $this->getDoctrine()->getManager();
            $em->persist($dish);
            $em->flush($dish);

            return $this->redirectToRoute('dish_show', array('id' => $dish->getId()));
        }

        return $this->render('dish/new.html.twig', array(
            'dish' => $dish,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a dish entity.
     *
     * @Route("/{id}", name="dish_show")
     * @Method("GET")
     */
    public function showAction(Dish $dish)
    {
        $deleteForm = $this->createDeleteForm($dish);

        return $this->render('dish/show.html.twig', array(
            'dish' => $dish,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing dish entity.
     *
     * @Route("/{id}/edit", name="dish_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Dish $dish)
    {
        $deleteForm = $this->createDeleteForm($dish);
        $editForm = $this->createForm('RestaurantBundle\Form\DishType', $dish);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($editForm->get('in_validation')->isClicked()) {
                $status = 'in_validation';
            } elseif ($editForm->get('refuse')->isClicked()) {
                $status = 'refuse';
            } elseif ($editForm->get('valid')->isClicked()) {
                $status = 'valid';
            } else {
                $status = 'draft';
            }

            $dish->setStatus($status);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dish_edit', array('id' => $dish->getId()));
        }

        return $this->render('dish/edit.html.twig', array(
            'dish' => $dish,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Change the status of the entity.
     *
     * @Route("/{id}/validation/{status}", name="dish_validation")
     * @Method("GET")
     */
    public function validationAction(Dish $dish, $status)
    {
        $dish->setStatus($status);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('menu_show', array('id' => $dish->getId()));
    }

    /**
     * Deletes a dish entity.
     *
     * @Route("/{id}", name="dish_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Dish $dish)
    {
        $form = $this->createDeleteForm($dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($dish);
            $em->flush($dish);
        }

        return $this->redirectToRoute('dish_index');
    }

    /**
     * Creates a form to delete a dish entity.
     *
     * @param Dish $dish The dish entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Dish $dish)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dish_delete', array('id' => $dish->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
