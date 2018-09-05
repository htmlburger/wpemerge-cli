<?php

namespace WPEmerge\Cli\Presets;

use Symfony\Component\Console\Output\OutputInterface;

class GravityFormsUtilities implements PresetInterface {
	use FilesystemTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getName() {
		return 'Gravity Forms Utilities';
	}

	/**
	 * {@inheritDoc}
	 */
	public function execute( $directory, OutputInterface $output ) {
		$copy_list = [
			$this->path( WPEMERGE_CLI_DIR, 'src', 'GravityForms', 'gravity-forms.php' )
				=> $this->path( $directory, 'app', 'helpers', 'gravity-forms.php' ),
		];

		$failures = $this->copy( $copy_list );
		foreach ( $failures as $source => $destination ) {
			$output->writeln( '<failure>File ' . $destination . ' already exists - skipped.</failure>' );
		}

		$this->addRequires( $directory );
		$this->addHooks( $directory );
	}

	/**
	 * Add require statements
	 *
	 * @param  string $directory
	 * @return void
	 */
	protected function addRequires( $directory ) {
		$this->appendUniqueStatement(
			$this->path( $directory, 'app', 'helpers.php' ),
			<<<'EOT'
require_once APP_APP_HELPERS_DIR . 'gravity-forms.php';
EOT
		);
	}

	/**
	 * Add hook statements
	 *
	 * @param  string $directory
	 * @return void
	 */
	protected function addHooks( $directory ) {
		$hooks_filepath = $this->path( $directory, 'app', 'hooks.php' );

		$this->appendUniqueStatement(
			$hooks_filepath,
			<<<'EOT'

/**
 * Add useful classes to gravity form elements to easier styling
 */
add_filter( 'gform_field_css_class', 'app_filter_decorate_gforms_classes', 10, 3 );
EOT
		);

		$this->appendUniqueStatement(
			$hooks_filepath,
			<<<'EOT'

/**
 * Replace the gravity forms spinner gif
 */
// add_filter( 'gform_ajax_spinner_url', 'app_filter_gform_ajax_spinner_url', 10, 2 );
EOT
		);

		$this->appendUniqueStatement(
			$hooks_filepath,
			<<<'EOT'

/**
 * Disables the Confirmation Anchor on Gravity Forms.
 *
 * @link http://www.gravityhelp.com/documentation/page/Gform_confirmation_anchor
 */
add_filter( 'gform_confirmation_anchor', '__return_false' );
EOT
		);

		$this->appendUniqueStatement(
			$hooks_filepath,
			<<<'EOT'

/**
 * Display an "Add Form" button above rich text fields on all custom field containers.
 *
 * @link http://www.gravityhelp.com/documentation/page/Gform_display_add_form_button
 */
add_filter( 'gform_display_add_form_button', '__return_true' );
EOT
		);
	}
}
