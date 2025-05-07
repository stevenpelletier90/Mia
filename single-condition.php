<?php
/**
 * Basic Single Template
 * Use as: single.php, single-procedures.php, single-surgeons.php, etc.
 */

get_header(); ?>

<main data-bs-spy="scroll" data-bs-target="#tableOfContents" data-bs-offset="<?php echo 65; // navbar height ?>" data-bs-smooth-scroll="true">
    <div class="container">
        <?php
        if ( function_exists('yoast_breadcrumb') ) {
            yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
        }
        ?>
    </div>
    <?php while (have_posts()) : the_post(); ?>
        <!-- Page Header -->
        <header class="bg-light py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <h1><?php the_title(); ?></h1>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <article class="py-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="mb-4">
                                <?php the_post_thumbnail('large', ['class' => 'img-fluid']); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="toc-container">
                            <h3>Table of Contents</h3>
                            <nav id="tableOfContents" class="toc-nav">
                                <ul class="toc-list nav flex-column">
                                    <!-- Table of contents will be populated by JavaScript -->
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<script>
/**
 * Table of Contents functionality for condition pages
 */
document.addEventListener("DOMContentLoaded", function () {
  generateTableOfContents();
});

/**
 * Generates a table of contents based on h2 headings in the content
 */
function generateTableOfContents() {
  const contentDiv = document.querySelector(".content");
  const tocList = document.querySelector("#tableOfContents .toc-list");
  
  // If either element doesn't exist, exit
  if (!contentDiv || !tocList) return;
  
  // Find all h2 elements in the content
  const headings = contentDiv.querySelectorAll("h2");
  
  // If no headings found, hide the TOC container
  if (headings.length === 0) {
    const tocContainer = document.querySelector(".toc-container");
    if (tocContainer) {
      tocContainer.style.display = "none";
    }
    return;
  }
  
  // Process each heading
  headings.forEach((heading, index) => {
    // Create a unique ID for the heading if it doesn't have one
    if (!heading.id) {
      // Create ID from heading text: lowercase, replace spaces with hyphens, remove special chars
      const headingId =
        "section-" +
        index +
        "-" +
        heading.textContent
          .toLowerCase()
          .replace(/[^\w\s-]/g, "") // Remove special characters
          .replace(/\s+/g, "-") // Replace spaces with hyphens
          .replace(/-+/g, "-"); // Replace multiple hyphens with single hyphen
      
      heading.id = headingId;
    }
    
    // Create list item for the TOC
    const listItem = document.createElement("li");
    listItem.className = "nav-item";
    
    const link = document.createElement("a");
    link.className = "nav-link";
    link.href = "#" + heading.id;
    link.textContent = heading.textContent;
    
    listItem.appendChild(link);
    tocList.appendChild(listItem);
  });
}
</script>

<?php get_footer(); ?>
