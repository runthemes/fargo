<?php
/**
 * Pro customizer section.
 *
 * @since  1.0.0
 * @access public
 */
class Fargo_Customize_Section_Recommend extends WP_Customize_Section {
	/**
	 * The type of customize section being rendered.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $type = 'fargo-recomended-section';
	/**
	 * Custom button text to output.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string
	 */
	public $required_actions = '';
	public $recommended_plugins = '';
	public $total_actions = '';
	public $social_text = '';
	public $plugin_text = '';
	public $facebook = '';
	public $twitter = '';
	public $wp_review = false;

	public function check_active( $slug ) {
		$plugin_path = MT_Notify_System::_get_plugin_basename_from_slug( $slug );
		if ( file_exists( ABSPATH . 'wp-content/plugins/' . $plugin_path ) ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			$needs = is_plugin_active( $plugin_path ) ? 'deactivate' : 'activate';

			return array( 'status' => is_plugin_active( $plugin_path ), 'needs' => $needs, 'plugin_path' => $plugin_path );
		}

		return array( 'status' => false, 'needs' => 'install' );
	}

	public function create_action_link( $state, $slug, $plugin_path = '' ) {
		if ( $plugin_path == '' ) {
			$plugin_path = $slug . '/' . $slug . '.php';
		}
		switch ( $state ) {
			case 'install':
				return wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'install-plugin',
							'plugin' => $slug
						),
						network_admin_url( 'update.php' )
					),
					'install-plugin_' . $slug
				);
				break;
			case 'deactivate':
				return add_query_arg( array(
					                      'action'        => 'deactivate',
					                      'plugin'        => rawurlencode( $plugin_path ),
					                      'plugin_status' => 'all',
					                      'paged'         => '1',
					                      '_wpnonce'      => wp_create_nonce( 'deactivate-plugin_' . $plugin_path ),
				                      ), network_admin_url( 'plugins.php' ) );
				break;
			case 'activate':
				return add_query_arg( array(
					                      'action'        => 'activate',
					                      'plugin'        => rawurlencode( $plugin_path ),
					                      'plugin_status' => 'all',
					                      'paged'         => '1',
					                      '_wpnonce'      => wp_create_nonce( 'activate-plugin_' . $plugin_path ),
				                      ), network_admin_url( 'plugins.php' ) );
				break;
			case 'update':
				return wp_nonce_url(
					add_query_arg(
						array(
							'action' => 'upgrade-plugin',
							'plugin' => rawurlencode( $plugin_path )
						),
						network_admin_url( 'update.php' )
					),
					'upgrade-plugin_' . $plugin_path
				);
				break;
		}
	}

	public function call_plugin_api( $slug ) {
		include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

		if ( false === ( $call_api = get_transient( 'fargo_plugin_information_transient_' . $slug ) ) ) {
			$call_api = plugins_api( 'plugin_information', array(
				'slug'   => $slug,
				'fields' => array(
					'downloaded'        => false,
					'rating'            => false,
					'description'       => false,
					'short_description' => true,
					'donate_link'       => false,
					'tags'              => false,
					'sections'          => true,
					'homepage'          => true,
					'added'             => false,
					'last_updated'      => false,
					'compatibility'     => false,
					'tested'            => false,
					'requires'          => false,
					'downloadlink'      => false,
					'icons'             => true
				)
			) );
			set_transient( 'fargo_plugin_information_transient_' . $slug, $call_api, 30 * MINUTE_IN_SECONDS );
		}

		return $call_api;
	}

	/**
	 * Add custom parameters to pass to the JS via JSON.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function json() {
		$json = parent::json();
		global $fargo_required_actions, $fargo_recommended_plugins;
		$formatted_array = array();
		$fargo_show_required_actions = get_option( "fargo_show_required_actions" );
		foreach ( $fargo_required_actions as $key => $fargo_required_action ) {
			if ( @$fargo_show_required_actions[ $fargo_required_action['id'] ] === false ) {
				continue;
			}
			if ( $fargo_required_action['check'] ) {
				continue;
			}

			$fargo_required_action['index'] = $key + 1;

			if ( isset($fargo_required_action['type']) && $fargo_required_action['type'] == 'plugin' ) {
				$active = $this->check_active( $fargo_required_action['plugin_slug'] );
				if ( !isset($active['plugin_path']) ) {
					$active['plugin_path'] = '';
				}

				if ( $active['needs'] == 'deactivate' && !MT_Notify_System::check_plugin_update( $fargo_required_action['plugin_slug'] ) ) {
					$active['needs'] = 'update';
				}

				$fargo_required_action['url']    = $this->create_action_link( $active['needs'], $fargo_required_action['plugin_slug'], $active['plugin_path'] );
				if ( $active['needs'] !== 'install' && $active['status'] ) {
					$fargo_required_action['class'] = 'active';
				}else{
					$fargo_required_action['class'] = '';
				}

				switch ( $active['needs'] ) {
					case 'install':
						$fargo_required_action['button_class'] = 'install-now button';
						$fargo_required_action['button_label'] = __( 'Install', 'fargo' );
						break;
					case 'activate':
						$fargo_required_action['button_class'] = 'activate-now button button-primary';
						$fargo_required_action['button_label'] = __( 'Activate', 'fargo' );
						break;
					case 'update':
						$fargo_required_action['button_class'] = 'update-now button button-primary';
						$fargo_required_action['button_label'] = __( 'Update', 'fargo' );
						break;
					case 'deactivate':
						$fargo_required_action['button_class'] = 'deactivate-now button';
						$fargo_required_action['button_label'] = __( 'Deactivate', 'fargo' );
						break;
				}

				$fargo_required_action['path'] = $active['plugin_path'];

			}
			$formatted_array[] = $fargo_required_action;
		}

		$customize_plugins = array();
		$fargo_show_recommended_plugins = get_option( "fargo_show_recommended_plugins" );
		foreach ( $fargo_recommended_plugins as $slug => $plugin_opt ) {
			
			if ( !$plugin_opt['recommended'] ) {
				continue;
			}

			if ( MT_Notify_System::has_import_plugin( $slug ) ) {
				continue;
			}

			if ( isset($fargo_show_recommended_plugins[$slug]) && $fargo_show_recommended_plugins[$slug] ) {
				continue;
			}

			$active = $this->check_active( $slug );
			$fargo_recommended_plugin['url']    = $this->create_action_link( $active['needs'], $slug );
			if ( $active['needs'] !== 'install' && $active['status'] ) {
				$fargo_recommended_plugin['class'] = 'active';
			}else{
				$fargo_recommended_plugin['class'] = '';
			}

			switch ( $active['needs'] ) {
				case 'install':
					$fargo_recommended_plugin['button_class'] = 'install-now button';
					$fargo_recommended_plugin['button_label'] = __( 'Install', 'fargo' );
					break;
				case 'activate':
					$fargo_recommended_plugin['button_class'] = 'activate-now button button-primary';
					$fargo_recommended_plugin['button_label'] = __( 'Activate', 'fargo' );
					break;
				case 'deactivate':
					$fargo_recommended_plugin['button_class'] = 'deactivate-now button';
					$fargo_recommended_plugin['button_label'] = __( 'Deactivate', 'fargo' );
					break;
			}
			$info   = $this->call_plugin_api( $slug );
			$fargo_recommended_plugin['id'] = $slug;
			$fargo_recommended_plugin['path'] = $active['plugin_path'];
			$fargo_recommended_plugin['plugin_slug'] = $slug;
			$fargo_recommended_plugin['description'] = $info->short_description;
			$fargo_recommended_plugin['title'] = $fargo_recommended_plugin['button_label'].': '.$info->name;

			$customize_plugins[] = $fargo_recommended_plugin;

		}

		$json['required_actions'] = $formatted_array;
		$json['recommended_plugins'] = $customize_plugins;
		$json['total_actions'] = count($fargo_required_actions);
		$json['social_text'] = $this->social_text;
		$json['plugin_text'] = $this->plugin_text;
		$json['facebook'] = $this->facebook;
		$json['facebook_text'] = __( 'Facebook', 'fargo' );
		$json['twitter'] = $this->twitter;
		$json['twitter_text'] = __( 'Twitter', 'fargo' );
		$json['wp_review'] = $this->wp_review;
		$json['wp_review_text'] = __( 'Review this theme on w.org', 'fargo' );
		if ( $this->wp_review ) {
			$json['theme_slug'] = get_template();
		}
		return $json;

	}
	/**
	 * Outputs the Underscore.js template.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	protected function render_template() { ?>

		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">

			<h3 class="accordion-section-title">
				<span class="section-title" data-social="{{ data.social_text }}" data-plugin_text="{{ data.plugin_text }}">
					<# if( data.required_actions.length > 0 ){ #>
						{{ data.title }}
					<# }else{ #>
						<# if( data.recommended_plugins.length > 0 ){ #>
							{{ data.plugin_text }}
						<# }else{ #>
							{{ data.social_text }}
						<# } #>
					<# } #>
				</span>
				<# if( data.required_actions.length > 0 ){ #>
					<span class="fargo-actions-count">
						<span class="current-index">{{ data.required_actions[0].index }}</span>
						/
						{{ data.total_actions }}
					</span>
				<# } #>
			</h3>
			<div class="recomended-actions_container" id="plugin-filter">
				<# if( data.required_actions.length > 0 ){ #>
					<# for (action in data.required_actions) { #>
						<div class="epsilon-recommeded-actions-container" data-index="{{ data.required_actions[action].index }}">
							<# if( !data.required_actions[action].check ){ #>
								<div class="epsilon-recommeded-actions">
									<p class="title">{{ data.required_actions[action].title }}</p>
									<span data-action="dismiss" class="dashicons dashicons-visibility fargo-dismiss-required-action" id="{{ data.required_actions[action].id }}"></span>
									<div class="description">{{{ data.required_actions[action].description }}}</div>
									<# if( data.required_actions[action].plugin_slug ){ #>
										<div class="custom-action">
											<p class="plugin-card-{{ data.required_actions[action].plugin_slug }} action_button {{ data.required_actions[action].class }}">
												<a  data-slug="{{ data.required_actions[action].plugin_slug }}"
													data-plugin="{{ data.required_actions[action].path }}"
												   	class="{{ data.required_actions[action].button_class }}"
												   	href="{{ data.required_actions[action].url }}">{{ data.required_actions[action].button_label }}</a>
											</p>
										</div>
									<# } #>
									<# if( data.required_actions[action].help ){ #>
										<div class="custom-action">{{{ data.required_actions[action].help }}}</div>
									<# } #>
								</div>
							<# } #>
						</div>
					<# } #>
				<# } #>

				<# if( data.recommended_plugins.length > 0 ){ #>
					<# for (action in data.recommended_plugins) { #>
						<div class="epsilon-recommeded-actions-container epsilon-recommended-plugins" data-index="{{ data.recommended_plugins[action].index }}">
							<# if( !data.recommended_plugins[action].check ){ #>
								<div class="epsilon-recommeded-actions">
									<p class="title">{{ data.recommended_plugins[action].title }}</p>
									<span data-action="dismiss" class="dashicons dashicons-visibility fargo-recommended-plugin-button" id="{{ data.recommended_plugins[action].id }}"></span>
									<div class="description">{{{ data.recommended_plugins[action].description }}}</div>
									<# if( data.recommended_plugins[action].plugin_slug ){ #>
										<div class="custom-action">
											<p class="plugin-card-{{ data.recommended_plugins[action].plugin_slug }} action_button {{ data.recommended_plugins[action].class }}">
												<a data-slug="{{ data.recommended_plugins[action].plugin_slug }}"
												   class="{{ data.recommended_plugins[action].button_class }}"
												   href="{{ data.recommended_plugins[action].url }}">{{ data.recommended_plugins[action].button_label }}</a>
											</p>
										</div>
									<# } #>
									<# if( data.recommended_plugins[action].help ){ #>
										<div class="custom-action">{{{ data.recommended_plugins[action].help }}}</div>
									<# } #>
								</div>
							<# } #>
						</div>
					<# } #>
				<# } #>

				<# if( data.required_actions.length == 0 && data.recommended_plugins.length == 0 ){ #>
					<p class="succes">
						<# if( data.facebook ){ #>
							<a href="{{ data.facebook }}" class="button social"><span class="dashicons dashicons-facebook-alt"></span>{{ data.facebook_text }}</a>
						<# } #>

						<# if( data.twitter ){ #>
							<a href="{{ data.twitter }}" class="button social"><span class="dashicons dashicons-twitter"></span>{{ data.twitter_text }}</a>
						<# } #>
						<# if( data.wp_review ){ #>
							<a href="https://wordpress.org/support/theme/{{ data.theme_slug }}/reviews/#new-post" class="button button-primary fargo-wordpress"><span class="dashicons dashicons-wordpress"></span>{{ data.wp_review_text }}</a>
						<# } #>
					</p>
				<# }else{ #>
					<p class="succes hide">
						<# if( data.facebook ){ #>
							<a href="{{ data.facebook }}" class="button social"><span class="dashicons dashicons-facebook-alt"></span>{{ data.facebook_text }}</a>
						<# } #>

						<# if( data.twitter ){ #>
							<a href="{{ data.twitter }}" class="button social"><span class="dashicons dashicons-twitter"></span>{{ data.twitter_text }}</a>
						<# } #>
						<# if( data.wp_review ){ #>
							<a href="https://wordpress.org/support/theme/{{ data.theme_slug }}/reviews/#new-post" class="button button-primary fargo-wordpress"><span class="dashicons dashicons-wordpress"></span>{{ data.wp_review_text }}</a>
						<# } #>
					</p>
				<# } #>
			</div>
		</li>
	<?php }
}