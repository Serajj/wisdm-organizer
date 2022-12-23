<?php

/**
 * Wdm_Event_Registrations_Form_Editor class.
 */
class Wsdm_Custom_Field_Editor
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		add_action('admin_menu', array($this, 'admin_menu'));
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
		add_action('wp_ajax_get_group_field_html', array($this, 'get_group_field_html'));
	}

	/**
	 * get_on_demand_label_size function.
	 *
	 * @access public
	 * @param
	 * @return
	 * @since 1.0.0
	 */
	public function get_group_field_html()
	{
		check_ajax_referer('_nonce_Wdm_Event_form_editor_security', 'security');

		$field_types = apply_filters(
			'event_manager_form_group_field_types',
			array(
				'text'        => __('Text', 'wdm-event'),
				'time'        => __('Time', 'wdm-event'),
				'checkbox'    => __('Checkbox', 'wdm-event'),
				'date'        => __('Date', 'wdm-event'),
				'timezone'    => __('Timezone', 'wdm-event'),
				'file'        => __('File', 'wdm-event'),
				'hidden'      => __('Hidden', 'wdm-event'),
				'multiselect' => __('Multiselect', 'wdm-event'),
				'number'      => __('Number', 'wdm-event'),
				'password'    => __('Password', 'wdm-event'),
				'radio'       => __('Radio', 'wdm-event'),
				'select'      => __('Select', 'wdm-event'),
				'textarea'    => __('Textarea', 'wdm-event'),
			)
		);

		ob_start();
		$child_index     = -1;
		$child_field_key = '';
		$child_field     = array(
			'type'        => 'text',
			'label'       => '',
			'placeholder' => '',
		);
		include 'wdm-event-form-field-editor-group-field-row.php';
		echo esc_attr(ob_get_clean());

		wp_die();
	}

	/**
	 * Add form editor menu item
	 */
	public function admin_menu()
	{
		add_submenu_page('edit.php?post_type=event_listing', __('Field Editor', 'wdm-event'), __('Field Editor', 'wdm-event'), 'manage_options', 'event-manager-form-editor', array($this, 'output'));
	}

	/**
	 * Register scripts
	 */
	public function admin_enqueue_scripts()
	{
		wp_register_script('chosen', EVENT_MANAGER_PLUGIN_URL . '/assets/js/jquery-chosen/chosen.jquery.min.js', array('jquery'), '1.1.0', true);
		wp_register_script('wdm-event-form-field-editor', EVENT_MANAGER_PLUGIN_URL . '/assets/js/field-editor.min.js', array('jquery', 'jquery-ui-sortable', 'chosen'), EVENT_MANAGER_VERSION, true);
		wp_localize_script(
			'wdm-event-form-field-editor',
			'Wdm_Event_form_editor',
			array(
				'cofirm_delete_i18n'                    => __('Are you sure you want to delete this row?', 'wdm-event'),
				'cofirm_reset_i18n'                     => __('Are you sure you want to reset your changes? This cannot be undone.', 'wdm-event'),
				'ajax_url'                              => admin_url('admin-ajax.php'),
				'Wdm_Event_form_editor_security' => wp_create_nonce('_nonce_Wdm_Event_form_editor_security'),
			)
		);
	}

	/**
	 * Output the screen
	 */
	public function output()
	{
		wp_enqueue_style('chosen', EVENT_MANAGER_PLUGIN_URL . '/assets/css/chosen.min.css');
		wp_enqueue_script('wdm-event-form-field-editor'); ?>
		<div class="wrap wdm-event-registrations-form-editor">
			<h1 class="wp-heading-inline"><?php esc_attr_e('Form fields'); ?></h1>
			<div class="wpem-wrap wdm-event-form-field-editor">
				<form method="post" id="mainform" action="edit.php?post_type=event_listing&amp;page=event-manager-form-editor">
					<?php $this->form_editor(); ?>
					<?php wp_nonce_field('save-wdm-event-form-field-editor'); ?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Output the fronted form editor
	 */
	private function form_editor()
	{

		if (!empty($_GET['event-reset-fields']) && !empty($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'reset')) {
			delete_option('event_manager_submit_event_form_fields');
			echo wp_kses_post('<div class="updated"><p>' . esc_attr('The fields were successfully reset.', 'wdm-event') . '</p></div>');
		}

		if (!empty($_GET['organizer-reset-fields']) && !empty($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'reset')) {
			delete_option('event_manager_submit_organizer_form_fields');
			echo wp_kses_post('<div class="updated"><p>' . esc_attr('The fields were successfully reset.', 'wdm-event') . '</p></div>');
		}

		if (!empty($_GET['venue-reset-fields']) && !empty($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'reset')) {
			delete_option('event_manager_submit_venue_form_fields');
			echo wp_kses_post('<div class="updated"><p>' . esc_attr('The fields were successfully reset.', 'wdm-event') . '</p></div>');
		}

		if (!empty($_POST) && !empty($_POST['_wpnonce'])) {
			echo wp_kses_post($this->form_editor_save());
		}

		$disbled_fields = apply_filters('wpem_admin_field_editor_disabled_fields', array('event_title', 'event_description', 'organizer_name', 'organizer_description', 'venue_name', 'venue_description'));
		$field_types    = apply_filters(
			'event_manager_form_field_types',
			array(
				'text'             => __('Text', 'wdm-event'),
				'time'             => __('Time', 'wdm-event'),
				'checkbox'         => __('Checkbox', 'wdm-event'),
				'date'             => __('Date', 'wdm-event'),
				'timezone'         => __('Timezone', 'wdm-event'),
				'file'             => __('File', 'wdm-event'),
				'hidden'           => __('Hidden', 'wdm-event'),
				'multiselect'      => __('Multiselect', 'wdm-event'),
				'number'           => __('Number', 'wdm-event'),               /*'password'       		=> __( 'Password', 'wdm-event' ),*/
				'radio'            => __('Radio', 'wdm-event'),
				'repeated'         => __('Repeated', 'wdm-event'),
				'select'           => __('Select', 'wdm-event'),
				'term-checklist'   => __('Term Checklist', 'wdm-event'),
				'term-multiselect' => __('Term Multiselect', 'wdm-event'),
				'term-select'      => __('Term Select', 'wdm-event'),
				'textarea'         => __('Textarea', 'wdm-event'),
				'wp-editor'        => __('WP Editor', 'wdm-event'),
				'url'              => __('URL', 'wdm-event'),          /*'group'       			=> __( 'Group', 'wdm-event' ),	*/
			)
		);

		$GLOBALS['wdm_event']->forms->get_form('submit-event', array());
		$form_submit_event_instance = call_user_func(array('Wdm_Event_Form_Submit_Event', 'instance'));
		$event_fields               = $form_submit_event_instance->merge_with_custom_fields('backend');

		if (get_option('enable_event_organizer')) {
			$GLOBALS['wdm_event']->forms->get_form('submit-organizer', array());
			$form_submit_organizer_instance = call_user_func(array('Wdm_Event_Form_Submit_organizer', 'instance'));
			$organizer_fields               = $form_submit_organizer_instance->merge_with_custom_fields('backend');
		} else {
			$organizer_fields = array();
		}

		if (get_option('enable_event_venue')) {
			$GLOBALS['wdm_event']->forms->get_form('submit-venue', array());
			$form_submit_venue_instance = call_user_func(array('Wdm_Event_Form_Submit_venue', 'instance'));
			$venue_fields               = $form_submit_venue_instance->merge_with_custom_fields('backend');
		} else {
			$venue_fields = array();
		}

		$fields = array_merge($event_fields, $organizer_fields, $venue_fields);

		foreach ($fields  as $group_key => $group_fields) {
			if (empty($group_fields)) {
				continue;
			} ?>

			<div class="wdm-event-event-form-field-editor">
				<h3><?php printf(esc_attr__('%s form fields', 'wdm-event'), ucfirst($group_key)); ?></h3>
				<table class="widefat">
					<thead>
						<tr>
							<th width="1%">&nbsp;</th>
							<th><?php esc_attr_e('Field Label', 'wdm-event'); ?></th>
							<th width="1%"><?php esc_attr_e('Type', 'wdm-event'); ?></th>
							<th><?php esc_attr_e('Description', 'wdm-event'); ?></th>
							<th><?php esc_attr_e('Placeholder / Options', 'wdm-event'); ?></th>
							<th width="1%"><?php esc_attr_e('Meta Key', 'wdm-event'); ?></th>
							<th width="1%"><?php esc_attr_e('Only For Admin', 'wdm-event'); ?></th>
							<th width="1%"><?php esc_attr_e('Priority', 'wdm-event'); ?></th>
							<th width="1%"><?php esc_attr_e('Validation', 'wdm-event'); ?></th>
							<th width="1%" class="field-actions">&nbsp;</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th colspan="4">
								<a class="button add-field" href="#"><?php esc_attr_e('Add field', 'wdm-event'); ?></a>
							</th>

							<th colspan="6" class="save-actions">
								<a href="<?php echo wp_nonce_url(add_query_arg($group_key . '-reset-fields', 1), 'reset'); ?>" class="reset"><?php esc_attr_e('Reset to default', 'wdm-event'); ?></a>
								<input type="submit" class="save-fields button-primary" value="<?php esc_attr_e('Save Changes', 'wdm-event'); ?>" />
							</th>

						</tr>
					</tfoot>
					<tbody id="form-fields" data-field="
						<?php
						ob_start();
						$index     = -1;
						$field_key = '';
						$field     = array(
							'type'        => 'text',
							'label'       => '',
							'placeholder' => '',
						);
						include 'wdm-event-form-field-editor-field.php';
						echo esc_attr(ob_get_clean());
						
						if (isset($group_fields) && !empty($group_fields)) {
							foreach ($group_fields as $field_key => $field) {
								$index++;

								include 'wdm-event-form-field-editor-field.php';
							}
						}
						?>												
					</tbody>
				</table>
			</div>
			<?php
		}
	}

	/**
	 * Save the form fields
	 */
	private function child_form_editor_save($field)
	{

		$index = 0;

		$child_fields = array();

		foreach ($field['fields'] as $field_key => $field_value) {
			echo $field_key;
			$index++;
			echo $index;
			$field['fields'][$field_key]['priority'] = $index;

			$field['fields'][$field_key]['label'] = trim($field_value['label']);

			if (isset($field_value['type']) && !in_array($field_value['type'], array('term-select', 'term-multiselect', 'term-checklist'))) {
				unset($field['fields'][$field_key]['taxonomy']);
			}

			if (isset($field_value['type']) && $field_value['type'] == 'select' || $field_value['type'] == 'radio' || $field_value['type'] == 'multiselect' || $field_value['type'] == 'button-options') {
				if (isset($field_value['options']) && !empty($field_value['options'])) {
					$field_value['options'] = explode('|', $field_value['options']);

					$temp_options = array();

					foreach ($field_value['options'] as $val) {
						$option_key = explode(':', $val);

						if (isset($option_key[1])) {
							$temp_options[strtolower(str_replace(' ', '_', trim($option_key[0])))] = trim($option_key[1]);
						} else {
							$temp_options[strtolower(str_replace(' ', '_', trim($option_key[0])))] = trim($option_key[0]);
						}
					}

					$field['fields'][$field_key]['options'] = $temp_options;
				}
			} else {
				unset($field['fields'][$field_key]['options']);
			}

			if (!is_int($field_key)) {
				continue;
			}

			if (isset($field_value['label'])) {
				$label_key = str_replace(' ', '_', $field_value['label']);

				$field['fields'][strtolower($label_key)] = $field['fields'][$field_key];
			}

			unset($field['fields'][$field_key]);
		}

		return $field['fields'];
	}

	/**
	 * Save the form fields
	 */
	private function form_editor_save()
	{
		if (wp_verify_nonce($_POST['_wpnonce'], 'save-wdm-event-form-field-editor')) {

			$event_field     = !empty($_POST['event']) ? $this->sanitize_array($_POST['event']) : array();
			$event_organizer = !empty($_POST['organizer']) ? $this->sanitize_array($_POST['organizer']) : array();
			$event_venue     = !empty($_POST['venue']) ? $this->sanitize_array($_POST['venue']) : array();
			$index           = 0;

			if (!empty($event_field)) {
				$new_fields = array(
					'event'     => $event_field,
					'organizer' => $event_organizer,
					'venue'     => $event_venue,
				);
				// find the numers keys from the fields array and replace with lable if label not exist remove that field
				foreach ($new_fields as $group_key => $group_fields) {
					$index = 0;
					foreach ($group_fields as $field_key => $field_value) {
						$index++;

						if (isset($new_fields[$group_key][$field_key]['type']) && $new_fields[$group_key][$field_key]['type'] === 'group') {
							if (isset($field_value['fields']) && !empty($field_value['fields'])) {
								$child_fields                                     = $this->child_form_editor_save($field_value);
								$new_fields[$group_key][$field_key]['fields'] = $child_fields;
							}
						}

						$new_fields[$group_key][$field_key]['priority'] = $index;

						$new_fields[$group_key][$field_key]['label'] = trim($new_fields[$group_key][$field_key]['label']);

						if (isset($new_fields[$group_key][$field_key]['type']) && !in_array($new_fields[$group_key][$field_key]['type'], array('term-select', 'term-multiselect', 'term-checklist'))) {
							unset($new_fields[$group_key][$field_key]['taxonomy']);
						}

						if (isset($new_fields[$group_key][$field_key]['type']) && ($new_fields[$group_key][$field_key]['type'] == 'select' || $new_fields[$group_key][$field_key]['type'] == 'radio' || $new_fields[$group_key][$field_key]['type'] == 'multiselect' || $new_fields[$group_key][$field_key]['type'] == 'button-options')) {
							if (isset($new_fields[$group_key][$field_key]['options'])) {
								$new_fields[$group_key][$field_key]['options'] = explode('|', $new_fields[$group_key][$field_key]['options']);

								$temp_options = array();

								foreach ($new_fields[$group_key][$field_key]['options'] as $val) {
									$option_key = explode(':', $val);

									if (isset($option_key[1])) {
										$temp_options[strtolower(str_replace(' ', '_', trim($option_key[0])))] = trim($option_key[1]);
									} else {
										$temp_options[strtolower(str_replace(' ', '_', trim($option_key[0])))] = trim($option_key[0]);
									}
								}

								$new_fields[$group_key][$field_key]['options'] = $temp_options;
							}
						} else {
							unset($new_fields[$group_key][$field_key]['options']);
						}

						if (!is_int($field_key)) {
							continue;
						}

						if (isset($new_fields[$group_key][$field_key]['label'])) {
							$label_key = str_replace(' ', '_', $new_fields[$group_key][$field_key]['label']);
							$new_fields[$group_key][strtolower($label_key)] = $new_fields[$group_key][$field_key];
						}

						unset($new_fields[$group_key][$field_key]);
					}
				}

				// merge field with default fields
				$GLOBALS['wdm_event']->forms->get_form('submit-event', array());
				$form_submit_event_instance = call_user_func(array('Wdm_Event_Form_Submit_Event', 'instance'));
				// $event_fields =   $form_submit_event_instance->get_default_fields('backend');
				$event_fields = $form_submit_event_instance->get_default_event_fields();

				if (get_option('enable_event_organizer')) {
					$GLOBALS['wdm_event']->forms->get_form('submit-organizer', array());
					$form_submit_organizer_instance = call_user_func(array('Wdm_Event_Form_Submit_organizer', 'instance'));
					$organizer_fields               = $form_submit_organizer_instance->init_fields();
				} else {
					$organizer_fields = array();
				}

				if (get_option('enable_event_venue')) {
					$GLOBALS['wdm_event']->forms->get_form('submit-venue', array());
					$form_submit_venue_instance = call_user_func(array('Wdm_Event_Form_Submit_venue', 'instance'));
					$venue_fields               = $form_submit_venue_instance->init_fields();
				} else {
					$venue_fields = array();
				}

				$default_fields = array_merge($event_fields, $organizer_fields, $venue_fields);

				// if field in not exist in new fields array then make visiblity false
				if (!empty($default_fields)) {
					foreach ($default_fields as $group_key => $group_fields) {
						foreach ($group_fields as $key => $field) {
							if (!isset($new_fields[$group_key][$key])) {
								$new_fields[$group_key][$key]               = $field;
								$new_fields[$group_key][$key]['visibility'] = 0; // it will make visiblity false means removed from the field editor.
							}
						}
					}
				}

				if (isset($new_fields['event'])) {
					update_option('event_manager_submit_event_form_fields', array('event' => $new_fields['event']));
				}

				if (isset($new_fields['organizer'])) {
					update_option('event_manager_submit_organizer_form_fields', array('organizer' => $new_fields['organizer']));
				}

				if (isset($new_fields['venue'])) {
					update_option('event_manager_submit_venue_form_fields', array('venue' => $new_fields['venue']));
				}

				// this will be removed in future
				$result = update_option('event_manager_form_fields', $new_fields);
			}
		}

		echo wp_kses_post('<div class="updated"><p>' . esc_attr__('The fields were successfully saved.', 'wdm-event') . '</p></div>');
	}

	/**
	 * Sanitize a 2d array
	 *
	 * @param  array $array
	 * @return array
	 */
	private function sanitize_array($input)
	{
		if (is_array($input)) {
			foreach ($input as $k => $v) {
				$input[$k] = $this->sanitize_array($v);
			}
			return $input;
		} else {
			return sanitize_text_field($input);
		}
	}
}

new Wdm_Event_Field_Editor();
