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
    public function findFirstUnredeemed()
    {
        $query = $this->createQuery();
        $voucher = $query->matching($query->equals('requesttime', null))->setLimit(1)->execute()->toArray();

        if (count($voucher)>0) {
            return $voucher[0];
        }
        return null;
    }

    /**
     * Count the number of unredeemed vouchers in the repository
     *
     * @return int
     */
    public function getNonRedeemedVoucherCount()
    {
        $query = $this->createQuery();
        $cond = $query->equals('outlet', null);
        return $query->matching($cond)->count();
    }

    /**
     * Gets a statistics array with the entries "outletName" and "Count"
     * @return array
     */
    public function createStatisticsArray()
    {
        $query = $this->createQueryBuilder('wv')
            ->select('o.name, COUNT(o.name)')
            ->join(
                'Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\Outlet',
                'o',
                \Doctrine\ORM\Query\Expr\Join::WITH,
                'wv.outlet=o'
            )
            ->groupBy('wv.outlet')
            ->getQuery();
        return $query->execute();
    }
}
