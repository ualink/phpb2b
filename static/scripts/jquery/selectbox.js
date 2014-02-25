/**
 * selectbox-utils for jQuery
 * For Virtual-Office Company
 * Copyright (c) 2007 Yoshiomi KURISU
 * Licensed under the MIT (MIT-LICENSE.txt)  licenses.
 * 
 * @example  $('#year1').numericOptions({from:2007,to:2011});
 * @example  $('#month1').numericOptions({from:1,to:12});
 * @example  $('#date1').numericOptions().datePulldown({year:$('#year1'),month:$('#month1')});
 * 
 */
(function() {
	//obj is Array
	// Array : [[label,value],[label,value],....] 
	//set options to select node
	//obj is null or obj is number
	//get options from select node
	$.fn.options = function(obj){
		if(obj || obj == 0){
			if(obj instanceof Array){
				this.each(function(){
					this.options.length = 0;
					for(var i = 0,len = obj.length;i<len;i++){
						var tmp = obj[i];
						if(tmp.length && tmp.length == 2){
							this.options[this.options.length] = new Option(tmp[0],tmp[1]);
						}
					}
				});
				return this;
			}else if(typeof obj == 'number'){
				return $('option:eq('+obj+')',this);
			}else if(obj == 'selected'){
				return this.val();
			}
		}else{
			return $('option',this)
		}
		return $([]);
	}
	$.fn.numericOptions = function(settings){
		settings = jQuery.extend({
			remove:true
			,from:1
			,to:31
			,selectedIndex:0
			,valuePadding:0
			,namePadding:0
			,labels:[]
			,exclude:null
			,startLabel:null
		},settings);
		//error check
		if(!(settings.from+'').match(/^\d+$/)||!(settings.to+'').match(/^\d+$/)||!(settings.selectedIndex+'').match(/^\d+$/)||!(settings.valuePadding+'').match(/^\d+$/)||!(settings.namePadding+'').match(/^\d+$/)) return;
		if(settings.from > settings.to) return;
		if(settings.to - settings.from < settings.selectedIndex) return;
		//add options
		if(settings.remove) this.children().remove();
		var padfunc = function(v,p){
			if((''+v).length < p){
				for(var i = 0,l = p - (v+'').length;i < l ;i++){
					v = '0' + v;
				}
			}
			return v;			
		}
		var exclude_strings = (settings.exclude && settings.exclude instanceof Array && settings.exclude.length > 0)?' '+settings.exclude.join(' ')+' ':'';
		this.each(function(){
			this.options.length = 0
			//set startLabel
			var sl = settings.startLabel;
			if(sl && sl.length && sl.length == 2){
				this.options[0] = new Option(sl[0],sl[1]);
			}
		});
		for(var i=settings.from,j=0;i<=settings.to;i++){
			this.each(function(){
				var val = padfunc(i,settings.valuePadding);
				if(exclude_strings.indexOf(' '+val+' ') < 0){
					var lab = (settings.labels[j])?settings.labels[j]:padfunc(i,settings.namePadding);
					this.options[this.options.length] = new Option(lab,val);
					j++;
				}
			});
		}
		this.each(function(){
				if(jQuery.browser.opera){
					this.options[settings.selectedIndex].defaultSelected = true;
				}else{
					this.selectedIndex = settings.selectedIndex;
				}
			});
		return this;
	};
	//
	$.fn.datePulldown = function(settings){
		if(!settings.year || !settings.month) return ;
		var y = settings.year;
		var m = settings.month;
		if(!y.val() || !m.val()) return;
		if(!y.val().match(/^\d{1,4}$/)) return;
		if(!m.val().match(/^[0][1-9]$|^[1][1,2]$|^[0-9]$/)) return;

		var self = this;
		var fnc = function(){
			var tmp = new Date(new Date(y.val(),m.val()).getTime() - 1000);
			var lastDay = tmp.getDate() - 0;
			self.each(function(){
				var ind = (this.selectedIndex<lastDay-1)?this.selectedIndex:lastDay-1;
				this.selectedIndex = ind;
				$(this).numericOptions({to:lastDay,selectedIndex:ind});
			});
		}
		y.change(fnc);
		m.change(fnc);
		return this;	
	};
})(jQuery);

