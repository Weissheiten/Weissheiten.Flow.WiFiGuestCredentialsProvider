<?php
namespace Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model;

/*
 * This file is part of the Weissheiten.Flow.WiFiGuestCredentialsProvider package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class WiFiVoucher
{

    /**
     * @var string
     * @Flow\Validate(type="NotEmpty")
     * @Flow\Validate(type="StringLength", options={ "minimum"=3, "maximum"=80 })
     */
    protected $username;

    /**
     * @var string
     * @Flow\Validate(type="NotEmpty")
     * @Flow\Validate(type="StringLength", options={ "minimum"=3, "maximum"=20 })
     */
    protected $password;

    /**
     * @var integer
     */
    protected $validitymin;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $requesttime;

    /**
     * @ORM\ManyToOne(inversedBy="vouchers")
     * @var Outlet
     */
    protected $outlet;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return integer
     */
    public function getValiditymin()
    {
        return $this->validitymin;
    }

    /**
     * @param integer $validitymin
     * @return void
     */
    public function setValiditymin($validitymin)
    {
        $this->validitymin = $validitymin;
    }

    /**
     * @return \DateTime
     */
    public function getRequesttime()
    {
        return $this->requesttime;
    }

    /**
     * @param \DateTime $date
     * @return void
     */
    public function setRequesttime(\DateTime $date)
    {
        $this->requesttime = $date;
    }

    /**
     * @return \Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\Outlet
     */
    public function getOutlet()
    {
        return $this->outlet;
    }

    /**
     * @param \Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\Outlet $outlet
     */
    public function setOutlet($outlet)
    {
        $this->outlet = $outlet;
    }
}
