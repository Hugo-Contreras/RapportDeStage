/**
 * Get HTML for CMS block premium
 *
 * @return string
 */
public function getCmsBlockPremiumHtml()
{
    $cms = $this->getChild('customer_become_premium');

    $cms->setBlockId('customer_already_premium');

    return $cms->toHtml();
}