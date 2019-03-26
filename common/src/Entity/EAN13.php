<?php

/* ***** BEGIN LICENSE BLOCK *****

Version: MPL 1.1

The contents of this file are subject to the Mozilla Public License Version
1.1 (the "License"); you may not use this file except in compliance with
the License. You may obtain a copy of the License at
http://www.mozilla.org/MPL/


Software distributed under the License is distributed on an "AS IS" basis,
WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License
for the specific language governing rights and limitations under the
License.

The Original Code is : Debora, un gÃ©nÃ©rateur de codes barre.

The Initial Developer of the Original Code is Olivier Meunier.
Portions created by the Initial Developer are Copyright (C) 2003
the Initial Developer. All Rights Reserved.

Contributor(s):
RÃ©mi ChÃ©no (ajout des sÃ©parateurs gauche, centre et droite),
CÃ©lian VeyssiÃ¨re (passage PHP5, exeptions, revue de code & doc)

***** END LICENSE BLOCK ***** */

/**
 * CrÃ©ation d'une image de code barre Ã  partir d'une code EAN13 
 * @author Olivier Meunier (2003 - <a href="http://neokraft.net/">Neo-Kraft</a> )
 * @author RÃ©mi ChÃ©no
 * @author CÃ©lian VeyssiÃ¨re (ZÃ©fling) (2014 - <a href="http://ikilote.net/fr/Blog/Techno-magis.html">Techno-magis</a>)
 * @version 1.1 (2014-03-25)
 */
class Debora {
	
	const VERSION = 1.1;
	const VERSION_DATE = '2014-03-25';
	
	/** 
	 * @var (float) Multiplicateur de la taille de l'image initiale (120 pixel 
	 * x 70 pixel).<br> Ce qui donne avec la valeur de 4.5 une image en 300 dpi de 
	 * 4.57 cm x 2.57 cm (540 pixel x 315 pixel)
	 */
	public $dimension;
	
	/**
	 * @var (array) DÃ©claration des propriÃ©tÃ©s (group)
	 */
	protected $arryGroup = array(
		'A' => array(
			0 => "0001101", 1 => "0011001",
			2 => "0010011",	3 => "0111101",
			4 => "0100011",	5 => "0110001",
			6 => "0101111",	7 => "0111011",
			8 => "0110111",	9 => "0001011"
		),
		'B' => array(
			0 => "0100111",	1 => "0110011",
			2 => "0011011",	3 => "0100001",
			4 => "0011101",	5 => "0111001",
			6 => "0000101",	7 => "0010001",
			8 => "0001001",	9 => "0010111"
		),
		'C' => array(
			0 => "1110010",	1 => "1100110",
			2 => "1101100",	3 => "1000010",
			4 => "1011100",	5 => "1001110",
			6 => "1010000",	7 => "1000100",
			8 => "1001000",	9 => "1110100"
		)
	);
	
	/**
	 * @var (array) DÃ©claration des propriÃ©tÃ©s (family)
	 */
	protected $arryFamily = array(
		0 => array('A','A','A','A','A','A'),
		1 => array('A','A','B','A','B','B'),
		2 => array('A','A','B','B','A','B'),
		3 => array('A','A','B','B','B','A'),
		4 => array('A','B','A','A','B','B'),
		5 => array('A','B','B','A','A','B'),
		6 => array('A','B','B','B','A','A'),
		7 => array('A','B','A','B','A','B'),
		8 => array('A','B','A','B','B','A'),
		9 => array('A','B','B','A','B','A')
	);
	
	/**
	 * @var (string) chaine binaire de l'EAN13
	 */
	protected $strCode;
	
	/**
	 * @var (string) EAN13
	 */
	protected $EAN13;
	
	/**
	 * getter 
	 * @param string $name nom de la varialbe
	 * @throws Exception si la variable n'existe pas
	 */
	public function __get($name) {
		if (isset($this->$name)) {
			return $this->$name;
		} else {
			throw new Exception('Cette variable n\'existe pas.');
		}
	}

	/**
	 * Initialise la classe
	 * @param string $EAN13 code EAN13
	 * @param float $dimension Multiplicateur de la taille de l'image initiale (120 pixel Ã— 70 pixel).
	 * Ce qui donne avec la valeur de â€œ4.5â€ une image en 300 dpi de 4,57 cm Ã— 2,57 cm (540 pixel Ã— 315 pixel) (defaut : 4.5)
	 * @throws Exception si l'EAN13 est invalide 
	 */
	public function __construct($EAN13, $dimension = 4.5) {
		if (strlen($EAN13) == 13 && is_numeric($EAN13)) {
			$this->EAN13     = (string) $EAN13;
			$this->dimension = (float) $dimension;
			$this->strCode   = $this->makeCode();
		} else {
			throw new Exception('L\'EAN13 ne fait pas 13 chiffres.');
		}
	}

	/**
	 * CrÃ©ation du code binaire.
	 * CrÃ©e une chaine contenant des 0 ou des 1 pour indiquer les espace blancs ou noir.
	 * @return (string) chaine binaire
	 */
	protected function makeCode() {
		//On rÃ©cupÃ¨re la classe de codage de la partie gauche
		$arryLeftClass = $this->arryFamily[$this->EAN13[0]];

		//Premier sÃ©parateur (101)
		$strCode = '101';

		//Codage partie gauche
		for ($i = 1 ; $i < 7 ; $i++) {
			$strCode .= $this->arryGroup[$arryLeftClass[$i-1]][$this->EAN13[$i]];
		}

		//SÃ©parateur central (01010)
		$strCode .= '01010';

		//Codage partie droite (tous de classe C)
		for ($i = 7 ; $i < 13 ; $i++) {
			$strCode .= $this->arryGroup['C'][$this->EAN13[$i]];
		}

		//Dernier sÃ©parateur (101)
		$strCode .= '101';

		return $strCode;
	}

	/**
	 * CrÃ©ation de l'image. CrÃ©e une image GIF ou PNG du code gÃ©nÃ©rÃ© par giveCode.
	 * @param string $imageType type d'image (png ou gif) (dÃ©faut : png)
	 * @throws Exception si le format est invalide
	 */
	 public function makeImage($imageType = "png", $path=null) {
		if ($imageType != "png" && $imageType != "gif") {
			throw new Exception('Format incorrect"png" ou "gif".');
		}	 	
	 	
		//Initialisation de l'image
		$width  = 120;
		$height = 70;
		$img    = imagecreate($width, $height);

		$color[0] = ImageColorAllocate($img, 255, 255, 255);
		$color[1] = ImageColorAllocate($img, 0, 0, 0);

		$coords[0] = 15;
		$coords[1] = 10;
		$coords[2] = 1;
		$coords[3] = 40;

		imagefilledrectangle($img, 0, 0, 95, 80, $color[0]);
		
		$strlen = strlen($this->strCode);
		for($i = 0 ; $i < $strlen ; $i++) {
			$posX = $coords[0];
			$posY = $coords[1];
			$intL = $coords[2];
			$intH = $coords[3];

			$fill_color = substr($this->strCode, $i, 1);

			# Allongement des 3 bandes latÃ©rales et centrales
			# sur une idÃ©e de RÃ©mi ChÃ©no
			if ($i < 3 || ($i >= 46 && $i < 49) || $i >= 92) {
				$intH = $intH + 8;
			}

			imagefilledrectangle($img, $posX, $posY, $posX, ($posY+$intH), $color[$fill_color]);

			//Deplacement du pointeur
			$coords[0] = $coords[0] + $coords[2];
		}

		# Affichage du code (RÃ©mi ChÃ©no)
		imagestring($img, 3,  5, 50, $this->EAN13[0],            $color[1]);
		imagestring($img, 3, 19, 50, substr($this->EAN13, 1, 6), $color[1]);
		imagestring($img, 3, 65, 50, substr($this->EAN13, 7   ), $color[1]);

		// Calcul des nouvelles dimensions
		$newwidth  = $width  * $this->dimension;
		$newheight = $height * $this->dimension;

		// Chargement
		$thumb = imagecreatetruecolor($newwidth, $newheight);

		// Redimensionnement
		imagecopyresized($thumb, $img, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

		if ( !$path )header("Content-type: image/".$imageType);

		$func_name = 'image'.$imageType;

		$func_name($thumb, $path);
		imagedestroy($img);
		imagedestroy($thumb);
	}

}


