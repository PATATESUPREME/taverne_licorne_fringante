<?php

namespace RestaurantBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Dish
 *
 * @ORM\Table(name="dish")
 * @ORM\Entity(repositoryClass="RestaurantBundle\Repository\DishRepository")
 */
class Dish
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Menu", inversedBy="dishes")
     */
    private $menus;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="homeMade", type="boolean")
     */
    private $homeMade;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @var array
     *
     * @ORM\Column(name="allergens", type="array", nullable=true)
     */
    private $allergens;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Dish
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Dish
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Dish
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Dish
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set homeMade
     *
     * @param boolean $homeMade
     *
     * @return Dish
     */
    public function setHomeMade($homeMade)
    {
        $this->homeMade = $homeMade;

        return $this;
    }

    /**
     * Get homeMade
     *
     * @return bool
     */
    public function getHomeMade()
    {
        return $this->homeMade;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Dish
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Dish
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Dish
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set allergens
     *
     * @param array $allergens
     *
     * @return Dish
     */
    public function setAllergens($allergens)
    {
        $this->allergens = $allergens;

        return $this;
    }

    /**
     * Get allergens
     *
     * @return array
     */
    public function getAllergens()
    {
        return $this->allergens;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->menus = new ArrayCollection();
    }

    /**
     * Add menu
     *
     * @param Menu $menu
     *
     * @return Dish
     */
    public function addMenu(Menu $menu)
    {
        $this->menus[] = $menu;

        return $this;
    }

    /**
     * Remove menu
     *
     * @param Menu $menu
     */
    public function removeMenu(Menu $menu)
    {
        $this->menus->removeElement($menu);
    }

    /**
     * Get menus
     *
     * @return Collection
     */
    public function getMenus()
    {
        return $this->menus;
    }
}
