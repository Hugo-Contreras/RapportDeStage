<?php
/**
 * Get HTML for CMS block premium
 *
 * @return string
 */
public function getCmsBlockPremiumHtml()
{
    $cms = $this->getChild('customer_become_premium'); // Recuperation of the default block

    $cms->setBlockId('customer_already_premium'); // We set an other identifiant to the method to load the premium block

    return $cms->toHtml();
}