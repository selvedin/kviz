<?php

use yii\bootstrap5\Html;

$this->title = 'Import Sciences';
$counts = ['sciences' => count($data), 'domains' => 0, 'subdomains' => 0, 'specs' => 0];
foreach ($data as $kd => $domains) {
  $counts['domains'] += count($domains);
  if (is_array($domains))
    foreach ($domains as $ksd => $subdomins) {
      $counts['subdomains'] += count($subdomins);
      if (is_array($subdomins))
        foreach ($subdomins as $specs)
          $counts['specs'] += count($specs);
    }
}
?>
<pre>
  <?= print_r($counts) ?>
</pre>