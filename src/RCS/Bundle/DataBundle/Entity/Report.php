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
     * @ORM\Column(name="latitude", type="decimal", precision=12, scale=9, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     * @ORM\Column(name="longitude", type="decimal", precision=12, scale=9, nullable=true)
     */
    private $longitude;

    /**
     * @var \DateTime
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;

    /**
     * @var integer
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var integer
     * @ORM\Column(name="month", type="integer")
     */
    private $month;

    /**
     * @var integer
     * @ORM\Column(name="day", type="integer")
     */
    private $day;

    /**
     * @var integer
     * @ORM\Column(name="participants", type="integer")
     */
    private $participants;

    /**
     * @var string
     * @ORM\Column(name="precipitation_description", type="integer", nullable=true)
     */
    private $precipitationDescription;

    /**
     * @var string
     * @ORM\Column(name="land_description", type="integer", nullable=true)
     */
    private $landDescription;

    /**
     * @var float
     * @ORM\Column(name="turbidity_ntu", type="decimal", precision=7, scale=2, nullable=true)
     */
    private $turbidityNtu;

    /**
     * @var float
     * @ORM\Column(name="temperature_c", type="decimal", precision=6, scale=2, nullable=true)
     */
    private $temperatureC;

    /**
     * @var float
     * @ORM\Column(name="dissolved_oxygen_ppm", type="integer", nullable=true)
     */
    private $dissolvedOxygenPpm;

    /**
     * @var float
     * @ORM\Column(name="ph", type="decimal", precision=4, scale=2, nullable=true)
     */
    private $ph;

    /**
     * @var float
     * @ORM\Column(name="air_temperature_c", type="decimal", precision=6, scale=2, nullable=true)
     */
    private $airTemperatureC;

    /**
     * @var boolean
     * @ORM\Column(name="rcs_test_kit_use", type="boolean")
     */
    private $rcsTestKitUse;

    /**
     * @var boolean
     * @ORM\Column(name="followed_q_a_protocols", type="boolean")
     */
    private $followedQAProtocols;

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
     * Set participants
     * @param integer $participants
     * @return Report
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
        return $this;
    }

    /**
     * Get participants
     * @return integer
     */
    public function getParticipants() { return $this->participants; }

    /**
     * @param string $description
     * @return Report
     */
    public function setPrecipitationDescription($description)
    {
        $this->precipitationDescription = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrecipitationDescription() { return $this->precipitationDescription; }

    /**
     * @param string $description
     * @return Report
     */
    public function setLandDescription($description)
    {
        $this->landDescription = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getLandDescription() { return $this->landDescription; }

    /**
     * Set turbidityNtu
     * @param float $ntu
     * @return Report
     */
    public function setTurbidityNtu($ntu)
    {
        $this->turbidityNtu = $ntu;
        return $this;
    }

    /**
     * @return float
     */
    public function getTurbidityNtu() { return $this->turbidityNtu; }

    /**
     * @param float $c
     * @return Report
     */
    public function setTemperatureC($c)
    {
        $this->temperatureC = $c;
        return $this;
    }

    /**
     * @return float
     */
    public function getTemperatureC() { return $this->temperatureC; }

    /**
     * @param $ppm
     * @return $this
     */
    public function setDissolvedOxygenPpm($ppm)
    {
        $this->dissolvedOxygenPpm = $ppm;
        return $this;
    }

    /**
     * @return float
     */
    public function getDissolvedOxygenPpm() { return $this->dissolvedOxygenPpm; }

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

    /**
     * @param float $c
     * @return Report
     */
    public function setAirTemperatureC($c)
    {
        $this->airTemperatureC = $c;
        return $this;
    }

    /**
     * @return float
     */
    public function getAirTemperatureC() { return $this->airTemperatureC; }

    /**
     * @param boolean $rcsTestKitUse
     * @return Report
     */
    public function setRcsTestKitUse($rcsTestKitUse)
    {
        $this->rcsTestKitUse = $rcsTestKitUse;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getRcsTestKitUse() { return $this->rcsTestKitUse; }

    /**
     * @param boolean $followedQAProtocols
     * @return Report
     */
    public function setFollowedQAProtocols($followedQAProtocols)
    {
        $this->followedQAProtocols = $followedQAProtocols;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getFollowedQAProtocols() { return $this->followedQAProtocols; }
}
