<?php

namespace h3tech\simplePay\sdk\v2;

/**
 * Back
 *
 * @category SDK
 * @package  SimplePayV2_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
class SimplePayBack extends Base
{
    protected $currentInterface = 'back';
    protected $notification = [];
    public $request = [
        'rRequest' => '',
        'sRequest' => '',
        'rJson' => '',
        'rContent' => [
            'r' => 'N/A',
            't' => 'N/A',
            'e' => 'N/A',
            'm' => 'N/A',
            'o' => 'N/A',
        ]
    ];

    /**
     * Validates CTRL variable
     *
     * @param string $rRequest Request data -> r
     * @param string $sRequest Request data -> s
     *
     * @return boolean
     */
    public function isBackSignatureCheck($rRequest = '', $sRequest = '')
    {
        //request handling
        $this->request['rRequest'] = $rRequest;
        $this->request['sRequest'] = $sRequest;
        $this->request['rJson'] = base64_decode($this->request['rRequest']);
        $this->request['rJson'] = $this->checkOrSetToJson($this->request['rJson']);

        foreach (json_decode($this->request['rJson']) as $key => $value) {
            $this->request['rContent'][$key] = $value;
        }
        $this->logContent = array_merge($this->logContent, $this->request);

        $this->addConfigData('merchantAccount', $this->request['rContent']['m']);
        $this->setConfig();

        //notification
        foreach ($this->request['rContent'] as $contentKey => $contentValue) {
            $this->notification[$contentKey] = $contentValue;
        }

        //signature check
        $this->request['checkCtrlResult'] = false;
        if ($this->isCheckSignature($this->request['rJson'], $this->request['sRequest'])) {
            $this->request['checkCtrlResult'] = true;

        }

        //write log
        $this->logTransactionId = $this->notification['t'];
        $this->logOrderRef = $this->notification['o'];
        $this->writeLog($this->logContent);
        return $this->request['checkCtrlResult'];
    }


    /**
     * Raw notification data of request
     *
     * @return array Notification array
     */
    public function getRawNotification()
    {
        return $this->notification;
    }

    /**
     * Formatted notification data of request
     *
     * @return string Notification in readable format
     */
    public function getFormatedNotification()
    {
        $this->backNotification();
        return $this->notificationFormated;
    }
}