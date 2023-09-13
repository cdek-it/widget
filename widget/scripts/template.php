<?php
header('Access-Control-Allow-Origin: *');
$files = scandir($D = __DIR__ . '/tpl');
unset($files[0]);
unset($files[1]);

$arTPL = array();

foreach ($files as $filesname) {
    $file_tmp = explode('.', $filesname);
	$arTPL[strtolower($file_tmp[0])] = file_get_contents($D . '/' . $filesname);
}

echo str_replace(array('\r','\n','\t',"\n","\r","\t"),'',json_encode($arTPL));