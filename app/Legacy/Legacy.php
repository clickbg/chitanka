<?php namespace App\Legacy;

use App\Util\Char;
use App\Util\Ary;
use App\Util\Number;
use Buzz\Browser;

class Legacy {

	static private $months = array(
		1 => 'Януари', 'Февруари', 'Март', 'Април', 'Май', 'Юни',
		'Юли', 'Август', 'Септември', 'Октомври', 'Ноември', 'Декември'
	);

	static private $types = array(
		// code => array(singular, plural, sing. article, pl. article)
		'anecdote' => array('Анекдот', 'Анекдоти', 'анекдота', 'анекдотите'),
		'fable' => array('Басня', 'Басни', 'баснята', 'басните'),
		'biography' => array('Биография', 'Биографии', 'биографията', 'биографиите'),
		'dialogue' => array('Диалог', 'Диалози', 'диалога', 'диалозите'),
		'docu' => array('Документалистика', 'Документалистика', 'книгата', 'книгите'),
		'essay' => array('Есе', 'Есета', 'есето', 'есетата'),
		'interview' => array('Интервю', 'Интервюта', 'интервюто', 'интервютата'),
		'gamebook' => array('Книга игра', 'Книги игри', 'книгата игра', 'книгите игри'),
		'memo' => array('Мемоари/спомени', 'Мемоари/спомени', 'творбата', 'творбите'),
		'science' => array('Научен текст', 'Научни текстове', 'научният текст', 'научните текстове'),
		'popscience' => array('Научнопопулярен текст', 'Научнопопулярни текстове', 'творбата', 'творбите'),
		'novelette' => array('Новела', 'Новели', 'новелата', 'новелите'),
		'ocherk' => array('Очерк', 'Очерци', 'очерка', 'очерците'),
		'shortstory' => array('Разказ', 'Разкази', 'разказа', 'разказите'),
		'review' => array('Рецензия', 'Рецензии', 'рецензията', 'рецензиите'),
		'novel' => array('Роман', 'Романи', 'романа', 'романите'),
		#'parable' => array('Парабола', 'Параболи', 'параболата', 'параболите'),
		'play' => array('Пиеса', 'Пиеси', 'пиесата', 'пиесите'),
		'letter' => array('Писмо', 'Писма', 'писмото', 'писмата'),
		'poetry' => array('Поезия', 'Поезия', 'поетичната творба', 'поетичните творби'),
		'poem' => array('Поема', 'Поеми', 'поемата', 'поемите'),
		'novella' => array('Повест', 'Повести', 'повестта', 'повестите'),
		'outro' => array('Послеслов', 'Послеслови', 'послеслова', 'послесловите'),
		'intro' => array('Предговор', 'Предговори', 'предговора', 'предговорите'),
		'tale' => array('Приказка', 'Приказки', 'приказката', 'приказките'),
		'pritcha' => array('Притча', 'Притчи', 'притчата', 'притчите'),
		'travelnotes' => array('Пътепис', 'Пътеписи', 'пътеписа', 'пътеписите'),
		'speech' => array('Реч', 'Речи', 'речта', 'речите'),
		'article' => array('Статия', 'Статии', 'статията', 'статиите'),
		'prosepoetry' => array('Лирика в проза', 'Лирика в проза', 'стихотворението', 'стихотворенията'),
		'screenplay' => array('Сценарий', 'Сценарии', 'сценария', 'сценариите'),
		'textbook' => array('Учебник', 'Учебници', 'учебника', 'учебниците'),
		'feuilleton' => array('Фейлетон', 'Фейлетони', 'фейлетона', 'фейлетоните'),
		'haiku' => array('Хайку', 'Хайку', 'поетичната творба', 'поетичните творби'),
		'jure' => array('Юридически текст', 'Юридически текстове', 'юридическият текст', 'юридическите текстове'),
		'critique' => array('Литературна критика', 'Литературна критика', 'творбата', 'творбите'),
		'philosophy' => array('Философски текст', 'Философски текст', 'творбата', 'творбите'),
		'religion' => array('Религиозен текст', 'Религиозен текст', 'творбата', 'творбите'),
		'historiography' => array('Историография', 'Историография', 'творбата', 'творбите'),
		'collection' => array('Сборник', 'Сборник', 'творбата', 'творбите'),

		'other' => array('Разни', 'Разни', 'творбата', 'творбите'),
	);

	/**
	 * @param string $code
	 * @param bool $singular
	 * @return string
	 */
	static public function workType($code, $singular = true) {
		if ( !array_key_exists($code, self::$types) ) return '';
		return $singular ? self::$types[$code][0] : self::$types[$code][1];
	}

	/**
	 * @param string $code
	 * @param bool $singular
	 * @return string
	 */
	static public function workTypeArticle($code, $singular = true) {
		if ( !array_key_exists($code, self::$types) ) return '';
		return $singular ? self::$types[$code][2] : self::$types[$code][3];
	}

	/**
	 * @param bool $singular
	 * @return array
	 */
	static public function workTypes($singular = true) {
		$ntypes = array();
		foreach (self::$types as $code => $name) {
			$ntypes[$code] = $singular ? self::$types[$code][0] : self::$types[$code][1];
		}
		return $ntypes;
	}

	static private $picTypes = array(
		'magazine' => 'Списание'
	);
	/**
	 * @param string $code
	 * @return string
	 */
	static public function picType($code) {
		if ( !array_key_exists($code, self::$picTypes) ) return '';
		return self::$picTypes[$code];
	}

	static private $seriesTypes = array(
		// code => array(singular, plural, sing. article, pl. article)
		'newspaper' => array('вестник', 'вестници', 'вестника', 'вестниците'),
		'series' => array('серия', 'серии', 'серията', 'сериите'),
		'collection' => array('сборник', 'сборници', 'сборника', 'сборниците'),
		'poetry' => array('стихосбирка', 'стихосбирки', 'стихосбирката', 'стихосбирките'),
	);

	static private $pseudoSeries = array('collection', 'poetry');

	/**
	 * @param string $code
	 * @return string
	 */
	static public function seriesSuffix($code) {
		return $code == 'series' || empty(self::$seriesTypes[$code][0])
			? ''
			: ' ('. self::$seriesTypes[$code][0] .')';
	}

	/**
	 * @param string $code
	 * @param bool $singular
	 * @return string
	 */
	static public function seriesType($code, $singular = true) {
		if ( !array_key_exists($code, self::$seriesTypes) ) return '';
		return $singular ? self::$seriesTypes[$code][0] : self::$seriesTypes[$code][1];
	}

	/**
	 * @param string $code
	 * @param bool $singular
	 * @return string
	 */
	static public function seriesTypeArticle($code, $singular = true) {
		if ( !array_key_exists($code, self::$seriesTypes) ) return '';
		return $singular ? self::$seriesTypes[$code][2] : self::$seriesTypes[$code][3];
	}

	/**
	 * @param string $type
	 * @return bool
	 */
	static public function isPseudoSeries($type) {
		return in_array($type, self::$pseudoSeries);
	}

	/**
	 * @param string|\DateTime $isodate
	 * @return string
	 */
	static public function humanDate($isodate = '') {
		$format = 'Y-m-d H:i:s';
		if ( empty($isodate) ) {
			$isodate = date($format);
		} else if ($isodate instanceof \DateTime) {
			$isodate = $isodate->format($format);
		}

		if ( strpos($isodate, ' ') === false ) { // no hours
			$ymd = $isodate;
			$hours = '';
		} else {
			list($ymd, $his) = explode(' ', $isodate);
			list($h, $i, $s) = explode(':', $his);
			$hours = " в $h:$i";
		}

		list($y, $m, $d) = explode('-', $ymd);

		return ltrim($d, '0') .' '. Char::mystrtolower(self::monthName($m)) .' '. $y . $hours;
	}

	/**
	 * @param var $var
	 * @param mixed $value
	 */
	static public function fillOnEmpty(&$var, $value) {
		if ( empty($var) ) {
			$var = $value;
		}
	}

	/**
	 * @param var $var
	 * @param mixed $value
	 */
	static public function fillOnNull(&$var, $value) {
		if ( is_null($var) ) {
			$var = $value;
		}
	}

	/**
	 * @param int $m
	 * @param bool $asUpper
	 * @return string
	 */
	static public function monthName($m, $asUpper = true) {
		$name = self::$months[(int)$m];

		return $asUpper ? $name : Char::mystrtolower($name);
	}

	/**
	 * @param $val Value
	 * @param $data Associative array
	 * @param $defVal Default value
	 * @return $val if it exists in $data, otherwise $defVal
	 */
	static public function normVal($val, $data, $defVal = null) {
		self::fillOnNull($defVal, @$data[0]);
		return in_array($val, $data) ? $val : $defVal;
	}

	static private $templates = array(
		'{SITENAME}' => '{SITENAME}',
	);

	/**
	 * @param string $s
	 * @return string
	 */
	static public function expandTemplates($s) {
		return strtr($s, self::$templates);
	}

	/**
	 * @param string $key
	 * @param string $val
	 */
	static public function addTemplate($key, $val) {
		self::$templates['{'.$key.'}'] = $val;
	}

	/**
	 * Remove diacritic characters from a latin string
	 * Never run this function on a string with cyrillic letters: they all get converted to "Y".
	 * @param string $s
	 * @return string
	 */
	static public function removeDiacritics($s) {
		return strtr(utf8_decode($s),
			utf8_decode(
			'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ'),
			'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
	}

	/**
	 * Removes trailing zeros after the decimal sign
	 * @param string $number
	 * @return string
	 */
	static public function rmTrailingZeros($number, $decPoint = ',') {
		$number = rtrim($number, '0');
		$number = rtrim($number, $decPoint); // remove the point too
		return $number;
	}

	static public function getMaxUploadSizeInMiB() {
		return Number::int_b2m(Number::iniBytes(ini_get('upload_max_filesize')));
	}

	/**
	 * @param int $num
	 * @param string $sing
	 * @param string $plur
	 * @param string $null
	 * @return string
	 */
	static public function chooseGrammNumber($num, $sing, $plur, $null = '') {
		settype($num, 'int');
		if ($num > 1) {
			return $plur;
		} else if ($num == 1) {
			return $sing;
		} else {
			return empty($null) ? $plur : $null;
		}
	}

	/**
	 * @param string $string
	 * @return bool
	 */
	static public function isUrl($string) {
		return strpos($string, 'http://') === 0;
	}

	/**
	 * @param string $words
	 */
	static public function getAcronym($words) {
		$acronym = '';
		$words = preg_replace('/[^a-zA-Z\d ]/', '', $words);
		foreach ( explode(' ', $words) as $word ) {
			$acronym .= empty($word) ? '' : $word[0];
		}
		return strtoupper($acronym);
	}

	/**
	 * @param array $assocArray
	 * @param UserPage $object
	 */
	static public function extract2object($assocArray, &$object) {
		foreach ( (array) $assocArray as $key => $val ) {
			if ( ctype_alnum($key[0]) ) {
				$object->$key = $val;
			}
		}
	}

	/**
	 * @param string $url
	 * @param array $postData
	 * @return string
	 */
	static public function getFromUrl($url, array $postData = array()) {
		$ch = curl_init();

		$options = array(
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,    // return content
			CURLOPT_HEADER         => false,   // don't return headers
			CURLOPT_CONNECTTIMEOUT => 30,      // timeout on connect
			CURLOPT_TIMEOUT        => 60,      // timeout on response
			CURLOPT_USERAGENT      => 'Mylib (http://chitanka.info)',
			CURLOPT_FOLLOWLOCATION => true,
		);
		if ( ! empty($postData)) {
			$options[CURLOPT_POST] = true;
			$options[CURLOPT_POSTFIELDS] = $postData;
		}

		curl_setopt_array($ch, $options);
		$contents = curl_exec($ch);

		curl_close($ch);

		return $contents;
	}

	/**
	 * @param string $url
	 * @param int $cacheTime
	 * @return string
	 */
	static public function getFromUrlOrCache($url, $cacheTime = 0) {
		$id = md5($url);
		$action = 'url';

		if ( $cacheTime && CacheManager::cacheExists($action, $id, $cacheTime) ) {
			return CacheManager::getCache($action, $id);
		}

		$content = self::getFromUrl($url);
		if ( empty($content) ) {
			return '';
		}

		return CacheManager::setCache($action, $id, $content);
	}

	/**
	 * @param string $url
	 * @param \Buzz\Browser $browser
	 * @param int $cacheDays
	 * @return string
	 */
	static public function getMwContent($url, Browser $browser, $cacheDays = 7) {
		$id = md5($url);
		$action = 'info';

		if ( CacheManager::cacheExists($action, $id, $cacheDays) ) {
			return CacheManager::getCache($action, $id);
		}

		try {
			$response = $browser->get("$url?action=render", array('User-Agent: Mylib (http://chitanka.info)'));
			if ($response->isOk()) {
				$content = self::processMwContent($response->getContent(), $url);
				return CacheManager::setCache($action, $id, $content);
			}
		} catch (\RuntimeException $e) {
			return null;
		}

		return null;
	}

	/**
	 * @param string $content
	 * @param string $url
	 * @return string
	 */
	static protected function processMwContent($content, $url) {
		$up = parse_url($url);
		$server = "$up[scheme]://$up[host]";
		$content = strtr($content, array(
			'&nbsp;' => '&#160;',
			' href="/wiki/' => ' href="'.$server.'/wiki/',
		));
		$patterns = array(
			'/rel="[^"]+"/' => '',
			// images
			'| src="(/\w)|' => " src=\"$server$1",
		);
		$content = preg_replace(array_keys($patterns), array_values($patterns), $content);

		$content = sprintf('<div class="editsection">[<a href="%s?action=edit" title="Редактиране на статията">±</a>]</div>', $url) . $content;

		return $content;
	}

	/**
	 * @param string $pass
	 * @param int $loops
	 */
	static public function sha1_loop($pass, $loops = 1) {
		for ($i=0; $i < $loops; $i++) {
			$pass = sha1($pass);
		}

		return $pass;
	}

}
