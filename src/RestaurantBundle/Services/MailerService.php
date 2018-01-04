<?php

namespace RestaurantBundle\Services;

use Doctrine\ORM\EntityManager;
use RestaurantBundle\Entity\Dish;
use RestaurantBundle\Entity\Menu;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorInterface;

class MailerService
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var TokenStorageInterface
     */
    private $token_storage;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var TwigEngine
     */
    private $templating;

    public function __construct(EntityManager $em, \Swift_Mailer $swiftMailer, TokenStorageInterface $tokenStorage, TranslatorInterface $translator, TwigEngine $twigEnvironment)
    {
        $this->em = $em;
        $this->mailer = $swiftMailer;
        $this->token_storage = $tokenStorage;
        $this->translator = $translator;
        $this->templating = $twigEnvironment;
    }

    /**
     * @param \RestaurantBundle\Entity\Dish $dish
     *
     * @throws \Twig\Error\Error
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
     * @param \RestaurantBundle\Entity\Dish $dish
     *
     * @throws \Twig\Error\Error
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
     * @param \RestaurantBundle\Entity\Menu $menu
     *
     * @throws \Twig\Error\Error
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
     * @param \RestaurantBundle\Entity\Menu $menu
     *
     * @throws \Twig\Error\Error
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
     * @throws \Twig\Error\Error
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
