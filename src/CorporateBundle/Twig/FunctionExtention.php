<?php

namespace CorporateBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class FunctionExtention extends \Twig_Extension
{
    protected $container;

    function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_simpleFunction('decodeJSON', function ($details) {
                    $detailsArray       = json_decode($details);
                    return $detailsArray;
                }),
            new \Twig_SimpleFunction('is_user_can_approved', array($this, 'isUserCanApproved'))
        );
    }

    /**
     * This extention method will get user approval by corpo_request_services_details.user_id
     *
     * @param $transactionUserId
     * @return bool
     */
    public function isUserCanApproved($transactionUserId)
    {
        $sessionInfo = $this->container->get('CorpoAdminServices')->getLoggedInSessionInfo();

        $accountId = $sessionInfo['accountId'];
        $userId = $sessionInfo['userId'];

        $isApprove = $this->container->get('CorpoApprovalFlowServices')->allowedToApproveForUser($userId, $transactionUserId, $accountId);

        $userObj = $this->container->get('UserServices')->getUserDetails(array('id' => $userId));
        $userRole = $userObj[0]['cu_cmsUserGroupId'];

        if ($isApprove || $userRole == $this->container->getParameter('ROLE_SYSTEM')) {
            return true;
        }

        return false;
    }
}
