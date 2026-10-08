<?php
// Applies the eval preamble to the live site (Tainacan AI default preamble). Field guidance comes from the
// collection blueprint (scripts/tainacan/*.json), applied separately by apply-blueprint.php.
// Usage: wp eval-file apply.php <preamble.txt>
$o = get_option('tainacan_ai_options', []);
$o['default_preamble'] = file_get_contents($args[0]);
update_option('tainacan_ai_options', $o);
echo "applied ", basename($args[0]), " (", mb_strlen($o['default_preamble']), " chars)\n";
