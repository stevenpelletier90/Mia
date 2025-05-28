<?php
/**
 * Menu structure and rendering for Mia Aesthetics theme.
 */

// Menu data structure to eliminate duplication
function get_mia_menu_structure() {
    return [
        'procedures' => [
            'title' => 'Procedures',
            'url' => home_url('/cosmetic-plastic-surgery/'),
            'sections' => [
                'body' => [
                    'title' => 'Body',
                    'url' => home_url('/cosmetic-plastic-surgery/body/'),
                    'items' => [
                        ['title' => 'Mia Waist Corset™', 'slug' => 'mia-corset'],
                        ['title' => 'Awake Lipo', 'slug' => 'awake-liposuction'],
                        ['title' => 'Body Lift', 'slug' => 'circumferential-body-lift'],
                        ['title' => 'Brazilian Butt Lift (BBL)', 'slug' => 'brazilian-butt-lift-bbl'],
                        ['title' => 'Lipo 360', 'slug' => 'lipo-360'],
                        ['title' => 'Liposuction', 'slug' => 'liposuction'],
                        ['title' => 'Tummy Tuck', 'slug' => 'tummy-tuck'],
                        ['title' => 'Mommy Makeover', 'slug' => 'mommy-makeover'],
                        ['title' => 'Arm Lift', 'slug' => 'arm-lift'],
                        ['title' => 'Thigh Lift', 'slug' => 'thigh-lift'],
                        ['title' => 'Vaginal Rejuvenation', 'slug' => 'labiaplasty-labia-reduction-vaginal-rejuvenation'],
                    ]
                ],
                'breast' => [
                    'title' => 'Breast',
                    'url' => home_url('/cosmetic-plastic-surgery/breast/'),
                    'items' => [
                        ['title' => 'Breast Augmentation', 'slug' => 'augmentation-implants'],
                        ['title' => 'Breast Reduction', 'slug' => 'reduction'],
                        ['title' => 'Breast Lift', 'slug' => 'lift'],
                        ['title' => 'Breast Implant Revision', 'slug' => 'implant-revision-surgery'],
                    ]
                ],
                'face' => [
                    'title' => 'Face',
                    'url' => home_url('/cosmetic-plastic-surgery/face/'),
                    'items' => [
                        ['title' => 'Brow Lift', 'slug' => 'brow-lift'],
                        ['title' => 'Buccal Fat Removal', 'slug' => 'buccal-cheek-fat-removal'],
                        ['title' => 'Blepharoplasty', 'slug' => 'eyelid-lift-blepharoplasty'],
                        ['title' => 'Chin Lipo', 'slug' => 'chin-lipo'],
                        ['title' => 'Facelift', 'slug' => 'facelift'],
                        ['title' => 'Mini Facelift', 'slug' => 'mini-facelift'],
                        ['title' => 'Neck Lift', 'slug' => 'neck-lift'],
                        ['title' => 'Otoplasty', 'slug' => 'ear-pinning-otoplasty'],
                        ['title' => 'Rhinoplasty', 'slug' => 'nose-job-rhinoplasty'],
                    ]
                ],
                'men' => [
                    'title' => 'Men',
                    'url' => home_url('#'),
                    'items' => [
                        ['title' => 'Male BBL', 'slug' => 'male-bbl', 'parent' => 'body'],
                        ['title' => 'Male Breast Procedures', 'slug' => 'male-breast-procedures', 'parent' => 'breast'],
                        ['title' => 'Male Liposuction', 'slug' => 'male-liposuction', 'parent' => 'body'],
                        ['title' => 'Male Tummy Tuck', 'slug' => 'male-tummy-tuck', 'parent' => 'body'],
                    ]
                ]
            ]
        ]
    ];
}

// Render menu for both desktop and mobile
function render_mia_menu($type = 'desktop') {
    $menu = get_mia_menu_structure();
    $is_mobile = $type === 'mobile';
    
    foreach ($menu as $key => $section) {
        if ($key === 'procedures') {
            render_procedures_menu($section, $is_mobile);
        }
        // Add other menu sections here
    }
}

// ... (other menu rendering functions: render_procedures_menu, render_desktop_procedures_menu, render_mobile_procedures_menu, etc.)
// For brevity, copy all related menu rendering functions from functions.php here.
