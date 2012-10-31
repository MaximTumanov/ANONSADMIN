<?php 
class EventsControllerParser extends EventsController {
	/**
	* @var string $view вид
	*/
	var $view      = 'parser';
	/**
	* @var string $component компонент
	*/
	var $component = 'com_events';
	var $model;
	
	function __construct(){
		parent::__construct();
		$this->registerTask('save', 'save');
		$this->model = $this->getModel('Parser');
	}

	function gorod_dp_category_to_our($category){
		$cache = array(
			2 => array('Поэзия'), 
			/*Литература*/
			3 => array(), 
			/*Психология*/
			4 => array('Живопись'), 
			/*Арт*/
			5 => array('Рок', 'Блюз', 'Джаз', 'Классическая музыка', 'Караоке', 'Рэп (Хип-хоп)', 'Вокальная музыка'), 
			/*Концерт*/
			6 => array('Танец'), 
			/*Танцы*/
			7 => array(), 
			/*Этно*/
			8 => array('Ярмарка', 'Археология', 'История', 'Керамика', 'Скульптура', 'Фото'), 
			/*Выставки*/
			9 => array(), 
			/*Кино*/
			10 => array(), 
			/*Музеи*/
			11 => array('Фото'), 
			/*Фото*/
			12 => array('Ролевая игра'), 
			/*Ролевики*/
			13 => array(), 
			/*Театр*/
			14 => array('Спорт', 'Экстрим'), 
			/*Активный отдых*/
			15 => array(), 
			/*Ночной клуб*/
			16 => array(), 
			/*Ремесла*/
			17 => array('Творческий вечер'), 
			/*Творческий вечер*/
			18 => array(), 
			/*ВУЗы*/
			19 => array('Мастер-класс'), 
			/*Мастер класс*/
			20 => array('Клубная вечеринка'), 
			/*Вечеринка*/
			21 => array(), 
			/*Флешмоб*/
			22 => array(),
			 /*Фестиваль*/
			23 => array(), 
			/*Тренинг*/
			24 => array(), 
			/*Кафе*/
			25 => array() 
			/*Ресторан*/
		);

		$id_category = false;
		foreach ($cache as $key => $gdp) {
			foreach ($gdp as $cat) {
				if( $cat == $category ) return array($key);
			}
		}
		return $id_category;
	}

	function save(){
		$is_debug = false;
		require('../simple_html_dom.php');
		$links = explode("\n", $_POST['links']);


		echo '<style type="text/css">
						.red{background: #EC7979; padding: 3px; display: inlini-block;}
						.yellow{background: #F7F76D; color: #000; padding: 3px; display: inlini-block;}
						.green{background: #ADE792; padding: 3px; display: inlini-block;}
					</style>';
		echo "<p><b>Итак, что-же получилось</b>:</p>";

		foreach($links as $link){
			$valid = true;
			$not_valid_text = '';
			//$html = file_get_html($link);

			$html = new simple_html_dom(); // создаем объект
			$html->load(iconv('windows-1251', 'utf-8', file_get_contents(trim($link)))); //загружаем HTML-код

			#TITLE
			$title = $html->find('.mainbox2', 0)->find('h4', 0)->plaintext;

			$categories = $html->find('.mainbox2', 0)->find('a', 0)->plaintext;
			$cat_arr = explode(',', $categories);
			
			$notice_message = array();
			if (!empty($cat_arr)){
				$category = $this->gorod_dp_category_to_our($categories);
				if (!$category) {
					$category = 2;
					$notice_message[] = 'Check category!';
				}
			}

			if ($title){
				$alias = JString::translit($title);
				if ($this->model->check_alias($alias) > 0) {
					$valid = false;
					$not_valid_text = 'Present in database';
				} else {
					#DESC

					#$desc_all = $html->find('.mainbox2', 0)->find('.norm9', 0)->find('p');
					$desc_all = $html->find('.mainbox2', 0)->find('.norm9', 0)->outertext;
					if ($is_debug) echo "<textarea>{$desc_all}</textarea>";
					if ($desc_all){
						#$desc_arr = array();
						#foreach($desc_all as $key => $el){
							#if ($key == (count($desc_all) - 1)) break;
							#$desc_arr[] = preg_replace("/(style=\".+?\"|style=\'.+?\'|onclick=\".+?\")/", "", $el);
						#}
						$desc = '';
						#$desc = mysql_real_escape_string(implode('', $desc_arr));
						if(preg_match("|(.*?)<div class=clear>|m", $desc_all, $matches)){
							if ($is_debug) print_r($matches);
							if(preg_match("|<br[^>]+><br[^>]+>(.*)|m", $matches[1], $desc_arr)){
								if ($is_debug) print_r($desc_arr);
								$desc = mysql_real_escape_string($desc_arr[1]);
							} else {
								$notice_message[] = 'Check Description';
							}
						} else {
							$notice_message[] = 'Check Description';
						}

						#IMG
						if ($html->find('.mainbox2',0) && $html->find('.mainbox2',0)->find('td.norm8', 0) && $html->find('.mainbox2',0)->find('td.norm8', 0)->find('a', 0) && $img_href = $html->find('.mainbox2',0)->find('td.norm8', 0)->find('a', 0)->href) {
							$img_href = $html->find('.mainbox2',0)->find('td.norm8', 0)->find('a', 0)->href;
						} else { 
							$img_href = ''; 
							$notice_message[] = 'Check image';
						}

								if ($img_href) {
									$img_page = file_get_html($img_href);
									$img = $img_page->find('div.page', 0)->find('div[style="padding:1em 0 1em 0;background-color:#fafafa;"]', 0)->find('img', 0)->src;
									$img_arr = explode('.', $img);
									$img_ext = $img_arr[(count($img_arr) - 1)];
									$img_name = "e_".md5($img).".{$img_ext}";

									if ($file = fopen("../images/sunny/events/events/{$img_name}", a)){
										fwrite($file, file_get_contents($img));
										fclose($file);
									} else {
										$valid = false;
										$not_valid_text = 'Image not save';
									}
								}

								#PLACE
								$id_place = $_POST['id_place'];

								#PRICE
								$price = '';

								#DATES
								$type = 1;
								$dates = new stdClass();
								$dates_str = $html->find('.mainbox2', 0)->find('.norm8', 0)->find('.blue', 0)->plaintext;
								$dates_arr = explode(' - ', $dates_str);

								$time = "00:00";
								if($html->find('.mainbox2', 0) && $html->find('.mainbox2', 0)->find('.norm8', 0) && $html->find('.mainbox2', 0)->find('.norm8', 0)->find('.norm8', 0) && $html->find('.mainbox2', 0)->find('.norm8', 0)->find('.norm8', 0)->find('b', 0)->plaintext){
									$time_arr = explode(':', $time_str);
									if (count($time_arr) == 2) { $time = $time_str; }
								} else {
									$notice_message[] = 'Check date and time';
								}

								if (count($dates_arr) == 2) {
									$type = 5;
									$dates->dateFrom = $this->_parse_date(explode(' ', $dates_arr[0]));
									$dates->dateTo = $this->_parse_date(explode(' ', $dates_arr[1]));
									$dates->time = $time;

								} elseif (count($dates_arr) == 1) {
									$d_p = explode(' ', $dates_arr[0]); 
									if (count($d_p) == 3 && strlen($d_p[2]) == 4) {
										$type = 1;
										$dates->date = $this->_parse_date($d_p);
										$dates->time = $time;
									} else {
										$dates->date = date("2012-01-01");
										$notice_message[] = 'Check date and time';
									}
								}


								if($valid){
									$lastId = $this->model->_save($title, $alias, $img_name, '', $desc, '', $type, 0, 0, 0, $category, $id_place, $dates, '', '', '', $price);

									if(!$lastId){
										$not_valid_text = 'Record not save';
										$valid = false;
									}
								}

					} else {
						$valid = false;
						$not_valid_text = 'Description is not defined';
					}
				}

			} else {
				$valid = false;
				$not_valid_text = 'Title is not defined';
			}

			if ($valid) {
				if (!empty($notice_message)){
					echo "<div style='padding: 10px 0'><span class='yellow'>{$link} -> TRUE -> <a href='http://adminka.anons.dp.ua/administrator/index.php?option=com_events&view=event&layout=form&task=edit&id={$lastId}' target='_blank'>[". implode(',', $notice_message) ."] -> {$title}</a></span></div>";
				} else {
				echo "<div style='padding: 10px 0'><span class='green'>{$link} -> TRUE -> <a href='http://adminka.anons.dp.ua/administrator/index.php?option=com_events&view=event&layout=form&task=edit&id={$lastId}' target='_blank'>{$title}</a></span></div>";
				}
			} else {
				echo "<div style='padding: 10px 0'><span class='red'>{$link} -> FALSE -> {$not_valid_text}</span></div>";
			}

		}
	}
	
	function _parse_date($arr){
		$m = array(
			'января' => 01, 'февраля' => 02, 'марта' => 03, 'апреля' => 04, 'мая' => 05, 'июня' => 06,
			'июля' => 7, 'августа' => 8, 'сентября' => 9, 'октября' => 10, 'ноября' => 11, 'декабря' => 12
		);

		return "{$arr[2]}-{$m[$arr[1]]}-{$arr[0]}";
	}

}

?>