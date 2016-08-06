#!/bin/env php
<?php

if ($argc < 3) {
	echo "[E] Need at least 2 arguments: (.c file), (.h file)\n";
	exit;
}

$cfile = $argv[1];
$hfile = $argv[2];

$write = isset($argv[3]) && ($argv[3] == '-w');
$dump = isset($argv[3]) && ($argv[3] == '-d');

if (!file_exists($cfile)) {
	echo "[E] File not found.\n";
	exit;
}

if (substr($cfile, -2) != '.c') {
	echo "[E] C file not ending with .c!";
	exit;
}

if (substr($hfile, -2) != '.h') {
	echo "[E] H file not ending with .h!";
	exit;
}

$ctxt = file_get_contents($cfile);
$htxt = file_get_contents($hfile);

$ctxt = preg_replace("/\/\*\*\s+\*\*\*/s", "/*\n", $ctxt);

preg_match_all('/(\/\*\*(?:.(?!\/\*\*))*?\*\/)\s+([a-z_+0-9 ]+)\s+([a-z_+0-9]+)\s*(?=\()/si', $ctxt, $matches);
$dox = $matches[1];
$types = $matches[2];
$names = $matches[3];

echo "FOUND " . count($dox) ." DOC BLOCKS\n";

$suc = [];

$x = $htxt;
for ($i = 0; $i < count($dox); $i++) {

	$t = trim(str_replace('__weak', '', $types[$i]));

	$search = '/' . preg_quote($t) . '\s+' . preg_quote($names[$i]) . '/';
	$replacement = "\n\n/*- injected dox -*/\n" . $dox[$i] . "\n" . $types[$i] . ' ' . $names[$i];
	
	if (strpos($x, $replacement) !== false) {
		$suc[$i] = 'already';
		continue;
	}

	$x0 = $x;
	$x = preg_replace($search, $replacement, $x);
	if ($x != $x0) {
		$suc[$i] = 'ok';
	} else {
		$suc[$i] = false;
	}
}

if ($write) {
	copy($hfile, $hfile.".bak");
	file_put_contents($hfile, $x);
	echo "Changes written to $hfile!\n";
} else if($dump) {
	echo $x."\n";
} else {
	for ($i = 0; $i < count($dox); $i++) {
		switch ($suc[$i]) {
			case 'already': echo "[alr] "; break;
			case 'ok': echo "[SUC] "; break;
			case false: echo "[ - ] "; break;
		}
	
		echo "$types[$i] $names[$i]()\n";
	}
}
