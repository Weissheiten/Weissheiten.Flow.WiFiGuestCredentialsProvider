<?php
namespace Weissheiten\Flow\WiFiGuestCredentialsProvider\Controller;

/*
 * This file is part of the Weissheiten.Flow.WiFiGuestCredentialsProvider package.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;
use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\WiFiVoucher;
use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Repository\WiFiVoucherRepository;

class WiFiVoucherController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
    /**
     * @var array
     */
    protected $viewFormatToObjectNameMap = array(
        'html' => 'TYPO3\Fluid\View\TemplateView',
        'json' => 'TYPO3\Flow\Mvc\View\JsonView'
    );

    /**
     * A list of IANA media types which are supported by this controller
     *
     * @var array
     */
    protected $supportedMediaTypes = array('application/json', 'text/html');

    protected $outlets;

    /**
     * @Flow\Inject     *
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
        $this->outlets = array(
            // calculated hash for demo password: $2y$10$XXG2015cn2lzS6r7o8BmLuBpnBjjQUDpSlNBQuymnli5x3.b1aD/K
            array('outletcode' => '1010', 'outletpassword' => 'salzpfeffer434'),
            array('outletcode' => '1070', 'outletpassword' => 'exchangeThisPW')
        );
    }

    /**
     * @param string $outletTag
     * @param string $securityHash
     * @return void
     */
    public function getVoucherAction($outletTag, $securityHash)
    {
        $found_outlet = null;

        foreach ($this->outlets as $key => $outlet) {
            if ($outlet['outletcode']===$outletTag) {
                $found_outlet = $outlet;
                break;
            }
        }

        if ($found_outlet!==null && password_verify($found_outlet['outletpassword'], $securityHash)) {
            $voucher = $this->WiFiVoucherRepository->findFirst();
            $this->view->assign(
                'value',
                array(
                    'status' => 1,
                    'voucher' => $voucher
                )
            );
        } else {
            $this->view->assign(
                'value',
                array('status' => 0, 'voucher' => 'Error while getting voucher')
            );
        }
    }
}
