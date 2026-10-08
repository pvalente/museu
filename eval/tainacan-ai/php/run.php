<?php
// Usage: wp eval-file run.php <attachment ids...>
// Runs Tainacan AI analysis on each attachment against collection 6's fields and prints Markdown.
wp_set_current_user(get_users(['role' => 'administrator', 'number' => 1])[0]->ID);
foreach ($args as $id) {
  $req = new WP_REST_Request('POST', '/tainacan-ai/v1/analyze');
  foreach (['attachment_id' => (int) $id, 'collection_id' => 6, 'force_refresh' => true] as $k => $v) $req->set_param($k, $v);
  $res = rest_do_request($req); $d = $res->get_data();
  $title = get_the_title($id);
  if ($res->get_status() !== 200) { echo "## $title ERROR {$d['code']}: {$d['message']}\n\n"; continue; }
  $m = $d['result']['ai_metadata'];
  echo "## $title ({$d['result']['model_used']}, {$d['result']['tokens_used']} tok)\n";
  foreach ($m as $k => $f) echo "- $k: " . json_encode($f['value'], JSON_UNESCAPED_UNICODE) . "\n  evidence: " . json_encode($f['evidence'] ?? null, JSON_UNESCAPED_UNICODE) . "\n";
  echo "\n";
}
