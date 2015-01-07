<?php


/**
 * WordPress User class.
 *
 * @since 2.0.0
 * @package WordPress
 * @subpackage User
 */
class WP_User {

	var $data;

	public $ID = 1;

	/**
	 * The individual capabilities the user has been given.
	 *
	 * @since 2.0.0
	 * @access public
	 * @var array
	 */
	public $caps = array('administrator' => true);

	/**
	 * User metadata option name.
	 *
	 * @since 2.0.0
	 * @access public
	 * @var string
	 */
	public $cap_key = 'wp_capabilities';

	/**
	 * The roles the user is part of.
	 *
	 * @since 2.0.0
	 * @access public
	 * @var array
	 */
	public $roles = array('administrator');

	/**
	 * All capabilities the user has, including individual and role based.
	 *
	 * @since 2.0.0
	 * @access public
	 * @var array
	 */
	public $allcaps = array(
		'switch_themes' => true,
      'edit_themes' => true,
      'activate_plugins' => true,
      'edit_plugins' => true,
      'edit_users' => true,
      'edit_files' => true,
      'manage_options' => true,
      'moderate_comments' => true,
      'manage_categories' => true,
      'manage_links' => true,
      'upload_files' => true,
      'import' => true,
      'unfiltered_html' => true,
      'edit_posts' => true,
      'edit_others_posts' => true,
      'edit_published_posts' => true,
      'publish_posts' => true,
      'edit_pages' => true,
      'read' => true,
      'level_10' => true,
      'level_9' => true,
      'level_8' => true,
      'level_7' => true,
      'level_6' => true,
      'level_5' => true,
      'level_4' => true,
      'level_3' => true,
      'level_2' => true,
      'level_1' => true,
      'level_0' => true,
      'edit_others_pages' => true,
      'edit_published_pages' => true,
      'publish_pages' => true,
      'delete_pages' => true,
      'delete_others_pages' => true,
      'delete_published_pages' => true,
      'delete_posts' => true,
      'delete_others_posts' => true,
      'delete_published_posts' => true,
      'delete_private_posts' => true,
      'edit_private_posts' => true,
      'read_private_posts' => true,
      'delete_private_pages' => true,
      'edit_private_pages' => true,
      'read_private_pages' => true,
      'delete_users' => true,
      'create_users' => true,
      'unfiltered_upload' => true,
      'edit_dashboard' => true,
      'update_plugins' => true,
      'delete_plugins' => true,
      'install_plugins' => true,
      'update_themes' => true,
      'install_themes' => true,
      'update_core' => true,
      'list_users' => true,
      'remove_users' => true,
      'add_users' => true,
      'promote_users' => true,
      'edit_theme_options' => true,
      'delete_themes' => true,
      'export' => true,
      'manage_admin_columns' => true,
      'wpseo_bulk_edit' => true,
      'administrator' => true,
	);



	/**
	 * The filter context applied to user data fields.
	 *
	 * @since 2.9.0
	 * @access private
	 * @var string
	 */
	var $filter = null;



	/**
	 * Constructor
	 *
	 * Retrieves the userdata and passes it to {@link WP_User::init()}.
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param int|string|stdClass|WP_User $id User's ID, a WP_User object, or a user object from the DB.
	 * @param string $name Optional. User's username
	 * @param int $blog_id Optional Blog ID, defaults to current blog.
	 * @return WP_User
	 */
	public function __construct( $id = 0, $name = '', $blog_id = '' ) {

		$this->data = new stdClass();
		$this->data->ID = 1;
		$this->data->user_login = 'admin';
		$this->data->user_pass = '$P$Bba3TarSzvQWS2lV3AKnU03dqIZ8QC1';
		$this->data->user_nicename = 'admin';
		$this->data->user_email = 'email@email.com';
		$this->data->user_url = '';
		$this->data->user_registered = '2014-12-26 00:36:15';
		$this->data->user_activation_key = '';
		$this->data->user_status = 0;
		$this->data->display_name = 'admin';

	}

}