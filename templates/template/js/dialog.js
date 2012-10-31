/**
 * объект для работы с диалоговом окном,
 * информирующем об ошибках и прочей информации
 * @param string text      текст сообщения
 * @param string attrClass css класс, 
 * который будет применен к тексту сообщения (example: dialog_error, dialog_info etc.)
 */
function Dialog(o) {	
	this.timeShow    = 3000;
	this.animateTime = 100;
	this.o = o || {}
	
	this.disableAllButtons = true;
	
	$('body').prepend($('<div class="dialog"><div class="dialog_text"></div></div>'));
	
	this.element = $('.dialog').css('cursor', 'pointer').attr('title', 'Закрыть');
	this.window  = $(window);
	this.infoBox = $('.dialog .dialog_text');
    
	var self = this;
	this.element.bind('click', function () {
		self.hide();
	})
	
    this.window.size = [this.window.width(), 
                        this.window.height()];    
}
/**
 * отображает диалоговое окно
 */
Dialog.prototype.show = function(text, attrClass, callback) {
    this.infoBox.addClass(attrClass).text(text);
    this.element.size = [this.element.width(), 
                         this.element.height()]; 
    
	if(this.element.is(':visible') == false) {
		
		if (this.disableAllButtons) {
			this._disableAllButtons();
		}
				
		this.element.css({
         	top: this.element.size[1] * -1, 
           	left: Math.floor(this.window.size[0] / 2) - 
           		  Math.floor(this.element.size[0] / 2)
        }).show();
        		
		if (this.o.type == 'animate') {
			this.element.animate({
		        top: Math.floor(this.window.size[1] / 2) - 
		             Math.floor(this.element.size[1] / 2)
		    }, this.animateTime);			
		} else if (this.o.type == 'opacity') {
			this.element.css({
		        top: Math.floor(this.window.size[1] / 2) - 
		             Math.floor(this.element.size[1] / 2),
		        opacity: 0
		    });
			this.element.animate({
				opacity: 1
			}, this.animateTime);
		}		
		
		$('#anonsdpua').animate({
			opacity: .5
		}, this.animateTime);		
		
		var self = this;
	    setTimeout(function() {
	      	self.hide();
	      	if (typeof(callback) == 'function') {
	      		callback();
	      	}
	    }, this.timeShow);
	}	
}
/**
 * скрывает диалоговое окно
 */
Dialog.prototype.hide = function() {
	if (this.disableAllButtons) {
		this._enableAllButtons();
	}
	
	var self = this;
	
	if (this.o.type == 'animate') {
		this.element.animate({
	        top: this.element.size[1] * -1
	    }, this.animateTime, function() {
	        self.element.hide();
	    });		
	} else if (this.o.type == 'opacity') {
		this.element.animate({
	        opacity: 0
	    }, this.animateTime, function() {
	        self.element.hide();
	    });		
	}
	
	$('#anonsdpua').animate({
		opacity: 1
	}, this.animateTime);	
}
/**
 * делает неактивными все кнопки на странице
 */
Dialog.prototype._disableAllButtons = function() {
	$(":button, :submit").attr('disabled', 'disabled');
}
/**
 * делает активными все кнопки на странице
 */
Dialog.prototype._enableAllButtons = function() {
	$(":button, :submit").removeAttr('disabled');
}