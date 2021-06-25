<?php

namespace h3tech\simplePay\sdk\v2\traits;

/**
 * Views
 *
 * @category SDK
 * @package  SimplePayV2_SDK
 * @author   SimplePay IT Support <itsupport@otpmobil.com>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html  GNU GENERAL PUBLIC LICENSE (GPL V3.0)
 * @link     http://simplepartner.hu/online_fizetesi_szolgaltatas.html
 */
trait Views
{
    public $formDetails = [
        'id' => 'SimplePayForm',
        'name' => 'SimplePayForm',
        'element' => 'button',
        'elementText' => 'Start SimplePay Payment',
    ];

    /**
     * Generates HTML submit element
     *
     * @param string $formName          The ID parameter of the form
     * @param string $submitElement     The type of the submit element ('button' or 'link' or 'auto')
     * @param string $submitElementText The label for the submit element
     *
     * @return string HTML submit
     */
    protected function formSubmitElement($formName = '', $submitElement = 'button', $submitElementText = '')
    {
        switch ($submitElement) {
            case 'link':
                $element = "\n<a href='javascript:document.getElementById(\"" . $formName ."\").submit()'>".addslashes($submitElementText)."</a>";
                break;
            case 'button':
                $element = "\n<button type='submit'>".addslashes($submitElementText)."</button>";
                break;
            case 'auto':
                $element = "\n<button type='submit'>".addslashes($submitElementText)."</button>";
                $element .= "\n<script language=\"javascript\" type=\"text/javascript\">document.getElementById(\"" . $formName . "\").submit();</script>";
                break;
            default :
                $element = "\n<button type='submit'>".addslashes($submitElementText)."</button>";
                break;
        }
        return $element;
    }

    /**
     * HTML form creation for redirect to payment page
     *
     * @return void
     */
    public function getHtmlForm()
    {
        $this->returnData['form'] = 'Transaction start was failed!';
        if (isset($this->returnData['paymentUrl']) && $this->returnData['paymentUrl'] != '') {
            $this->returnData['form'] = '<form action="' . $this->returnData['paymentUrl'] . '" method="GET" id="' . $this->formDetails['id'] . '" accept-charset="UTF-8">';
            $this->returnData['form'] .= $this->formSubmitElement($this->formDetails['name'], $this->formDetails['element'], $this->formDetails['elementText']);
            $this->returnData['form'] .= '</form>';
        }
    }

    /**
     * Notification based on back data
     *
     * @return void
     */
    protected function backNotification()
    {
        $this->notificationFormated = '<div>';
        $this->notificationFormated .= '<b>Sikertelen fizetés!</b>';
        if ($this->request['rContent']['e'] == 'SUCCESS') {
            $this->notificationFormated = '<div>';
            $this->notificationFormated .= '<b>Sikeres fizetés</b>';
        }
        $this->notificationFormated .= '<b>SimplePay tranzakció azonosító:</b> ' . $this->request['rContent']['t'] . '</br>';
        $this->notificationFormated .= '<b>Kereskedői referencia szám:</b> ' . $this->request['rContent']['o'] . '</br>';
        $this->notificationFormated .= '</div>';
    }
}