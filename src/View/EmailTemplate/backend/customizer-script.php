<script type="text/javascript">
    var customizerSetting = {
        <?php foreach($settings as $key => $setting): ?>
            <?= $key ?>: `<?=
                ($setting->getArgs()['customFieldValue']) ?
                    $setting->getArgs()['customFieldValue'] :
                    $setting->getArgs()['default']
            ?>`,
        <?php endforeach; ?>
    };
</script>