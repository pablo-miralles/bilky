<?php get_header(); ?>

<main class="mwm-main-container">

    <div class="wp-block-group has-white-background-color has-background is-right-after-header">
        <div class="wp-block-group__inner-container is-layout-constrained wp-block-group-is-layout-constrained">
            <section class="mwm-section-07 mwm-section-07--center mwm-section-07--background-white">
                <div class="mwm-section-07__wrapper">
                    <div class="mwm-section-07__breadcrumbs">
                        <div class="mwm-button mwm-button--outline mwm-button--secundary mwm-button--sm mwm-button--tag">
                            <span class="mwm-button__dot mwm-button__dot--sm"></span>
                            <span class="mwm-button__text"><?php mwm_echo_mod( 'mwm_404_breadcrumb_1' ); ?></span>
                        </div>
                    </div>
                    <div class="mwm-section-07__content">
                        <h1 class="mwm-section-07__title is-style-h-100">
                            <?php mwm_echo_mod( 'mwm_404_title' ); ?>
                        </h1>
                        <div class="mwm-section-07__text-body is-style-b-200">
                            <p><?php mwm_echo_mod( 'mwm_404_description' ); ?></p>
                        </div>
                        <div class="mwm-section-07__button">
                            <a href="<?php echo esc_url( home_url() ); ?>" class="mwm-button mwm-button--fill-icon mwm-button--primary mwm-button--xl mwm-button--active-focus">
                                <span class="mwm-button__text"><?php echo esc_html( mwm_echo_mod( 'mwm_404_button_text' ) ); ?></span>
                                <span class="mwm-button__icon mwm-button__icon--xl">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </span>  
                            </a>
                        </div>
                    </div>
                </div>
            </section>
            <div class="mwm-image-video-automatic-01">
                <div class="mwm-image-video-automatic-01__wrapper">
                    <div class="mwm-image-video-automatic-01__media">
                       <?php mwm_echo_mod_img( 'mwm_404_img' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<?php get_footer(); ?>
