<?php
/**
 * Class loading a default admin for the app
 */

namespace RestaurantBundle\DataFixtures\ORM;

use RestaurantBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData
 *
 * @package RestaurantBundle\DataFixtures\ORM
 */
class LoadUserData
    extends AbstractFixture
    implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager An ObjectManager
     */
    public function load(ObjectManager $manager)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $encoder = $this->container->get('security.password_encoder');

        $user = new User();
        $user->setSalt('salt');
        $user->setUsername('admin');
        $user->setEmail('admin@admin.com');
        $user->setPassword('password');
        $encoded = $encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encoded);
        $user->setRoles(array('ROLE_SUPER_ADMIN'));
        $em->persist($user);
        $em->flush();
    }

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 1;
    }

}
