<?php
trait SP_Framework_Meta_Data_Field {

	private function add_field( $type, $name, $label, $caption, $default, $required, $id ) {

		$calledClass = get_called_class();

		//name with pref
		if ( $type == 'images' ) {
			$name = 'sp_img_' . $name;
		} else {
			$name = 'sp_' . $name;
		}

		$value = $this->get_meta( $id, $name );

		//default meta
		if ( empty( $value ) ) {
			$value = $default;
		}

		//get meta
		if ( $type != 'select' ) {
			if ( ! empty( $required ) && $required == 'y' ) {
				$required = 'required';
			} else {
				$required = '';
			}
		}

		//label
		if ( ! empty( $label ) ) {
			echo '<tr class="form-field sp-fields">';
			echo '<th scope="row" valign="top"><label for="id_' . $name . '">' . $label . ':</label>';

			if ( $type == 'images' ) {

				$addTitle    = __( 'Add image(s)', 'spf86' );
				$changeTitle = __( 'Change image', 'spf86' );
				$removeTitle = __( 'Remove', 'spf86' );

				echo '<p><a href="#" class="sp-add-image button" data-id="' . $name . '" data-uploader-title="' . $addTitle . '" data-uploader-button-text="' . $addTitle . '">' . $addTitle . '</a></p>';
			}

			echo '</th>';
		}

		echo '<td>';

		//input text
		if ( $type == 'text' ) {
			echo '<input type="text" name="' . $name . '" id="id_' . $name . '" class="cl_' . $name . ' sp-field-text" value="' . $value . '" ' . $required . '>';
		}

		//input number
		if ( $type == 'number' ) {
			echo '<input type="number" name="' . $name . '" id="id_' . $name . '" class="cl_' . $name . ' sp-field-number" step="any" value="' . $value . '" ' . $required . '>';
		}

		//textarea
		if ( $type == 'textarea' ) {
			echo '<textarea name="' . $name . '" id="id_' . $name . '" class="cl_' . $name . ' sp-field-textarea" ' . $required . '>' . $value . '</textarea>';
		}

		//select
		if ( $type == 'select' ) {
			$dv = $value;

			echo '<select name="' . $name . '" id="id_' . $name . '" class="cl_' . $name . ' sp-field-select">';
				$prop = $default;
			foreach ( $prop as $key => $value ) {
				if ( $dv == $key ) {
					echo '<option value="' . $key . '">' . $value . '</option>';
					echo '<option value="0" disabled>------</option>';
				}
			}
			foreach ( $prop as $key => $value ) {
				echo '<option value="' . $key . '">' . $value . '</option>';
			}
			echo '</select>';
		}

		//checkbox
		if ( $type == 'checkbox' ) {
			$chk = '';

			if ( empty( $value ) ) {
				$value = 'n';
			}
			if ( $value == 'y' ) {
				$chk = 'checked';
			}

			echo '<p>';

			echo '<input type="hidden" name="' . $name . '" id="id_' . $name . '_hd" class="cl_' . $name . '_hd" value="' . $value . '">';
			echo '<input type="checkbox" id="id_' . $name . '" class="cl_' . $name . ' sp-field-checkbox" data-value="y" ' . $chk . ' ' . $required . '>';

			if ( $calledClass == 'SP_Framework_Post_Type_Meta_Box' ) {
				echo '<label for="id_' . $name . '">' . $label . '</label>';
			}

			echo '</p>';
		}

		//images
		if ( $type == 'images' ) {
			$images = $value;

			echo '<table class="form-table">';
			echo '<tr><td>';

			echo '<ul class="sp-gallery-metabox-list" id="id_' . $name . '" data-name="' . $name . '" data-change="' . $changeTitle . '" data-remove="' . $removeTitle . '">';

			if ( $images && $images[0] != '' ) {
				foreach ( $images as $key => $val ) {
					$image = wp_get_attachment_image_src( $val );
					echo '<li>';
					echo '<input type="hidden" name="' . $name . '[' . $key . ']" value="' . $val . '">';
					echo '<img class="sp-image-preview" src="' . $image[0] . '">';
					echo '<a class="sp-change-image button button-small" href="#" data-uploader-title="' . $changeTitle . '" data-uploader-button-text="' . $changeTitle . '">';
					echo $changeTitle;
					echo '</a><br>';
					echo '<small><a class="sp-remove-image" data-id="' . $name . '" href="#">' . $removeTitle . '</a></small>';
					echo '</li>';
				}
			}

			echo '</ul>';
			echo '</td></tr>';
			echo '</table>';
		}

		if ( $type == 'map' ) {

			$coords = $this->get_meta( $id, $name . '_coords' );

			echo '<div class="sp-field-map-wrap">';
				echo '<label for="id_sp_address">' . $label . ':</label><br>';

				echo '<input type="text" name="' . $name . '" id="id_' . $name . '" class="cl_' . $name . ' sp-field-address" value="' . $value . '" ' . $required . '><span class="button button-primary button-large sp-add-marker" data-id="' . $name . '">Add to map</span>';

				echo '<input type="hidden" name="' . $name . '_coords" id="id_' . $name . '_coords" class="cl_' . $name . '_coords" value="' . $coords . '">';

				echo '<div id="' . $name . '_map" class="sp-field-map" data-name="' . $name . '"></div>';
			echo '</div>';

		}

		//caption
		if ( ! empty( $caption ) ) {
			echo '<p class="sp-caption description"><i>' . $caption . '</i></p>';
		}

		echo '</td>';
	}

	private function get_meta( $id, $name ) {
		$calledClass = get_called_class();

		if ( $calledClass == 'SP_Framework_Post_Type_Meta_Box' ) {
			$value = get_post_meta( $id, $name, true );
		}
		if ( $calledClass == 'SP_Framework_Taxonomy_Meta_Box' ) {
			$value = get_term_meta( $id, $name, true );
		}
		if ( $calledClass == 'SP_Framework_User_Meta_Box' ) {
			$value = get_the_author_meta( $name, $id );
		}
		if ( $calledClass == 'SP_Framework_Admin_Meta_Box' ) {
			$value = get_option( $name );
		}

		return $value;
	}
}
