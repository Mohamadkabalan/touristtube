<?php

namespace CorporateBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use \DateTime;

class FunctionExtention extends \Twig_Extension
{
    public $container;
    protected $_content;
    protected $_data = array();

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setContent($_content)
    {
        $this->_content = $_content;
    }

    public function getFunctions()
    {
        //
        $template = $this;
        //
        return array(
            new \Twig_simpleFunction('corpoListWaitingApprovalTemplate', function ($record) use($template) {
                    //
                    $moduleId          = $record['moduleId'];
                    //
                    $record['details'] = json_decode($record['details'], true);
                    //
                    switch ($moduleId) {
                        case $this->container->getParameter("MODULE_HOTELS");
                            $record = $this->hotelsHandler($record);
                            break;
                        case $this->container->getParameter("MODULE_DEALS");
                            $record = $this->dealsHandler($record);
                            break;
                        case $this->container->getParameter("MODULE_FLIGHTS");
                            $record = $this->flightsHandler($record);
                            break;
                    }
                    //
                    $template->setContent($template->getTemplate($moduleId));
                    $template->setKeys($record);
                    //
                    echo $template->render();
                })
        );
    }

    public function formatDate($dateValue, $withTime = false)
    {
        $FULL_DATE_FORMAT = $this->container->getParameter("FULL_DATE_FORMAT");
        if ($withTime) $FULL_DATE_FORMAT .= " H:i";
        //
        $values           = new DateTime($dateValue);
        $values           = $values->format($FULL_DATE_FORMAT);
        //
        return $values;
    }

    public function formatAmount($amount)
    {
        $amount           = number_format($amount, $this->container->getParameter("FULL_NUMBER_FORMAT"));
        //
        return $amount;
    }

    public function defaultHandler($values, $withTime = false)
    {
        $values["fromdate"] = $this->formatDate($values["fromdate"], $withTime);
        $values["todate"]   = $this->formatDate($values["todate"], $withTime);
        $values["amount"]   = $this->formatAmount($values["amount"]);

        if($values["moduleId"] == $this->container->getParameter("MODULE_FLIGHTS")){
            $values["openpopup"]     = "detailPopup('/corporate/flights/reservation/approval/". $values["reservationId"] ."')";
        }elseif($values["moduleId"] == $this->container->getParameter("MODULE_HOTELS")){
            $values["openpopup"]     = "detailPopup('/corporate/hotels/reservation/approval/". $values["reservationId"] ."')";
        }elseif($values["moduleId"] == $this->container->getParameter("MODULE_DEALS")){
            $values["openpopup"]     = "detailPopup('/corporate/deals/reservation/approval/". $values["reservationId"] ."')";

        }

        if($values["status"]==$this->container->getParameter("CORPO_APPROVAL_EXPIRED")) {
            $html                       = "<span class='approvetext_travelapp '>Expired</span>";
            $values["approvalLnk"]      = $html;
        }else if($values["status"]==$this->container->getParameter("CORPO_APPROVAL_APPROVED")) {
            $html                       = "<span class='approvetext_travelapp'>Approved</span>";
            $values["approvalLnk"]      = $html;
        }else if($values["status"]==$this->container->getParameter("CORPO_APPROVAL_CANCELED")) {
            $html                       = "<span class='approvetext_travelapp'>Canceled</span>";
            $values["approvalLnk"]      = $html;
        }else {
            $link                   = '/corporate/approval-flow/approve';
            $appLink                = "<a href='" . $link . "/" . $values["reservationId"] . "-" . $values["moduleId"] . "-" . $values["accountId"] . "-" . $values["transactionUserId"] . "-" . $values["requestServicesDetailsId"]. "' class='approvetext_travelapp'>approve</a>";
            $values["approvalLnk"]  = $appLink;
        }
        //
        return $values;
    }

    public function hotelsHandler($values)
    {
        return $this->defaultHandler($values);
    }

    public function dealsHandler($values)
    {
        return $this->defaultHandler($values);
    }

    public function flightsHandler($values)
    {
        $values                     = $this->defaultHandler($values, true);
        //
        return $values;
    }

    public function getTemplate($moduleId)
    {
        $templates                                                   = array();
        //
        $templates[$this->container->getParameter("MODULE_DEFAULT")] = '';
        //
//        $link                                                        = '/corporate/approval-flow/approve';
        //FLIGHTS TEMPLATE
        $templates[$this->container->getParameter("MODULE_FLIGHTS")] = '
        <div class="col-xs-12 nopad">
            <div class="moduleLogo book-flight-icon"></div>
            <div class="moduleInfo">
                <div class="col-xs-12 nopad margintop24_paydue">
                    <p class="flightcomptext_paydue">
                        <img src="/media/images/airline-logos/{details[\'logo\']}" class="flightLogo"/> {name}<span class="flightcomptext_smal_paydue">{details[\'airline\']}{details[\'flightNumber\']}</span>
                    </p>
                    <p class="rightpricesmal_paydue">{currency} {amount}</p>
                </div>
                <div class="col-xs-12 nopad">
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 nopad">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nopad">
                            <div class="col-xs-12 nopad hiddenpmargin">
                                <p class="deparrbluetext_paydue">Departure<p>
                            </div>
                            <div class="col-xs-12 nopad">
                                <p class="datetextcom_paydue">{fromdate}</p> <p><b>{details[\'departureAirport\']}</b></p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nopad">
                            <div class="col-xs-12 nopad hiddenpmargin">
                                <p class="deparrbluetext_paydue">Arrival<p>
                            </div>
                            <div class="col-xs-12 nopad">
                                <p class="datetextcom_paydue">{todate}</p> <p><b>{details[\'arrivalAirport\']}</b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 nopad">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nopad">
                            <div class="col-xs-12 nopad hiddenpmargin">
                                <p class="deparrbluetext_paydue">number of stops<p>
                            </div>
                            <div class="col-xs-12 nopad">
                                <p><b>{details[\'nStops\']}</b></p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nopad">
                            <div class="col-xs-12 nopad hiddenpmargin">
                                <p class="deparrbluetext_paydue">number of passengers<p>
                            </div>
                            <div class="col-xs-12 nopad">
                                <p><b>{details[\'nPassengers\']}</b></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12 nopad">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nopad">
                            <div class="col-xs-12 nopad hiddenpmargin">
                                <p class="deparrbluetext_paydue">Flight Type<p>
                            </div>
                            <div class="col-xs-12 nopad">
                                <p><b>{details[\'flightType\']}</b></p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 nopad">
                            <div class="col-xs-12 nopad">
                                <p><b>{details[\'refundable\']}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div>Requested By: {userName}</div>
                <div class="col-xs-12 nopad approve_travelapp">
                    <span class="approvetext_travelapp">Canceled</span>
                    <div class="">|</div>
                    <a class="approvetext_travelapp" href="">reject</a>
                    <div class="">|</div>
                    {approvalLnk}
                    <div class="barseptrapp">|</div>
                    <button type="button" class="btn btn-primary" data-toggle="modal1" data-target="#myModal1" onclick="{openpopup}">details</button>
                </div>

            </div>
            <div class="col-xs-12 nopad borderedsectiontwolines_travelapp margin_top_15"></div>
        </div>';
        //
        //HOTELS TEMPLATE
        $templates[$this->container->getParameter("MODULE_HOTELS")]  = '
        <div class="col-xs-12 nopad">
            <div class="moduleLogo book-hotel-icon"></div>
            <div class="moduleInfo">
                <div class="col-xs-12 nopad margintop24_paydue">
                    <p class="htelnametext_paydue">{name}</p>
                    <p class="bookingnumtextgrey_paydue">Booking number:<span class="booknumberblack_paydue">{reservationId}</span></p>
                    <p class="rightpricesmal_paydue">{currency} {amount}</p>
                    <!-- <p class="pincodetextgrey_paydue">PIN Code:<span class="pincodenumberblack_paydue"></span></p> -->
                </div>
                <div class="col-xs-12 nopad">
                    <p class="chekinouttextgrey_paydue">CHECK-IN<span class="datebigblack_paydue">{fromdate}</span> <span class="dayblacksmal_paydue"></span><span class="dotpadleftright12">.</span></p>
                    <p class="chekinouttextgrey_paydue">CHECK-OUT<span class="datebigblack_paydue">{todate}</span> <span class="dayblacksmal_paydue"></span></p>
                </div>
                <div>Requested By: {userName}</div>
                <div class="col-xs-12 nopad approve_travelapp">
                    <span class="approvetext_travelapp">Canceled</span>
                    <div class="">|</div>
                    <a class="approvetext_travelapp" href="">reject</a>
                    <div class="">|</div>
                    {approvalLnk}
                    <div class="barseptrapp">|</div>
                    <button type="button" class="btn btn-primary" data-toggle="modal1" data-target="#myModal1" onclick="{openpopup}">details</button>
                </div>

            </div>
            <div class="col-xs-12 nopad borderedsectiontwolines_travelapp margin_top_15"></div>
        </div>';
        //
        //DEALS TEMPLATE
        $templates[$this->container->getParameter("MODULE_DEALS")]   = '
        <div class="col-xs-12 nopad">
            <div class="moduleLogo book-deal-icon"></div>
            <div class="moduleInfo">
                <div class="col-xs-12 nopad margintop24_paydue">
                    <p class="htelnametext_paydue">{name}</p>
                    <p class="bookingnumtextgrey_paydue">Booking number:<span class="booknumberblack_paydue">{reservationId}</span></p>
                    <p class="rightpricesmal_paydue">{currency} {amount}</p>
                    <!-- <p class="pincodetextgrey_paydue">PIN Code:<span class="pincodenumberblack_paydue"></span></p> -->
                </div>
                <div class="col-xs-12 nopad">
                    <p class="chekinouttextgrey_paydue">CHECK-IN<span class="datebigblack_paydue">{fromdate}</span> <span class="dotpadleftright12">.</span></p>
                    <p class="chekinouttextgrey_paydue">CHECK-OUT<span class="datebigblack_paydue">{todate}</span> </p>
                </div>
                <div>Requested By: {userName}</div>
                <div class="col-xs-12 nopad approve_travelapp">
                    <span class="approvetext_travelapp">Canceled</span>
                    <div class="">|</div>
                    <a class="approvetext_travelapp" href="">reject</a>
                    <div class="">|</div>
                    {approvalLnk}
                    <div class="barseptrapp">|</div>
                    <button type="button" class="btn btn-primary" data-toggle="modal1" data-target="#myModal1" onclick="{openpopup}">details</button>
                </div>

            </div>
            <div class="col-xs-12 nopad borderedsectiontwolines_travelapp margin_top_15"></div>
        </div>';
        //
        //
        return $templates[$moduleId];
    }

    public function setKeys($keys = array())
    {
        $this->_data = $keys;
        return $this;
    }

    public function set($key, $value)
    {
        $this->_data[$key] = $value;
        return $this;
    }

    public function render()
    {
        $string_processed = preg_replace_callback(
            '~\{(.*?)\}~si', function($match) //use()
        {
            $data = $this->_data;
            //unset($data['details']);
            //$details = $this->_data['details'];
            //
            extract($data);
            //extract($details);
            //
            return eval('return $'.$match[1].';');
        }, $this->_content);

        return $string_processed;
    }

    public function jsonDecode($str)
    {
        return json_decode($str);
    }
}