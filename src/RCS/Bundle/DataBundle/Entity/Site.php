<?php

namespace RCS\Bundle\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Site
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     * @ORM\Column(name="latitude", type="decimal", precision=12, scale=9, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     * @ORM\Column(name="longitude", type="decimal", precision=12, scale=9, nullable=true)
     */
    private $longitude;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    public function get($field)
    {
        return $this->$field;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Report
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName() { return $this->name; }

    /**
     * @param string $description
     * @return Report
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() { return $this->description; }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Report
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Report
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    public function __toString()
    {
        return $this->name;
    }
}
