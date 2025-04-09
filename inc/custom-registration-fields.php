<?php
// Display custom fields in registration form
function custom_add_registration_fields() {
    ?>
    <p>
        <label for="degree">Degree<br/>
            <input type="text" name="degree" id="degree" class="input" value="" size="25" /></label>
    </p>
    <p>
        <label for="passing_year">Passing Year<br/>
            <input type="text" name="passing_year" id="passing_year" class="input" value="" size="25" /></label>
    </p>
    <p>
        <label for="percentage">Passing Percentage<br/>
            <input type="text" name="percentage" id="percentage" class="input" value="" size="25" /></label>
    </p>
    <p>
        <label for="file_url">Upload Your File (PDF)<br/>
            <input type="file" name="file_url" id="file_url" /></label>
    </p>
    <?php
    //  Add nonce field here
    wp_nonce_field('crp_register_nonce', 'crp_nonce');
    ?>
      <script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.querySelector('form#registerform');
        if (form) {
            form.setAttribute('enctype', 'multipart/form-data');
        }
    });
    </script>
    <?php
}
add_action('register_form', 'custom_add_registration_fields');
