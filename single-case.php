<?php

get_header(); ?>

<main>
    <div class="container">
        <?php
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
        }
        ?>
    </div>

    <!-- Hero Section -->
    <header class="bg-light py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h1 class="display-4 mb-3"><?php the_title(); ?></h1>
                    
                    <!-- Main Before & After Images - side by side on all devices -->
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="position-relative">
                                <img src="https://placehold.co/800x600" 
                                     class="img-fluid rounded cursor-pointer" 
                                     alt="Before Treatment"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#imageModal" 
                                     data-bs-image="https://placehold.co/1200x900"
                                     data-bs-title="Before Treatment">
                                <span class="position-absolute top-0 start-0 bg-dark text-white px-2 py-1 m-1 fs-6">Before</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="position-relative">
                                <img src="https://placehold.co/800x600" 
                                     class="img-fluid rounded cursor-pointer" 
                                     alt="After Treatment"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#imageModal" 
                                     data-bs-image="https://placehold.co/1200x900"
                                     data-bs-title="After Treatment">
                                <span class="position-absolute top-0 start-0 bg-success text-white px-2 py-1 m-1 fs-6">After</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <article class="py-5">
        <div class="container">
            <div class="row">
                <!-- Main Content Column -->
                <div class="col-lg-8">
                    <!-- Patient Background -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">Patient Background</h2>
                        <p class="lead">Patient presented with concerns about [condition]. Their primary goals were to improve [specific goals].</p>
                        <div class="bg-light p-3 rounded">
                            <strong>Chief Concerns:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Concern 1</li>
                                <li>Concern 2</li>
                                <li>Concern 3</li>
                            </ul>
                        </div>
                    </section>

                    <!-- Treatment Timeline -->
                    <section class="mb-5">
                        <h2 class="h4 mb-3">Treatment & Recovery Timeline</h2>
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded h-100">
                                    <h5 class="h6">Initial Consultation</h5>
                                    <p class="small mb-0">Assessment and planning</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded h-100">
                                    <h5 class="h6">Procedure</h5>
                                    <p class="small mb-0">Treatment details</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded h-100">
                                    <h5 class="h6">Early Recovery</h5>
                                    <p class="small mb-0">Initial healing</p>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="p-3 bg-light rounded h-100">
                                    <h5 class="h6">Final Results</h5>
                                    <p class="small mb-0">Outcomes achieved</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Main Content -->
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Call to Action -->
                    <div class="card">
                        <div class="card-body text-center">
                            <h3 class="h5 mb-3">Interested in Treatment?</h3>
                            <a href="/contact" class="btn btn-primary">Schedule a Consultation</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </article>
</main>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <img src="" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>

<!-- Modal Initialization Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const image = button.getAttribute('data-bs-image');
            const title = button.getAttribute('data-bs-title');
            
            const modalTitle = this.querySelector('.modal-title');
            const modalImage = this.querySelector('.modal-body img');
            
            modalTitle.textContent = title;
            modalImage.src = image;
            modalImage.alt = title;
        });
    }
});
</script>

<?php get_footer(); ?>