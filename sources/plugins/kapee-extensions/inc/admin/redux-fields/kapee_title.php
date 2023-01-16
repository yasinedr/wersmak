<?php 

// **********************************************************************//
// Title
// **********************************************************************//

if( ! class_exists( 'ReduxFramework_kapee_title' ) && class_exists( 'ReduxFramework' ) ) {

    class ReduxFramework_kapee_title extends ReduxFramework {
    
        function __construct( $field = array(), $value = '', $parent ) {
            $this->parent = $parent;
            $this->field  = $field;
            $this->value  = $value;
        }

        public function render() {
            echo '</td></tr></table>';

            echo '<div class="kapee-settings-title">';
                if ( isset( $this->field['kapee-title'] ) && $this->field['kapee-title'] ) {
                    echo '<h4 class="kapee-title">' . esc_html( $this->field['kapee-title'] ) . '</h4>';
                }
                if ( isset( $this->field['kapee-desc'] ) && $this->field['kapee-desc'] ) {
                    echo '<p class="kapee-title-desc">' . esc_html( $this->field['kapee-desc'] ) . '</p>';
                }
            echo '</dev>';

            echo '</div><table class="form-table no-border" style="margin-top: 0;"><tbody><tr style="border-bottom:0; display:none;"><th style="padding-top:0;"></th><td style="padding-top:0;">';
        }
    }
}