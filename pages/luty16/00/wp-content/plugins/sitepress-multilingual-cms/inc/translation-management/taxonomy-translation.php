<?php

define('WPML_TT_TAXONOMIES_NOT_TRANSLATED', 1);
define('WPML_TT_TAXONOMIES_ALL', 0);
define('WPML_TT_TERMS_PER_PAGE', 20);

class WPML_Taxonomy_Translation{

	var $taxonomy;
	var $args;

	function __construct($taxonomy = '', $args = array()){
		global $wpdb, $sitepress, $sitepress_settings;

		$default_language = $sitepress->get_default_language();
		$_active_languages = $sitepress->get_active_languages();

		if(empty($taxonomy)){

			global $wp_taxonomies;
			foreach($wp_taxonomies as $tax_key => $tax){
				if($sitepress->is_translated_taxonomy($tax_key)){
					$this->taxonomy = $tax_key;
					break;
				}
			}

		}else{

			$this->taxonomy = $taxonomy;

		}

		$this->args     = $args;

		$this->show_selector = isset($args['taxonomy_selector']) ? $args['taxonomy_selector'] : true;
		$this->show_tax_sync = isset($args['taxonomy_sync']) ? $args['taxonomy_sync'] : true;


		$this->taxonomy_obj = get_taxonomy($this->taxonomy);

		// filters
		$this->status = isset($this->args['status']) ? $this->args['status'] : WPML_TT_TAXONOMIES_NOT_TRANSLATED;

		if(isset($this->args['languages']) && $this->args['languages']){
			foreach($_active_languages as $language){
				if(in_array($language['code'], $args['languages'])){
					$selected_languages[$language['code']] = $language;
				}
			}
		}

		$this->selected_languages = !empty($selected_languages) ? $selected_languages : $_active_languages;

		if(defined('WPML_ST_FOLDER')){
			// get labels translations

			if($sitepress_settings['st']['strings_language'] != $default_language ){

				$singular_original = $wpdb->get_var($wpdb->prepare("SELECT s.value FROM {$wpdb->prefix}icl_strings s
                    JOIN {$wpdb->prefix}icl_string_translations t ON t.string_id = s.id 
                    WHERE s.context='WordPress' AND t.value = %s AND s.name LIKE %s AND t.language=%s",
					$this->taxonomy_obj->labels->singular_name, 'taxonomy singular name: %', $sitepress->get_admin_language()));

				$general_original  = $wpdb->get_var($wpdb->prepare("SELECT s.value FROM {$wpdb->prefix}icl_strings s
                    JOIN {$wpdb->prefix}icl_string_translations t ON t.string_id = s.id 
                    WHERE s.context='WordPress' AND t.value = %s AND s.name LIKE %s AND t.language=%s",
					$this->taxonomy_obj->labels->name, 'taxonomy general name: %', $sitepress->get_admin_language()));

			}

			if(empty($singular_original)){
				$singular_original = $this->taxonomy_obj->labels->singular_name;
			}
			if(empty($general_original)){
				$general_original  = $this->taxonomy_obj->labels->name;
			}

			$this->taxonomy_obj->labels_translations[$sitepress_settings['st']['strings_language']]['singular'] = $singular_original;
			$this->taxonomy_obj->labels_translations[$sitepress_settings['st']['strings_language']]['general']  = $general_original;


			$languages_pool = array_diff(array_merge(array_keys($this->selected_languages), array( $default_language )), array($sitepress_settings['st']['strings_language']));

			foreach($languages_pool as $language){

				$singular = $wpdb->get_var($wpdb->prepare("SELECT t.value FROM {$wpdb->prefix}icl_string_translations t
                        JOIN {$wpdb->prefix}icl_strings s ON t.string_id = s.id 
                        WHERE s.context='WordPress' and s.name=%s AND t.language=%s", 'taxonomy singular name: ' . $singular_original, $language));
				$general = $wpdb->get_var($wpdb->prepare("SELECT t.value FROM {$wpdb->prefix}icl_string_translations t
                        JOIN {$wpdb->prefix}icl_strings s ON t.string_id = s.id 
                        WHERE s.context='WordPress' and s.name=%s AND t.language=%s", 'taxonomy general name: ' . $general_original, $language));
				$this->taxonomy_obj->labels_translations[$language]['singular'] = $singular ? $singular : '';
				$this->taxonomy_obj->labels_translations[$language]['general'] = $general ? $general : '';

			}

		}

		// build list of exclusion based on filters
		foreach($this->selected_languages as $language){
			$lcode_alias = str_replace('-', '', $language['code']);
			$joins[]    = " LEFT JOIN {$wpdb->prefix}icl_translations t{$lcode_alias} ON t{$lcode_alias}.trid = t.trid AND t{$lcode_alias}.language_code='{$language['code']}'";
			$selects[]  = "t{$lcode_alias}.element_id AS element_id_{$lcode_alias}";
		}

		if ( isset( $joins ) && isset( $selects ) ) {
			$joins   = join( ' ', $joins );
			$selects = join( ', ', $selects );
			if ( $this->status == WPML_TT_TAXONOMIES_NOT_TRANSLATED ) {
				$res = $wpdb->get_results( $wpdb->prepare( "
		                SELECT t.element_id, {$selects}
		                FROM {$wpdb->prefix}icl_translations t
		                    {$joins}
		                WHERE t.element_type = %s AND t.language_code = %s
		            ", 'tax_' . $this->taxonomy, $default_language ) );

				foreach ( $res as $row ) {
					$translations = 0;
					foreach ( $row as $r ) {
						if ( $r > 0 ) {
							$translations ++;
						}
					}
				}
			}
		}
		if ( ! empty( $excludes ) ) {
			$get_terms_args[ 'exclude' ] = $wpdb->get_col( $wpdb->prepare( "SELECT term_id FROM {$wpdb->term_taxonomy} WHERE taxonomy=%s AND term_taxonomy_id IN (" . join( ',', $excludes ) . ")", $this->taxonomy ) );
		}

		// get_terms args
		$get_terms_args['hide_empty'] = false;
		$get_terms_args['orderby'] = 'name';
		if(!empty($this->args['search'])){
			$get_terms_args['search'] = $this->args['search'];
			$this->search = $args['search'];
		}

		if(!empty($this->args['child_of'])){
			$get_terms_args['child_of'] = $this->args['child_of'];
			$this->child_of = $get_terms_args['child_of'];
		}else{
			$this->child_of = 0;
		}
		//No filtering necessary here. All language filtering is done be the subsequent functionality.
		remove_all_filters('terms_clauses');
		remove_all_filters('list_terms_exclusions');
		$_terms = get_terms($this->taxonomy, $get_terms_args);

		// on search - force include parents
		if(!empty($this->search)){
			if($_terms) foreach($_terms as $term){
				$in_results = false;
				foreach($_terms as $term2){
					if($term2->term_id == $term->parent){
						$in_results = true;
						break;
					}
				}
				if(!$in_results){
					while($term->parent > 0){

						$term = get_term($term->parent, $this->taxonomy);
						$_terms[] = $term;

					}
				}
			}

		}

		$this->terms_count = count($_terms);


		$_terms = $this->order_terms_by_hierarchy($_terms);

		$this->current_page = isset($this->args['page']) ? $this->args['page'] : 1;
		$offset = ($this->current_page - 1) * WPML_TT_TERMS_PER_PAGE;

		$this->terms = array_slice($_terms, $offset, WPML_TT_TERMS_PER_PAGE);

		// prepend parents if needed
		if(isset($this->terms[0])){
			while($this->terms[0]->parent > 0 && $this->terms[0]->parent != $this->child_of){

				foreach($_terms as $term){
					if($term->term_id == $this->terms[0]->parent){
						$guide_parent = $term;
						break;
					}
				}
				if(!empty($guide_parent)){
					array_unshift($this->terms, $guide_parent);
				}
			}
		}

		unset($_terms);

		if(is_wp_error($this->terms)){
			$this->error = sprintf(__('Unknown taxonomy: %s'), $this->taxonomy);
			return false;
		}

		if(empty($this->terms) || is_wp_error($this->terms)) return;

		// limit for pagination?

		// get term taxonomy ids
		foreach($this->terms as $term){
			$tt_ids[] = $term->term_taxonomy_id;
		}

		// get list of matching trids
		$trids = $wpdb->get_col($wpdb->prepare("SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_type = %s AND element_id IN (" . join(',', $tt_ids) . ")", 'tax_' . $this->taxonomy));

		// get terms by trids
		$res = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}icl_translations WHERE element_type = %s AND trid IN (" . join( ',', $trids ) . ")", 'tax_' . $this->taxonomy ) );

		$terms_by_trid = array();

		foreach ( $res as $row ) {
			$terms_by_trid[ $row->trid ][ $row->language_code ] = array( 'term_taxonomy_id' => $row->element_id, 'source_lang' => $row->source_language_code );
		}

		$terms_in_old_format = $this->terms;

		$this->terms = array();

		foreach ( $terms_by_trid as $trid => $term_translation ) {
			foreach ( $term_translation as $lang => $term_trid_array ) {
				foreach ( $terms_in_old_format as $key => $term_object ) {
					if ( isset( $term_object->term_taxonomy_id ) && $term_object->term_taxonomy_id == $term_trid_array[ 'term_taxonomy_id' ] ) {
						if ( ! $term_trid_array[ 'source_lang' ] ) {
							$term_object->translation_of = $term_object->term_taxonomy_id;
						} else {
							$term_object->translation_of = SitePress::get_original_element_id( $term_object->term_taxonomy_id, 'tax' . $taxonomy );
						}
						$this->terms[ $trid ][ $lang ] = $term_object;
						if ( $term_object->translation_of == $term_object->term_taxonomy_id || !isset($this->terms[ $trid ][ 'source_lang' ]) ) {
							$this->terms[ $trid ][ 'source_lang' ] = $lang;
						}
					}
				}
			}
		}

		/* If we try to filter for untranslated terms, we will not show any row that has translations in every language. */
		foreach ( $this->terms as $key => $translated_terms ) {
			$all_found = true;
			foreach ( $this->selected_languages as $lang => $langauge ) {
				if ( ! isset( $translated_terms[ $lang ] ) ) {
					$all_found = false;
				}
			}
			if ( ! $all_found && isset($_active_languages[ $translated_terms[ 'source_lang' ] ]) ) {
				/* If we try to filter for untranslated terms, we will always show the original element and its column.*/
				$this->selected_languages[ $translated_terms[ 'source_lang' ] ] = $_active_languages[ $translated_terms[ 'source_lang' ] ];
			} elseif ( isset( $this->args[ 'status' ] ) && $this->args[ 'status' ] == 1 ) {
				unset ( $this->terms[ $key ] );
			}
		}

	}

	function order_terms_by_hierarchy($terms){

		$ordered_list = array();
		foreach($terms as $term){
			if($term->parent ==  $this->child_of){
				$term->level = 0;
				$ordered_list[] = $term;
			}
		}


		foreach($ordered_list as $parent){
			self::_insert_child_terms_in_list($terms, $ordered_list, $parent->term_id);
		}

		return $ordered_list;

	}

	static function _insert_child_terms_in_list($terms, &$ordered_list, $parent, $level = 0){

		$children = array();
		foreach($terms as $term){
			if($term->parent ==  $parent){
				$children[] = $term;
			}
		}

		// get index of parent
		$parent_index = -1;
		foreach($ordered_list as $k => $term){
			if($term->term_id == $parent){
				$parent_index = $k;
				break;
			}
		}

		if($children && $parent_index >= 0){
			array_splice($ordered_list, $parent_index+1, 0, $children);

			foreach($children as $child){
				$child->level = $level + 1;
				self::_insert_child_terms_in_list($terms, $ordered_list, $child->term_id, $level + 1);
			}
		}



	}


	function render(){
		if(!empty($this->error)){

			echo '<div class="icl_error_text">' . $this->error . '</div>';

		}
		elseif(!$this->taxonomy_obj){

			echo '<div class="icl_error_text">' . sprintf(__('Unknown taxonomy: %s', 'sitepress'), $this->taxonomy ) . '</div>';

		}else{

			include ICL_PLUGIN_PATH . '/menu/taxonomy-translation-content.php';

		}


	}

	static function show_terms(){
		$taxonomy = isset($_POST['taxonomy']) ? $_POST['taxonomy'] : false;

		$args = array();
		if(!empty($_POST['language'])){
			$args['languages'] = array($_POST['language']);
		}
		$args['status'] = isset($_POST['status']) ? $_POST['status'] : WPML_TT_TAXONOMIES_ALL;

		$args['search'] = isset($_POST['search']) ? $_POST['search'] : '';

		if(isset($_POST['page'])){
			$args['page'] = $_POST['page'];
		}

		if(isset($_POST['parent'])){
			$args['parent'] = $_POST['parent'];
		}

		if(isset($_POST['child_of']) && intval($_POST['child_of']) > 0){
			$args['child_of'] = $_POST['child_of'];
		}

		if(isset($_POST['taxonomy_selector'])){
			$args['taxonomy_selector'] = $_POST['taxonomy_selector'];
		}

		$inst = new WPML_Taxonomy_Translation($taxonomy, $args);

		$inst->render();
		exit;

	}

	public static function save_term_translation() {
		global $sitepress, $wpdb;

		$original_element = $_POST[ 'translation_of' ];
		$taxonomy         = $_POST[ 'taxonomy' ];
		$language         = $_POST[ 'language' ];
		$trid             = $sitepress->get_element_trid( $original_element, 'tax_' . $taxonomy );
		$translations     = $sitepress->get_element_translations( $trid, 'tax_' . $taxonomy );

		$_POST[ 'icl_tax_' . $taxonomy . '_language' ] = $language;
		$_POST[ 'icl_trid' ]                           = $trid;
		$_POST[ 'icl_translation_of' ]                 = $original_element;

		$errors = '';

		$term_args = array(
			'name'        => $_POST[ 'name' ],
			'slug'        => WPML_Terms_Translations::pre_term_slug_filter( $_POST[ 'slug' ], $taxonomy ),
			'description' => $_POST[ 'description' ]
		);

		$original_tax_sql      = "SELECT * FROM {$wpdb->term_taxonomy} WHERE taxonomy=%s AND term_taxonomy_id = %d";
		$original_tax_prepared = $wpdb->prepare( $original_tax_sql, array( $taxonomy, $original_element ) );
		$original_tax          = $wpdb->get_row( $original_tax_prepared );

		// hierarchy - parents
		if ( is_taxonomy_hierarchical( $taxonomy ) ) {
			// fix hierarchy
			if ( $original_tax->parent ) {
				$original_parent_translated = icl_object_id( $original_tax->parent, $taxonomy, false, $_POST[ 'language' ] );
				if ( $original_parent_translated ) {
					$term_args[ 'parent' ] = $original_parent_translated;
				}
			}
		}

		if ( isset( $translations[ $language ] ) ) {
			$result = wp_update_term( $translations[ $language ]->term_id, $taxonomy, $term_args );
		} else {
			$result                        = wp_insert_term( $_POST[ 'name' ], $taxonomy, $term_args );
			$original_element_lang_details = $sitepress->get_element_language_details( $original_element, 'tax_' . $taxonomy );
			if ( isset( $original_element_lang_details->language_code ) ) {
				$sitepress->set_element_language_details( $result[ 'term_taxonomy_id' ], 'tax_' . $taxonomy, $trid, $language, $original_element_lang_details->language_code );
			}
		}

		if ( is_wp_error( $result ) ) {
			foreach ( $result->errors as $ers ) {
				$errors .= join( '<br />', $ers );
			}
			$errors .= '<br />';
		} else {

			// hierarchy - children
			if ( is_taxonomy_hierarchical( $taxonomy ) ) {

				// get children of original
				$children_sql      = "SELECT term_id FROM {$wpdb->term_taxonomy} WHERE taxonomy=%s AND parent=%d";
				$children_prepared = $wpdb->prepare( $children_sql, array( $taxonomy, $original_tax->term_id ) );
				$children          = $wpdb->get_col( $children_prepared );

				if ( $children ) {
					foreach ( $children as $child ) {
						$child_translated = icl_object_id( $child, $taxonomy, false, $_POST[ 'language' ] );
						if ( $child_translated ) {
							$wpdb->update( $wpdb->term_taxonomy, array( 'parent' => $result[ 'term_id' ] ), array( 'taxonomy' => $taxonomy, 'term_id' => $child_translated ) );
						}
					}
				}

				$sitepress->update_terms_relationship_cache( $children, $taxonomy );
				//delete_option($_POST['taxonomy'] . '_children');

			}

			$term = get_term( $result[ 'term_id' ], $taxonomy );

			do_action( 'icl_save_term_translation', $original_tax, $result );
		}

		$html = '';

		echo json_encode( array( 'html' => $html, 'slug' => isset( $term ) ? urldecode( $term->slug ) : '', 'errors' => $errors ) );
		exit;
	}

	public static function save_labels_translation() {

		$errors = '';

		if ( empty( $_POST[ 'singular' ] ) || empty( $_POST[ 'general' ] ) ) {
			$errors .= __( 'Please fill in all fields!', 'sitepress' ) . '<br />';
		}

		$string_id = icl_st_is_registered_string( 'WordPress', 'taxonomy singular name: ' . $_POST[ 'singular_original' ] );
		if ( !$string_id ) {
			$string_id = icl_register_string( 'WordPress', 'taxonomy singular name: ' . $_POST[ 'singular_original' ], $_POST[ 'singular_original' ] );
		}
		icl_add_string_translation( $string_id, $_POST[ 'language' ], $_POST[ 'singular' ], ICL_STRING_TRANSLATION_COMPLETE );

		$string_id = icl_st_is_registered_string( 'WordPress', 'taxonomy general name: ' . $_POST[ 'general_original' ] );
		if ( !$string_id ) {
			$string_id = icl_register_string( 'WordPress', 'taxonomy general name: ' . $_POST[ 'general_original' ], $_POST[ 'general_original' ] );
		}
		icl_add_string_translation( $string_id, $_POST[ 'language' ], $_POST[ 'general' ], ICL_STRING_TRANSLATION_COMPLETE );

		$html = '';

		echo json_encode( array( 'html' => $html, 'errors' => $errors ) );
		exit;


	}

	public static function sync_taxonomies_in_content_preview(){
		global $wp_taxonomies;

		$html = $message = $errors = '';


		if(isset($wp_taxonomies[$_POST['taxonomy']])){
			$object_types = $wp_taxonomies[$_POST['taxonomy']]->object_type;

			foreach($object_types as $object_type){

				$html .= self::render_assignment_status($object_type, $_POST['taxonomy'], $preview = true);

			}

		}else{
			$errors = sprintf(__('Invalid taxonomy %s', 'sitepress'), $_POST['taxonomy']);
		}


		echo json_encode(array('html' => $html, 'message'=> $message, 'errors' => $errors));
		exit;


	}

	public static function sync_taxonomies_in_content(){
		global $wp_taxonomies;

		$html = $message = $errors = '';

		if(isset($wp_taxonomies[$_POST['taxonomy']])){
			$html .= self::render_assignment_status($_POST['post'], $_POST['taxonomy'], $preview = false);

		}else{
			$errors .= sprintf(__('Invalid taxonomy %s', 'sitepress'), $_POST['taxonomy']);
		}


		echo json_encode(array('html' => $html, 'errors' => $errors));
		exit;


	}


	public static function render_assignment_status($object_type, $taxonomy, $preview = true){
		global $sitepress, $wp_post_types, $wp_taxonomies,$wpdb;

		$default_language = $sitepress->get_default_language();
		$posts            = get_posts( array( 'post_type' => $object_type, 'suppress_filters' => false, 'posts_per_page' => -1  ) );

		foreach($posts as $post){

			$terms = wp_get_post_terms($post->ID, $taxonomy);

			$term_ids = array();
			foreach($terms as $term){
				$term_ids[] = $term->term_id;
			}

			$trid = $sitepress->get_element_trid($post->ID, 'post_' . $post->post_type);
			$translations = $sitepress->get_element_translations($trid, 'post_' . $post->post_type, true, true);

			foreach($translations as $language => $translation){

				if($language != $default_language && $translation->element_id){

					$terms_of_translation =  wp_get_post_terms($translation->element_id, $taxonomy);

					$translation_term_ids = array();
					foreach($terms_of_translation as $term){

						$term_id_original = icl_object_id($term->term_id, $taxonomy, false, $default_language );
						if(!$term_id_original || !in_array($term_id_original, $term_ids)){
							// remove term

							if($preview){
								$needs_sync = true;
								break(3);
							}

							$current_terms = wp_get_post_terms($translation->element_id, $taxonomy);
							$updated_terms = array();
							foreach($current_terms as $cterm){
								if($cterm->term_id != $term->term_id){
									$updated_terms[] = is_taxonomy_hierarchical($taxonomy) ? $term->term_id : $term->name;
								}
								if(!$preview){
									wp_set_post_terms($translation->element_id, $updated_terms, $taxonomy);
								}

							}


						}else{
							$translation_term_ids[] = $term_id_original;
						}

					}

					foreach($term_ids as $term_id){

						if(!in_array($term_id, $translation_term_ids)){
							// add term

							if($preview){
								$needs_sync = true;
								break(3);
							}
							$terms_array = array();
							$term_id_translated = icl_object_id($term_id, $taxonomy, false, $language);

							// not using get_term
							$translated_term = $wpdb->get_row($wpdb->prepare("
                            SELECT * FROM {$wpdb->terms} t JOIN {$wpdb->term_taxonomy} x ON x.term_id = t.term_id WHERE t.term_id = %d AND x.taxonomy = %s", $term_id_translated, $taxonomy));

							if(is_taxonomy_hierarchical($taxonomy)){
								$terms_array[] = $translated_term->term_id;
							} else {
								$terms_array[] = $translated_term->name;
							}

							if(!$preview){
								wp_set_post_terms($translation->element_id, $terms_array, $taxonomy, true);
							}

						}

					}

				}


			}


		}

		$out = '';


		if($preview){

			$out .= '<div class="icl_tt_sync_row">';
			if(!empty($needs_sync)){
				$out .= '<form class="icl_tt_do_sync">';
				$out .= '<input type="hidden" name="post" value="' . $object_type . '" />';
				$out .= '<input type="hidden" name="taxonomy" value="' . $taxonomy . '" />';
				$out .= sprintf(__('Some translated %s have different %s assignments.', 'sitepress'),
					'<strong>' . mb_strtolower($wp_post_types[$object_type]->labels->name) . '</strong>',
					'<strong>' . mb_strtolower($wp_taxonomies[$taxonomy]->labels->name) . '</strong>');
				$out .= '&nbsp;<a class="submit button-secondary" href="#">' . sprintf(__('Update %s for all translated %s', 'sitepress'),
						'<strong>' . mb_strtolower($wp_taxonomies[$taxonomy]->labels->name) . '</strong>',
						'<strong>' . mb_strtolower($wp_post_types[$object_type]->labels->name) . '</strong>') . '</a>' .
					'&nbsp;<img src="'. ICL_PLUGIN_URL . '/res/img/ajax-loader.gif" alt="loading" height="16" width="16" class="wpml_tt_spinner" />';
				$out .= "</form>";
			}else{
				$out .= sprintf(__('All %s have the same %s assignments.', 'sitepress'),
					'<strong>' . mb_strtolower($wp_taxonomies[$taxonomy]->labels->name) . '</strong>',
					'<strong>' . mb_strtolower($wp_post_types[$object_type]->labels->name) . '</strong>');
			}
			$out .= "</div>";

		}else{

			$out .= sprintf(__('Successfully updated %s for all translated %s.', 'sitepress'), $wp_taxonomies[$taxonomy]->labels->name, $wp_post_types[$object_type]->labels->name);

		}

		return $out;

	}

	public static function render_parent_taxonomies_dropdown($taxonomy, $child_of = 0){
		$args = array(
			'name'              => 'child_of',
			'selected'          => $child_of,
			'hierarchical'      => 1,
			'taxonomy'          => $taxonomy,
			'show_option_none'  => '--- ' . __('select parent', 'sitepress') . ' ---',
			'hide_empty'        => 0,
		);

//        $categories = get_categories($args);
//        $max_depth = 0;
//
//        foreach($categories as $category){
//            $this_depth = 0;
//            while($category->category_parent > 0){
//                foreach($categories as $category2){
//                    if($category2->term_id == $category->category_parent){
//                        $category = $category2;
//                        break;
//                    }
//                }
//                $this_depth++;
//            }
//            if($this_depth > $max_depth){
//                $max_depth = $this_depth;
//            }
//        }
//
//        $args['depth'] = $max_depth;

		wp_dropdown_categories($args);
	}
}


add_action('wp_ajax_wpml_tt_show_terms', array('WPML_Taxonomy_Translation', 'show_terms'));

add_action('wp_ajax_wpml_tt_save_term_translation', array('WPML_Taxonomy_Translation', 'save_term_translation'));
add_action('wp_ajax_wpml_tt_save_labels_translation', array('WPML_Taxonomy_Translation', 'save_labels_translation'));

add_action('wp_ajax_wpml_tt_sync_taxonomies_in_content_preview', array('WPML_Taxonomy_Translation', 'sync_taxonomies_in_content_preview'));
add_action('wp_ajax_wpml_tt_sync_taxonomies_in_content', array('WPML_Taxonomy_Translation', 'sync_taxonomies_in_content'));
