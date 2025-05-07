<?php
/**
 * Procedure Archive Template
 * Organizes procedures into categories: Body, Breast, Face, and Men
 */

get_header(); ?>

<main>
    <div class="container">
        <?php
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
        }
        ?>
    </div>
    
    <!-- Archive Header -->
    <header class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1><?php post_type_archive_title(); ?></h1>
                    <p class="lead">Explore our range of aesthetic procedures to help you look and feel your best.</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Procedure Categories -->
    <section class="procedure-categories py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Body Procedures -->
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="procedure-category h-100">
                        <div class="category-image-container rounded overflow-hidden shadow-sm mb-3">
                            <img src="https://placehold.co/600x400/007bff/ffffff?text=Body+Procedures" alt="Body Procedures" class="img-fluid w-100">
                        </div>
                        <h2 class="h4 mb-3">Body Procedures</h2>
                        <ul class="list-unstyled procedure-links mb-3">
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/mia-corset/')); ?>" class="text-decoration-none">Mia Waist Corset&trade;</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/awake-liposuction/')); ?>" class="text-decoration-none">Awake Lipo</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/circumferential-body-lift/')); ?>" class="text-decoration-none">Body Lift</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/brazilian-butt-lift-bbl/')); ?>" class="text-decoration-none">Brazilian Butt Lift (BBL)</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/lipo-360/')); ?>" class="text-decoration-none">Lipo 360</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/liposuction/')); ?>" class="text-decoration-none">Liposuction</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/tummy-tuck/')); ?>" class="text-decoration-none">Tummy Tuck</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/mommy-makeover/')); ?>" class="text-decoration-none">Mommy Makeover</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/arm-lift/')); ?>" class="text-decoration-none">Arm Lift</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/thigh-lift/')); ?>" class="text-decoration-none">Thigh Lift</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/labiaplasty-labia-reduction-vaginal-rejuvenation/')); ?>" class="text-decoration-none">Vaginal Rejuvenation</a></li>
                        </ul>
                        <a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/')); ?>" class="btn btn-outline-primary btn-sm">View All</a>
                    </div>
                </div>
                
                <!-- Breast Procedures -->
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="procedure-category h-100">
                        <div class="category-image-container rounded overflow-hidden shadow-sm mb-3">
                            <img src="https://placehold.co/600x400/dc3545/ffffff?text=Breast+Procedures" alt="Breast Procedures" class="img-fluid w-100">
                        </div>
                        <h2 class="h4 mb-3">Breast Procedures</h2>
                        <ul class="list-unstyled procedure-links mb-3">
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/augmentation-implants/')); ?>" class="text-decoration-none">Breast Augmentation</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/reduction/')); ?>" class="text-decoration-none">Breast Reduction</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/lift/')); ?>" class="text-decoration-none">Breast Lift</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/implant-revision-surgery/')); ?>" class="text-decoration-none">Breast Implant Revision</a></li>
                        </ul>
                        <a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/')); ?>" class="btn btn-outline-primary btn-sm">View All</a>
                    </div>
                </div>
                
                <!-- Face Procedures -->
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="procedure-category h-100">
                        <div class="category-image-container rounded overflow-hidden shadow-sm mb-3">
                            <img src="https://placehold.co/600x400/28a745/ffffff?text=Face+Procedures" alt="Face Procedures" class="img-fluid w-100">
                        </div>
                        <h2 class="h4 mb-3">Face Procedures</h2>
                        <ul class="list-unstyled procedure-links mb-3">
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/brow-lift/')); ?>" class="text-decoration-none">Brow Lift</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/buccal-cheek-fat-removal/')); ?>" class="text-decoration-none">Buccal Fat Removal</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/eyelid-lift-blepharoplasty/')); ?>" class="text-decoration-none">Blepharoplasty</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/chin-lipo/')); ?>" class="text-decoration-none">Chin Lipo</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/facelift/')); ?>" class="text-decoration-none">Facelift</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/mini-facelift/')); ?>" class="text-decoration-none">Mini Facelift</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/neck-lift/')); ?>" class="text-decoration-none">Neck Lift</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/ear-pinning-otoplasty/')); ?>" class="text-decoration-none">Otoplasty</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/nose-job-rhinoplasty/')); ?>" class="text-decoration-none">Rhinoplasty</a></li>
                        </ul>
                        <a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/face/')); ?>" class="btn btn-outline-primary btn-sm">View All</a>
                    </div>
                </div>
                
                <!-- Men's Procedures -->
                <div class="col-lg-3 col-md-6 col-12">
                    <div class="procedure-category h-100">
                        <div class="category-image-container rounded overflow-hidden shadow-sm mb-3">
                            <img src="https://placehold.co/600x400/6f42c1/ffffff?text=Men's+Procedures" alt="Men's Procedures" class="img-fluid w-100">
                        </div>
                        <h2 class="h4 mb-3">Men's Procedures</h2>
                        <ul class="list-unstyled procedure-links mb-3">
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/male-bbl/')); ?>" class="text-decoration-none">Male BBL</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/breast/male-breast-procedures/')); ?>" class="text-decoration-none">Male Breast Procedures</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/male-liposuction/')); ?>" class="text-decoration-none">Male Liposuction</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/body/male-tummy-tuck/')); ?>" class="text-decoration-none">Male Tummy Tuck</a></li>
                        </ul>
                        <a href="<?php echo esc_url(home_url('/cosmetic-plastic-surgery/')); ?>" class="btn btn-outline-primary btn-sm">View All</a>
                    </div>
                </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
