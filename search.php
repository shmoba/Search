<?php
ob_start();
header('Content-Type: text/html;');
error_reporting(E_ALL);
mb_internal_encoding('UTF-8');
/*
Есть длинный текст и есть форма поиска по этому тексту. При вводе слова в форму поиска необходимо найти все упоминания этого слова в тексте и выделить (подсветить желтым фоном). В случае, если указываются 2 слова, то каждое должно искаться индивидуально, если словосочетание указывается в кавычках, то ищется как единое словосочетание.
*/
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>Search</title>
</head>
<body>
	<h1>Поиск</h1>
<?php

function string_cut($word) { // обрезать кавычки
	$word = mb_substr($word, 1);
	$word = mb_substr($word, 0, -1);
	return $word;
}

function highlight_one( $content, $word) { // заменить как единый кусок
    $replace = '<span style="background-color: #FEFF49;">' . $word . '</span>'; // create replacement
    $content = str_replace( $word, $replace, $content); // replace content
    //print_r($word);
    echo '<span style="color: green;">' .$content. '</span>'; // return highlighted data
}

function highlight_many( $content, $words) { // заменить несколько слов
	$textArray = explode(' ', $content); // разбить текст на слова
	foreach($textArray as $word){
		if(in_array($word, $words)){
		$content = str_replace($word, '<span style="background-color: #FEFF49;">' . $word . '</span>', $content); // <b> . $text . </b> поменяй на то, что тебе надо. Например на <font color=red> . $text . </font>
		}
	}echo '<span style="color: red;">' .$content. '</span>';
}
	
function string_to_array ($string) { //str_split так сказать
	$array = '';
    $strlen = mb_strlen($string); 
    while ($strlen) { 
        $array[] = mb_substr($string,0,1,'UTF-8'); 
        $string = mb_substr($string,1,$strlen,'UTF-8');
        $strlen = mb_strlen($string); 
    } 
    return $array; 
}
	
function replace_value(&$value) { // манипуляция с элементами массива
    $value = str_replace('"','',$value);
}

$text = 'Стволовые клетки — недифференцированные (незрелые) клетки, имеющиеся у многих видов многоклеточных организмов. Стволовые клетки способны самообновляться, образуя новые стволовые клетки, делиться посредством митоза и дифференцироваться в специализированные клетки, то есть превращаться в клетки различных органов и тканей. Развитие многоклеточных организмов начинается с одной стволовой клетки, которую принято называть зиготой. В результате многочисленных циклов деления и процесса дифференцировки образуются все виды клеток, характерные для данного биологического вида.';

?>

<form>
	<input type="text" name="search_string" placeholder="Найти текст">
	<button>Искать!</button>
</form>
<br>

<?php

if(isset($_GET['search_string'])) { // слово которое нужно найти и выделить

	$search_needles = explode(' ',$_GET['search_string']); // что будем искать

	$text_pieces = explode(' ', $text); // где будем искать

	//echo mb_detect_encoding($_GET['search_string']); // проверка кодировки

	$letters = string_to_array($_GET['search_string']); //str_split так сказать

	$errors = array();

	if (mb_strlen($_GET['search_string']) < 1)
	die($errors[] = error_style('Запрос пустой :(',$text));

	array_walk($search_needles, 'replace_value'); // обрезать кавычки для последующего сравнения в каждом элементе массива

	if (!array_intersect($search_needles,$text_pieces)) // находим схождение между текстом и поиском
	die($errors[] = error_style('Нет совпадения :(',$text));
?>
<p><span class='bold'>Поиск по тексту: </span><?=($_GET['search_string'])?></p>
<?php

	if ( ('"' == end($letters)) && ('"' == reset($letters)) ) { // поиск = "в кавычках"

		$string = implode($letters);
		$string = string_cut($string);
		highlight_one($text,$string);
		return $string;
	}

	if (mb_stripos($_GET['search_string'],' ')) { // поиск = несколько слов или с пробелом

		$string = explode(' ', $_GET['search_string']) ;
		//print_r($string);
		highlight_many($text,$string);
	}

	else{ // поиск = одно слово
		highlight_one ($text, $_GET['search_string']);
	}
		
	//header("Location:index.php");
	//exit();
}

if (empty($_GET['search_string'])) {echo $text;}

?>
</body>
</html>
