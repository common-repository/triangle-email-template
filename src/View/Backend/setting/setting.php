<form method="POST" id="setting-form">
    <input type="hidden" name="field_menu_slug" value="<?= $this->esc('attr', $menuSlug) ?>">
    <div id="form-result" class="form-result-<?= $this->esc('attr',$result) ?>">Options saved successfully!</div>
    <div id="form-message"></div>

    <div class="grid">
        <!-- Animation -->
        <div class="row section-title">
            <div class="col-sm-12">
                <h3>Animation</h3>
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_animation">Enable Option</label>
            </div>
            <div class="col-sm-5">
                <label class="switch">
                    <input type="checkbox" name="field_option_animation" <?= ($options['triangle_animation']) ? 'checked' : '' ?>>
                    <span class="slider round"></span>
                </label>
                <p class="field-info">
                    You can turn on/off the animation by switching the toggle button. To see animation reference you can go to <code><a href="https://daneden.github.io/animate.css/" target="_blank">Animate.css</a></code>.
                </p>
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_animation_tab">Section Tab</label>
            </div>
            <div class="col-sm-5">
                <select name="field_option_animation_tab" id="field_option_animation_tab" class="select2">
                    <?= $this->loadContent('Element.option_animations', [
                        'value' =>  $this->esc('attr', $options['triangle_animation_tab'])
                    ]) ?>
                </select>
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_animation_content">Section Content</label>
            </div>
            <div class="col-sm-5">
                <select name="field_option_animation_content" id="field_option_animation_content" class="select2">
                    <?= $this->loadContent('Element.option_animations', [
                        'value' => $this->esc('attr',$options['triangle_animation_content'])
                    ]) ?>
                </select>
            </div>
        </div>

        <div class="clear-row"></div>

        <!-- Builder -->
        <div class="row section-title">
            <h3>Builder</h3>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_builder_inliner">CSS Inliner</label>
            </div>
            <div id="field-template-container" class="col-sm-5">
                <?php
                $value = $this->esc('attr', $options['triangle_builder_inliner']);
                $opts = [ 'none' => 'None', 'juice' => 'Automattic/Juice'];
                ?>
                <select name="field_option_builder_inliner" id="field_option_builder_inliner" class="select2">
                    <?php foreach($opts as $key => $opt): ?>
                        <option value="<?= $key ?>" <?= ($key==$value) ? 'selected' : '' ?>>
                            <?= $opt ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="field-info">
                    For more info you can go to <code>Docs</code> tab to see the reference.
                </p>
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_animation">Enable Code Editor</label>
            </div>
            <div class="col-sm-5">
                <label class="switch">
                    <input type="checkbox" name="field_option_builder_codeeditor" <?= ($options['triangle_builder_codeeditor']) ? 'checked' : '' ?>>
                    <span class="slider round"></span>
                </label>
                <p class="field-info">
                    Enable advanced code editor. Write your own custom html script into your email template.
                </p>
            </div>
        </div>

        <div class="clear-row"></div>

        <!-- SMTP -->
        <div class="row section-title">
            <h3>SMTP</h3>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_smtp">Enable Option</label>
            </div>
            <div class="col-sm-5">
                <label class="switch">
                    <input type="checkbox" name="field_option_smtp" <?= ($options['triangle_smtp']) ? 'checked' : '' ?> id="field_option_smtp">
                    <span class="slider round"></span>
                </label>
                <p class="field-info">
                    Please turn off SMTP options if you're using <code>wp_mail</code> route plugin like <code>WP MAIL SMTP</code>.
                    So then it's not conflicted between these two options.
                </p>
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_smtp_auth">Auth</label>
            </div>
            <div class="col-sm-5">
                <label class="switch">
                    <input type="checkbox" name="field_option_smtp_auth" <?= ($options['triangle_smtp_auth']) ? 'checked' : '' ?>>
                    <span class="slider round"></span>
                </label>
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_smtp_host">Host/Port</label>
            </div>
            <div class="col-sm-5">
                <div class="col-sm-9">
                    <input type="text" name="field_option_smtp_host" value="<?= ($options['triangle_smtp_host']) ? $this->esc('attr', $options['triangle_smtp_host']) : '' ?>" placeholder="mail.host.com" style="width:99%;">
                </div>
                <div class="col-sm-3">
                    <input type="number" name="field_option_smtp_port" value="<?= ($options['triangle_smtp_port']) ? $this->esc('attr', $options['triangle_smtp_port']) : '' ?>" placeholder="25 | 465 | 587">
                </div>
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_smtp_username">Username</label>
            </div>
            <div class="col-sm-5">
                <input type="text" name="field_option_smtp_username" value="<?= ($options['triangle_smtp_username']) ? $this->esc('attr', $options['triangle_smtp_username']) : '' ?>" placeholder="Username">
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_smtp_password">Password</label>
            </div>
            <div class="col-sm-5">
                <input type="password" name="field_option_smtp_password" value="<?= ($options['triangle_smtp_password']) ? $this->esc('attr', $options['triangle_smtp_password']) : '' ?>" placeholder="Password">
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_smtp_encryption">Encryption</label>
            </div>
            <div class="col-sm-5">
                <?php
                    $value = $this->esc('attr',$options['triangle_smtp_encryption']);
                    $opts = [ 'none' => 'None', 'tls' => 'TLS', 'ssl' => 'SSL'];
                ?>
                <select name="field_option_smtp_encryption" id="field_option_smtp_encryption" class="select2">
                    <?php foreach($opts as $key => $opt): ?>
                        <option value="<?= $key ?>" <?= ($key==$value) ? 'selected' : '' ?>>
                            <?= $opt ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row section-fields">
            <div class="col-sm-2">
                <label for="field_option_smtp_tls">Auto TLS</label>
            </div>
            <div class="col-sm-5">
                <label class="switch">
                    <input type="checkbox" name="field_option_smtp_tls" <?= ($options['triangle_smtp_tls']) ? 'checked' : '' ?>>
                    <span class="slider round"></span>
                </label>
                <p class="field-info"> By default TLS encryption is automatically used if the server supports it, which is recommended. In some cases, due to server misconfigurations, this can cause issues and may need to be disabled.  </p>
            </div>
        </div>

        <div class="row section-submit">
            <div class="col-sm-2">
            </div>
            <div class="col-1">
                <input type="submit" class="btn-submit" value="SAVE">
            </div>
        </div>
    </div>

</form>