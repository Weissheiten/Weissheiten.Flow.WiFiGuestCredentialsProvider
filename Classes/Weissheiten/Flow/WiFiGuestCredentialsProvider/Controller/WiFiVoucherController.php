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

    protected $outlets = array('1010', '1020');

    /**
     * @Flow\Inject     *
     * @var WiFiVoucherRepository
     */
    protected $WiFiVoucherRepository;

    /**
     * @param string $outletTag
     * @param string $sharedSecurityHash
     * @return void
     */
    public function getVoucherAction($outletTag, $sharedSecurityHash)
    {
        if (!in_array($outletTag, $this->outlets) || $sharedSecurityHash!=="rubberDuckOfDoom!") {
            $this->view->assign(
                'value',
                array('status' => 0, 'voucher' => 'Error while getting voucher')
            );
        } else {
            $voucher = $this->WiFiVoucherRepository->findFirst();
            $this->view->assign(
                'value',
                array('status' => 1, 'voucher' => $voucher)
            );
        }
    }
}
