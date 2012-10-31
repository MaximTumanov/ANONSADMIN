var host = 'http://' + window.location.host + '/',
	dialog, calendar, social_nets, user, lang = 'ru';


var i18n = {
	user_not_found: {
		ru: "Только для авторизированных пользователей"
	},
	event_not_found: {
		ru: "Извините, но что-то пошло не так!"
	}
}

var Pagination = function () {
	this.Box = $('.wrapp');
	this.Button = $('.letters .hse');
	this.Items = $('.place_line', this.Box);
	this.Pages = 1;
	this.ItemPerPage = items_per_page;
	this.PageNumber = $('#pag_number');
	
	this.hash = window.location.hash;	
	
	this.init = function () {
		this.initControls();
		this.Pages = Math.ceil(this.Items.size() / this.ItemPerPage);
		if (this.hash) {
			this.Button.removeClass('activ');
			var tmp = this.hash.replace('#', '');
			//$('.l-' + tmp).addClass('activ');
			this.build(false);
			$('.letters li[class*=l-' + tmp + ']').click();
		} else {
			this.build(false);
		}	
	}
	
	this.initControls = function () {
		var self = this;
		
		this.Button.click(function () {
			var letter = $(this).text();
			self.Button.removeClass('activ');
			$(this).addClass('activ');
			window.location.hash = $(this).text();
			self.build(letter);
		});
		
		$('#pag_number span').live('click', function () {
			self.drawPages($(this).index());
		});
	}
	
	this.build = function (letter) {
		if (letter == 'ВСЕ') {
			this.Pages = Math.ceil(this.Items.size() / this.ItemPerPage);
			this.Letter = false;
			this.build(false);
		} else {
			this.Items.hide();
			var showed = (letter === false ? this.Items : $('[letter=' + letter + ']'));
			this.Pages = Math.ceil(showed.size() / this.ItemPerPage);
			this.Letter = letter;
			
			for (var i = 0; i < this.Pages; i++) {
				showed.slice((i * this.ItemPerPage), ((i + 1) * this.ItemPerPage)).attr('class', 'page-' + i);
			}
			
			showed.show();
		}
		
		this.drawPages(0);
	}
	
	this.drawPages = function(n) {
		this.PageNumber.empty();
		$('[class*=page-]').parent().hide();
		$('[class*=page-]').removeClass('asm');
		$('[class*=page-]').hide();
		
		this.PageNumber.hide();
		
		if (this.Pages > 1) {	
			this.PageNumber.show();
			for (var i = 0, span; i < this.Pages; i++ ) {
				span = $('<span/>')
				span.text((i + 1));
				this.PageNumber.append(span);
			}
			
			$('#pag_number span').removeClass('activ');
			$('#pag_number span').eq(n).addClass('activ');
		} 
		
		var Items = $('.page-' + n);
		
		if (this.Letter != false) {
			Items = $('.page-' + n + '[letter=' + this.Letter + ']');
		}
				
		Items.show();
		Items.parent().show();
		Items.parent().each(function (i) {
			var parent = $(this);
			if (i%2) {
				parent.removeClass('asm');
			} else {
				parent.addClass('asm');
			}
		});
	};
	
	this.init();
}




Date.prototype.toNormalDate = function () {
	var m = Number(this.getMonth()) + 1;
	var date = ((String(this.getDate()).length == 1) ? "0" + this.getDate() : this.getDate()) + '.' + ((String(m).length == 1) ? "0" + m : m) + '.' + this.getFullYear()
	return date;	
}

Date.prototype.toEventDate = function () {
	var m = Number(this.getMonth()) + 1;
	var date = this.getFullYear() + '-' + ((String(m).length == 1) ? "0" + m : m) + '-' + ((String(this.getDate()).length == 1) ? "0" + this.getDate() : this.getDate());
	return date;	
}

var MyCalendar = function () {
	this.Grid  = $('#grid');
	this.Title = $('.title_month');
	this.Success = false;
	this.DatesHasEvents = [];
	this.date = new Date();
	this.href = "index.php?option=com_events&layout=day&Itemid=35";
	
	this.months = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 
	               'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
	
	this.months_d = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 
	               'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
	
	this.init = function () {
		this.today = new Date(this.date.getFullYear(), this.date.getMonth(), this.date.getDate()).getTime();
		this.maxDay = new Date(this.date.getFullYear(), this.date.getMonth(), (Number(this.date.getDate()) + 90));
		this.getEvents();
		this.fillTitle();
		this.fillGrid();
		this.initControls();
	}
	
	this.fillTitle = function () {
		var m = this.date.getMonth();
		this.Title.text(this.months[m] + ' ' + this.date.getFullYear())
	}
	
	this.fillGrid = function () {
		var date     = this.date,
			year     = date.getFullYear(), 					  
			month    = date.getMonth(), 					  
			first    = new Date(year, month, 1).getDay(),     	
			last     = new Date(year, month + 1, 0).getDate(),
			prev     = new Date(year, month, 0).getDate(),    
			i, Week, Day, data, title, m = month;
		
		this.Grid.html('');
	
		for (i = 1; i < 43; i += 1) {
			if ((i - 1) % 7 == 0) {
				Week = $("<tr/>");
				this.Grid.append(Week);
			}

			if (i < first) {
				date = new Date(year, month - 1, prev - first + i + 1);
				m = ((month - 1) < 0 ? 0 : (month - 1));
			} else if (i > last + first - 1) {
				m = ((month + 1) < 12 ? (month + 1) : 0);
				date = new Date(year, month, i - first + 1);
			} else if (first == 0) {
				date = new Date(year, month - 1, last - 6 + i);
				m = ((month - 1) < 0 ? 0 : (month - 1));
				if (i == 6) {
					first = 7;
				}
			} else {
				m = month;
				date = new Date(year, month, i - first + 1);
			}
			
			Day = $("<td date='" + date.toNormalDate() + "'>" + date.getDate() + "</td>");

			if (date.getMonth() > month || date.getMonth() < month) {
				Day.empty();
			} else if (date.getTime() == this.today) {
				if (this.DatesHasEvents.indexOf(String(String(date.getTime()).substr(0, 10))) != -1) {
					Day.html("<span class='current'><a title='События " + date.getDate() + ' ' + this.months_d[date.getMonth()] + "' href='" + host + this.href + "&day=" + date.toEventDate() + "'>" + date.getDate() + "</a></span>");
					Day.addClass('hasEvents');					
				} else {
					Day.html("<span class='current'>" + date.getDate() + "</span>");
				}
			} else if (date.getTime() < this.today) {
				Day.addClass('disable');
			} else if (this.DatesHasEvents.indexOf(String(String(date.getTime()).substr(0, 10))) != -1) {
				Day.html("<a title='События " + date.getDate() + ' ' + this.months_d[date.getMonth()] + "' href='" + host + this.href + "&day=" + date.toEventDate() + "'>" + date.getDate() + "</a>");
				Day.addClass('hasEvents');
			}
			
			Week.append(Day);
		}
	}
	
	this.initControls = function () {
		var self = this;
		$('.arrow.next').click(function (e) {
			e.preventDefault();
			e.stopPropagation();
			self.date = new Date(self.date.getFullYear(), self.date.getMonth() + 1, 1);
			self.fillGrid();
			self.fillTitle();
		});
		
		$('.arrow.prev').click(function (e) {
			e.preventDefault();
			e.stopPropagation();
			self.date = new Date(self.date.getFullYear(), self.date.getMonth() - 1, 1);
			self.fillGrid();
			self.fillTitle();
		});
	}	
	
	this.getEvents = function () {
		var self = this;
		
		$.ajax({
			url: host + 'index2.php?option=com_events&task=getEvents',
			dataType: "txt",
			type: "POST",
			async: false,
			data: {
				from: self.date.toNormalDate(),
				to: self.maxDay.toNormalDate()
			},
			beforeSend: function () {
				if (self.Success === true) {
					return;
				} else {
					self.Success = true;
				}
			},
			success: function (resp) {
				self.DatesHasEvents = resp;
			}
		})
	}
	
	this.init()
}

var SocialNets = function () {
	this.domain=location.href+'/';
	this.domain=this.domain.substr(this.domain.indexOf('://')+3);
	this.domain=this.domain.substr(0,this.domain.indexOf('/'));
	this.location=false;
	this.url=function(system) {
	var title=encodeURIComponent($('title').text().replace('|', '★'));
	var description=encodeURIComponent($('meta[name=description]').attr('content'));
	var url=encodeURIComponent(location.href);
	var image=encodeURIComponent($('meta[name=image]').attr('content'));
	var date = encodeURIComponent($('meta[name=date]').attr('content'));
	if (date != 'undefined') {
		title = title + " ★ " + date;	
	}
		switch (system) {
			case 1: return 'http://vkontakte.ru/share.php?url='+url+'&title='+title+'&description='+description+'&image='+image;
			case 2: return 'http://www.facebook.com/sharer.php?u='+url+'&t='+title;
			case 3: return 'http://twitter.com/share?text='+title+'&url='+url;
			case 4: return 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st._surl='+url;
			//case 5: return 'http://connect.mail.ru/share?share_url='+url;
			case 6: return 'http://www.livejournal.com/update.bml?event='+url+'&subject='+title;
			//case 7: return 'http://memori.ru/link/?sm=1&u_data[url]='+url+'&u_data[name]='+title;
			//case 8: return 'http://bobrdobr.ru/addext.html?url='+url+'&title='+title;
			case 9: return 'http://www.google.com/bookmarks/mark?op=add&bkmk='+url+'&title='+title;
			case 10: return 'http://zakladki.yandex.ru/userarea/links/addfromfav.asp?bAddLink_x=1&lurl='+url+'&lname='+title;
			//case 11: return 'http://surfingbird.ru/share?url='+url;
			//case 12: return 'http://text20.ru/add/?title=' + title + '&source='+url+'&text='+title;
		}
	}
	this.redirect = function() {
		if (this.location) location.href=this.location;
		this.location=false;
	}
	
	this.go = function(i) {
		this.location=this.url(i);
	}
	
	this.init = function() {
		var titles=new Array('&#1042; &#1050;&#1086;&#1085;&#1090;&#1072;&#1082;&#1090;&#1077;','Facebook','Twitter','&#1054;&#1076;&#1085;&#1086;&#1082;&#1083;&#1072;&#1089;&#1089;&#1085;&#1080;&#1082;&#1080;','&#1052;&#1086;&#1081; &#1052;&#1080;&#1088;','LiveJournal','Memori','&#1041;&#1086;&#1073;&#1088;&#1044;&#1086;&#1073;&#1088;','&#1047;&#1072;&#1082;&#1083;&#1072;&#1076;&#1082;&#1080; Google','&#1071;&#1085;&#1076;&#1077;&#1082;&#1089;.&#1047;&#1072;&#1082;&#1083;&#1072;&#1076;&#1082;&#1080;','Surfingbird','&#1058;&#1077;&#1082;&#1089;&#1090; 2.0');;
		var html='';
		for (i=0;i<12;i++) {
			if (i == 11 || i == 10 || i == 7 || i == 6 || i == 4 || i == 9) continue;
			html+='<a href="'+this.url(i+1)+'"><img src="'+host+'templates/template/images/blank.gif" width="16" height="16" alt=" #" title="'+titles[i]+'" style="border:0;padding:0;margin:0 4px 0 0;background-position: 0 -'+(i*16)+'px"/></a>';
		}
		$('#social_nets').append(html);
	}
	
	this.init();
}

var User = function () {
	this.u = {
		is_logged: false,
		is_partner: false,
		name: null,
		id: null
	};
	
	this.init = function () {
		if ($.cookie('anons_dp_ua')) {
			var USER = $.cookie('anons_dp_ua').split('|');
			this.u = {
				is_logged: true,
				is_partner: false,
				name: USER[1],
				id: USER[0]
			};
		}
		
		this.initControls();
	}
	
	this.initControls = function () {
		var self = this;
		if ($('.event_go').length) {
			$('.event_go').click(function() {
				var el = $(this);
				
				if (self.u.is_logged === false) {
					dialog.show(i18n.user_not_found[lang], 'dialog_error');
					return;
				}
				
				var id_event = el.attr('id').replace('event_go_', '');
				var date = el.attr('date');
				
				var action = (el.hasClass('disabled') ? 'remove' : 'add');
				
				if (self.eventToList(id_event, date, action)) {
					var i = Number($('.user_go').text());
					
					if (action == 'add') {
						$('.user_go').text((i+1));
						el.text("Я передумала(а)").addClass('disabled');
					} else {
						$('.user_go').text((i-1));
						el.text("Я пойду").removeClass('disabled');
					}
				};
			});			
		}
		
		if ($('.place_go').length) {
			$('.place_go').click(function() {
				var el = $(this);
				
				if (self.u.is_logged === false) {
					dialog.show(i18n.user_not_found[lang], 'dialog_error');
					return;
				}
				
				var id_place = el.attr('id').replace('place_go_', '');			
				var action = (el.hasClass('disabled') ? 'remove' : 'add');
				
				if (self.placeToList(id_place, action)) {
					var i = Number($('.user_like').text());
					
					if (action == 'add') {
						$('.user_like').text((i+1));
						el.text("Я передумала(а)").addClass('disabled');
					} else {
						$('.user_like').text((i-1));
						el.text("Мне нравится").removeClass('disabled');
					}
				};
			});			
		}		
		
	}
	
	this.eventToList = function (id_event, date, action) {
		var self = this;
		if (this.LOCK === true) return;
		$.ajax({
			url: host + 'index2.php?option=com_user&task=eventToList',
			data: {id_event: id_event, id_user: this.u.id, date: date, action: action},
			type: 'POST',
			beforeSend: function () {
				self.LOCK = true;
			},
			success: function (msg) {
				self.LOCK = false;
				var i = parseInt($('#my_events').text());
				i = (action == 'add' ? (i+1) : (i-1));
				$('#my_events').text(makeEventsGood(i));
			},				
			error: function () {
				dialog.show(i18n.event_not_found[lang], 'dialog_error', function() {self.LOCK = false});
				return false;
			}
		})
		return true;
	}
	
	this.placeToList = function (id_place, action) {
		var self = this;
		if (this.LOCK === true) return;
		$.ajax({
			url: host + 'index2.php?option=com_user&task=placeToList',
			data: {id_place: id_place, id_user: this.u.id, action: action},
			type: 'POST',
			beforeSend: function () {
				self.LOCK = true;
			},
			success: function (msg) {
				self.LOCK = false;
				var i = parseInt($('#my_places').text());
				i = (action == 'add' ? (i+1) : (i-1));
				$('#my_places').text(makePlacesGood(i));
			},				
			error: function () {
				dialog.show(i18n.event_not_found[lang], 'dialog_error', function() {self.LOCK = false});
				return false;
			}
		})
		return true;
	}	

	this.init();
}

function makeEventsGood(i) {
	var str = i + " событий";
	if (i == 1) {
		str = i + " событие";
		
	} else if (i > 1) {
		if (i < 5) {
			str = i + " события";
		}
	}
	
	return str;
}

function makePlacesGood(i) {
	var str = i + " любимых мест";
	if (i == 1) {
		str = i + " любимое место";
		
	} else if (i > 1) {
		if (i == 2 || i == 3 || i == 4) {
			str = i + " любимых места";
		} else {
			str = i + " любимых мест";
		} 
	}
	
	return str;
}

$.fn.focus_blur = function (text) {
	$(this).focus(function () {
		if(!$(this).val() || $(this).val() == text) {
			$(this).val('');
		}
	}).blur(function () {
		if (!$(this).val()) {
			$(this).val(text);
		}
	});
}

var ScrollerTop = function () {
	this.el = $('#top');
	this.H = $(window).height();
	this.O = 0;
	console.log(1);
	var self = this;	
	$(window).bind('scroll', function () {
		self.O = Math.ceil(((window.pageYOffset / 1.3) / self.H) * 10000) / 10000;
		if (self.O > 1) self.O = 1;
		self.el.css({
			opacity: self.O
		})
	});
}

$(function () {
	var scroller = new ScrollerTop();
	dialog = new Dialog({type: 'opacity'});
	calendar = new MyCalendar();	
	social_nets = new SocialNets();	
	user = new User();
	
	$('.pointer').click(function () {
		window.location.href = $(this).attr('href');
	});
	
	$('#search #text').bind('keyup', function (e) {
		if(e.keyCode == 13) {
			$(this).parent().submit();
		}
	})
	
	$('#top').click(function () {
		$.scrollTo('#anonsdpua', 200);
	});
	
	if ($('#google_maps').length && !$('#google_maps').hasClass('hide')) { createMap() }
	
	$('#show_google_maps').click(function () {
		var el = $('#google_maps');
		if (el.hasClass('hide')) {
			el.removeClass('hide');
			createMap();			
		}
		$.scrollTo('#google_maps', 250);
	});
	
	$('#get_all_recomended').click(function () {
		var el = $(this), ids = el.attr('ids');
		el.parent().parent().hide();
		
		$.ajax({
			url: host + 'index2.php?option=com_events&task=getAll',
			data: {ids: ids},
			type: "POST",
			success: function (data) {
				if (data) {
					var box = $('#other_recomended');
					box.html(data);
				}
			}
		})
	});
	
	if (typeof(MAKE_HIGHLIGHT) != 'undefined') { $('.p_info h2').highlight(HIGHLIGHT_TEXT) }
	
	$('#type').bind('change', function () {
		if ($(this).val() == 1) {
			$('#date').hide().prev('label').hide();
			$('#set_dates').hide();
		} else {
			$('#date').show().prev('label').show();
			if ($('#date').val() == 5) {
				$('#set_dates').show();
			}
		}
	});
	
	$('#date').bind('change', function () {
		if ($(this).val() == 5) {
			$('#set_dates').show();
		} else {
			$('#set_dates').hide();
		}
	});
	
	if (typeof(use_pagination) != 'undefined' && use_pagination === true) {
		var placesPagination = new Pagination();
	}
	
	$('#search .button').click(function () { 
		var action = actions[$('#search #type').val()];
		$('#Itemid').val(action[1]);
		$('#search form').attr('action', action[0]).submit();
	});
		
	$('#text').focus_blur('Поиск');
	$('#log').focus_blur('Логин');
	$('#pass').focus_blur('Пароль');
	
	$('.p_href').click(function () {
		var href = $(this).attr('href');
		window.location.href = href;
	});
	
	$("#login .button").click(function () {
		if ($(this).hasClass('login')) {
			if (!$('#log').val() || $('#log').val() == 'Логин') {
				$('#log').focus();
				return false;
			} else if (!$('#pass').val() || $('#pass').val() == 'Пароль') {
				$('#pass').focus();
				return false;
			}			
		}
		
		$("#login form").submit();
	});
})