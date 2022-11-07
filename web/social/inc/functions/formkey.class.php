<?php 
//You can of course choose any name for your class or integrate it in something like a functions or base class
class formKey{
    //Here we store the generated form key
    private $formKey;
     
    //Here we store the old form key (more info at step 4)
    private $old_formKey;     
    function __construct(){
        if(isset($_SESSION['form_key'])) {
            $this->old_formKey = $_SESSION['form_key'];
        }
    }
    //Function to generate the form key
    private function generateKey() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $uniqid = uniqid(mt_rand(), true);
        return md5($ip . $uniqid);
    }
    //Function to output the form key
    public function outputKey() {
        $this->formKey = $this->generateKey();
        $_SESSION['form_key'] = $this->formKey;
        return "<input type='hidden' name='form_key' id='form_key' value='".$this->formKey."' />";
    }
    //Function that validated the form key POST data
    public function validate($forms_key) {
        if( $forms_key == $this->old_formKey){
            return true;
        } else {
            return false;
        }
    }
}
