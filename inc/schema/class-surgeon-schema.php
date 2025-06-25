<?php
/**
 * Surgeon Schema
 * 
 * Generates schema markup for surgeon pages
 * 
 * @package Mia_Aesthetics
 */

namespace Mia_Aesthetics\Schema;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Surgeon_Schema {
    
    /**
     * @var \Yoast\WP\SEO\Context\Meta_Tags_Context
     */
    private $context;
    
    /**
     * Constructor
     * 
     * @param \Yoast\WP\SEO\Context\Meta_Tags_Context $context
     */
    public function __construct( $context ) {
        $this->context = $context;
    }
    
    /**
     * Determines if this schema piece is needed
     * 
     * @return bool
     */
    public function is_needed() {
        return is_singular( 'surgeon' );
    }
    
    /**
     * Generate the surgeon schema
     * 
     * @return array Schema.org compliant Person/Physician data
     */
    public function generate() {
        $surgeon_id = get_the_ID();
        $org_id     = $this->context->site_url . '#organization';
        
        // Get associated clinic
        $clinic_obj = get_field( 'surgeon_location', $surgeon_id );
        $clinic_id  = $clinic_obj ? get_permalink( $clinic_obj->ID ) . '#clinic' : null;
        
        $surgeon = [
            '@type'            => [ 'Person', 'Physician' ],
            '@id'              => get_permalink( $surgeon_id ) . '#physician',
            'name'             => get_the_title(),
            'jobTitle'         => 'Board Certified Plastic Surgeon',
            'medicalSpecialty' => 'PlasticSurgery',
            'url'              => get_permalink( $surgeon_id ),
            'affiliation'      => [ '@id' => $org_id ],
        ];
        
        // Link to clinic
        if ( $clinic_id ) {
            $surgeon['worksFor']   = [ '@id' => $clinic_id ];
            $surgeon['department'] = [ '@id' => $clinic_id ];
        }
        
        // Description
        if ( $desc = get_post_meta( $surgeon_id, '_yoast_wpseo_metadesc', true ) ) {
            $surgeon['description'] = $desc;
        }
        
        // Image
        if ( has_post_thumbnail() ) {
            $surgeon['image'] = get_the_post_thumbnail_url( $surgeon_id, 'full' );
        }
        
        // Professional credentials
        if ( get_field( 'board_certified', $surgeon_id ) ) {
            $surgeon['hasCredential'] = [
                '@type' => 'EducationalOccupationalCredential',
                'credentialCategory' => 'Board Certification',
                'recognizedBy' => [
                    '@type' => 'Organization',
                    'name' => 'American Board of Plastic Surgery'
                ]
            ];
        }
        
        // Specialties
        $specialties = $this->get_specialties( $surgeon_id );
        if ( ! empty( $specialties ) ) {
            $surgeon['knowsAbout'] = $specialties;
        }
        
        // Education
        if ( $school = get_field( 'medical_school', $surgeon_id ) ) {
            $surgeon['alumniOf'] = [
                '@type' => 'EducationalOrganization',
                'name' => $school
            ];
        }
        
        return $surgeon;
    }
    
    /**
     * Get surgeon specialties
     * 
     * @param int $surgeon_id
     * @return array
     */
    private function get_specialties( $surgeon_id ) {
        $specialties = [];
        
        for ( $i = 1; $i <= 3; $i++ ) {
            if ( $specialty = get_field( 'specialty_' . $i, $surgeon_id ) ) {
                $specialties[] = $specialty;
            }
        }
        
        return $specialties;
    }
}