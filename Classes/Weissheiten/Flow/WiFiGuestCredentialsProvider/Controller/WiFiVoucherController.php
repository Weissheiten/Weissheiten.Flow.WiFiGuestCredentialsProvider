<?php
namespace Weissheiten\Flow\WiFiGuestCredentialsProvider\Controller;

/*
 * This file is part of the Weissheiten.Flow.WiFiGuestCredentialsProvider package.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\Repository;
use Neos\Flow\Persistence\Generic\PersistenceManager;

use Neos\SwiftMailer\Message as SwiftMailerMessage;

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
     * @var array
     */
    protected $settings;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Log\SystemLoggerInterface
     */
    protected $systemLogger;

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
     * Inject the settings
     *
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings) {
        $this->settings = $settings;
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
                $vouchercount = $this->WiFiVoucherRepository->getNonRedeemedVoucherCount();

                if ($vouchercount > 0) {
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
                    $responseMessage = 'There is currently no free voucher in the database, voucher not marked redeemed';
                }

                if ($this->settings['alertEmail']['active']) {
                    // send an alert e-mail if the vouchercount is low
                    if (in_array($vouchercount, $this->settings['alertEmail']['tresholds'])) {
                        // send an infomail, that the vouchercount is low
                        $this->sendAlertMail($vouchercount);
                        $responseMessage = 'alert mail sent';
                    }
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

    /**
     * Sends an alert e-mail to inform that the voucher count is low
     *
     * @param $vouchercount Number of redeemable vouchers left in the system
     */
    private function sendAlertMail(int $vouchercount){
        if (!class_exists(SwiftMailerMessage::class)) {
            $this->systemLogger->logException('The "neos/swiftmailer" doesn\'t seem to be installed, but is required for the WiFiVoucher Alert feature to work!');
        }

        $mail = new SwiftMailerMessage();

        $sendto = [];
        foreach ($this->settings['alertEmail']['to'] as $to) {
            $sendto[$to['email']] = $to['name'];
        }

        $sendcc = [];
        foreach ($this->settings['alertEmail']['cc'] as $to) {
            $sendcc[$to['email']] = $to['name'];
        }

        $mail
            ->setFrom(array($this->settings['alertEmail']['from']['email'] => $this->settings['alertEmail']['from']['name']))
            ->setSubject("WiFi Voucher System | Vorrat an ungenutzten WiFiVouchern im System liegt unter 100")
            ->setTo($sendto)
            ->setCC($sendcc)
            ->setBody(vsprintf($this->settings['alertEmail']['text'],$vouchercount), 'text/plain')
            ->send();
    }
}
