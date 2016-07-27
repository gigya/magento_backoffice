<?php

/**
 * Created by PhpStorm.
 * User: Yaniv Aran-Shamir
 * Date: 7/26/16
 * Time: 10:31 AM
 */
class Gigya_BackOffice_Model_Observer_Customer {

  public function refreshFromGigya($observer) {
    $exec = Mage::registry("gigya_backoffice_refresh_executed");
    if (isset($_SESSION['adminhtml']) && null === $exec && count($_POST) === 0) {
      /** @var Mage_Customer_Model_Customer $customer */
      $customer  = $observer->getData('customer');
      $gigya_uid = $customer->getData('gigya_uid');
      /** @var Gigya_Social_Helper_Data $helper */
      $helper       = Mage::helper('Gigya_Social');
      $gigyaAccount = $helper->utils->getAccount($gigya_uid);
      if (is_numeric($gigyaAccount)) {
        Mage:
        log(
          "Error retrieving account from gigya error code was " . $gigyaAccount
        );
      }
      else {
        $updater = new Gigya_Social_Helper_FieldMapping_MagentoUpdater(
          $gigyaAccount
        );
        if ($updater->isMapped()) {
          $updater->updateMagentoAccount($customer);
        }
      }
      Mage::register("gigya_backoffice_refresh_executed", TRUE);
    }
  }

}