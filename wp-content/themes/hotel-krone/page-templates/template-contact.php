<?php
/**
 * Template Name: Contact
 * Template Post Type: page
 */
get_header(); ?>

<div class="hk-page-header">
    <div class="container">
        <div class="hk-page-header-content">
            <span class="hk-section-tag"><?php esc_html_e( 'Get in Touch', 'hotel-krone' ); ?></span>
            <h1><?php esc_html_e( 'Contact Us', 'hotel-krone' ); ?></h1>
            <p><?php esc_html_e( 'We\'d love to hear from you. Reach out for reservations, inquiries, or just to say hello.', 'hotel-krone' ); ?></p>
        </div>
    </div>
</div>

<section class="hk-section">
    <div class="container">
        <?php hk_breadcrumbs(); ?>

        <div class="hk-contact-grid">

            <!-- Contact Info -->
            <div class="hk-contact-info hk-animate-left">
                <h3><?php esc_html_e( 'Rhein-Hotel Krone', 'hotel-krone' ); ?></h3>

                <div class="hk-contact-item">
                    <div class="hk-contact-item-icon"><?php echo hk_icon( 'location' ); ?></div>
                    <div class="hk-contact-item-text">
                        <h5><?php esc_html_e( 'Address', 'hotel-krone' ); ?></h5>
                        <p><?php echo esc_html( hk_get_option( 'hk_address', 'Rheinuferstraße 8, 56154 Boppard am Rhein' ) ); ?></p>
                    </div>
                </div>

                <div class="hk-contact-item">
                    <div class="hk-contact-item-icon"><?php echo hk_icon( 'phone' ); ?></div>
                    <div class="hk-contact-item-text">
                        <h5><?php esc_html_e( 'Phone', 'hotel-krone' ); ?></h5>
                        <p><a href="tel:<?php echo esc_attr( preg_replace( '/\s/', '', hk_get_option( 'hk_phone', '+4967422313' ) ) ); ?>"><?php echo esc_html( hk_get_option( 'hk_phone', '+49 6742 2313' ) ); ?></a></p>
                    </div>
                </div>

                <div class="hk-contact-item">
                    <div class="hk-contact-item-icon"><?php echo hk_icon( 'email' ); ?></div>
                    <div class="hk-contact-item-text">
                        <h5><?php esc_html_e( 'Email', 'hotel-krone' ); ?></h5>
                        <p><a href="mailto:<?php echo esc_attr( hk_get_option( 'hk_email', 'info@rhein-hotel-krone.de' ) ); ?>"><?php echo esc_html( hk_get_option( 'hk_email', 'info@rhein-hotel-krone.de' ) ); ?></a></p>
                    </div>
                </div>

                <div class="hk-contact-item">
                    <div class="hk-contact-item-icon"><?php echo hk_icon( 'clock' ); ?></div>
                    <div class="hk-contact-item-text">
                        <h5><?php esc_html_e( 'Reception Hours', 'hotel-krone' ); ?></h5>
                        <p><?php esc_html_e( 'Mon – Sun: 07:00 – 23:00', 'hotel-krone' ); ?></p>
                    </div>
                </div>

                <!-- Map -->
                <div class="hk-map-embed">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2562.7!2d7.5878!3d50.2309!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47be93a1b8df9f57%3A0xc8a2e2a5a5a5a5a5!2sRhein-Hotel+Krone!5e0!3m2!1sen!2sde!4v1234567890"
                        allowfullscreen=""
                        loading="lazy"
                        title="<?php esc_attr_e( 'Rhein-Hotel Krone Location', 'hotel-krone' ); ?>"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="hk-animate-right">
                <h3><?php esc_html_e( 'Send Us a Message', 'hotel-krone' ); ?></h3>
                <p style="color:var(--text-muted);margin-bottom:24px;"><?php esc_html_e( 'For room reservations, we recommend calling us directly. For general inquiries, use the form below.', 'hotel-krone' ); ?></p>

                <?php
                // If Contact Form 7 is active
                if ( function_exists( 'wpcf7_contact_form' ) ) {
                    echo do_shortcode( '[contact-form-7 id="contact-form"]' );
                } else {
                    // Fallback HTML form
                    ?>
                    <form class="hk-contact-form" action="mailto:<?php echo esc_attr( hk_get_option( 'hk_email', 'info@rhein-hotel-krone.de' ) ); ?>" method="post" enctype="text/plain">
                        <div class="hk-form-row-2">
                            <input type="text" name="name" placeholder="<?php esc_attr_e( 'Your Name *', 'hotel-krone' ); ?>" required>
                            <input type="email" name="email" placeholder="<?php esc_attr_e( 'Email Address *', 'hotel-krone' ); ?>" required>
                        </div>
                        <input type="tel" name="phone" placeholder="<?php esc_attr_e( 'Phone Number', 'hotel-krone' ); ?>">
                        <select name="subject">
                            <option value=""><?php esc_html_e( '— Subject —', 'hotel-krone' ); ?></option>
                            <option value="reservation"><?php esc_html_e( 'Room Reservation', 'hotel-krone' ); ?></option>
                            <option value="restaurant"><?php esc_html_e( 'Restaurant Reservation', 'hotel-krone' ); ?></option>
                            <option value="general"><?php esc_html_e( 'General Inquiry', 'hotel-krone' ); ?></option>
                            <option value="event"><?php esc_html_e( 'Event / Conference', 'hotel-krone' ); ?></option>
                        </select>
                        <textarea name="message" rows="6" placeholder="<?php esc_attr_e( 'Your message...', 'hotel-krone' ); ?>" required></textarea>
                        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                            <?php esc_html_e( 'Send Message', 'hotel-krone' ); ?>
                        </button>
                    </form>
                    <?php
                }
                ?>
            </div>

        </div>
    </div>
</section>

<?php get_footer(); ?>
