<?php
// Usage: wp eval-file run.php <blueprint.json> [model=<anthropic model id>] <attachment ids...>
// Runs Tainacan AI analysis on each attachment against the blueprint's collection and prints Markdown.
$blueprint = json_decode(file_get_contents(array_shift($args)), true);
// Optional model override for this run only (the site's own preference lives in mu-plugins/museu-ai.php).
if ($args && str_starts_with($args[0], 'model=')) {
  $model = substr(array_shift($args), 6);
  if ($model !== '') {
    add_filter('wpai_preferred_vision_models', fn ($models) => array_merge([['anthropic', $model]], $models), 99);
  }
}
$name = $blueprint['collection']['name'];
$col = \Tainacan\Repositories\Collections::get_instance()->fetch(['title' => $name, 'posts_per_page' => 1], 'OBJECT');
if (!$col) { echo "Collection '$name' not found; apply the blueprint first.\n"; exit(1); }
$col_id = $col[0]->get_id();
echo "collection: $name (#$col_id)\n\n";

wp_set_current_user(get_users(['role' => 'administrator', 'number' => 1])[0]->ID);
foreach ($args as $id) {
  $req = new WP_REST_Request('POST', '/tainacan-ai/v1/analyze');
  foreach (['attachment_id' => (int) $id, 'collection_id' => $col_id, 'force_refresh' => true] as $k => $v) $req->set_param($k, $v);
  $res = rest_do_request($req); $d = $res->get_data();
  $title = get_the_title($id);
  if ($res->get_status() !== 200) { echo "## $title ERROR {$d['code']}: {$d['message']}\n\n"; continue; }
  echo "## $title ({$d['result']['model_used']}, {$d['result']['tokens_used']} tok)\n";
  foreach ($d['result']['ai_metadata'] as $k => $f) {
    // Taxonomy suggestions carry a human-readable label next to the term id.
    $shown = $f['label'] ?? $f['value'];
    echo "- $k: " . json_encode($shown, JSON_UNESCAPED_UNICODE) . "\n";
    if ($f['value'] !== null) echo "  evidence: " . json_encode($f['evidence'] ?? null, JSON_UNESCAPED_UNICODE) . "\n";
  }
  echo "\n";
}
