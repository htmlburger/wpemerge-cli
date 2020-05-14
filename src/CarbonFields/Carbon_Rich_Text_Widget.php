<?php

namespace MyApp\Widgets;

use Carbon_Fields\Widget\Widget;
use Carbon_Fields\Field\Field;

/**
 * A widget with a title and rich text fields.
 */
class Carbon_Rich_Text_Widget extends Widget {
	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->setup(
			'rich_text',
			__( 'Rich Text', 'my_app' ),
			__( 'Displays a block with title and WYSIWYG content.', 'my_app' ),
			array(
				Field::make( 'text', 'title', __( 'Title', 'my_app' ) ),
				Field::make( 'rich_text', 'content', __( 'Content', 'my_app' ) ),
			)
		);
	}

	/**
	 * Renders the widget front-end.
	 *
	 * @param  array $args     Widgets arguments.
	 * @param  array $instance Instance values.
	 * @return void
	 */
	public function front_end( $args, $instance ) {
		if ( $instance['title'] ) {
			$title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );

			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo apply_filters( 'the_content', $instance['content'] );
	}
}
