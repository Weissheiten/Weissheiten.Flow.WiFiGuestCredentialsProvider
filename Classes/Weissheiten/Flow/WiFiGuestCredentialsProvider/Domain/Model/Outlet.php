<?php
namespace Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model;

/*
 * This file is part of the Weissheiten.Flow.WiFiGuestCredentialsProvider package.
 */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @Flow\Entity
 */
class Outlet
{
    /**
     * @var string
     * @Flow\Validate(type="NotEmpty")
     * @Flow\Validate(type="StringLength", options={ "minimum"=3, "maximum"=80 })
     */
    protected $name;

    /**
     * @var integer
     */
    protected $zipcode;

    /**
     * The vouchers used in this outlet
     *
     * @ORM\OneToMany(mappedBy="outlet")
     * @ORM\OrderBy({"requesttime" = "DESC"})
     * @var Collection<WiFiVoucher>
     */
    protected $vouchers;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param int $zipcode
     * @return int
     */
    public function getZipcode()
    {
        return $this->zipcode;
    }

    /**
     * @param int $zipcode
     * @return void
     */
    public function setZipcode($zipcode)
    {
        $this->zipcode = $zipcode;
    }

    /**
     * @return Collection
     */
    public function getVouchers()
    {
        return $this->vouchers;
    }

    /**
     * @param WiFiVoucher $voucher
     */
    public function addVoucher(WiFiVoucher $voucher)
    {
        $this->vouchers->add($voucher);
    }
}
