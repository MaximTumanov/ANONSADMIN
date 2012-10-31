<?php 
jimport('joomla.application.component.controller');
class EventsController extends JController {
	function display() {	
		parent::display();
	}
	
	function getEvents() {
		$model = $this->getModel();
		$from = explode('.', JRequest::getVar('from'));
		$from = "{$from[2]}-{$from[1]}-{$from[0]}";

		$to = explode('.', JRequest::getVar('to'));
		$to = "{$to[2]}-{$to[1]}-{$to[0]}";
		
		$events = $model->getEventsDates($from, $to);
		$resp = array();
		
		if ($events) {
			$resp = explode(',', $events);
		}
		
		echo json_encode($resp);
	}
	
	function getAll() {
		$model = $this->getModel();
		$ids = JRequest::getVar('ids');
		$eventList = $model->getAllRecomended($ids);
		
		$html = "";
		 
		foreach ($eventList as $event):
		list($day, $month, $year, $time) = explode(' ', JHTML::date($event->date, JText::_('DATE_FORMAT_LC5')));
		$eventHref = JRoute::_("index.php?option=com_events&layout=item&catid={$event->catId}&id={$event->id_event}&Itemid=" . ITEMID_EVENTS);
					
		$html .= '<div class="left main_event">
					<div class="comment-column clear">
						<div class="comment justify pointer" href="'.$eventHref.'" title="'.$event->title.'">
							<div class="who clear">
						  		<a href="'.$eventHref.'" title="'.$event->title.'">'.JHTML::MegaImg('events/events', $event->image, 'img_small', 'float: left', $event->title, EVENT_IMG_FRONT).'</a>
						 		<p class="date"><span>'.$day.'</span> '.$month.' <span>'.$time.'</span></p>
						 		<p class="href"><a class="block" title="'.$event->title.'" href="'.$eventHref.'">'.$event->title.'</a></p>
						 		<u>'.(($event->place_dop_title) ? "{$event->place_dop_title}" : $event->place_title).'></u>
								'.JString::cropstr($event->s_desc, 30).'
							</div>			    	
						</div>
					</div>
				  </div>';	
		endforeach;
		
		echo $html;
	}
}
?>