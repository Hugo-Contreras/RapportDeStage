jQuery(document).ready(function () {
    jQuery( '.express-wishlist input.add-to-wishlist' ).each(function() {
        jQuery( this ).attr("title", '<?php echo $this->__("Add to my cravings"); ?>');
    });
    <?php if ($this->helper('customer')->isLoggedIn()):?>
        <?php $_wishlist = Mage::helper('darjeelingdesign/customer')->getCustomerWishlistItemsToJavascript(); ?>
        <?php if(!empty($_wishlist)): ?>
            /* Display Wishlist Items */
            <?php $wishlist = explode(",",$_wishlist); ?>
            <?php foreach($wishlist as $wish): ?>
                jQuery("<?php echo $wish ?>").addClass('wished');
                jQuery("<?php echo $wish ?>").attr("title", '<?php echo $this->__("Remove from my cravings"); ?>');
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif;?>
    jQuery('.tooltip').tooltipster();
    jQuery( '.express-wishlist input.add-to-wishlist' ).each(function() {
        jQuery( this ).attr("title", '<?php echo $this->__(""); ?>');
    });
});

jQuery( '.express-wishlist input.add-to-wishlist').click( function() {
    if( jQuery( this).hasClass( 'wished' ) ) {
        jQuery( this ).tooltipster('content','<?php echo $this->__("Add to my cravings"); ?>');
    }
    else {
        jQuery( this ).tooltipster('content','<?php echo $this->__("Remove from my cravings"); ?>');
    }
});