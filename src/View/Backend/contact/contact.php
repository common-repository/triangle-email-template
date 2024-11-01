<form method="POST" id="contact-form">
    <input type="hidden" name="triangle_contact" value="send">
    <div id="form-message"></div>

    <div class="grid">
        <!-- Recipient -->
        <div class="row section-title">
            <h3>Recipient</h3>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_users">User</label>
            </div>
            <div class="col-sm-5">
                <?= $this->loadContent('Element.loading-field', [
                    'id' => 'loading-field-user'
                ]) ?>
                <div id="field-user-container" class="field-ajax">
                    <!-- UI -->
                    <div class="col-sm-12">
                        <div class="col-sm-11">
                            <select name="user" id="select-user-lists"></select>
                        </div>
                        <div class="col-sm-1" style="margin:5px 0;">
                            <a id="add-user-to-lists">+</a>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <p class="field-info"> Select user to be contacted </p>
                        <!-- Values -->
                        <input type="hidden" id="default-user" value="<?= $this->esc('attr',$user_id) ?>">
                        <input type="hidden" name="field_users" id="field-users">
                        <div id="user-lists"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_template">Template</label>
            </div>
            <div class="col-sm-5">
                <?= $this->loadContent('Element.loading-field', [
                    'id' => 'loading-field-template'
                ]) ?>
                <div id="field-template-container" class="field-ajax">
                    <select name="field_template" id="select-field-template"></select>
                    <p class="field-info">Choose email template</p>
                </div>
            </div>
        </div>

        <!-- Information -->
        <div class="row section-title">
            <h3>Information</h3>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_from_name">From Name</label>
            </div>
            <div class="col-sm-5">
                <input type="text" name="field_from_name" id="field-from-name" placeholder="Your Name...">
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_from_email">From Email</label>
            </div>
            <div class="col-sm-5">
                <input type="text" name="field_from_email" id="field-from-email" placeholder="Your Email Address...">
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_email_subject">Email Subject</label>
            </div>
            <div class="col-sm-5">
                <input type="text" name="field_email_subject" id="field-email-subject" placeholder="Email Subject...">
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-1">
                <button typpe="submit" class="btn-submit">SEND</button>
            </div>
        </div>
    </div>
</form>