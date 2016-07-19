<?php

use Phalcon\Diff;
use Phalcon\Diff\Render\Html\Inline;
use Phalcon\Diff\Render\Text\Unified;
use Phalcon\Diff\Render\Text\Context;
use Phalcon\Diff\Render\Html\SideBySide;

// Include the diff class
require_once dirname(dirname(__FILE__)) . '/vendor/autoload.php';

// Include two sample files for comparison
$a = explode("\n", file_get_contents(dirname(__FILE__) . '/a.txt'));
$b = explode("\n", file_get_contents(dirname(__FILE__) . '/b.txt'));

// Options for generating the diff
$options = [
	// 'ignoreWhitespace' => true,
	// 'ignoreCase' => true,
];

// Initialize the diff class
$diff = new Diff($a, $b, $options);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
		<title>Phalcon Diff - Examples</title>
		<link rel="stylesheet" href="styles.css" type="text/css" charset="utf-8"/>
	</head>
	<body>
		<h1>Phalcon Diff - Examples</h1>
		<hr />

		<h2>Side by Side Diff</h2>
		<?php echo $diff->render(new SideBySide);?>

		<h2>Inline Diff</h2>
		<?php echo $diff->render(new Inline); ?>

		<h2>Unified Diff</h2>
		<pre><?php echo htmlspecialchars($diff->render(new Unified)); ?></pre>

		<h2>Context Diff</h2>
		<pre><?php echo htmlspecialchars($diff->render(new Context)); ?></pre>
	</body>
</html>
