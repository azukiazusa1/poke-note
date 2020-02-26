<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<script>
    M.toast({html: '<?= $message ?>', classes: 'rounded green accent-1 green-text'})
</script>