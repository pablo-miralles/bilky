<div class="mwm-popup is-newsletter" style="display:none;">
    <div class="mwm-popup__wrapper">
        <div class="mwm-popup__box">
            <div class="mwm-popup__info" style="grid-column:-1/1">
                <div class="mwm-popup__close">
                    <div class="mwm-btn-n400 has-only-icon">
                        <span><?php get_template_part( '/assets/images/icons/icon-close' ); ?></span>
                    </div>
                </div>
                <p class="mwm-popup__title is-style-h300"><?php mwm_echo_mod( 'mwm_newsletter_title' ); ?></p>
                <div class="mwm-popup__content is-style-b100">
                    <?php mwm_echo_mod( 'mwm_newsletter_desc' ); ?>
                </div>
                <?php echo do_shortcode( get_option( 'mwm_newsletter_shortcode' ) ); ?>
            </div>
        </div>
    </div>
</div>
