<?php

$files = scandir($D = __DIR__ . '/tpl');
unset($files[0]);
unset($files[1]);

$arTPL = array();

foreach ($files as $filesname) {
	$arTPL[strtolower(explode('.', $filesname)[0])] = file_get_contents($D . '/' . $filesname);
}

echo str_replace(array('\r','\n','\t',"\n","\r","\t"),'',json_encode($arTPL));