<script type="text/javascript">
    window.trianglePlugin = {
        name: '<?= TRIANGLE_NAME ?>',
        version: '<?= TRIANGLE_VERSION ?>',
        screen: <?= json_encode($screen) ?>,
        options: {
            animation_tab: '<?= $options['animation_tab'] ?>',
            animation_content: '<?= $options['animation_content'] ?>',
        }
    };
</script>