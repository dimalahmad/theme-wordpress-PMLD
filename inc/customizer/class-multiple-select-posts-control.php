<?php
/**
 * Custom Control untuk Memilih Multiple Posts
 *
 * @package INVIRO
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
    return;
}

/**
 * Multiple Select Posts Control
 */
class Inviro_Multiple_Select_Posts_Control extends WP_Customize_Control {
    
    /**
     * Type.
     *
     * @var string
     */
    public $type = 'multiple-select-posts';
    
    /**
     * Post type.
     *
     * @var string
     */
    public $post_type = 'post';
    
    /**
     * Number of posts to show.
     *
     * @var int
     */
    public $max_posts = 5;
    
    /**
     * Enqueue scripts/styles for the control.
     */
    public function enqueue() {
        // Enqueue jQuery UI Sortable for drag and drop
        wp_enqueue_script( 'jquery-ui-sortable' );
        
        wp_enqueue_script( 
            'inviro-multiple-select-posts-control', 
            get_template_directory_uri() . '/assets/js/customizer-multiple-select-posts.js', 
            array( 'jquery', 'jquery-ui-sortable', 'customize-controls' ), 
            '1.0.0', 
            true 
        );
        
        wp_enqueue_style( 
            'inviro-multiple-select-posts-control', 
            get_template_directory_uri() . '/assets/css/customizer-multiple-select-posts.css', 
            array(), 
            '1.0.0' 
        );
        
        // Localize script with posts data
        $posts = get_posts( array(
            'post_type' => $this->post_type,
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'post_status' => 'publish'
        ) );
        
        $posts_array = array();
        foreach ( $posts as $post ) {
            $thumbnail_url = '';
            if ( has_post_thumbnail( $post->ID ) ) {
                $thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'thumbnail' );
            }
            
            $posts_array[] = array(
                'id' => $post->ID,
                'title' => $post->post_title,
                'thumbnail' => $thumbnail_url,
                'date' => get_the_date( '', $post )
            );
        }
        
        wp_localize_script( 'inviro-multiple-select-posts-control', 'inviroPostsData', array(
            'posts' => $posts_array,
            'maxPosts' => $this->max_posts
        ) );
    }
    
    /**
     * Refresh the parameters passed to JavaScript via JSON.
     */
    public function to_json() {
        parent::to_json();
        $this->json['post_type'] = $this->post_type;
        $this->json['max_posts'] = $this->max_posts;
    }
    
    /**
     * Render the control's content.
     */
    public function render_content() {
        $selected_posts = $this->value() ? json_decode( $this->value(), true ) : array();
        ?>
        <div class="multiple-select-posts-control">
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif; ?>
            
            <?php if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <?php endif; ?>
            
            <div class="selected-posts-container">
                <h4><?php echo esc_html__( 'Proyek Terpilih', 'inviro' ); ?> <span class="count">(<?php echo count( $selected_posts ); ?>/<?php echo $this->max_posts; ?>)</span></h4>
                <div class="selected-posts" data-max="<?php echo esc_attr( $this->max_posts ); ?>">
                    <?php
                    if ( ! empty( $selected_posts ) ) {
                        foreach ( $selected_posts as $post_id ) {
                            $post = get_post( $post_id );
                            if ( $post ) {
                                $thumbnail_url = '';
                                if ( has_post_thumbnail( $post->ID ) ) {
                                    $thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'thumbnail' );
                                }
                                ?>
                                <div class="selected-post-item" data-post-id="<?php echo esc_attr( $post->ID ); ?>">
                                    <?php if ( $thumbnail_url ) : ?>
                                        <div class="post-thumbnail">
                                            <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="">
                                        </div>
                                    <?php else : ?>
                                        <div class="post-thumbnail no-thumbnail">
                                            <span class="dashicons dashicons-format-image"></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="post-info">
                                        <span class="post-title"><?php echo esc_html( $post->post_title ); ?></span>
                                        <span class="post-date"><?php echo get_the_date( '', $post ); ?></span>
                                    </div>
                                    <button type="button" class="remove-post" aria-label="<?php esc_attr_e( 'Hapus', 'inviro' ); ?>">
                                        <span class="dashicons dashicons-no-alt"></span>
                                    </button>
                                </div>
                                <?php
                            }
                        }
                    }
                    ?>
                </div>
                <?php if ( count( $selected_posts ) < $this->max_posts ) : ?>
                    <p class="no-posts-message" <?php echo ! empty( $selected_posts ) ? 'style="display:none;"' : ''; ?>>
                        <?php echo esc_html__( 'Belum ada proyek yang dipilih. Klik tombol di bawah untuk memilih.', 'inviro' ); ?>
                    </p>
                <?php endif; ?>
            </div>
            
            <div class="available-posts-container">
                <button type="button" class="button button-secondary toggle-available-posts">
                    <span class="dashicons dashicons-plus-alt"></span>
                    <?php echo esc_html__( 'Pilih Proyek', 'inviro' ); ?>
                </button>
                
                <div class="available-posts-wrapper" style="display: none;">
                    <div class="search-posts">
                        <input type="text" class="search-posts-input" placeholder="<?php esc_attr_e( 'Cari proyek...', 'inviro' ); ?>">
                    </div>
                    <div class="available-posts">
                        <?php
                        $posts = get_posts( array(
                            'post_type' => $this->post_type,
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC',
                            'post_status' => 'publish'
                        ) );
                        
                        if ( ! empty( $posts ) ) {
                            foreach ( $posts as $post ) {
                                $is_selected = in_array( $post->ID, $selected_posts );
                                $thumbnail_url = '';
                                if ( has_post_thumbnail( $post->ID ) ) {
                                    $thumbnail_url = get_the_post_thumbnail_url( $post->ID, 'thumbnail' );
                                }
                                ?>
                                <div class="available-post-item <?php echo $is_selected ? 'selected' : ''; ?>" data-post-id="<?php echo esc_attr( $post->ID ); ?>">
                                    <?php if ( $thumbnail_url ) : ?>
                                        <div class="post-thumbnail">
                                            <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="">
                                        </div>
                                    <?php else : ?>
                                        <div class="post-thumbnail no-thumbnail">
                                            <span class="dashicons dashicons-format-image"></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="post-info">
                                        <span class="post-title"><?php echo esc_html( $post->post_title ); ?></span>
                                        <span class="post-date"><?php echo get_the_date( '', $post ); ?></span>
                                    </div>
                                    <button type="button" class="select-post" aria-label="<?php esc_attr_e( 'Pilih', 'inviro' ); ?>">
                                        <span class="dashicons dashicons-<?php echo $is_selected ? 'yes' : 'plus-alt2'; ?>"></span>
                                    </button>
                                </div>
                                <?php
                            }
                        } else {
                            ?>
                            <p class="no-posts-available">
                                <?php echo esc_html__( 'Tidak ada proyek yang tersedia.', 'inviro' ); ?>
                            </p>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( $this->value() ); ?>" />
        </div>
        <?php
    }
}
