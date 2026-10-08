<?php
// Usage: wp eval-file run.php <blueprint.json> [model=<anthropic model id>] [effort=<low|medium|high|xhigh|max>]
//        [max_tokens=<n>] <attachment ids...>
// Runs Tainacan AI analysis on each attachment against the blueprint's collection and prints Markdown.
$blueprint = json_decode(file_get_contents(array_shift($args)), true);
// Optional model override for this run only (the site's own preference lives in mu-plugins/museu-ai.php).
if ($args && str_starts_with($args[0], 'model=')) {
  $model = substr(array_shift($args), 6);
  if ($model !== '') {
    add_filter('wpai_preferred_vision_models', fn ($models) => array_merge([['anthropic', $model]], $models), 99);
  }
}
// Optional effort (Anthropic output_config.effort) and max_tokens for this run only: set on the request's model
// config after mu-plugins/museu-ai.php has filled in its own (priority 10), so the site's settings stay untouched.
$effort = '';
$max_tokens = 0;
while ($args && preg_match('/^(effort|max_tokens)=(.*)$/', $args[0], $m)) {
  array_shift($args);
  if ($m[1] === 'effort') $effort = $m[2]; else $max_tokens = (int) $m[2];
}
add_action('wp_ai_client_before_generate_result', function ($event) use ($effort, $max_tokens) {
  $config = $event->getModel()->getConfig();
  if ($effort !== '') $config->setCustomOption('output_config', ['effort' => $effort]);
  if ($max_tokens > 0) $config->setMaxTokens($max_tokens);
}, 20);
// Usage details Tainacan AI doesn't report: output and thinking tokens, and why generation stopped.
add_action('wp_ai_client_after_generate_result', function ($event) {
  $r = $event->getResult(); $u = $r->getTokenUsage();
  $GLOBALS['eval_usage'] = sprintf('out %d, thinking %s, %s', $u->getCompletionTokens(), $u->getThoughtTokens() ?? '?', $r->getCandidates()[0]->getFinishReason()->value);
});
if ($effort !== '' || $max_tokens > 0) echo 'effort: ' . ($effort ?: 'default') . ', max_tokens: ' . ($max_tokens ?: 'site') . "\n";
$name = $blueprint['collection']['name'];
$col = \Tainacan\Repositories\Collections::get_instance()->fetch(['title' => $name, 'posts_per_page' => 1], 'OBJECT');
if (!$col) { echo "Collection '$name' not found; apply the blueprint first.\n"; exit(1); }
$col_id = $col[0]->get_id();
echo "collection: $name (#$col_id)\n\n";

wp_set_current_user(get_users(['role' => 'administrator', 'number' => 1])[0]->ID);
foreach ($args as $id) {
  $req = new WP_REST_Request('POST', '/tainacan-ai/v1/analyze');
  foreach (['attachment_id' => (int) $id, 'collection_id' => $col_id, 'force_refresh' => true] as $k => $v) $req->set_param($k, $v);
  $GLOBALS['eval_usage'] = '';
  $t0 = microtime(true);
  $res = rest_do_request($req); $d = $res->get_data();
  $secs = round(microtime(true) - $t0, 1);
  $title = get_the_title($id);
  if ($res->get_status() !== 200) { echo "## $title ERROR {$d['code']}: {$d['message']} ({$GLOBALS['eval_usage']}, {$secs} s)\n\n"; continue; }
  echo "## $title ({$d['result']['model_used']}, {$d['result']['tokens_used']} tok; {$GLOBALS['eval_usage']}; {$secs} s)\n";
  foreach ($d['result']['ai_metadata'] as $k => $f) {
    // Taxonomy suggestions carry a human-readable label next to the term id.
    $shown = $f['label'] ?? $f['value'];
    echo "- $k: " . json_encode($shown, JSON_UNESCAPED_UNICODE) . "\n";
    if ($f['value'] !== null) echo "  evidence: " . json_encode($f['evidence'] ?? null, JSON_UNESCAPED_UNICODE) . "\n";
  }
  // What the Tesauro Museus matcher saw and picked (names tried, top candidates).
  if (isset($d['result']['tesauro'])) {
    $t = $d['result']['tesauro'];
    echo '  tesauro: names=' . json_encode($t['names'], JSON_UNESCAPED_UNICODE) . ' -> ' . json_encode(array_map(fn ($c) => "{$c['label']} ({$c['method']} {$c['score']})", $t['candidates']), JSON_UNESCAPED_UNICODE) . "\n";
  }
  echo "\n";
}
