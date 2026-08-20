<?php
$opt = get_option('docy_opt');
$footer_visibility = docy_meta('footer_visibility', '1');
?>

<footer class="cmgalaxy-main-footer">
    <div class="container">
        <div class="footer-top-row row">
            
            <!-- Column 1: Logo & Social -->
            <div class="col-lg-4 col-md-12 footer-col footer-col-brand">
                <div class="footer-logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/kblogo.svg" alt="CMGalaxy Logo">
                    </a>
                </div>
                <p class="footer-description">
                    Streamline operations with cutting-edge solutions that future-proof your business.
                </p>
                <div class="footer-social-wrapper">
                    <p class="social-heading">Follow on Social Media</p>
                    <div class="social-icons">
                        <a href="https://www.linkedin.com/company/cmgalaxy" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.facebook.com/CMGalaxy" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.instagram.com/cmgalaxyhq" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="https://www.youtube.com/@CMGalaxyHQ" target="_blank" rel="noopener noreferrer" class="social-icon" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            <!-- Column 2: Features -->
            <div class="col-lg-3 col-md-4 col-sm-6 footer-col footer-col-links">
                <h4 class="footer-widget-title">Features</h4>
                <ul class="footer-menu">
                    <li><a href="#">Omnichannel Marketing Dashboard</a></li>
                    <li><a href="#">AI Agent</a></li>
                    <li><a href="#">Full Funnel Attribution</a></li>
                    <li><a href="#">Integration And Connectors</a></li>
                    <li><a href="#"><i class="fas fa-sparkles" style="font-size: 0.8em; margin-right: 5px;"></i> Lex</a></li>
                </ul>
            </div>

            <!-- Column 3: Company -->
            <div class="col-lg-2 col-md-4 col-sm-6 footer-col footer-col-links">
                <h4 class="footer-widget-title">Company</h4>
                <ul class="footer-menu">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Glossary</a></li>
                </ul>
            </div>

            <!-- Column 4: Contacts -->
            <div class="col-lg-3 col-md-4 col-sm-12 footer-col footer-col-contact">
                <h4 class="footer-widget-title">Contacts</h4>
                
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-details">
                        <span class="contact-label">Singapore Office</span>
                        <span class="contact-text">1 North Bridge Road, #07-10 High Street Center</span>
                    </div>
                </div>
                
                <div class="contact-item mt-4">
                    <div class="contact-icon">
                        <i class="far fa-envelope"></i>
                    </div>
                    <div class="contact-details">
                        <span class="contact-label">Email</span>
                        <a href="mailto:enquiry@cmgalaxy.com" class="contact-text">enquiry@cmgalaxy.com</a>
                    </div>
                </div>
            </div>

        </div>
        
        <!-- Bottom Bar -->
        <div class="footer-bottom-row">
            <div class="copyright-text">
                Copyright &copy; <?php echo date('Y'); ?> CMGalaxy | All Rights Reserved.
            </div>
            <div class="footer-legal-links">
                <a href="https://cmgalaxy.com/privacy-policy">Privacy Policy</a>
                <a href="https://cmgalaxy.com/terms-and-conditions">Terms And Conditions</a>
            </div>
        </div>
    </div>
</footer>