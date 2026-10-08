<?php
// Applies the eval prompt to the live site: Tainacan AI default preamble + field guidance.
// Usage: wp eval-file apply.php <preamble.txt> <fields.json>
[$pfile, $ffile] = $args;
$o = get_option('tainacan_ai_options', []);
$o['default_preamble'] = file_get_contents($pfile);
update_option('tainacan_ai_options', $o);
$repo = \Tainacan\Repositories\Metadata::get_instance();
foreach (json_decode(file_get_contents($ffile), true) as $id => [$desc, $ph]) {
  $m = $repo->fetch((int) $id); $m->set_description($desc); $m->set_placeholder($ph);
  if ($m->validate()) $repo->update($m); else print_r($m->get_errors());
}
echo "applied ", basename($pfile), " (", mb_strlen($o['default_preamble']), " chars)\n";
