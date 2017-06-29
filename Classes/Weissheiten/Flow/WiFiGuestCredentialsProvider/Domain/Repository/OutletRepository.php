<?php
namespace Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Repository;

/*
 * This file is part of the Weissheiten.Flow.WiFiGuestCredentialsProvider package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;
use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\Outlet;

/**
 * @Flow\Scope("singleton")
 */
class OutletRepository extends Repository
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
        $outlet = $this->createQuery()->setLimit(1)->execute()->toArray();
        if (count($outlet)>0) {
            return $outlet[0];
        }
        return null;
    }

    /**
     * @param string $outletName
     *
     * @return Outlet
     * @api
     * @see \Neos\Flow\Persistence\QueryInterface::execute()
     */
    public function findOutletByName($outletName)
    {
        $query = $this->createQuery();
        $outlet = $query->matching($query->equals('name', $outletName))->setLimit(1)->execute()->toArray();

        if (count($outlet)>0) {
            return $outlet[0];
        }
        return null;
    }
}
