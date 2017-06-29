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

class WiFiVoucherController extends \Neos\Flow\Mvc\Controller\ActionController
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
     * @param string $outletTag
     * @param string $password
     * @return void
     */
    public function getVoucherAction($outletTag, $password)
    {
        $responseMessage = "No actions performed.";

        if ($outletTag!=null) {
            /* @var \Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\Outlet $outlet */
            $outlet = $this->OutletRepository->findOutletByName($outletTag);

            if ($outlet!==null && password_verify($password, $outlet->getPwhash())) {
                if ($this->WiFiVoucherRepository->countAll() > 0) {
                    /* @var WiFiVoucher $voucher */
                    $voucher = $this->WiFiVoucherRepository->findFirstUnredeemed();

                    if ($voucher!=null) {
                        $voucher->setRequesttime(new \DateTime());
                        $voucher->setOutlet($outlet);

                        $this->WiFiVoucherRepository->update($voucher);
                        $this->persistenceManager->persistAll();
                        $responseMessage = 'OK';
                    } else {
                        $responseMessage = 'There is currently no free voucher in the database, voucher not marked redeemed';
                    }
                } else {
                    $responseMessage = 'There is currently no voucher in the database, voucher not marked redeemed';
                }
            } else {
                $responseMessage = 'There is currently no outlet in the database matching this name, voucher not marked redeemed';
            }
        } else {
            $responseMessage = 'There is currently no outlet in the database, voucher not marked redeemed';
        }

        // prepare the JSON View for the output of a voucher
        if ($responseMessage==='OK' && $voucher!==null) {
            // configure the JSON Output for this view if requested
            if (is_a($this->view, '\Neos\Flow\Mvc\View\JsonView')) {
                $this->view->setConfiguration(
                    array(
                        'value' => array(
                            '_only' => array('status', 'wifivoucher'),
                            'wifivoucher' => array(
                                '_only' => array('username', 'password', 'validitymin', 'outlet', 'requesttime'),
                                '_descend' => array(
                                    'requesttime' => array(
                                        '_only' => array('date')
                                    ),
                                    'outlet' => array(
                                        '_only' => array('name', 'zipcode')
                                    )
                                )
                            )
                        )
                    )
                );

                $this->view->assign(
                    'value',
                    array(
                        'status' => 1,
                        'wifivoucher' => $voucher
                    )
                );
            }
        } else {
            $this->view->assign(
                'value',
                array('status' => 0, 'wifivoucher' => $responseMessage)
            );
        }
    }
}
