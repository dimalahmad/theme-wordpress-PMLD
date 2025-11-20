/**
 * Customizer Live Preview untuk Warna
 * Update CSS variables secara real-time saat user mengubah warna di Customizer
 */
(function($) {
    'use strict';
    
    // Update CSS variables
    function updateColors() {
        var primaryColor = wp.customize('inviro_primary_color').get();
        var primaryLight = wp.customize('inviro_primary_color_light').get();
        var primaryMedium = wp.customize('inviro_primary_color_medium').get();
        
        var style = document.getElementById('inviro-custom-colors') || document.createElement('style');
        style.id = 'inviro-custom-colors';
        style.innerHTML = ':root {' +
            '--inviro-primary: ' + primaryColor + ';' +
            '--inviro-primary-light: ' + primaryLight + ';' +
            '--inviro-primary-medium: ' + primaryMedium + ';' +
        '}';
        
        if (!document.getElementById('inviro-custom-colors')) {
            document.head.appendChild(style);
        }
    }
    
    // Bind to color changes
    wp.customize('inviro_primary_color', function(value) {
        value.bind(function(newval) {
            updateColors();
        });
    });
    
    wp.customize('inviro_primary_color_light', function(value) {
        value.bind(function(newval) {
            updateColors();
        });
    });
    
    wp.customize('inviro_primary_color_medium', function(value) {
        value.bind(function(newval) {
            updateColors();
        });
    });
    
    // Initial update
    updateColors();
    
})(jQuery);

