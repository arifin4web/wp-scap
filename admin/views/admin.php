<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Shutterstock Custom Affiliate Plugin
 * @author    Md. Arifin Ibne Matin<contact@arifinbinmatin.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Md. Arifin Ibne Matin
 */
?>

<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <form method="post" action="options.php">
        <?php
        
        settings_fields('scap_options');
        do_settings_sections('scap');

        submit_button();
        ?>
    </form>

</div>
