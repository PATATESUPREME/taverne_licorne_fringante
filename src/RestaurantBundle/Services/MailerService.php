<?php

namespace RestaurantBundle\Services;

use RestaurantBundle\Entity\Dish;
use RestaurantBundle\Entity\Menu;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailerService
{
    private $em;
    private $mailer;
    private $token_storage;
    private $translator;
    private $templating;

    public function __construct(ContainerInterface $container)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->mailer = $container->get('mailer');
        $this->token_storage = $container->get('security.token_storage');
        $this->translator = $container->get('translator');
        $this->templating = $container->get('templating');
    }

    /**
     * @param Dish $dish
     */
    public function dishMailToWaiters(Dish $dish)
    {
        $possibleReviewer = $this->em
            ->getRepository('RestaurantBundle:User')
            ->findPossibleReviewerList();

        $from = method_exists ( $this->token_storage->getToken()->getUser() , 'getEmail' ) ?
            $this->token_storage->getToken()->getUser()->getEmail() :
            'super.admin.taverne.licorne.fringante@gmail.com'
        ;

        $subject =
            '[' . $this->translator->trans('website_name', array(), 'general') . ']' .
            $this->translator->trans('dish_confirmed_subject', array(), 'mail');
        $mail = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($possibleReviewer)
            ->setBody(
                $this->templating->render(
                    'email/dish_confirmed.html.twig',
                    array(
                        'dish' => $dish)
                ),
                'text/html'
            )
        ;
        $this->mailer->send($mail);
    }

    /**
     * @param Dish $dish
     */
    public function dishMailToReviewers(Dish $dish)
    {
        $possibleReviewer = $this->em
            ->getRepository('RestaurantBundle:User')
            ->findPossibleReviewerList();

        $from = method_exists ( $this->token_storage->getToken()->getUser() , 'getEmail' ) ?
            $this->token_storage->getToken()->getUser()->getEmail() :
            'super.admin.taverne.licorne.fringante@gmail.com'
        ;

        $subject =
            '[' . $this->translator->trans('website_name', array(), 'general') . ']' .
            $this->translator->trans('dish_confirmation_subject', array(), 'mail');
        $mail = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($possibleReviewer)
            ->setBody(
                $this->templating->render(
                    'email/dish_confirmation.html.twig',
                    array(
                        'dish' => $dish)
                ),
                'text/html'
            )
        ;
        $this->mailer->send($mail);
    }

    /**
     * @param Menu $menu
     */
    public function menuMailToWaiters(Menu $menu)
    {
        $possibleReviewer = $this->em
            ->getRepository('RestaurantBundle:User')
            ->findPossibleWaiterList();

        $from = method_exists ( $this->token_storage->getToken()->getUser() , 'getEmail' ) ?
            $this->token_storage->getToken()->getUser()->getEmail() :
            'super.admin.taverne.licorne.fringante@gmail.com'
        ;

        $subject =
            '[' . $this->translator->trans('website_name', array(), 'general') . ']' .
            $this->translator->trans('menu_confirmed_subject', array(), 'mail');
        $mail = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($possibleReviewer)
            ->setBody(
                $this->templating->render(
                    'email/menu_confirmed.html.twig',
                    array(
                        'menu' => $menu)
                ),
                'text/html'
            )
        ;
        $this->mailer->send($mail);
    }

    /**
     * @param Menu $menu
     */
    public function menuMailToReviewers(Menu $menu)
    {
        $possibleReviewer = $this->em
            ->getRepository('RestaurantBundle:User')
            ->findPossibleReviewerList();

        $from = method_exists ( $this->token_storage->getToken()->getUser() , 'getEmail' ) ?
            $this->token_storage->getToken()->getUser()->getEmail() :
            'super.admin.taverne.licorne.fringante@gmail.com'
        ;

        $subject =
            '[' . $this->translator->trans('website_name', array(), 'general') . ']' .
            $this->translator->trans('menu_confirmation_subject', array(), 'mail');
        $mail = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($possibleReviewer)
            ->setBody(
                $this->templating->render(
                    'email/menu_confirmation.html.twig',
                    array(
                        'menu' => $menu)
                ),
                'text/html'
            )
        ;
        $this->mailer->send($mail);
    }

    /**
     *
     */
    public function startMenuVerification()
    {
        $emptyDishesMenu = $this->em
            ->getRepository('RestaurantBundle:Menu')
            ->findEmptyDishesMenuList();

        if (sizeof($emptyDishesMenu) > 0) {
            $from = 'system.taverne.licorne.fringante@gmail.com';
            $to = 'admin.taverne.licorne.fringante@gmail.com';

            $subject =
                '[' . $this->translator->trans('website_name', array(), 'general') . ']' .
                $this->translator->trans('menu_empty_subject', array(), 'mail');
            $mail = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setBody(
                    $this->templating->render(
                        'email/menu_empty.html.twig',
                        array(
                            'menus' => $emptyDishesMenu
                        )
                    ),
                    'text/html'
                )
            ;
            $this->mailer->send($mail);
        }

    }
}