<?php
namespace Weissheiten\Flow\WiFiGuestCredentialsProvider\Controller;

/*
 * This file is part of the Weissheiten.Flow.WiFiGuestCredentialsProvider package.
 */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;
use TYPO3\Flow\Persistence\Generic\PersistenceManager;

use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\WiFiVoucher;
use Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Repository\OutletRepository;
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
        /* @var \Weissheiten\Flow\WiFiGuestCredentialsProvider\Domain\Model\Outlet $found_outlet */
        $found_outlet = null;

        $found_outlet = $this->OutletRepository->findFirst();

        if ($found_outlet!==null && password_verify($password, $found_outlet->getPwhash())) {
            $voucher = $this->WiFiVoucherRepository->findFirst();
            $voucher->setOutlet($found_outlet);
            $voucher->setRequesttime(new \DateTime('now'));

            // configure the JSON Output for this view if requested
            if(is_a($this->view,'\TYPO3\Flow\Mvc\View\JsonView')){
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
            }

            $this->view->assign(
                'value',
                array(
                    'status' => 1,
                    'wifivoucher' => $voucher
                )
            );
        } else {
            $this->view->assign(
                'value',
                array('status' => 0, 'wifivoucher' => 'Error while getting voucher')
            );
        }
    }
}
