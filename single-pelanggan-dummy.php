<?php
/**
 * Single Pelanggan Dummy Template
 * Professional Article Layout
 */

// Get dummy data from page-pelanggan.php
$dummy_detail_data = isset($dummy_detail_data) ? $dummy_detail_data : null;

if (!$dummy_detail_data) {
    // Try to load it again
    $dummy_id = isset($_GET['dummy_id']) ? intval($_GET['dummy_id']) : 0;
    if ($dummy_id > 0) {
        $json_file = get_template_directory() . '/dummy-data/pelanggan.json';
        if (file_exists($json_file)) {
            $json_content = file_get_contents($json_file);
            $dummy_pelanggan = json_decode($json_content, true);
            foreach ($dummy_pelanggan as $item) {
                if (isset($item['id']) && $item['id'] == $dummy_id) {
                    $dummy_detail_data = $item;
                    break;
                }
            }
        }
    }
}

if (!$dummy_detail_data) {
    wp_redirect(home_url('/pelanggan'));
    exit;
}

// Extract data
$title = isset($dummy_detail_data['title']) ? $dummy_detail_data['title'] : '';
$content = isset($dummy_detail_data['description']) ? $dummy_detail_data['description'] : (isset($dummy_detail_data['excerpt']) ? $dummy_detail_data['excerpt'] : '');
$main_image = isset($dummy_detail_data['image']) ? $dummy_detail_data['image'] : '';
$gallery = isset($dummy_detail_data['gallery']) ? $dummy_detail_data['gallery'] : array();
$client_name = isset($dummy_detail_data['client_name']) ? $dummy_detail_data['client_name'] : 'Admin INVIRO';
$customer_name = isset($dummy_detail_data['customer_name']) ? $dummy_detail_data['customer_name'] : (isset($dummy_detail_data['client_name']) ? $dummy_detail_data['client_name'] : '');
$depot_name = isset($dummy_detail_data['depot_name']) ? $dummy_detail_data['depot_name'] : '';
$location = isset($dummy_detail_data['location']) ? $dummy_detail_data['location'] : '';
$location_map = isset($dummy_detail_data['location_map']) ? $dummy_detail_data['location_map'] : '';
$product_purchased = isset($dummy_detail_data['product_purchased']) ? $dummy_detail_data['product_purchased'] : '';
$installation_date = isset($dummy_detail_data['installation_date']) ? $dummy_detail_data['installation_date'] : (isset($dummy_detail_data['date']) ? $dummy_detail_data['date'] : '');
$proyek_date = isset($dummy_detail_data['date']) ? $dummy_detail_data['date'] : date('Y-m-d');
$region = isset($dummy_detail_data['region']) ? $dummy_detail_data['region'] : '';
$excerpt = isset($dummy_detail_data['excerpt']) ? $dummy_detail_data['excerpt'] : '';

// Format date
$formatted_date = date('d/m/Y', strtotime($proyek_date));
$formatted_date_long = date('d F Y', strtotime($proyek_date));

// Add main image to gallery if exists
if ($main_image && empty($gallery)) {
    $gallery = array($main_image);
} elseif ($main_image && !in_array($main_image, $gallery)) {
    array_unshift($gallery, $main_image);
}

// Get region name
$region_name = ucfirst($region);
?>

<div class="pelanggan-article-page">
    <article class="pelanggan-article">
        <div class="container">
            <!-- Article Header -->
            <header class="article-header">
                <h1 class="article-title"><?php echo esc_html($title); ?></h1>
                <div class="article-meta">
                    <span class="meta-author">Oleh <?php echo esc_html($client_name); ?></span>
                    <span class="meta-separator">|</span>
                    <span class="meta-date">Diposting pada <?php echo esc_html($formatted_date); ?></span>
                    <?php if ($region) : ?>
                    <span class="meta-separator">|</span>
                    <span class="meta-region"><?php echo esc_html($region_name); ?></span>
                    <?php endif; ?>
                </div>
            </header>

            <!-- Article Content -->
            <div class="article-content-wrapper">
                <div class="article-main-content">
                    <!-- Featured Image -->
                    <?php if ($main_image) : ?>
                    <div class="article-featured-image">
                        <img src="<?php echo esc_url($main_image); ?>" alt="<?php echo esc_attr($title); ?>" loading="eager">
                    </div>
                    <?php endif; ?>

                    <!-- Customer Information Section -->
                    <?php if ($customer_name || $depot_name || $location || $product_purchased || $installation_date) : ?>
                    <section class="customer-info-section">
                        <h2><?php echo esc_html($title); ?></h2>
                        
                        <?php if ($customer_name) : ?>
                        <div class="info-row">
                            <strong>Nama konsumen/pembeli:</strong> <?php echo esc_html($customer_name); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($depot_name) : ?>
                        <div class="info-row">
                            <strong>Nama depot air minum:</strong> <?php echo esc_html($depot_name); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($location) : ?>
                        <div class="info-row">
                            <strong>Lokasi/alamat pemasangan:</strong> <?php echo esc_html($location); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($location_map) : ?>
                        <div class="info-row">
                            <strong>Detail/shareloc lokasi:</strong> 
                            <a href="<?php echo esc_url($location_map); ?>" target="_blank" rel="noopener noreferrer">Google Maps</a>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($product_purchased) : ?>
                        <div class="info-row">
                            <strong>Produk yang dibeli di INVIRO:</strong> <?php echo esc_html($product_purchased); ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($installation_date) : ?>
                        <div class="info-row">
                            <strong>Unit DAMIU terpasang dilokasi pada tanggal:</strong> <?php echo esc_html(date('d F Y', strtotime($installation_date))); ?>
                        </div>
                        <?php endif; ?>
                    </section>
                    <?php endif; ?>

                    <!-- Main Article Content with Inline Images -->
                    <?php if ($content || !empty($gallery)) : ?>
                    <section class="article-body">
                        <?php 
                        // Convert newlines to paragraphs for dummy data
                        if ($content) {
                            $content_paragraphs = explode("\n\n", $content);
                            $para_count = 0;
                            $gallery_index = 0;
                            
                            foreach ($content_paragraphs as $para) {
                                $para = trim($para);
                                if (!empty($para)) {
                                    echo '<p>' . nl2br(esc_html($para)) . '</p>';
                                    $para_count++;
                                    
                                    // Insert gallery image after every 2-3 paragraphs
                                    if (!empty($gallery) && $gallery_index < count($gallery) && $para_count % 3 == 0) {
                                        echo '<div class="article-inline-image">';
                                        echo '<img src="' . esc_url($gallery[$gallery_index]) . '" alt="' . esc_attr($title) . '" loading="lazy">';
                                        echo '</div>';
                                        $gallery_index++;
                                    }
                                }
                            }
                            
                            // Add remaining gallery images at the end
                            if (!empty($gallery) && $gallery_index < count($gallery)) {
                                for ($i = $gallery_index; $i < count($gallery); $i++) {
                                    echo '<div class="article-inline-image">';
                                    echo '<img src="' . esc_url($gallery[$i]) . '" alt="' . esc_attr($title) . '" loading="lazy">';
                                    echo '</div>';
                                }
                            }
                        } elseif (!empty($gallery)) {
                            // If no content, just show gallery
                            foreach ($gallery as $img_url) {
                                echo '<div class="article-inline-image">';
                                echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($title) . '" loading="lazy">';
                                echo '</div>';
                            }
                        }
                        ?>
                    </section>
                    <?php endif; ?>

                    <!-- Article Footer -->
                    <footer class="article-footer">
                        <p class="article-closing">
                            Sekian informasi terbaru <?php echo esc_html(date('F Y')); ?> dari inviro.co.id mengenai <?php echo esc_html($title); ?>, semoga bermanfaat bagi bapak/ibu semuanya, Kami tunggu untuk segera bergabung menjadi mitra INVIRO. Semoga kesuksesan, kesehatan & kebahagiaan senantiasa dilimpahkan kepada Bapak/Ibu sekeluarga sekalian. Terima kasih.
                        </p>
                        
                        <!-- Share Buttons -->
                        <div class="share-buttons">
                            <span class="share-label">Sebarkan ini:</span>
                            <?php $share_url = home_url('/pelanggan/?dummy_id=' . $dummy_detail_data['id']); ?>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($share_url); ?>" target="_blank" class="share-btn share-facebook" rel="noopener noreferrer" title="Share on Facebook">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($share_url); ?>&text=<?php echo urlencode($title); ?>" target="_blank" class="share-btn share-twitter" rel="noopener noreferrer" title="Share on Twitter">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                            </a>
                            <a href="https://wa.me/?text=<?php echo urlencode($title . ' - ' . $share_url); ?>" target="_blank" class="share-btn share-whatsapp" rel="noopener noreferrer" title="Share on WhatsApp">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($share_url); ?>" target="_blank" class="share-btn share-linkedin" rel="noopener noreferrer" title="Share on LinkedIn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                            </a>
                        </div>
                    </footer>
                </div>

                <!-- Sidebar -->
                <aside class="article-sidebar">
                    <!-- Related Posts -->
                    <div class="related-posts-widget">
                        <h3>Posting terkait:</h3>
                        <?php
                        // Get related dummy projects
                        $json_file = get_template_directory() . '/dummy-data/pelanggan.json';
                        $related_projects = array();
                        if (file_exists($json_file)) {
                            $json_content = file_get_contents($json_file);
                            $all_projects = json_decode($json_content, true);
                            if (is_array($all_projects)) {
                                // Get 5 random projects excluding current
                                $filtered = array_filter($all_projects, function($p) use ($dummy_detail_data) {
                                    return isset($p['id']) && $p['id'] != $dummy_detail_data['id'];
                                });
                                shuffle($filtered);
                                $related_projects = array_slice($filtered, 0, 5);
                            }
                        }
                        
                        if (!empty($related_projects)) :
                        ?>
                        <ul class="related-posts-list">
                            <?php foreach ($related_projects as $proj) : ?>
                            <li>
                                <a href="<?php echo esc_url(home_url('/pelanggan/?dummy_id=' . $proj['id'])); ?>"><?php echo esc_html($proj['title']); ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else : ?>
                        <p>Tidak ada posting terkait.</p>
                        <?php endif; ?>
                    </div>
                </aside>
            </div>
        </div>
    </article>

    <!-- Comments Section -->
    <section class="article-comments">
        <div class="container">
            <div class="comments-wrapper">
                <h2>Komentar</h2>
                
                <!-- Comment Form -->
                <div class="comment-form-wrapper">
                    <h3>Tinggalkan Komentar</h3>
                    <form id="pelanggan-comment-form" class="comment-form">
                        <?php wp_nonce_field('submit_comment', 'comment_nonce'); ?>
                        <input type="hidden" name="proyek_id" value="dummy_<?php echo esc_attr($dummy_detail_data['id']); ?>">
                        <input type="hidden" name="is_dummy" value="1">
                        <input type="hidden" name="comment_type" value="pelanggan">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="commenter_name">Nama *</label>
                                <input type="text" id="commenter_name" name="commenter_name" required>
                            </div>
                            <div class="form-group">
                                <label for="commenter_email">Email *</label>
                                <input type="email" id="commenter_email" name="commenter_email" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="comment_content">Komentar *</label>
                            <textarea id="comment_content" name="comment_content" rows="5" required placeholder="Tulis komentar Anda..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn-submit-comment">Kirim Komentar</button>
                        <div class="form-message"></div>
                    </form>
                </div>
                
                <!-- Comments List -->
                <div class="comments-list">
                    <p class="no-comments">Belum ada komentar. Jadilah yang pertama memberikan komentar!</p>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
jQuery(document).ready(function($) {
    // Comment form submission
    $('#pelanggan-comment-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        var messageDiv = form.find('.form-message');
        
        submitBtn.prop('disabled', true).text('Mengirim...');
        messageDiv.removeClass('success error').html('');
        
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: form.serialize() + '&action=submit_pelanggan_comment',
            success: function(response) {
                if (response.success) {
                    messageDiv.addClass('success').html(response.data.message);
                    form[0].reset();
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    messageDiv.addClass('error').html(response.data.message);
                    submitBtn.prop('disabled', false).text('Kirim Komentar');
                }
            },
            error: function() {
                messageDiv.addClass('error').html('Terjadi kesalahan. Silakan coba lagi.');
                submitBtn.prop('disabled', false).text('Kirim Komentar');
            }
        });
    });
});
</script>
</div>

<?php
// Enqueue the article CSS
wp_enqueue_style('inviro-pelanggan-article', get_template_directory_uri() . '/assets/css/pelanggan-article.css', array('inviro-base', 'inviro-components-cards'), '1.0.0');
get_footer();
?>

