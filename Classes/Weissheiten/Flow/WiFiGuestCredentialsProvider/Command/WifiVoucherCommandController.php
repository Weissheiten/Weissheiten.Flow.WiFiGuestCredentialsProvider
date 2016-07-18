<?php
namespace Weissheiten\Flow\WiFiGuestCredentialsProvider\Command;

/*
 * This file is part of the Weissheiten.Flow.WiFiGuestCredentialsProvider package.
 */

use TYPO3\Flow\Annotations as Flow;
use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\WiFiVoucher;
use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Repository;

/**
 * @Flow\Scope("singleton")
 */
class WifiVoucherCommandController extends \TYPO3\Flow\Cli\CommandController
{

    /**
     * @Flow\Inject
     * @var Repository\WiFiVoucherRepository
     */
    protected $voucherRepository;

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
    public function setupCommand($username, $password, $validitymin = 60)
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


                $this->outputLine('The voucher was added to the database.');
                $this->outputLine($validitymin);
            }
        } else {
            $response = <<<OUT
              The voucher could not be added, your arguments passed were:
              Username: "%s", Password: "%s1", Validity in minutes: "%s2".
OUT;

            $this->outputLine($response, array($username, $password, $validitymin));
        }
    }
}
