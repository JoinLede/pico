<?php

	class Pico_API_Controller {

		// Here initialize our namespace and resource name.
		public function __construct() {
			$this->namespace     = 'pico/v1';
			$this->health_resource_name = 'check';
		}

		/**
		 * Register routes with callbacks and schema
		 */
		public function register_routes() {
			register_rest_route( $this->namespace, '/' . $this->health_resource_name, array(
                'methods'   => 'POST',
                'callback'  => array( $this, 'health_callback' ),
                'args' => self::validate_health_arguments(),
                'permission_callback' => '__return_true',
			));
		}

		/**
		 * Passes the params to make the request
		 * @param WP_REST_Request $request Current request.
		 */
		public function health_callback( $request ) {
			$publisher_id = sanitize_key($request->get_param('publisher_id'));
			$publisher_key = sanitize_key($request->get_param('key'));
			$stored_data = Pico_Setup::get_publisher_id(true);
            global $wp_version;
            $active_plugins = get_option('active_plugins');
            $list_of_plugins = [];
            foreach ($active_plugins as $key => $value) {
                array_push($list_of_plugins, explode('/', $value)[0]);
            }

            $response = array(
                "endpoints" => array(
                    "api" => Pico_Setup::get_api_endpoint(),
                    "widget" => Pico_Setup::get_widget_endpoint(),
                    "rest" => esc_url_raw(rest_url())
                ),
                "versions" => array(
                    "plugin" => PICO_VERSION,
                    "widget" => Pico_Setup::get_widget_version_info(),
                    "wp" => $wp_version,
                ),
                "active_plugins" => $list_of_plugins,
                "taxonomies" => Pico_Widget::get_list_of_taxonomies(),
                "url" => get_home_url()
            );

			if (
				$publisher_id === $stored_data['publisher_id'] &&
				$publisher_key === $stored_data['api_key']
			) {
                $response['connected'] = true;
			} else {

                $response['connected'] = false;
            }

            return rest_ensure_response($response);
		}

		/**
		 * Arguments for verify endpoint
		 * Registers the schema for arguments
		 */
		public function validate_health_arguments() {
			$args = array();

			// registering the schema for the post_id argument.
			$args['publisher_id'] = array(
				'description' => esc_html__( 'The publisher id.', 'pico' ),
				'type'        => 'string',
				'required'    => true
			);

			// registering the schema for the user_article_id argument.
			$args['key'] = array(
				'description' => esc_html__( 'The publisher api key.', 'pico' ),
				'type'        => 'string',
				'required'    => true
			);

			return $args;
		}

		/**
		 *
		 */
		public function check_api_health( $params ) {
			$args = array(
				'method'	=> 'GET',
				'blocking'  => true,
				'headers'   => array(
					'Content-Type' => 'application/json',
                    'Accept' => 'application/json',

				)
			);

			$api_url = Pico_Setup::get_api_endpoint();

			$pico_response = wp_remote_request(
					$api_url . '/status',
					$args
			);

			$response_code =  wp_remote_retrieve_response_code( $pico_response );
			$response_message = wp_remote_retrieve_response_message( $pico_response );
			$response_body = json_decode( wp_remote_retrieve_body( $pico_response ) );

			if ( $response_code == 200 ) {
				return new WP_Error($response_message, $response_body, array('status' => $response_code));
			} else {
				return new WP_Error($response_message, array('content' => self::get_item($params) ), array('status' => $response_code) );
			}
        }
    }

	// Function to register route from the controller.
	function pico_register_routes() {
		$controller = new Pico_API_Controller();
		$controller->register_routes();
	}

	add_action( 'rest_api_init', 'pico_register_routes' );
