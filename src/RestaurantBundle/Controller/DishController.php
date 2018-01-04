<?php

namespace RestaurantBundle\Controller;

use RestaurantBundle\Entity\Dish;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * Gets the status of a dish.
     *
     * @param Form  $form
     * @param Dish $dish
     *
     * @return string
     */
    private function getDishStatus(Form $form, Dish $dish)
    {
        if ($form->get('in_validation')->isClicked()) {
            $status = 'in_validation';

            $this->get('restaurant.mailer')->dishMailToReviewers($dish);
        } elseif ($form->get('refuse')->isClicked()) {
            $status = 'refuse';
        } elseif ($form->get('valid')->isClicked()) {
            $status = 'valid';

            $this->get('restaurant.mailer')->dishMailToWaiters($dish);
        } else {
            $status = 'draft';
        }

        return $status;
    }

    /**
     * Creates a new dish entity.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/new", name="dish_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $dish = new Dish();
        $user = $this->getUser();
        $dish->setAuthor($user->getUsername());

        $form = $this->createForm('RestaurantBundle\Form\Type\DishType', $dish);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $status = $this->getDishStatus($form, $dish);

            if (!empty($dish->getImage())) {
                $file = $dish->getImage();
                $fileName = $this->get('restaurant.uploader')->upload($file);

                $dish->setImage($fileName);
            }
            $dish->setStatus($status);

            $em->persist($dish);
            $em->flush();

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
     * @param Dish $dish
     *
     * @return Response
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
     * @param Request $request
     * @param Dish    $dish
     *
     * @return Response
     *
     * @Route("/{id}/edit", name="dish_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Dish $dish)
    {
        if (!empty($dish->getImage())) {
            $dish->setImage(
                new File($this->getParameter('images_directory').'/'.$dish->getImage())
            );
        }
        $deleteForm = $this->createDeleteForm($dish);
        $editForm = $this->createForm('RestaurantBundle\Form\Type\DishType', $dish);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $status = $this->getDishStatus($editForm, $dish);

            $dish->setStatus($status);

            $em->flush();

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
     * @param Dish   $dish
     * @param string $status
     *
     * @return Response
     *
     * @Route("/{id}/validation/{status}", name="dish_validation")
     * @Method("GET")
     */
    public function validationAction(Dish $dish, $status)
    {
        $dish->setStatus($status);

        if ('valid' === $status) {
            $this->get('restaurant.mailer')->dishMailToWaiters($dish);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('dish_show', array('id' => $dish->getId()));
    }

    /**
     * Deletes a dish entity.
     *
     * @param Request $request
     * @param Dish    $dish
     *
     * @return Response
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
            $em->flush();
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
