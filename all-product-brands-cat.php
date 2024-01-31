<?php 
/**
 * Plugin Name:       ALl Products Under Brands Category
 * Description:       Directory ALl Products Under Brands Category
 * Version:           1.0
 * Author:            Md. Abdul Hannan
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       apubc
 */
add_shortcode('brands_directory_items', 'nmd_brands_directory_items');
function nmd_brands_directory_items($atts){
	ob_start();
	$args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'tax_query'      => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => 'brands', 
			),
		),
	'orderby'        => 'title',
	'order'          => 'ASC'
	);

	$products = new WP_Query( $args );
	
	$items = array();
	$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	foreach (str_split($alphabet) as $letter) {
		$items['#'] = array();
		$items[$letter] = array();
	}
	
	if ( $products->have_posts() ) {
		while ( $products->have_posts() ) {
			$products->the_post();
			$matched = false;
			foreach (str_split($alphabet) as $letter) {
				$id = get_the_ID();
				$title = get_the_title();
				$permalink = get_permalink();
				$first_letter = substr($title, 0, 1);
				if($first_letter == $letter){
					array_push($items[$letter], array(
						'id' => $id,
						'title' => $title,
						'permalink' => $permalink
					));
					$matched = true;
					break;
				}
				
			}
			
			if($matched == false){
				array_push($items['#'], array(
					'id' => $id,
					'title' => $title,
					'permalink' => $permalink
				));
			}
		}
		
$number = 0;
		foreach($items as $selected_items_key => $selected_items_value){
			
if(count($selected_items_value) > 0): ?>
<div class="brands_items" id="brand_alphabet_<?php echo strtolower($selected_items_key);?>">
	
<h2 class="brands_alphabet">
	<?php echo $selected_items_key;?>
</h2>

<ul>
	<?php foreach ($selected_items_value as $value){ ?>
			<li><a href="<?php echo $value['permalink'];?>" class="brand_item_<?php echo $value['id'];?>"><?php echo $value['title'];?></a></li>
	<?php } ?>
	</ul>
	</div>
	<?php 
endif; 	
			$number++;
		}
		
		
		
// 		echo '<pre>';
// 		var_dump($items);
// 		echo '</pre>';
		
		
		
	} else {
		echo '<p>No products found</p>';
	}

	wp_reset_postdata();
	
	return ob_get_clean();
}

add_shortcode('brands_directory_items_index_widget', 'brands_directory_items_index_widget');
function brands_directory_items_index_widget($atts){
	ob_start();
	$args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'tax_query'      => array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => 'brands', 
			),
		),
	'orderby'        => 'title',
	'order'          => 'ASC'
	);

	$products = new WP_Query( $args );
	
	$items = array();
	$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	foreach (str_split($alphabet) as $letter) {
		$items[$letter] = array();
	}
	
	if ( $products->have_posts() ) {
		while ( $products->have_posts() ) {
			$products->the_post();
			
			foreach (str_split($alphabet) as $letter) {
				$id = get_the_ID();
				$title = get_the_title();
				$permalink = get_permalink();
				$first_letter = substr($title, 0, 1);
				if($first_letter == $letter){
					
					array_push($items[$letter], array(
						'id' => $id,
						'title' => $title,
						'permalink' => $permalink
					));
					
					break;
				}
			}
		}
echo '<div class="brand_alphabet_index">';	
		foreach($items as $selected_items_key => $selected_items_value){ ?>
<a href="#brand_alphabet_<?php echo strtolower($selected_items_key);?>" class="<?php echo count($selected_items_value) > 0 ? 'active_items' : 'empty_items'; ?>"><?php echo $selected_items_key;?></a>
	<?php 
		}
echo '</div>';	
	}
	wp_reset_postdata();
	return ob_get_clean();
}


