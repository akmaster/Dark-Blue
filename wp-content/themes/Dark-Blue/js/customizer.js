/* global wp */

( function( $ ) {
    // Site title and description.
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            $( '.site-title a' ).text( to );
        } );
    } );
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            $( '.site-description' ).text( to );
        } );
    } );

    // Footer about text
    wp.customize( 'footer_about', function( value ) {
        value.bind( function( to ) {
            $( '.footer-widget p' ).text( to );
        } );
    } );

    // Contact information
    wp.customize( 'contact_email', function( value ) {
        value.bind( function( to ) {
            $( '.contact-info .email' ).text( to );
        } );
    } );
    wp.customize( 'contact_phone', function( value ) {
        value.bind( function( to ) {
            $( '.contact-info .phone' ).text( to );
        } );
    } );
    wp.customize( 'contact_address', function( value ) {
        value.bind( function( to ) {
            $( '.contact-info .address' ).text( to );
        } );
    } );
} )( jQuery ); 