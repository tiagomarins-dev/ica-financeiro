<?php 

class normaliza
{
	public function normalizar($string)
	{
	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýýþÿ';
	$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuuyyby';
	$string = utf8_decode($string);
	$string = strtr($string, utf8_decode($a), $b); //substitui letras acentuadas por &quot;normais&quot;
	
	//$string = str_replace(" ","_",$string);
	//$string = str_replace(",","",$string);
	//$string = str_replace("/","",$string);
	$string = strtolower($string); // passa tudo para minusculo
	return utf8_encode($string); //finaliza, gerando uma saída para a funcao
	}

}
