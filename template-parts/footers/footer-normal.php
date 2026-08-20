<?php
$opt = get_option('docy_opt');
$footer_visibility = docy_meta('footer_visibility', '1');
?>


<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-row">
                <!-- Logo Section -->
                <div class="footer-logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.png" alt="CMGalaxy Logo">
                    </a>
                </div>
                
                <!-- Links Section -->
                <div class="footer-links">
                    <a href="https://cmgalaxy.com/privacy-policy">Privacy Policy</a>
                    <a href="https://cmgalaxy.com/terms-and-conditions">Terms & Conditions</a>
                </div>
                
                <!-- Social Media Section -->
                <div class="footer-social">
                    <p>Follow on Social Media</p>
                    <div class="social-icons">
                        <a href="https://www.linkedin.com/company/cmgalaxy" target="_blank" rel="noopener noreferrer" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.facebook.com/CMGalaxy" target="_blank" rel="noopener noreferrer" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/cmgalaxyhq" target="_blank" rel="noopener noreferrer" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@CMGalaxyHQ" target="_blank" rel="noopener noreferrer" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Copyright Section -->
            <div class="footer-copyright">
                <p>Copyright © <?php echo date('Y'); ?> <a href="<?php echo esc_url(home_url('/')); ?>">CMGalaxy</a> | All Rights Reserved</p>
            </div>
        </div>
    </div>
</footer>