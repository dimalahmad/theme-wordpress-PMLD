/**
 * Multiple Select Posts Control Script
 */
(function( $ ) {
    'use strict';
    
    wp.customize.controlConstructor['multiple-select-posts'] = wp.customize.Control.extend({
        ready: function() {
            var control = this;
            var container = control.container;
            var selectedPosts = [];
            var maxPosts = parseInt( container.find('.selected-posts').data('max') ) || 5;
            
            // Initialize selected posts
            if ( control.setting.get() ) {
                try {
                    selectedPosts = JSON.parse( control.setting.get() );
                } catch (e) {
                    selectedPosts = [];
                }
            }
            
            // Toggle available posts
            container.on( 'click', '.toggle-available-posts', function(e) {
                e.preventDefault();
                var wrapper = container.find('.available-posts-wrapper');
                wrapper.slideToggle();
                
                if ( wrapper.is(':visible') ) {
                    $(this).html('<span class="dashicons dashicons-minus"></span> Tutup Daftar');
                } else {
                    $(this).html('<span class="dashicons dashicons-plus-alt"></span> Pilih Proyek');
                }
            });
            
            // Search posts
            container.on( 'input', '.search-posts-input', function() {
                var searchTerm = $(this).val().toLowerCase();
                
                container.find('.available-post-item').each(function() {
                    var postTitle = $(this).find('.post-title').text().toLowerCase();
                    
                    if ( postTitle.indexOf(searchTerm) > -1 ) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
            
            // Select post
            container.on( 'click', '.available-post-item .select-post', function(e) {
                e.preventDefault();
                var postItem = $(this).closest('.available-post-item');
                var postId = postItem.data('post-id');
                
                if ( postItem.hasClass('selected') ) {
                    // Remove from selected
                    removePost(postId);
                } else {
                    // Add to selected
                    if ( selectedPosts.length < maxPosts ) {
                        addPost(postId);
                    } else {
                        alert('Maksimal ' + maxPosts + ' proyek yang dapat dipilih.');
                    }
                }
            });
            
            // Remove selected post
            container.on( 'click', '.selected-post-item .remove-post', function(e) {
                e.preventDefault();
                var postId = $(this).closest('.selected-post-item').data('post-id');
                removePost(postId);
            });
            
            // Make selected posts sortable
            if ( $.fn.sortable ) {
                container.find('.selected-posts').sortable({
                    items: '.selected-post-item',
                    cursor: 'move',
                    opacity: 0.6,
                    containment: 'parent',
                    update: function() {
                        updateSelectedPosts();
                    }
                });
            }
            
            function addPost(postId) {
                postId = parseInt(postId);
                
                if ( selectedPosts.indexOf(postId) === -1 ) {
                    selectedPosts.push(postId);
                    
                    // Update UI
                    var availableItem = container.find('.available-post-item[data-post-id="' + postId + '"]');
                    availableItem.addClass('selected');
                    availableItem.find('.select-post .dashicons')
                        .removeClass('dashicons-plus-alt2')
                        .addClass('dashicons-yes');
                    
                    // Add to selected list
                    var postTitle = availableItem.find('.post-title').text();
                    var postDate = availableItem.find('.post-date').text();
                    var thumbnail = availableItem.find('.post-thumbnail').html();
                    
                    var selectedHtml = '<div class="selected-post-item" data-post-id="' + postId + '">' +
                        '<div class="post-thumbnail">' + thumbnail + '</div>' +
                        '<div class="post-info">' +
                            '<span class="post-title">' + postTitle + '</span>' +
                            '<span class="post-date">' + postDate + '</span>' +
                        '</div>' +
                        '<button type="button" class="remove-post" aria-label="Hapus">' +
                            '<span class="dashicons dashicons-no-alt"></span>' +
                        '</button>' +
                    '</div>';
                    
                    container.find('.selected-posts').append(selectedHtml);
                    
                    // Hide no posts message
                    container.find('.no-posts-message').hide();
                    
                    // Update count
                    updateCount();
                    
                    // Save
                    saveSelectedPosts();
                }
            }
            
            function removePost(postId) {
                postId = parseInt(postId);
                var index = selectedPosts.indexOf(postId);
                
                if ( index > -1 ) {
                    selectedPosts.splice(index, 1);
                    
                    // Update UI
                    container.find('.selected-post-item[data-post-id="' + postId + '"]').remove();
                    
                    var availableItem = container.find('.available-post-item[data-post-id="' + postId + '"]');
                    availableItem.removeClass('selected');
                    availableItem.find('.select-post .dashicons')
                        .removeClass('dashicons-yes')
                        .addClass('dashicons-plus-alt2');
                    
                    // Show no posts message if empty
                    if ( selectedPosts.length === 0 ) {
                        container.find('.no-posts-message').show();
                    }
                    
                    // Update count
                    updateCount();
                    
                    // Save
                    saveSelectedPosts();
                }
            }
            
            function updateSelectedPosts() {
                selectedPosts = [];
                container.find('.selected-post-item').each(function() {
                    selectedPosts.push( parseInt( $(this).data('post-id') ) );
                });
                saveSelectedPosts();
            }
            
            function updateCount() {
                container.find('.selected-posts-container .count').text('(' + selectedPosts.length + '/' + maxPosts + ')');
            }
            
            function saveSelectedPosts() {
                control.setting.set( JSON.stringify(selectedPosts) );
            }
        }
    });
    
})( jQuery );
