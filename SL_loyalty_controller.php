<?php
/**
 * Customer Loyalty Controller
 *
 * @category Snowleader
 * @package  Snowleader_Customer
 */
class Snowleader_Customer_LoyaltyController extends Mage_Core_Controller_Front_Action
{
    /**
     * Only logged in users can use this functionality,
     * this function checks if user is logged in before all other actions
     *
     */
    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::getSingleton('customer/session')->authenticate($this)) {
            $this->setFlag('', 'no-dispatch', true);
        }
        if (!Mage::helper('enterprise_reward')->isEnabledOnFront()) {
            $this->norouteAction();
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
    }

    /**
     * Customer Loyalty Page
     *
     */
    public function indexAction()
    {
        /* Gift Card */
        $data = $this->getRequest()->getPost();
        if (isset($data['giftcard_code'])) {
            $code = $data['giftcard_code'];
            try {
                if (!Mage::helper('enterprise_customerbalance')->isEnabled()) {
                    Mage::throwException($this->__('Redemption functionality is disabled.'));
                }
                Mage::getModel('enterprise_giftcardaccount/giftcardaccount')->loadByCode($code)
                    ->setIsRedeemed(true)->redeem();
                Mage::getSingleton('customer/session')->addSuccess(
                    $this->__('Gift Card "%s" was redeemed.', Mage::helper('core')->htmlEscape($code))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('customer/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('customer/session')->addException($e, $this->__('Cannot redeem Gift Card.'));
            }
            $this->_redirect('*/*/*');
            return;
        }

        /*Customer Balance */
        if (!Mage::helper('enterprise_customerbalance')->isEnabled()) {
            $this->_redirect('customer/account/');
            return;
        }
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $this->loadLayoutUpdates();
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle($this->__('Customer Loyalty'));
        }
        $this->renderLayout();
    }

    /**
     * Info Action
     */
    public function infoAction()
    {
        Mage::register('current_reward', $this->_getReward());
        $this->loadLayout();
        $this->_initLayoutMessages('customer/session');
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::helper('enterprise_reward')->__('Reward Points'));
        }
        $this->renderLayout();
    }

    /**
     * Save settings
     */
    public function saveSettingsAction()
    {
        if (!$this->_validateFormKey()) {
            return $this->_redirect('*/*/info');
        }

        $customer = $this->_getCustomer();
        if ($customer->getId()) {
            $customer->setRewardUpdateNotification($this->getRequest()->getParam('subscribe_updates'))
                ->setRewardWarningNotification($this->getRequest()->getParam('subscribe_warnings'));
            $customer->getResource()->saveAttribute($customer, 'reward_update_notification');
            $customer->getResource()->saveAttribute($customer, 'reward_warning_notification');

            $this->_getSession()->addSuccess(
                $this->__('The settings have been saved.')
            );
        }
        $this->_redirect('*/*/info');
    }

    /**
     * Unsubscribe customer from update/warning balance notifications
     */
    public function unsubscribeAction()
    {
        $notification = $this->getRequest()->getParam('notification');
        if (!in_array($notification, array('update','warning'))) {
            $this->_forward('noroute');
        }

        try {
            /* @var $customer Mage_Customer_Model_Session */
            $customer = $this->_getCustomer();
            if ($customer->getId()) {
                if ($notification == 'update') {
                    $customer->setRewardUpdateNotification(false);
                    $customer->getResource()->saveAttribute($customer, 'reward_update_notification');
                } elseif ($notification == 'warning') {
                    $customer->setRewardWarningNotification(false);
                    $customer->getResource()->saveAttribute($customer, 'reward_warning_notification');
                }
                $this->_getSession()->addSuccess(
                    $this->__('You have been unsubscribed.')
                );
            }
        } catch (Exception $e) {
            $this->_getSession()->addError($this->__('Failed to unsubscribe.'));
        }

        $this->_redirect('*/*/info');
    }

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    protected function _getCustomer()
    {
        return $this->_getSession()->getCustomer();
    }

    /**
     * Load reward by customer
     *
     * @return Enterprise_Reward_Model_Reward
     */
    protected function _getReward()
    {
        $reward = Mage::getModel('enterprise_reward/reward')
            ->setCustomer($this->_getCustomer())
            ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
            ->loadByCustomer();
        return $reward;
    }
}