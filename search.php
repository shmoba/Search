<?php
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

$text = 'Стволовые клетки — недифференцированные (незрелые) клетки, имеющиеся у многих видов многоклеточных организмов. Стволовые клетки способны самообновляться, образуя новые стволовые клетки, делиться посредством митоза и дифференцироваться в специализированные клетки, то есть превращаться в клетки различных органов и тканей. Развитие многоклеточных организмов начинается с одной стволовой клетки, которую принято называть зиготой. В результате многочисленных циклов деления и процесса дифференцировки образуются все виды клеток, характерные для данного биологического вида.';

?>

<form>
	<input type="text" name="search_string" placeholder="Найти текст">
	<button>Искать!</button>
</form>
<br>

<?php

if(isset($_GET['search_string'])) { // слово которое нужно найти и выделить

	echo 'Поиск по тексту: '.($_GET['search_string']).'<br>';
	echo '<br>';

	//if ((mb_stripos($_GET['search_string'],'"')) and (mb_strripos($_GET['search_string'],'"')) != 0) { // поиск = "в кавычках"

		$string = string_cut($_GET['search_string']);
		highlight_one ($text, $string);
		
	//}

	if (!mb_stripos($_GET['search_string'],'"') && !mb_strripos($_GET['search_string'],'"')){

		print_r($_GET['search_string']);

		if (mb_stripos($_GET['search_string'],' ')) { // поиск = несколько слов

			$string = explode(' ', $_GET['search_string']) ;
			highlight_many($text,$string);
		}
		else{ // поиск = одно слово
			highlight_one ($text, $_GET['search_string']);
		}
		
	}
	else { echo 'что происходить :(';}
}

?>

</body>
</html>