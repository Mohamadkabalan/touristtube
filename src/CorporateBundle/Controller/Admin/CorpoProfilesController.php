<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CorporateBundle\Model\Profiles;

/**
 * Controller receiving actions related to the request for Profiles
 */
class CorpoProfilesController extends CorpoAdminController
{

    /**
     * controller action for the list of profiles
     *
     * @return TWIG
     */
    public function profilesAction()
    {
        $this->data['profilesList'] = $this->get('CorpoProfilesServices')->getCorpoAdminProfilesList();
        return $this->render('@Corporate/admin/profiles/profilesList.twig', $this->data);
    }

    /**
     * controller action for edditing or adding a profile
     *
     * @return TWIG
     */
    public function profilesAddEditAction($id)
    {

        $this->data['profile'] = array();
        if ($id) {
            $this->data['profile'] = $this->get('CorpoProfilesServices')->getCorpoAdminProfile($id);
        }else{
            $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
            $accountInfo = $this->get('CorpoAccountServices')->getCorpoAdminAccount($sessionInfo['accountId']);
            $profile['accountId'] = $sessionInfo['accountId'];
            $profile['accountName'] = $accountInfo['al_name'];
            $this->data['profile'] = $profile;
        }

        return $this->render('@Corporate/admin/profiles/profilesEdit.twig', $this->data);
    }

    /**
     * controller action for deleting a profile
     *
     * @return TWIG
     */
    public function profilesDeleteAction($id)
    {
        $success = $this->get('CorpoProfilesServices')->deleteProfiles($id);
        return $this->redirectToLangRoute('_corpo_profiles');
    }

    /**
     * controller action for submiting either the add or edit of profile
     *
     * @return TWIG
     */
    public function profilesAddSubmitAction()
    {
        $parameters = $this->get('request')->request->all();
        $profilesObj = Profiles::arrayToObject($parameters);
        
        if ($parameters['id'] != 0) {
            $success = $this->get('CorpoProfilesServices')->updateProfiles($profilesObj);
            if ($success) {
                $this->addSuccessNotification($this->translator->trans("Updated successfully."));
                return $this->redirectToLangRoute('_corpo_profiles_edit', array('id'=>$parameters['id']));
            } else {
                return $this->redirectToLangRoute('_corpo_profiles');
            }
        } else {
            $success = $this->get('CorpoProfilesServices')->addProfiles($profilesObj);
            if ($success) {
                $this->addSuccessNotification($this->translator->trans("Added successfully."));
                return $this->redirectToLangRoute('_corpo_profiles');
            } else {
                $this->addErrorNotification($this->translator->trans("Error adding profile."));
                return $this->redirectToLangRoute('_corpo_profiles_add');
            }
        }
    }
}