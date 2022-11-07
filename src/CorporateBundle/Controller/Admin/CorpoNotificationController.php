<?php

namespace CorporateBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Datetime;
use CorporateBundle\Model\Notification;
use CorporateBundle\Model\NotificationUsers;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller receiving actions related to the notification
 */
class CorpoNotificationController extends CorpoAdminController
{

    /**
     * controller action for the list of notifications
     *
     * @return TWIG
     */
    public function notificationAction()
    {
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $accountId = $sessionInfo['accountId'];
        $parameters = array(
            'accountId' => $accountId
        ); 
       
        $this->data['notificationList'] = $this->get('CorpoNotificationServices')->getCorpoNotificationList($parameters);
        return $this->render('@Corporate/admin/notification/notificationList.twig', $this->data);
    }

    public function getDataTableAction()
	{
        $dtService = $this->get('CorpoDatatable');
        $request = $this->get('request')->request->all();

        $sql = $this->get('CorpoNotificationServices')->prepareNotificationsDtQuery();
        $params = array(
            'request' => $request,
            'sql' => $sql->all_query,
            'addWhere' => NULL,
            'table' => 'corpo_notification',
            'key' => 'cn.id',
            'columns' => NULL
        );
        $queryArr = $dtService->buildQuery($params);
        $notification = $dtService->runQuery($queryArr);
        
        return new JsonResponse($notification);
	}

    /**
     * controller action for adding or  edditing a notification
     *
     * @return TWIG
     */
    public function notificationAddEditAction($id)
    {
        $this->data['notificationTypeList'] = $this->get('CorpoNotificationServices')->getCorpoNotificationTypeList();
        $this->data['notification'] = array();
        if($id){
            $parameters = array(
              'notificationId' => $id  
            );
            $notification = $this->get('CorpoNotificationServices')->getCorpoNotificationList($parameters);
            $this->data['notification'] = $notification[0];
        }
        return $this->render('@Corporate/admin/notification/notificationEdit.twig', $this->data);
    }

    /**
     * controller action for submiting either the add or edit of notification
     *
     * @return TWIG
     */
    public function notificationAddSubmitAction()
    {
        $parameters = $this->get('request')->request->all();
        $sessionInfo = $this->get('CorpoAdminServices')->getLoggedInSessionInfo();
        $parameters['createdBy'] = $sessionInfo['userId'];
        $notificationObj = Notification::arrayToObject($parameters);
        
        if ($parameters['id'] != 0) {
            $update = $this->get('CorpoNotificationServices')->updateNotification($notificationObj);
            $parameters['notificationId'] = $parameters['id'];
            $notificationUsersObj = NotificationUsers::arrayToObject($parameters);
            $success = $this->get('CorpoNotificationServices')->updateNotificationUser($notificationUsersObj);
            if ($success) {
                $this->addSuccessNotification($this->translator->trans("Notification updated successfully."));
                return $this->redirectToLangRoute('_corpo_notification_edit', array('id' => $parameters['id']));
            } else {
                return $this->redirectToLangRoute('_corpo_notification');
            }
        } else {
            $notificationId = $this->get('CorpoNotificationServices')->addNotification($notificationObj);
            if($notificationId) {
                $parameters['notificationId'] = $notificationId;
                $notificationUsersObj = NotificationUsers::arrayToObject($parameters);
                $success = $this->get('CorpoNotificationServices')->addNotificationUser($notificationUsersObj);
                if ($success) {
                    $this->addSuccessNotification($this->translator->trans("Notification added successfully."));
                    return $this->redirectToLangRoute('_corpo_notification');
                } else {
                    $this->addErrorNotification($this->translator->trans("Error adding notification users."));
                    return $this->redirectToLangRoute('_corpo_notification_add');
                }
            } else {
                $this->addErrorNotification($this->translator->trans("Error adding notification."));
                return $this->redirectToLangRoute('_corpo_notification_add');
            }
        }
    }

    /**
     * controller action for deleting a notification
     *
     * @return TWIG
     */
    public function notificationDeleteAction($id)
    {
        $success = $this->get('CorpoNotificationServices')->deleteNotification($id);
        return $this->redirectToLangRoute('_corpo_notification');
    }
}