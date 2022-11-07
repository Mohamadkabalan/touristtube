<?php
namespace OTPBundle\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OTPRequestRepository extends EntityRepository
{

    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->em = $em;
        $this->container = $container;
    }
    
    public function generateIdHash($id)
    {
        $salt = "$id";
        
        do
        {
            $salt = str_repeat($salt, 3);
        }
        while (strlen($salt) < 22);
        
        $salt = substr($salt, 0, 22);
        
        return str_replace(array('$', '/', '%', '.'), array('', '', '', ''), password_hash($id, PASSWORD_BCRYPT, array('salt' => $salt)));
    }

    public function addCode($otpRequest)
    {
        $this->em->persist($otpRequest);
        $this->em->flush();
        $id = $otpRequest->getId();
        $encrypted = $this->generateIdHash($id);
        $this->updateRecordGeneratedId($id, $encrypted);
        return $encrypted;
    }

    public function verifyCode($id, $code)
    {
        $response = $this->em->getRepository('OTPBundle:RequestOTP')->findOneBy(array(
            'id' => $id,
            'code' => $code
        ));
        return $response;
    }

    public function getRequestByCode($code)
    {
        $response = $this->em->getRepository('OTPBundle:RequestOTP')->findOneBy(array(
            'code' => $code
        ));
        return $response;
    }
    
    public function getRequestByGenId($genId)
    {
        $response = $this->em->getRepository('OTPBundle:RequestOTP')->findOneBy(array(
            'generated_id' => $genId
        ));
        return $response;
    }

    public function getUserInfo($id)
    {
        $con = $this->em->getConnection();
        $sql = 'SELECT * FROM cms_users cu WHERE cu.id = :uid';
        $stmt = $con->prepare($sql);
        $stmt->execute([
            'uid' => $id
        ]);
        return $stmt->fetchAll();
    }
    
    public function verifyCodeValidation($moduleId, $transactionId, $generatedId)
    {
        $con =$this->em->getConnection();
        $sql = 'SELECT * FROM otp_request otp WHERE otp.module_id = :module_id
                AND otp.transaction_id = :trans_id
                AND otp.generated_id = :gen_id';
        $stmt = $con->prepare($sql);
        $stmt->execute([
            'module_id' => $moduleId,
            'trans_id' => $transactionId,
            'gen_id' => $generatedId
        ]);
        return $stmt->fetchAll();
        
    }

    public function updateRecord($id)
    {
        $response = $this->em->getRepository('OTPBundle:RequestOTP')->find($id);
        $response->setIs_used(1);
        $this->em->flush();
        return $response;
    }

    public function updateRecordGeneratedId($id, $generatedId)
    {
        $response = $this->em->getRepository('OTPBundle:RequestOTP')->find($id);
        $response->setGenerated_id($generatedId);
        $this->em->flush();
    }
}