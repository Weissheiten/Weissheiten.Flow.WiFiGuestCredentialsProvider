<?php
namespace Weissheiten\Flow\WiFiGuestCredentialsProvider\Command;

/*
 * This file is part of the Weissheiten.Flow.WiFiGuestCredentialsProvider package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;

use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\WiFiVoucher;
use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\Outlet;
use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Repository;

/**
 * @Flow\Scope("singleton")
 */
class WifiVoucherCommandController extends \Neos\Flow\Cli\CommandController
{

    /**
     * @Flow\Inject
     * @var Repository\WiFiVoucherRepository
     */
    protected $voucherRepository;


    /**
     * @Flow\Inject
     * @var Repository\OutletRepository
     */
    protected $outletRepository;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Persistence\PersistenceManagerInterface
     */
    protected $persistenceManager;


    /**
     * WiFi Code creation via command line
     *
     * Adds a new voucher to the database with the passed credentials.
     * Validity can be set in minutes as optional parameter
     *
     * @param string $username Username - This argument is required
     * @param string $password Password - This argument is required
     * @param string $validitymin Validity in minutes - this argument is optional, default is 60
     * @return void
     */
    public function insertVoucherCommand($username, $password, $validitymin = "60")
    {
        if ($username !== null && $password !== null && $validitymin !== null && is_numeric($validitymin)) {
            if (strlen($password) !== 7) {
                $this->outputLine('The string length of the password must be exactly 7.');
            } else {
                $voucher = new WiFiVoucher();
                $voucher->setUsername($username);
                $voucher->setPassword($password);
                $voucher->setValiditymin($validitymin);

                $this->voucherRepository->add($voucher);


                $this->outputLine(
                    'The voucher %s was added to the database, it is valid for %u minutes',
                    array($voucher->getUsername(), $voucher->getValiditymin())
                );
            }
        } else {
            $response = <<<OUT
              The voucher could not be added, your arguments passed were:
              Username: "%s", Password: "%s", Validity in minutes: "%s".
OUT;

            $this->outputLine($response, array($username, $password, $validitymin));
        }
    }

    /**
     * Outlet creation via the command line
     * Adds a new outlet to the database with the passed name and zipcode
     *
     *
     * @param string $name - this argument is required
     * @param int $zipcode - this argument is required
     * @return void
     */
    public function insertOutletCommand($name, $zipcode)
    {
        $outlet = new Outlet();
        $outlet->setName($name);
        $outlet->setZipcode($zipcode);

        $this->outletRepository->add($outlet);

        $this->outputLine('The Outlet %s with zipcode %s was added to the database.', array($name,$zipcode));
    }

    /**
     * Sets the first voucher found in the database as redeemed with the current timestamp
     * in the first outlet found
     *
     * @return void
     */
    public function markVoucherRedeemedCommand()
    {
        if ($this->outletRepository->countAll() > 0) {
            /* @var Outlet $outlet */
            $outlet = $this->outletRepository->findAll()->toArray()[0];

            if ($this->voucherRepository->countAll() > 0) {
                /* @var WiFiVoucher $voucher */
                $voucher = $this->voucherRepository->findAll()[0];
                $voucher->setRequesttime(new \DateTime());
                $voucher->setOutlet($outlet);

                $this->voucherRepository->update($voucher);
//                $this->persistenceManager->persistAll();

                $this->outputLine(
                    "The voucher %s was redeemed for outlet %s with timestamp %s.",
                    array($voucher->getUsername(), $outlet->getName(), $voucher->getRequesttime()->format('d.M.Y h:s'))
                );

            } else {
                $this->outputLine('There is currently no voucher in the database, voucher not marked redeemed');
            }
        } else {
            $this->outputLine('There is currently no outlet in the database, voucher not marked redeemed');
        }
    }

    /**
     * List all outlets currently in the database
     *
     * @return void
     */
    public function listOutletsCommand()
    {
        if ($this->outletRepository->countAll() > 0) {
            $this->outputLine("Outlet | Zip");

            /* @var Outlet $outlet */
            foreach ($this->outletRepository->findAll() as $outlet) {
                $this->outputLine(
                    '%s | %s',
                    array($outlet->getName(),
                        $outlet->getZipcode())
                );
            }
        } else {
            $this->outputLine("There are currently no outlets in the database");
        }
    }

    /**
     * List all vouchers currently in the database
     *
     * @return void
     */
    public function listVouchersCommand()
    {
        if ($this->voucherRepository->countAll() > 0) {
            $this->outputLine("Voucher | Redeemed | Outlet");

            /* @var WiFiVoucher $voucher */
            foreach ($this->voucherRepository->findAll() as $voucher) {
                $redeemed = ($voucher->getRequesttime()!==null) ?
                    $voucher->getRequesttime()->format('d.M.Y h:s') : 'not redeemed';

                $this->outputLine(
                    '%s | %s | %s',
                    array($voucher->getUsername(),
                        $redeemed,
                        $voucher->getOutlet()->getName())
                );
            }
        } else {
            $this->outputLine("There are currently no vouchers in the database");
        }
    }

    /**
     * Clears all vouchers and outlets
     */
    public function clearCommand()
    {
        $voucherCount = $this->voucherRepository->countAll();
        $outletCount = $this->outletRepository->countAll();

        $this->voucherRepository->removeAll();
        $this->outletRepository->removeAll();

        $this->outputLine(
            'Repositories cleared - %u Outlets and %u Vouchers were removed',
            array($outletCount, $voucherCount)
        );
    }

    /**
     * Creates a statistics file
     * @return void
     */

    public function createStatisticsCommand(){
        $stats = $this->voucherRepository->createStatisticsArray();
        foreach($stats as $entry)
        $this->outputLine(
            $entry[0] . ' ' . $entry[1]
        );
    }

}
