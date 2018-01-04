<?php

namespace RestaurantBundle\Controller;

use RestaurantBundle\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menu controller.
 *
 * @Route("menu")
 */
class MenuController extends Controller
{
    /**
     * Lists all menu entities.
     *
     * @Route("/", name="menu_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $menus = $em->getRepository('RestaurantBundle:Menu')->findAll();

        return $this->render('menu/index.html.twig', array(
            'menus' => $menus,
        ));
    }

    /**
     * Gets the status of a dish.
     *
     * @param Form $form
     * @param Menu $menu
     *
     * @return string
     */
    private function getMenuStatus(Form $form, Menu $menu)
    {
        if ($form->get('in_validation')->isClicked()) {
            $status = 'in_validation';

            $this->get('restaurant.mailer')->menuMailToReviewers($menu);
        } elseif ($form->get('refuse')->isClicked()) {
            $status = 'refuse';
        } elseif ($form->get('valid')->isClicked()) {
            $status = 'valid';

            $this->get('restaurant.mailer')->menuMailToWaiters($menu);
        } else {
            $status = 'draft';
        }

        return $status;
    }

    /**
     * Creates a new menu entity.
     *
     * @param Request $request
     *
     * @return Response
     *
     * @Route("/new", name="menu_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $menu = new Menu();
        $user = $this->getUser();
        $menu->setAuthor($user->getUsername());

        $form = $this->createForm('RestaurantBundle\Form\Type\MenuType', $menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $status = $this->getMenuStatus($form, $menu);

            $menu->setStatus($status);

            $em->persist($menu);
            $em->flush();

            return $this->redirectToRoute('menu_show', array('id' => $menu->getId()));
        }

        return $this->render('menu/new.html.twig', array(
            'menu' => $menu,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a menu entity.
     *
     * @param Menu $menu
     *
     * @return Response
     *
     * @Route("/{id}", name="menu_show")
     * @Method("GET")
     */
    public function showAction(Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);

        return $this->render('menu/show.html.twig', array(
            'menu' => $menu,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing menu entity.
     *
     * @param Request $request
     * @param Menu $menu
     *
     * @return Response
     *
     * @Route("/{id}/edit", name="menu_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Menu $menu)
    {
        $deleteForm = $this->createDeleteForm($menu);
        $editForm = $this->createForm('RestaurantBundle\Form\Type\MenuType', $menu);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $status = $this->getMenuStatus($editForm, $menu);

            $menu->setStatus($status);

            $em->flush();

            return $this->redirectToRoute('menu_edit', array('id' => $menu->getId()));
        }

        return $this->render('menu/edit.html.twig', array(
            'menu' => $menu,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Change the status of the entity.
     *
     * @param Menu $menu
     * @param string $status
     *
     * @return Response
     *
     * @Route("/{id}/validation/{status}", name="menu_validation")
     * @Method("GET")
     */
    public function validationAction(Menu $menu, $status)
    {
        $menu->setStatus($status);

        if ('valid' === $status) {
            $this->get('restaurant.mailer')->menuMailToWaiters($menu);
        }

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('menu_show', array('id' => $menu->getId()));
    }

    /**
     * Deletes a menu entity.
     *
     * @param Request $request
     * @param Menu $menu
     *
     * @return Response
     *
     * @Route("/{id}", name="menu_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Menu $menu)
    {
        $form = $this->createDeleteForm($menu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($menu);
            $em->flush();
        }

        return $this->redirectToRoute('menu_index');
    }

    /**
     * Creates a form to delete a menu entity.
     *
     * @param Menu $menu The menu entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Menu $menu)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('menu_delete', array('id' => $menu->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
