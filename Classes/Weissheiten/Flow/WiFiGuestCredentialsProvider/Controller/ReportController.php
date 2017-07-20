<?php
namespace Weissheiten\Flow\WiFiGuestCredentialsProvider\Controller;

/*
 * This file is part of the Weissheiten.Flow.WiFiGuestCredentialsProvider package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;
use Neos\Flow\Persistence\Generic\PersistenceManager;

use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\WiFiVoucher;
use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Repository\OutletRepository;
use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Repository\WiFiVoucherRepository;

class ReportController extends \Neos\Flow\Mvc\Controller\ActionController
{
    /**
     * @var array
     */
    protected $viewFormatToObjectNameMap = array(
        'html' => 'Neos\FluidAdaptor\View\TemplateView',
        'json' => 'Neos\Flow\Mvc\View\JsonView'
    );

    /**
     * A list of IANA media types which are supported by this controller
     *
     * @var array
     */
    protected $supportedMediaTypes = array('application/json', 'text/html');

    /**
     * @Flow\Inject
     * @var OutletRepository
     */
    protected $OutletRepository;

    /**
     * @Flow\Inject
     * @var WiFiVoucherRepository
     */
    protected $WiFiVoucherRepository;

    /**
     * Initializes the controller before invoking an action method.
     *
     * Override this method to solve tasks which all actions have in
     * common.
     *
     * @return void
     * @api
     */
    protected function initializeAction()
    {
    }

    /**
     * Show a list of InstagramCollections and their properties
     * @return void
     */
    public function indexAction()
    {
        // get the number of available vouchers in the system
        $nonRedeemedVoucherCount = $this->WiFiVoucherRepository->getNonRedeemedVoucherCount();
        $redeemedVoucherCount = $this->WiFiVoucherRepository->getRedeemedVoucherCount();

        // get the total voucher requests for each outlet
        $vouchers = $this->WiFiVoucherRepository->findAllRedeemed();
        $vouchers_mapped = [];

        foreach ($vouchers as $voucher) {
            /* @var \Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\WiFiVoucher $voucher */
            $key = $voucher->getRequesttime()->format("M/Y");
            $outlet = $voucher->getOutlet()->getName();
            if (key_exists($key, $vouchers_mapped) && key_exists($outlet, $vouchers_mapped[$key])) {
                $vouchers_mapped[$key][$outlet] += 1;
            } else {
                $vouchers_mapped[$key][$outlet] = 1;
            }
        }

        $this->view->assignMultiple(array(
            'redeemedVoucherCount' => $redeemedVoucherCount,
            'nonRedeemedVoucherCount' => $nonRedeemedVoucherCount,
            'vouchers' => $vouchers_mapped
        ));
    }

    /**
     * Returns all voucher requests from the database
     * @return void
     */
    public function getVoucherRequestEntriesAction()
    {
        $vouchers = $this->WiFiVoucherRepository->findAll();
        $this->view->assign('value', $vouchers);
    }
}
