<?php
/**
 * Debug script untuk cek gambar hero dan cabang
 * Akses via: yoursite.com/wp-content/themes/theme-wordpress-PMLD/debug-images.php
 * HAPUS FILE INI SETELAH DEBUG SELESAI!
 */

// Load WordPress - coba beberapa path
$wp_load_paths = array(
    __DIR__ . '/../../../wp-load.php',
    __DIR__ . '/../../../../wp-load.php',
    dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php',
    dirname(dirname(dirname(__FILE__))) . '/wp-load.php',
);

$wp_loaded = false;
foreach ($wp_load_paths as $path) {
    if (file_exists($path)) {
        require_once($path);
        $wp_loaded = true;
        break;
    }
}

if (!$wp_loaded) {
    die('Error: Cannot find wp-load.php. Tried paths: ' . implode(', ', $wp_load_paths));
}

// Prevent direct access in production
if (!current_user_can('manage_options')) {
    die('Access denied');
}

echo "<h1>Debug Images - Hero & Branch</h1>";
echo "<style>body{font-family:monospace;padding:20px;} .success{color:green;} .error{color:red;} .info{color:blue;} pre{background:#f5f5f5;padding:10px;border:1px solid #ddd;}</style>";

// 1. Cek Hero Projects
echo "<h2>1. Hero Projects</h2>";
$posts = inviro_get_hero_projects();
echo "<p>Total posts: " . count($posts) . "</p>";

foreach ($posts as $index => $post) {
    echo "<h3>Post #" . ($index + 1) . " (ID: {$post['id']})</h3>";
    echo "<p><strong>Title:</strong> " . esc_html($post['title']) . "</p>";
    echo "<p><strong>Permalink:</strong> " . esc_url($post['permalink']) . "</p>";
    
    // Cek thumbnail ID
    $thumb_id = get_post_thumbnail_id($post['id']);
    echo "<p><strong>Thumbnail ID:</strong> " . ($thumb_id ? $thumb_id : '<span class="error">TIDAK ADA</span>') . "</p>";
    
    // Cek has_post_thumbnail
    $has_thumb = has_post_thumbnail($post['id']);
    echo "<p><strong>has_post_thumbnail:</strong> " . ($has_thumb ? '<span class="success">YA</span>' : '<span class="error">TIDAK</span>') . "</p>";
    
    // Cek berbagai metode get image URL
    echo "<h4>Image URLs:</h4>";
    echo "<ul>";
    
    $methods = array(
        'get_the_post_thumbnail_url(medium_large)' => get_the_post_thumbnail_url($post['id'], 'medium_large'),
        'get_the_post_thumbnail_url(full)' => get_the_post_thumbnail_url($post['id'], 'full'),
        'get_the_post_thumbnail_url(large)' => get_the_post_thumbnail_url($post['id'], 'large'),
        'wp_get_attachment_image_src(medium_large)' => '',
        'inviro_get_image_url(large)' => inviro_get_image_url($post['id'], 'large'),
    );
    
    if ($thumb_id) {
        $img_data = wp_get_attachment_image_src($thumb_id, 'medium_large');
        $methods['wp_get_attachment_image_src(medium_large)'] = $img_data ? $img_data[0] : '';
    }
    
    foreach ($methods as $method => $url) {
        if (!empty($url)) {
            echo "<li class='success'><strong>{$method}:</strong> <a href='{$url}' target='_blank'>{$url}</a> <img src='{$url}' style='max-width:100px;display:block;margin-top:5px;' onerror='this.style.border=\"2px solid red\"' /></li>";
        } else {
            echo "<li class='error'><strong>{$method}:</strong> <span class='error'>KOSONG</span></li>";
        }
    }
    echo "</ul>";
    
    // Cek attachment
    if ($thumb_id) {
        $attachment = get_post($thumb_id);
        echo "<p><strong>Attachment exists:</strong> " . ($attachment ? '<span class="success">YA</span>' : '<span class="error">TIDAK</span>') . "</p>";
        if ($attachment) {
            echo "<p><strong>Attachment URL:</strong> " . wp_get_attachment_url($thumb_id) . "</p>";
        }
    }
    
    echo "<hr>";
}

// 2. Cek Branches
echo "<h2>2. Branches</h2>";
$branch_count = get_theme_mod('inviro_branch_count', 4);
$displayed_branches = array();
for ($i = 1; $i <= 8; $i++) {
    $branch_id = get_theme_mod('inviro_branch_' . $i);
    if ($branch_id) {
        $displayed_branches[] = $branch_id;
    }
}

echo "<p>Total branches: " . count($displayed_branches) . "</p>";

foreach ($displayed_branches as $index => $branch_id) {
    echo "<h3>Branch #" . ($index + 1) . " (ID: {$branch_id})</h3>";
    echo "<p><strong>Title:</strong> " . get_the_title($branch_id) . "</p>";
    
    // Cek thumbnail ID
    $thumb_id = get_post_thumbnail_id($branch_id);
    echo "<p><strong>Thumbnail ID:</strong> " . ($thumb_id ? $thumb_id : '<span class="error">TIDAK ADA</span>') . "</p>";
    
    // Cek has_post_thumbnail
    $has_thumb = has_post_thumbnail($branch_id);
    echo "<p><strong>has_post_thumbnail:</strong> " . ($has_thumb ? '<span class="success">YA</span>' : '<span class="error">TIDAK</span>') . "</p>";
    
    // Cek berbagai metode get image URL
    echo "<h4>Image URLs:</h4>";
    echo "<ul>";
    
    $methods = array(
        'get_the_post_thumbnail_url(inviro-branch)' => get_the_post_thumbnail_url($branch_id, 'inviro-branch'),
        'get_the_post_thumbnail_url(full)' => get_the_post_thumbnail_url($branch_id, 'full'),
        'get_the_post_thumbnail_url(large)' => get_the_post_thumbnail_url($branch_id, 'large'),
        'wp_get_attachment_image_src(inviro-branch)' => '',
        'inviro_get_image_url(inviro-branch)' => inviro_get_image_url($branch_id, 'inviro-branch'),
    );
    
    if ($thumb_id) {
        $img_data = wp_get_attachment_image_src($thumb_id, 'inviro-branch');
        $methods['wp_get_attachment_image_src(inviro-branch)'] = $img_data ? $img_data[0] : '';
    }
    
    foreach ($methods as $method => $url) {
        if (!empty($url)) {
            echo "<li class='success'><strong>{$method}:</strong> <a href='{$url}' target='_blank'>{$url}</a> <img src='{$url}' style='max-width:100px;display:block;margin-top:5px;' onerror='this.style.border=\"2px solid red\"' /></li>";
        } else {
            echo "<li class='error'><strong>{$method}:</strong> <span class='error'>KOSONG</span></li>";
        }
    }
    echo "</ul>";
    
    echo "<hr>";
}

// 3. Cek registered image sizes
echo "<h2>3. Registered Image Sizes</h2>";
global $_wp_additional_image_sizes;
$sizes = get_intermediate_image_sizes();
echo "<ul>";
foreach ($sizes as $size) {
    $width = isset($_wp_additional_image_sizes[$size]['width']) ? $_wp_additional_image_sizes[$size]['width'] : get_option("{$size}_size_w");
    $height = isset($_wp_additional_image_sizes[$size]['height']) ? $_wp_additional_image_sizes[$size]['height'] : get_option("{$size}_size_h");
    echo "<li><strong>{$size}:</strong> {$width}x{$height}</li>";
}
echo "</ul>";

echo "<p class='info'><strong>Catatan:</strong> Hapus file ini setelah selesai debug!</p>";
?>

