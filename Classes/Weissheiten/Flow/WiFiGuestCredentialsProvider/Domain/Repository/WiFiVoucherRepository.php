<?php
namespace Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Repository;

/*
 * This file is part of the Weissheiten.Flow.WiFiGuestCredentialsProvider package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Doctrine\Repository;

/**
 * @Flow\Scope("singleton")
 */
class WiFiVoucherRepository extends Repository
{
    /**
     * Returns the first object of this repository
     *
     * @return \Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\WiFiVoucher
     * @api
     * @see \Neos\Flow\Persistence\QueryInterface::execute()
     */
    public function findFirst()
    {
        $voucher = $this->createQuery()->setLimit(1)->execute()->toArray();
        if (count($voucher)>0) {
            return $voucher[0];
        }
        return null;
    }

    /**
     * Gets a statistics array with the entries "outletName" and "Count"
     * @return array
     */
    public function createStatisticsArray(){
        $query = $this->createQuery();
        $query->getQueryBuilder()->addGroupBy('name')->addSelect('COUNT(zipcode)');
        return $query->execute()->toArray();
    }
}
