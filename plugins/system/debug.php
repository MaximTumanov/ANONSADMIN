<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.plugin.plugin' );

/**
 * Joomla! Debug plugin
 *
 * @package		Joomla
 * @subpackage	System
 */
class  plgSystemDebug extends JPlugin{

	function plgSystemDebug(& $subject, $config){
		parent::__construct($subject, $config);
	}

	function onAfterRender(){
		global $_PROFILER, $mainframe, $database;
		$rss = JRequest::getVar('type');
		if(!JDEBUG || $rss) { return; }
		
		$dump = array();
		
		$db	      =& JFactory::getDBO();		
		$profiler =& $_PROFILER;

		$path = JURI::base();
		$time = date( 'Y-m-d H:i:s', time() );			
		$file = "logs/{$_SERVER['QUERY_STRING']}.txt";
		$dump[] = "Date: {$time}\r\n\r\n";		
		  	
		ob_start();
	
        $firephp = FirePHP::getInstance(true);
        
        $firephp->group('xDebug', array(
        			  'Collapsed' => true,
                      'Color' => '#000')
        );	
        	/*--APP--*/
			$firephp->group('APP');
				$firephp->log("Memory Usage: {$profiler->getMemory()}"); 
				$dump[] = "---APP---";
				$dump[] = "Memory Usage: {$profiler->getMemory()}";
				foreach ( $profiler->getBuffer() as $mark ):
					$firephp->log($mark);
					$dump[] = $mark;
				endforeach;
				$dump[] = "---------\r\n";
	        $firephp->groupEnd();      
			/*--//--*/	        
	        
	        /*--Memcache--*/
	        if( MEMCACHE_ACTIV ){
		 		$memcache = new Memcache;
		  		$memcache->addServer(LOCALHOST);	        	
				$firephp->group('Memcache');
					$firephp->table('Details', $this->getMemCacheStats($memcache->getStats()));				
		        $firephp->groupEnd();	
	        }        
	        /*--//--*/
	        
	        /*--SQL--*/
	        $dump[] = "---SQL---";
			$firephp->group('SQL');
				$totalQuery    = "Total Query: ".count($db->getLog());
				$totalDuration = "Total Duration: {$db->getTotalDurationQuery()} sec.";
				$totalSqlCache = "Total Query from Cache: {$db->getTotalSqlFromCache()}";
				$firephp->log($totalQuery);
					$dump[] = $totalQuery;
				$firephp->log($totalDuration);
					$dump[] = $totalDuration;
				$firephp->log($totalSqlCache);
					$dump[] = $totalSqlCache;
				$firephp->table('Details', $db->FirePhpLog());				
	        $firephp->groupEnd();
			
		        if( $db->getAlertQuery() ){
		        	$firephp->group('Repeated Requests');
		        	$dump[] = "\r\nBad Requests:";
			        	$alertQuery[] = array('sql', 'count');
				        foreach( $db->getAlertQuery() as $key => $val ):
				        	$alertQuery[] = array($key, $val);
				        	$dump[] = "{$key} : {$val}";
				        endforeach;
				        $firephp->table('Details', $alertQuery);
			        $firephp->groupEnd();
	        	}			
	        	$dump[] = "---------\r\n";
	        	/*--//--*/
        $firephp->groupEnd();
		ob_get_clean();
		
		if( FIREPHPFRONT_SAVE_IN_FILE && isset($dump) ){
			jimport('joomla.filesystem.file');		
			$dump = implode("\r\n", $dump);
			JFile::write($file, $dump);
		}		
	}
	/**
	 * возвращает переделанную таблицу статистики Memcache сервера
	 * @param array $status
	 * @return array для дебагера
	 */
	function getMemCacheStats($status){
        $percCacheHit  = ((real)$status["get_hits"] / (real)$status["cmd_get"] * 100);
        $percCacheHit  = round($percCacheHit, 3);
        $percCacheMiss = 100 - $percCacheHit;
        $MBRead        = (real)$status["bytes_read"] / (1024*1024);
		$MBWrite       = (real)$status["bytes_written"] / (1024*1024);
		$MBSize        = (real)$status["limit_maxbytes"] / (1024*1024) ;
		
		$table         = array();
		$table[]       = array('val', 'key');
        $table[]       = array('Версия сервера:', $status['version']);
        $table[]       = array('Идентификатор процесса', $status['pid']);
        $table[]       = array('Сервер работает, сек', $status['uptime']);
        $table[]       = array('Общее количество элементов, хранящихся на сервере', $status['total_items']);
        $table[]       = array('Количество открытых соединений с начала старта', $status['curr_connections']);
        $table[]       = array('Общее число подключений к серверу', $status['total_connections']);
        $table[]       = array('Кол-во открытых соединений', $status['connection_structures']);
        $table[]       = array('Общее кол-во запросов выборки', $status['cmd_get']);
        $table[]       = array('Общее кол-во запросов записи', $status['cmd_set']);
        $table[]       = array('Кол-во успешных запросов, %', "{$status['get_hits']} ({$percCacheHit}%)");
        $table[]       = array('Кол-во неудачных запросов, %', "{$status['get_misses']} ({$percCacheMiss}%)");		
        $table[]       = array('Общее кол-во байтов, прочитанных с сервера', "{$MBRead} Mb");
        $table[]       = array('Общее кол-во байтов, отправленных серверу', "{$MBWrite} Mb");       
        $table[]       = array('Общее кол-во байт, разрешенных для хранения на сервере', "{$MBSize} Mb");
        $table[]       = array('Число удаленных элементов из кэша, чтобы освободить память для новых элементов', $status['evictions']);	
		return $table;		
	}
}