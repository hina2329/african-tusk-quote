<?php

// Product prices and fabrics combo class
class FPCombo extends ATQ {

    // Contructor
    public function __construct($combo_rel, $combo_fab, $combo_price, $combo_rel_code, $combo_action) {
        parent::__construct();

        // Vars
        $combo_count = count($combo_fab);
        $prod_id = array(
            'combo_pid' => $combo_rel
        );

        // Create array for each fabric price combo
        for ($i = 0; $i < $combo_count; $i++) {
            $combos[$i] = array(
                'fab' => $combo_fab[$i],
                'price' => $combo_price[$i],
            );
        }

        // Delete if any combo data exists
        $this->wpdb->delete($this->products_fp_combos_tbl, $prod_id);


        foreach ($combos as $combo) {

            // Combo relation product code
            $combo_code = $combo_rel_code . '-' . $combo['fab'];

            // Prep combo data
            $combo_data = array(
                'combo_pid' => $combo_rel,
                'combo_code' => $combo_code,
                'combo_price' => $combo['price']
            );

            // Insert combos
            $this->wpdb->insert($this->products_fp_combos_tbl, $combo_data);
        }
    }

}
