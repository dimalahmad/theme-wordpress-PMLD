<?php
/**
 * Debug script sederhana - tambahkan di functions.php atau akses langsung
 * Cara pakai: Tambahkan ?debug=images di akhir URL beranda
 * Contoh: localhost:8080/?debug=images
 */

// Tambahkan ini di functions.php untuk debug via URL parameter
add_action('wp', function() {
    if (isset($_GET['debug']) && $_GET['debug'] === 'images' && current_user_can('manage_options')) {
        header('Content-Type: text/html; charset=utf-8');
        echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Debug Images</title>";
        echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;} .success{color:green;} .error{color:red;} .info{color:blue;} pre{background:#fff;padding:10px;border:1px solid #ddd;overflow:auto;} h2{border-bottom:2px solid #333;padding-bottom:5px;} .img-preview{max-width:200px;border:2px solid #ddd;margin:5px 0;}</style></head><body>";
        echo "<h1>🔍 Debug Images - Hero & Branch</h1>";
        
        // 1. Hero Projects
        echo "<h2>1. Hero Projects</h2>";
        if (function_exists('inviro_get_hero_projects')) {
            $posts = inviro_get_hero_projects();
            echo "<p><strong>Total posts:</strong> " . count($posts) . "</p>";
            
            foreach ($posts as $index => $post) {
                $post_id = $post['id'];
                echo "<h3>Post #" . ($index + 1) . " (ID: {$post_id}) - " . esc_html($post['title']) . "</h3>";
                
                // Cek thumbnail
                $thumb_id = get_post_thumbnail_id($post_id);
                $has_thumb = has_post_thumbnail($post_id);
                echo "<p>Thumbnail ID: " . ($thumb_id ? "<span class='success'>{$thumb_id}</span>" : "<span class='error'>TIDAK ADA</span>") . "</p>";
                echo "<p>has_post_thumbnail: " . ($has_thumb ? "<span class='success'>YA</span>" : "<span class='error'>TIDAK</span>") . "</p>";
                
                // Test berbagai metode
                $urls = array();
                $urls['get_the_post_thumbnail_url(medium_large)'] = get_the_post_thumbnail_url($post_id, 'medium_large');
                $urls['get_the_post_thumbnail_url(full)'] = get_the_post_thumbnail_url($post_id, 'full');
                $urls['get_the_post_thumbnail_url(large)'] = get_the_post_thumbnail_url($post_id, 'large');
                
                if ($thumb_id) {
                    $img_data = wp_get_attachment_image_src($thumb_id, 'medium_large');
                    $urls['wp_get_attachment_image_src(medium_large)'] = $img_data ? $img_data[0] : '';
                    $urls['wp_get_attachment_url'] = wp_get_attachment_url($thumb_id);
                }
                
                $urls['inviro_get_image_url(large)'] = function_exists('inviro_get_image_url') ? inviro_get_image_url($post_id, 'large') : 'Function not found';
                
                echo "<h4>Image URLs:</h4><ul>";
                foreach ($urls as $method => $url) {
                    if (!empty($url) && $url !== 'Function not found') {
                        echo "<li class='success'><strong>{$method}:</strong><br>";
                        echo "<a href='{$url}' target='_blank'>{$url}</a><br>";
                        echo "<img src='{$url}' class='img-preview' onerror=\"this.style.border='3px solid red'; this.alt='FAILED TO LOAD'\" /></li>";
                    } else {
                        echo "<li class='error'><strong>{$method}:</strong> <span class='error'>KOSONG</span></li>";
                    }
                }
                echo "</ul><hr>";
            }
        } else {
            echo "<p class='error'>Function inviro_get_hero_projects() tidak ditemukan!</p>";
        }
        
        // 2. Branches
        echo "<h2>2. Branches</h2>";
        $branch_count = get_theme_mod('inviro_branch_count', 4);
        $displayed_branches = array();
        for ($i = 1; $i <= 8; $i++) {
            $branch_id = get_theme_mod('inviro_branch_' . $i);
            if ($branch_id) {
                $displayed_branches[] = $branch_id;
            }
        }
        
        echo "<p><strong>Total branches:</strong> " . count($displayed_branches) . "</p>";
        
        foreach ($displayed_branches as $index => $branch_id) {
            echo "<h3>Branch #" . ($index + 1) . " (ID: {$branch_id}) - " . get_the_title($branch_id) . "</h3>";
            
            $thumb_id = get_post_thumbnail_id($branch_id);
            $has_thumb = has_post_thumbnail($branch_id);
            echo "<p>Thumbnail ID: " . ($thumb_id ? "<span class='success'>{$thumb_id}</span>" : "<span class='error'>TIDAK ADA</span>") . "</p>";
            echo "<p>has_post_thumbnail: " . ($has_thumb ? "<span class='success'>YA</span>" : "<span class='error'>TIDAK</span>") . "</p>";
            
            $urls = array();
            $urls['get_the_post_thumbnail_url(inviro-branch)'] = get_the_post_thumbnail_url($branch_id, 'inviro-branch');
            $urls['get_the_post_thumbnail_url(full)'] = get_the_post_thumbnail_url($branch_id, 'full');
            $urls['get_the_post_thumbnail_url(large)'] = get_the_post_thumbnail_url($branch_id, 'large');
            
            if ($thumb_id) {
                $img_data = wp_get_attachment_image_src($thumb_id, 'inviro-branch');
                $urls['wp_get_attachment_image_src(inviro-branch)'] = $img_data ? $img_data[0] : '';
                $urls['wp_get_attachment_url'] = wp_get_attachment_url($thumb_id);
            }
            
            $urls['inviro_get_image_url(inviro-branch)'] = function_exists('inviro_get_image_url') ? inviro_get_image_url($branch_id, 'inviro-branch') : 'Function not found';
            
            echo "<h4>Image URLs:</h4><ul>";
            foreach ($urls as $method => $url) {
                if (!empty($url) && $url !== 'Function not found') {
                    echo "<li class='success'><strong>{$method}:</strong><br>";
                    echo "<a href='{$url}' target='_blank'>{$url}</a><br>";
                    echo "<img src='{$url}' class='img-preview' onerror=\"this.style.border='3px solid red'; this.alt='FAILED TO LOAD'\" /></li>";
                } else {
                    echo "<li class='error'><strong>{$method}:</strong> <span class='error'>KOSONG</span></li>";
                }
            }
            echo "</ul><hr>";
        }
        
        echo "<p class='info'><strong>Catatan:</strong> Hapus kode debug ini dari functions.php setelah selesai!</p>";
        echo "</body></html>";
        exit;
    }
});

