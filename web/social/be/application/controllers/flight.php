<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Flight extends MY_Controller {

    const PER_PAGE = 100;

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('login', 'refresh');
        } else {
            $session_data = $this->session->userdata('logged_in');
            $action = $this->router->fetch_method();
            $role = $session_data['role'];
        }
    }
    /**
     * Index where the flights page initially loaded
     * @param  integer $start pagination
     * @return string 
     */
    public function index($cc = 'all', $start = 0){
        $this->load->library('pagination');
        
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Manage Flights';
        $data['content']= 'flight/list';
        
        $PnrModel = new Passenger_name_record_Model();

        $config['uri_segment'] = 3;
        $config['per_page'] = self::PER_PAGE;
        $config["num_links"] = 14;
        $config['base_url'] = 'flight/ajax_list'; 
        
        $total = $PnrModel->count();
        
        $config['total_rows'] = $total;  
        
        $PnrModel->order_by('id desc')->get(self::PER_PAGE);

        $data['pnrs']= $PnrModel;

        $this->pagination->initialize($config);
        
        $data['links'] = $this->pagination->create_links();
        $data['jsIncludes'] = array('flight.js');
        
        $this->load->view('template', $data);
    }

    function ajax_list($start = 0){
        $this->load->library('pagination');
        
        $pnr = $this->input->post('pnr');
        $email = $this->input->post('email');
        $fname = $this->input->post('fname');
        $sname = $this->input->post('sname');
        $status = $this->input->post('status');
        
        $PnrModel = new Passenger_name_record_Model();
        
        $config['uri_segment'] = 3;
        $config['per_page'] = self::PER_PAGE;
        $config["num_links"] = 14;
        $config['base_url'] = 'flight/ajax_list';

        if ( $status !== "0" ) {
            $total = $PnrModel->where('status', $status)->like( 'pnr', $pnr )->like('email', $email)->like('first_name', $fname)->like('surname', $sname)->count();
        } else {
            $total = $PnrModel->like( 'pnr', $pnr )->like('email', $email)->like('first_name', $fname)->like('surname', $sname)->count();
        }
        
        $config['total_rows'] = $total;
        $limit = $total;

        $PnrModel->like( 'pnr', $pnr )->like('email', $email)->like('first_name', $fname)->like('surname', $sname);
        
        if ( $status !== "0" ) {
            $PnrModel->where('status', $status);
        }

        $PnrModel->order_by('id desc')->get(self::PER_PAGE, $start);

        $data['pnrs']= $PnrModel;
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();

        $this->load->view('flight/ajax_list', $data);
    }

    /**
     * Flights details page
     * @param  integer $id PNR Id
     * @return string view page
     */
    public function view( $id ) {
        
        $session_data = $this->session->userdata('logged_in');
        $data['username'] = $session_data['username'];
        $data['title']= 'Flight Itenirary';
        $data['content']= 'flight/view';

        $PnrModel = new Passenger_name_record_Model();
        $PnrModel->where('id', $id)->get();

        $data['pnr'] = $PnrModel;
        $data['flighinfo'] = $PnrModel->flighinfo->all_to_array();
        $data['flights_details'] = $PnrModel->flight->all_to_array();
        $data['passenger_details'] = $PnrModel->detail->all_to_array();
        $data['jsIncludes'] = array('flight.js');
        
        $payment = new Payment_Model();
        $payment->where('uuid', $PnrModel->payment_uuid)->get();

        $data['payment'] = $payment;

        $this->load->view('template', $data);
    }

    /**
     * Send Cancellation Fees
     * @param  integer $id PNR Id
     * @return string
     */
    public function sendCancellationFee() {

        $pnr_id = $this->input->post('pnr_id');
        $fee = $this->input->post('add_fee');
        $email = $this->input->post('customer_email');
        $message = $this->input->post('message_body');
        
        $CmsEmailModel = new Cms_Email_Model();

        $PnrModel = new Passenger_name_record_Model();
        $PnrModel->where('id', $pnr_id)->get();
        
        $this->load->helper('url');
        $url_parts = parse_url(current_url());
        
        $msg = $this->load->view(
            'flight/cancelled_template', 
            array(
                'message' =>  $message,
                'trans_id' => $PnrModel->payment_uuid,
                'host' => $url_parts['host']
            ),
            true
        );

        $subject = "Flight Cancellation";
        $title = "Flight Cancellation";
        $priority = 1;

        $data = array(
            'to_email' => $email,
            'msg' => $msg,
            'subject' => $subject,
            'title' => $title,
            'priority' => $priority
        );

        $CmsEmailModel->persist( $data );

        /*
        $path = "../";
        include_once ( $path . "inc/classes/phpmailer.class.php" );

        $mail = new PHPMailer(true);
        $mail->IsSMTP();
        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->Username = 'test@gmail.com';
        $mail->Password = 'password';
        
        $mail->ClearAddresses();
        $mail->SetFrom('info@touristtube.com', 'Tourista'); 
        $mail->Subject = $subject;
        $mail->MsgHTML($msg);
        $mail->AddAddress($email);
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!";

        if($mail->Send()){
            //$CmsEmailModel->persist( $data ) ;
        }
        */
    }

}
