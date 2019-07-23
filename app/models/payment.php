<?php 

class Payment
{
    public $card_number;
    public $card_holder;
    private $card_ccv;

    public $status = false;

    public function __construct($auth)
    {
        $this->auth = $auth;
    }

    public function set_ccv(int $ccv=null)
    {   
        if($ccv != null) $this->card_ccv = $ccv;
    }

    public function process_payment()
    {
        //function to process payment is empty, because of study purpose =)) 
        return true;
    }

    public function validate_number()
    {

    }

    public function validate_holder()
    {
        
    }

    public function validate_ccv()
    {
        
    }
}