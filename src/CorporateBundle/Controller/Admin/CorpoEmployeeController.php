<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller receiving actions related to the Employee
 */
class CorpoEmployeeController extends CorpoAdminController
{
    
    /**
     * controller action for the list of employee
     *
     * @return TWIG
     */
    public function employeeAction()
    {
        $this->data['employeeList'] = $this->get('CorpoEmployeesServices')->getCorpoAdminEmployeeList();
        return $this->render('@Corporate/admin/employee/employeeList.twig', $this->data);
    }
    
    /**
     * controller action for adding or edditing an employee
     *
     * @return TWIG
     */
    public function employeeAddEditAction($id)
    {
        $this->data['employee'] = array();
        if ($id) {
            $this->data['employee'] = $this->get('CorpoEmployeesServices')->getCorpoAdminEmployeeById($id);
        } else {
            $sessionInfo             = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $accountInfo             = $this->get('CorpoAccountServices')->getCorpoAdminAccount($sessionInfo['accountId']);
            $employee['accountId']   = $sessionInfo['accountId'];
            $employee['accountName'] = $accountInfo['al_name'];
            $this->data['employee']  = $employee;
        }
        
        return $this->render('@Corporate/admin/employee/employeeEdit.twig', $this->data);
    }
    
    /**
     * controller action for adding or edditing an employee Settings
     *
     * @return TWIG
     */
    public function employeeSettingsAddEditAction()
    {
        $sessionInfo          = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId            = $sessionInfo['accountId'];
        $userId               = $sessionInfo['userId'];
        //this variable is used because we have two pages:"Profile Settings" , "Employee" this variable is used to know from which page we are using this action
        //Author: Anthony Malak
        $type                 = 1;
        $employee             = array();
        $userId               = $sessionInfo['userId'];
        $parameters['userId'] = $userId;
        $employee             = $this->get('CorpoEmployeesServices')->getCorpoAdminEmployeeById($parameters);

        if ($employee) {
            $employee['p_image_path'] = '/'.$this->getParameter('USER_AVATAR_RELATIVE_PATH');
            $employee['profile_empty_pic']= 1;
            if( $employee['p_profilePicture'] !='' )
            {
                $employee['profile_empty_pic']= 0;
            }
            $type                         = 2;
        } else {
            $employee['p_accountId']      = $accountId;
            $accountInfo                  = $this->get('CorpoAccountServices')->getCorpoAdminAccount($accountId);
            if($accountInfo){
                $employee['accountName']      = $accountInfo['al_name'];
            }
            
            $userInfo = $this->get('UserServices')->getUserInfoById($userId, false);
            if (empty($userInfo)) throw new HttpException(400, $this->translator->trans('Invalid user credentials. Please try again.'));
        
            $profile_empty_pic            = $userInfo['profile_empty_pic'];
            $fname                        = $userInfo[0]->getFname();
            $lname                        = $userInfo[0]->getLname();
            $email                        = $userInfo[0]->getYouremail();
            $image                        = $userInfo[0]->getProfilePic();
            $employee['countryCode']      = $userInfo[0]->getYourcountry();
            $employee['p_countryCode']      = $userInfo[0]->getYourcountry();
            $employee['p_image_path']     = '/'.$this->getParameter('USER_AVATAR_RELATIVE_PATH');
            $employee['p_profilePicture'] = $image;
            $employee['profile_empty_pic']= $profile_empty_pic;
            $employee['p_email']          = $email;
            $employee['p_fname']          = $fname;
            $employee['p_lname']          = $lname;
        }

        $this->data['employee'] = $employee;
        $this->data['type']     = $type;
        return $this->render('@Corporate/corporate/corporate-profile-settings.twig', $this->data);
    }
    
    /**
     * controller action for deleting an employee
     *
     * @return TWIG
     */
    public function employeeDeleteAction($id)
    {
        $success = $this->get('CorpoEmployeesServices')->deleteEmployee($id);
        return $this->redirectToLangRoute('_corpo_employee');
    }
    
    /**
     * controller action for submiting either the add or edit an employee
     *
     * @return TWIG
     */
    public function employeeAddSubmitAction()
    {
        $parameters   = $this->get('request')->request->all();
        $sessionInfo          = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        
        if (isset($parameters['type']) && $parameters['type'] == 1) {
            $accountId            = $sessionInfo['accountId'];
            $userId               = $sessionInfo['userId'];
            $parameters['userId'] = $userId;
        } else {
            $userId = $parameters['accountUserId'];
        }

        if(!isset($parameters['p_profile_picture'])){
            if(isset($parameters['userId']))
                $userInfo = $this->get('UserServices')->getUserInfoById($parameters['userId'], false);
            else 
                $userInfo = $this->get('UserServices')->getUserInfoById($sessionInfo['userId'], false);

            if (empty($userInfo)) throw new HttpException(400, $this->translator->trans('Invalid user credentials. Please try again.'));
            $parameters['p_profile_picture'] = $userInfo[0]->getProfilePic();
        }

        $redirectRoute="";
        if ($parameters['id'] != 0) {
            $success = $this->get('CorpoEmployeesServices')->updateEmployee($parameters);
            if ($success) {
                if (isset($parameters['type'])) {
                    $redirectRoute='_corporate_profile_settings';
                } else {
                    $redirectRoute='_corpo_employee';
                }
            } else {
                if (isset($parameters['type'])) {
                    $redirectRoute='_corpo_employee';
                } else {
                    return $this->redirectToLangRoute('_corpo_employee_edit', array(
                        'id' => $parameters['id']
                    ));
                }
            }
            
            return $this->redirectToLangRoute($redirectRoute);
        } else {            
            $success = $this->get('CorpoEmployeesServices')->addEmployee($parameters);
            if ($success) {
                if (isset($parameters['type'])) {
                    $redirectRoute='_corporate_profile_settings';
                } else {
                    $redirectRoute='_corpo_employee';
                }
            } else {
                if (isset($parameters['type'])) {
                    $redirectRoute='_corporate_profile_settings';
                } else {
                    $redirectRoute='_corpo_employee_add';
                }
            }
            
            return $this->redirectToLangRoute($redirectRoute);
        }
    }
}
