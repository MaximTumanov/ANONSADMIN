<?php
jimport('joomla.application.component.model');
class FilmsModelFilms extends JModel
{
    var $tFilms = '#__films';
    var $tXref = '#__films_xref';
    var $tCinema = '#__cinema';
    var $eTime = '#__films_time';
    /**
     * @var string $id поле в таблице с PRIMARY KEY
     */
    var $id = 'id_films';

    /**
     * возвращает кол-во записей с учетом фильтров
     * @return int
     */
    function getItemsCount($lists)
    {
        $db = &JFactory::getDBO();
        $q = " SELECT count(*) FROM `{$this->tFilms}` as c WHERE `id_films` > 0 ";

        if ($lists['time']) {
            $db->setQuery("SELECT GROUP_CONCAT(distinct id_films) FROM `{$this->eTime}` WHERE `time` >= '{$lists['time']}'");
            $id_films = $db->loadResult();
            if (!$id_films) return null;
            $q .= " AND c.id_films IN({$id_films}) ";
        }

        $db->setQuery($q);
        return $db->loadResult();

        ;
    }

    /**
     * получает массив записей с учетом фильтров
     * @param array $lists array('order' => по какому полю сортируем, 'order_Dir' => ASC или DESC)
     * @param int $limit кол-во записей
     * @param int $limitstart с какой записи начинать отчет
     * @return array
     */
    function getItems($lists, $limit, $limitstart)
    {
        $db = &JFactory::getDBO();

        $db->setQuery("SET SESSION group_concat_max_len = 16384");
        $db->query();

        $order = "";
        if ($lists['order'] && $lists['order_Dir'])
            $order = " ORDER BY {$lists['order']} {$lists['order_Dir']} ";


        $q = " SELECT c.* FROM `{$this->tFilms}` AS c WHERE `id_films` > 0 ";
        if ($lists['date_from']) {
            $db->setQuery("SELECT GROUP_CONCAT(distinct id_films) FROM `{$this->eTime}` WHERE `date` >= '{$lists['time']}'");
            $id_films = $db->loadResult();
            if (!$id_films) return null;
            $q .= " AND c.id_films IN({$id_films}) ";
        }

        $q .= $order;
        $db->setQuery($q, $limitstart, $limit);
        return $db->loadObjectList();
    }

    /**
     * возвращает информацию об объявлении
     * @param int $id ID объявления
     * @return object
     */
    function getItem($id)
    {
        $db = &JFactory::getDBO();
        $db->setQuery("SELECT * FROM {$this->tFilms} WHERE `{$this->id}` = '{$id}' ");
        return $db->loadObject();
    }


    function getMoreInfo($id)
    {
        $db = &JFactory::getDBO();
        $db->setQuery("SELECT GROUP_CONCAT(id_cinema)  as `idCinema` FROM `{$this->tXref}` WHERE `{$this->id}` = '{$id}'");
        return $db->loadObject();
    }


    function getCinema($id)
    {
        $db = &JFactory::getDBO();
        $db->setQuery("SELECT `id_cinema`, `title` FROM `{$this->tCinema}` WHERE `id_cinema` = '{$id}'");
        return $db->loadObject();
    }

    function getCinemaList()
    {
        $db = &JFactory::getDBO();
        $db->setQuery("SELECT `id_cinema` as `value`, `title` as `text` FROM `{$this->tCinema}` WHERE `id_cinema` != '0' ORDER BY `title`");
        return $db->loadObjectList();
    }

    function getIdCinema($id)
    {
        $db = &JFactory::getDBO();
        $db->setQuery("SELECT `id_cinema` FROM `{$this->tXref}` WHERE `{$this->id}` = '{$id}'");
        return $db->loadResult();
    }


    #PHP IS SHIT!!!!!!!!!!!!!!!!!!!!!!!!!
    function getTimeAtFilm($id) {
      $db = &JFactory::getDBO();
      $db->setQuery("SELECT GROUP_CONCAT(time) as `time_at` FROM `{$this->eTime}` WHERE `{$this->id}` = '{$id}'");
      $res = $db->loadResult();
      if ($res) {
        $res = explode(',', $res);
        sort($res);
        return $res;
      } else {
        return array();
      }
    }


    function getFilmsDate($id, $fromTo = false)
    {
        $db = &JFactory::getDBO();
        if ($fromTo) {
            $db->setQuery("SELECT `time` FROM `{$this->eTime}` WHERE `{$this->id}` = '{$id}'");
            $res = $db->loadObjectList();

            $date = new stdClass();
            $date->time = null;


            if ($res) {
                foreach ($res as $key => $val):
                    if (!$date->time) {
                        $date->time = $val->time;
                    }


                endforeach;
                $result = $date;
            }

        } else {
            $db->setQuery("SELECT `time` FROM `{$this->eTime}` WHERE `{$this->id}` = '{$id}'");
            $result = $db->loadObject();
        }
        return $result;
    }


    /**
     * публикует выбранные записи
     * @param string $cids список ID записей через запятую (1,2,3,4....)
     * @return void
     */
    function _publish($cids)
    {
        $db = &JFactory::getDBO();
        $q = "UPDATE `{$this->tFilms}` SET `published` = '1' where `{$this->id}` IN({$cids})";
        $db->setQuery($q);
        $db->query();
    }

    /**
     * снимает с публикации выбранные записи
     * @param string $cids список ID записей через запятую (1,2,3,4....)
     * @return void
     */
    function _unpublish($cids)
    {
        $db = &JFactory::getDBO();
        $q = "UPDATE `{$this->tFilms}` SET `published` = '0' where `{$this->id}` IN({$cids})";
        $db->setQuery($q);
        $db->query();
    }

    function _premiere($cids)
    {
        $db = &JFactory::getDBO();
        $q = "UPDATE `{$this->tFilms}` SET `premiere` = '1' where `{$this->id}` IN({$cids})";
        $db->setQuery($q);
        $db->query();
    }

    function _unpremiere($cids)
    {
        $db = &JFactory::getDBO();
        $q = "UPDATE `{$this->tFilms}` SET `premiere` = '0' where `{$this->id}` IN({$cids})";
        $db->setQuery($q);
        $db->query();
    }

    /**
     * удаляет выбранные записи
     * @param string $cids список ID записей через запятую (1,2,3,4....)
     * @return void
     */
    function _removeSelect($cids)
    {
        $db = &JFactory::getDBO();
        $q = " DELETE FROM `{$this->tFilms}` WHERE {$this->id} IN({$cids}) ";
        $db->setQuery($q);
        $db->query();
    }

    function _save($title, $alias, $img, $s_desc, $desc, $premiere, $video, $date_first, $date_last, $genre, $duration, $director, $country, $actors, $_3d, $published, $id_cinema, $times, $k_title, $k_desc, $k_keyw, $time_at, $original_name)
    {
        $db = &JFactory::getDBO();
        $q = "INSERT INTO `{$this->tFilms}` VALUES('', '{$title}', '{$alias}', '{$img}', '{$s_desc}', '{$desc}', '{$premiere}', '{$video}', '{$date_first}', '{$date_last}', '{$genre}', '{$duration}', '{$director}', '{$country}', '{$actors}', '{$_3d}', '{$published}', '{$k_title}', '{$k_desc}', '{$k_keyw}', '{$original_name}')";
        $db->setQuery($q);
        $db->query();

        $id_films = $db->insertid();

        // заполняем таблицу XREF

        $db->setQuery("INSERT INTO `{$this->tXref}` VALUES('{$id_films}', '{$id_cinema}')");
        $db->query();

        if ($time_at) {
            foreach ($time_at as $key => $value) {
                $db->setQuery("INSERT INTO `{$this->eTime}` VALUES('{$id_films}', '{$value}')");
                $db->query();                
            }
        }

    }

    /**
     * перезаписывает информацию о комментарии
     * @param int    $id_comment  ID комментария
     * @param string $text текст  комментария
     */
    function _update($id_films, $title, $alias, $img, $s_desc, $desc, $premiere, $video, $date_first, $date_last, $genre, $duration, $director, $country, $actors, $_3d, $published, $id_cinema, $time, $k_title, $k_desc, $k_keyw, $time_at, $original_name)
    {
        $db = &JFactory::getDBO();
        $q = "UPDATE `{$this->tFilms}` SET
			`title`   = '{$title}',
			`alias`   = '{$alias}',
			`image`   = '{$img}',
			`s_desc`  = '{$s_desc}',
			`desc`    = '{$desc}',

			`premiere` = '{$premiere}',
			`video`    = '{$video}',
			`date_first`     = '{$date_first}',
			`date_last`     = '{$date_last}',
			

			`genre` = '{$genre}',
			`duration`    = '{$duration}',
			`director`     = '{$director}',
			`country`     = '{$country}',
			`actors` = '{$actors}',

			`_3d`    = '{$_3d}',
			`published`     = '{$published}',
			

			`k_title` = '{$k_title}',
			`k_desc` = '{$k_desc}',
			`k_keyw` = '{$k_keyw}',

            `original_name` = '{$original_name}'
		WHERE `{$this->id}` = '{$id_films}'";
        $db->setQuery($q);

        $db->query();

        // перезаписываем таблицу XREF для выбранного события
        $db->setQuery("DELETE FROM `{$this->tXref}` WHERE `{$this->id}` = '{$id_films}'");
        $db->query();

        $db->setQuery("INSERT INTO `{$this->tXref}` VALUES('{$id_films}', '{$id_cinema}')");
        $db->query();

        $db->setQuery("DELETE FROM `{$this->eTime}` WHERE `{$this->id}` = '{$id_films}'");
        $db->query();   

        if ($time_at) {
            foreach ($time_at as $key => $value) {
                $db->setQuery("INSERT INTO `{$this->eTime}` VALUES('{$id_films}', '{$value}')");
                $db->query();                
            }
        }

    }
}

?>