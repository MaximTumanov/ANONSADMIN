<?php 
	$db = JFactory::getDBO();	
	$q = "SELECT 
			distinct 
			event.title,
			event.id_event as eventId,
			MIN(dates.date) as `date`,
			cat.id_category as catId,
			cat.icon
		  FROM `#__events` as event
		  	JOIN `#__events_dates` as `dates` ON event.id_event = dates.id_event
		  	JOIN `#__events_xref` as `xref` ON event.id_event = xref.id_event
		  	JOIN `#__events_category` as `cat` ON xref.id_category = cat.id_category
		  WHERE dates.date >= NOW() AND dates.type = '3' AND event.published  = '1' GROUP BY event.id_event ORDER BY dates.date ";
	$db->setQuery($q);
	$eventList = $db->loadObjectList();

?>
	<div class="left upcoming">
            	<ul>
            	    <li>
                    	<h2 class="title fiolet bold">Ближайшие события</h2>
                        
                        <dl class="dotted">
                        <?php 
                        	foreach ($eventList as $event):
                        	$dayHref   = JRoute::_("index.php?option=com_events&layout=day&day={$event->date}&Itemid=" . ITEMID_EVENTS);
                        	$eventHref = JRoute::_("index.php?option=com_events&layout=item&catid={$event->catId}&id={$event->eventId}&Itemid=" . ITEMID_EVENTS);
                        ?>
                            <dt>
                            	<i class="icons park"></i>
                                <a href="<?php echo $dayHref?>" class="date color-orang"><?php echo JHTML::date($event->date, JText::_('DATE_FORMAT_LC3'))?></a>
                                <span class="small"><a title="<?php echo $event->title?>" class="darklink" href="<?php echo $eventHref?>"><?php echo $event->title?></a></span>
                            </dt>
                        <?php endforeach;?>                         
                            <dd>
                            	<i class="icons details"></i>
                            	<a href="<?php echo JRoute::_("index.php?option=com_events&layout=all&Itemid=" . ITEMID_EVENTS)?>" class="date color-orang">Остальные события</a>
                            </dd>
            
                        </dl>
                    </li>
               </ul>
			</div>