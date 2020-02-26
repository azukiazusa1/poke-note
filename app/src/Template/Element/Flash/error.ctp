<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<script>
    M.toast({html: '<?= $message ?>', classes: 'rounded red lighten-4 red-text darken-2-text'})
</script>