<?php
namespace OTPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="otp_request", indexes={@ORM\Index(name="generatedId_idx", columns={"generated_id"}),@ORM\Index(name="moduleId_idx", columns={"module_id"}),@ORM\Index(name="transactionId_idx", columns={"transaction_id"}),@ORM\Index(name="isUsed_idx", columns={"is_Used"})})
 */
class RequestOTP
{

    /**
     *
     * @var integer @ORM\Column(name="id", type="integer", nullable=false)
     *      @ORM\Id
     *      @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $code;

    /**
     * @ORM\Column(type="integer")
     */
    private $module_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $transaction_id;

    /**
     * @ORM\Column(type="datetime", options={"default": 0})
     */
    private $creation_date;

    /**
     * @ORM\Column(type="boolean", options={"default":0})
     */
    private $is_used;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $generated_id;
    
    /**
     * @ORM\Column(type="string", length=200)
     */
    private $url_params;

    /**
     * @return mixed
     */
    public function getUrl_params()
    {
        return $this->url_params;
    }

    /**
     * @param mixed $url_params
     */
    public function setUrl_params($url_params)
    {
        $this->url_params = $url_params;
    }

    /**
     *
     * @return mixed
     */
    public function getCreation_date()
    {
        return $this->creation_date;
    }

    /**
     *
     * @param mixed $creation_date
     */
    public function setCreation_date($creation_date)
    {
        $this->creation_date = $creation_date;
    }

    /**
     *
     * @return mixed
     */
    public function getGenerated_id()
    {
        return $this->generated_id;
    }

    /**
     *
     * @param mixed $generated_id
     */
    public function setGenerated_id($generated_id)
    {
        $this->generated_id = $generated_id;
    }

    /**
     *
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     *
     * @return mixed
     */
    public function getModule_id()
    {
        return $this->module_id;
    }

    /**
     *
     * @return mixed
     */
    public function getTransaction_id()
    {
        return $this->transaction_id;
    }

    /**
     *
     * @param mixed $transaction_id
     */
    public function setTransaction_id($transaction_id)
    {
        $this->transaction_id = $transaction_id;
    }

    /**
     *
     * @return mixed
     */
    public function getIs_used()
    {
        return $this->is_used;
    }

    /**
     *
     * @return mixed
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     *
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     *
     * @param mixed $module_id
     */
    public function setModule_id($module_id)
    {
        $this->module_id = $module_id;
    }

    /**
     *
     * @param mixed $is_used
     */
    public function setIs_used($is_used)
    {
        $this->is_used = $is_used;
    }

    /**
     *
     * @param mixed $user_id
     */
    public function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     *
     * @param mixed $id
     */
    public function getId()
    {
        return $this->id;
    }
}