<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FlightBundle\Model;

/**
 * Description of TpaExtensions
 *
 * @author para-soft7
 */
class TpaExtensions
{
     /**
     * @var tpaExtensionsETicketInd
     */
    protected $tpaExtensionsETicketInd;

    /**
     * @var tpaExtensionsMileageAmount
     */
    protected $tpaExtensionsMileageAmount;

    /**
     * Set Tpa Extensions ETicket Ind
     *
     * @param string tpaExtensionsETicketInd
     */
    public function setTpaExtensionsETicketInd($tpaExtensionsETicketInd) {
        $this->tpaExtensionsETicketInd = $tpaExtensionsETicketInd;
    }

    /**
     * Get Tpa Extensions ETicket Ind
     *
     * @return string tpaExtensionsETicketInd
     */
    public function getTpaExtensionsETicketInd() {
        return $this->tpaExtensionsETicketInd;
    }

    /**
     * Set Tpa Extensions Mileage Amount
     *
     * @param string tpaExtensionsMileageAmount
     */
    public function setTpaExtensionsMileageAmount($tpaExtensionsMileageAmount) {
        $this->tpaExtensionsMileageAmount = $tpaExtensionsMileageAmount;
    }

    /**
     * Get Tpa Extensions Mileage Amount
     *
     * @return string tpaExtensionsETicketInd
     */
    public function getTpaExtensionsMileageAmount() {
        return $this->tpaExtensionsMileageAmount;
    }
}