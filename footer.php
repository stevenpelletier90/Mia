<!-- footer.php -->
</div><!-- .site-content -->
<footer class="site-footer">
    <div class="container-fluid wide-container">
        <div class="footer-columns">     
            <div class="footer-column">
                <h5 class="text-gold">Follow Us</h5>
                <div class="social-icons d-flex flex-wrap gap-3 mb-3">
                    <a href="https://www.facebook.com/miaaesthetics" target="_blank" rel="noopener" aria-label="Facebook" class="social-icon">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.instagram.com/miaaesthetics" target="_blank" rel="noopener" aria-label="Instagram" class="social-icon">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.tiktok.com/@miaaesthetics" target="_blank" rel="noopener" aria-label="TikTok" class="social-icon">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <a href="https://twitter.com/miaaesthetics" target="_blank" rel="noopener" aria-label="Twitter" class="social-icon">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.snapchat.com/add/miaaesthetics" target="_blank" rel="noopener" aria-label="SnapChat" class="social-icon">
                        <i class="fab fa-snapchat-ghost"></i>
                    </a>
                    <a href="https://www.youtube.com/c/miaaesthetics" target="_blank" rel="noopener" aria-label="YouTube" class="social-icon">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>

            <!-- About Column -->
            <div class="footer-column">
                <h5 class="text-gold">About</h5>
                <ul class="footer-menu">
                    <li><a href="<?php echo esc_url(home_url('/our-story')); ?>">Our Story</a></li>
                    <li><a href="<?php echo esc_url(home_url('/mia-foundation')); ?>">Mia Foundation</a></li>
                    <li><a href="<?php echo esc_url(home_url('/locations')); ?>">Locations</a></li>
                    <li><a href="<?php echo esc_url(home_url('/surgeons')); ?>">Surgeons</a></li>
                    <li><a href="<?php echo esc_url(home_url('/careers')); ?>">Careers</a></li>
                    <li><a href="<?php echo esc_url(home_url('/patient-portal')); ?>">Patient Portal</a></li>
                </ul>
            </div>

            <!-- Resources Column -->
            <div class="footer-column">
                <h5 class="text-gold">Resources</h5>
                <ul class="footer-menu">
                    <li><a href="<?php echo esc_url(home_url('/faqs')); ?>">FAQs</a></li>
                    <li><a href="<?php echo esc_url(home_url('/conditions-we-treat')); ?>">Conditions We Treat</a></li>
                    <li><a href="<?php echo esc_url(home_url('/calculate-bmi')); ?>">Calculate Your BMI</a></li>
                    <li><a href="<?php echo esc_url(home_url('/patient-resources')); ?>">Patient Resources</a></li>
                    <li><a href="<?php echo esc_url(home_url('/surgical-journey')); ?>">Surgical Journey</a></li>
                    <li><a href="<?php echo esc_url(home_url('/out-of-town-patients')); ?>">Out of Town Patients</a></li>
                </ul>
            </div>
            
            <!-- Procedures Column -->
            <div class="footer-column">
                <h5 class="text-gold">Procedures</h5>
                <ul class="footer-menu">
                    <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/')); ?>">Face Procedures</a></li>
                    <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/')); ?>">Body Procedures</a></li>
                    <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/')); ?>">Breast Procedures</a></li>
                    <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/men/')); ?>">Men's Procedures</a></li>
                    <li><a href="<?php echo esc_url(home_url('/non-surgical/')); ?>">Non-Surgical Options</a></li>
                    <li><a href="<?php echo esc_url(home_url('/before-after/')); ?>">Before & After Gallery</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-divider-container">
            <hr class="footer-divider">
        </div>
        
        <!-- Locations & Surgeons Section with Accordion -->
        <div class="locations-section mt-4">
            <h5 class="text-gold mb-3">Locations & Surgeons</h5>
            <div class="accordion" id="locationsAccordion">
                <?php
                // Get only parent locations (no child pages)
                $locations_args = array(
                    'post_type' => 'location',
                    'posts_per_page' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'post_parent' => 0 // Only get parent pages (no children)
                );
                $locations_query = new WP_Query($locations_args);
                
                if ($locations_query->have_posts()) :
                    $location_index = 0;
                    while ($locations_query->have_posts()) : $locations_query->the_post();
                        $location_id = get_the_ID();
                        $location_title = get_the_title();
                        $location_index++;
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="location-heading-<?php echo $location_id; ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#location-collapse-<?php echo $location_id; ?>" aria-expanded="false" aria-controls="location-collapse-<?php echo $location_id; ?>">
                            <?php echo esc_html($location_title); ?>
                        </button>
                    </h2>
                    <div id="location-collapse-<?php echo $location_id; ?>" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <!-- Location Link -->
                            <div class="location-link mb-3">
                                <a href="<?php the_permalink(); ?>" class="surgeon-link text-gold">
                                    <span>View <?php echo esc_html($location_title); ?> Location</span>
                                    <i class="fas fa-arrow-right surgeon-arrow"></i>
                                </a>
                            </div>
                            
                            <?php
                            // Get surgeons for this location
                            $surgeons_args = array(
                                'post_type' => 'surgeon',
                                'posts_per_page' => -1,
                                'meta_query' => array(
                                    array(
                                        'key' => 'surgeon_location',
                                        'value' => $location_id,
                                        'compare' => '='
                                    )
                                )
                            );
                            $surgeons_query = new WP_Query($surgeons_args);
                            
                            if ($surgeons_query->have_posts()) :
                            ?>
                                <div class="surgeons-list">
                                    <ul class="list-unstyled">
                                        <?php while ($surgeons_query->have_posts()) : $surgeons_query->the_post(); ?>
                                            <li class="mb-2">
                                                <a href="<?php the_permalink(); ?>" class="surgeon-link">
                                                    <span><?php the_title(); ?></span>
                                                    <i class="fas fa-arrow-right surgeon-arrow"></i>
                                                </a>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </div>
                            <?php else : ?>
                                <p class="mb-0">No surgeons currently listed for this location.</p>
                            <?php 
                            endif;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <p>No locations found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="footer-divider-container">
        <div class="container-fluid wide-container">
            <hr class="footer-divider">
        </div>
    </div>
    
    <!-- Bottom Footer - Simplified for better responsive behavior -->
    <div class="footer-bottom">
        <div class="container-fluid wide-container">
            <!-- Copyright Section -->
            <div class="text-center mb-4">
                <p class="copyright mb-1">© <?php echo date('Y'); ?> Mia Aesthetics. All rights reserved.</p>
                <p class="disclaimer">The pictures on this website consist of both models and actual patients.</p>
            </div>
            
            <!-- Links Section - Centered -->
            <div class="text-center">
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy Policy</a></li>
                    <li><a href="<?php echo esc_url(home_url('/patient-privacy-practices')); ?>">Patient Privacy Practices</a></li>
                    <li><a href="<?php echo esc_url(home_url('/terms-conditions')); ?>">Terms & Conditions</a></li>
                    <li><a href="<?php echo esc_url(home_url('/terms-of-use')); ?>">Terms of Use</a></li>
                    <li><a href="<?php echo esc_url(home_url('/sms-terms')); ?>">SMS Terms & Conditions</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
