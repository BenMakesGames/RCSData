<?php

namespace RCS\Bundle\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Report
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="RCS\Bundle\DataBundle\Entity\ReportRepository")
 */
class Report
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
     *
     * @ORM\Column(name="latitude", type="decimal", precision=12, scale=9)
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="decimal", precision=12, scale=9)
     */
    private $longitude;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var integer
     *
     * @ORM\Column(name="month", type="integer")
     */
    private $month;

    /**
     * @var integer
     *
     * @ORM\Column(name="day", type="integer")
     */
    private $day;

    /**
     * @var float
     *
     * @ORM\Column(name="ph", type="decimal", precision=4, scale=2)
     */
    private $ph;

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

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return Report
     */
    public function setTimestamp(\DateTime $timestamp)
    {
        $this->timestamp = $timestamp;

        $this->year = (int)$timestamp->format('Y');
        $this->month = (int)$timestamp->format('m');
        $this->day = (int)$timestamp->format('d');

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime 
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Get day
     *
     * @return integer
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set ph
     *
     * @param float $ph
     * @return Report
     */
    public function setPh($ph)
    {
        $this->ph = $ph;

        return $this;
    }

    /**
     * Get ph
     *
     * @return float
     */
    public function getPh()
    {
        return $this->ph;
    }
}
