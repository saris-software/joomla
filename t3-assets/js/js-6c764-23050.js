

/*===============================
/sampo4/components/com_rseventspro/assets/js/scripts.js
================================================================================*/;
var rsepro_timer;function isset(){var a=arguments,l=a.length,i=0,undef;if(l===0){throw new Error('Empty isset');}
while(i!==l){if(a[i]===undef||a[i]===null){return false;}
i++;}
return true;}
function rse_calculatetotal(tickets){var params='task=total&idevent='+parseInt($('eventID').innerHTML);rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';if(!isset(tickets)){var ticketId=isset($('RSEProTickets'))?$('RSEProTickets').value:$('ticket').value;if($('from').value==0){var numberOfTickets=$('numberinp').value;}else{var numberOfTickets=$('number').value;}
if(isset($('hiddentickets'))){var ticketsstring='';$$('#hiddentickets input').each(function(el){ticketsstring+='&'+el.name+'='+el.value;});params+=ticketsstring;}else{params+='&tickets['+ticketId+']='+numberOfTickets;}}else{params+=tickets;}
if(isset($('coupon')))params+='&coupon='+$('coupon').value;if(isset($('RSEProCoupon')))params+='&coupon='+$('RSEProCoupon').value;if($$('select[name=payment]').length){params+='&payment='+$('payment').value;}else if($$('input[name=payment]').length){if(isset($$('input[name=payment]:checked')[0]))
params+='&payment='+$$('input[name=payment]:checked')[0].value;}
if($$('select[name^=form[RSEProPayment]]').length){params+='&payment='+$('RSEProPayment').value;}else if($$('input[name=form[RSEProPayment]]').length){if(isset($$('input[name=form[RSEProPayment]]:checked')[0]))
params+='&payment='+$$('input[name=form[RSEProPayment]]:checked')[0].value}
params+='&randomTime='+Math.random();var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:params,onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);response=response.split('|');if(response[0]!=0){$('grandtotalcontainer').style.display='';$('grandtotal').innerHTML=response[0];}else{$('grandtotalcontainer').style.display='none';$('grandtotal').innerHTML=0;}
if(response[1]!=''){$('paymentinfocontainer').style.display='';$('paymentinfo').innerHTML=response[1];}else{$('paymentinfocontainer').style.display='none';$('paymentinfo').innerHTML='';}}});req.send();}
function rs_stop(){clearTimeout(rsepro_timer);}
function rsepro_description_on(id){document.getElementById('rsehref'+id).style.display='none';document.getElementById('rsedescription'+id).className='rsepro_extra_on';}
function rsepro_description_off(id){document.getElementById('rsehref'+id).style.display='inline';document.getElementById('rsedescription'+id).className='rsepro_extra_off';}
function rs_add_option(theoption){$('rseprosearch').value=theoption;$('rs_results').style.display='none';}
function rs_add_filter(){if($('rseprosearch').value!='')
document.adminForm.submit();}
function rs_clear_filters(){$('rs_clear').value=1;document.adminForm.submit();}
function rs_remove_filter(key){$('rs_remove').value=key;document.adminForm.submit();}
function rse_verify_coupon(ide,coupon){if(coupon=='')
return false;rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=verify&id='+ide+'&coupon='+coupon,onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);alert(response);}});req.send();}
function rs_search(){if($('filter_from').value=='description')return;if($('rseprosearch').value=='')return;rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';rsepro_timer=setTimeout(function(){var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=filter&type='+$('filter_from').value+'&search='+$('rseprosearch').value+'&condition='+$('filter_condition').value,onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);if(response!=''){$('rs_results').innerHTML=response;$('rs_results').style.display='block';}else $('rs_results').style.display='none';}});req.send();},1000);}
function rspagination(tpl,limitstart,ide){$('rs_loader').style.display='';if(tpl=='day'||tpl=='week')
var params='&view=calendar&layout=items&tpl='+tpl+'&format=raw&limitstart='+limitstart;else
var params='view=rseventspro&layout=items&tpl='+tpl+'&format=raw&limitstart='+limitstart;if(isset($('parent'))&&parseInt($('parent').innerHTML)>0)params+='&parent='+parseInt($('parent').innerHTML);if(isset($('date'))&&$('date').innerHTML!='')params+='&date='+$('date').innerHTML;if(ide)params+='&id='+parseInt(ide);params+='&Itemid='+parseInt($('Itemid').innerHTML);params+='&randomTime='+Math.random();rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:params,onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);var code=response.toDOM();for(i=0;i<code.length;i++)
$('rs_events_container').appendChild(code[i]);$('rs_loader').style.display='none';if($$('#rs_events_container li').length>0&&(tpl=='events'||tpl=='locations'||tpl=='subscribers'||tpl=='day'||tpl=='week'))
{$$('#rs_events_container li').addEvents({mouseenter:function(){if(isset($(this).getElement('div.rs_options')))
$(this).getElement('div.rs_options').style.display='';},mouseleave:function(){if(isset($(this).getElement('div.rs_options')))
$(this).getElement('div.rs_options').style.display='none';}});}
if(($('rs_events_container').getElements('li').length)>=parseInt($('total').innerHTML))$('rsepro_loadmore').style.display='none';}});req.send();}
function rsepro_feedback(val,id){for(var i=1;i<=5;i++)
document.getElementById('rsepro_feedback_'+i).onclick=function(){return false;}
$('rs_loading_img').style.display='';rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=rseventspro.rate&id='+id+'&feedback='+val+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);response=response.split('|');$('rs_loading_img').style.display='none';if(parseInt(response[0])>0)
document.getElementById('rs_rating').innerHTML='<li id="rsepro_current_rating" class="rsepro_feedback_selected_'+parseInt(response[0])+'">&nbsp;</li>';$('rs_rating_info').innerHTML=response[1];window.setTimeout(function(){$('rs_rating_info').tween('opacity','0');},2000);}});req.send();}
function rs_get_ticket(val){$('rs_loader').style.display='';rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=tickets&id='+val+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);response=response.split('|');$('rs_loader').style.display='none';if(parseInt(response[0])==0){$('numberinp').style.display='';$('number').style.display='none';$('numberinp').value=1;$('from').value=0;}else{$('numberinp').style.display='none';$('number').style.display='';$('from').value=1;$('number').options.length=0;for(i=1;i<=parseInt(response[0]);i++)
$('number').options[i-1]=new Option(i,i);}
$('tdescription').innerHTML=response[1];rse_calculatetotal();}});req.send();}
function svalidation(){ret=true;msg=new Array();if($('name').value==''){ret=false;$('name').className='rs_edit_inp_error_small';msg.push(smessage[0]);}else{$('name').className='rs_edit_inp_small';}
if($('email').value==''){ret=false;$('email').className='rs_edit_inp_error_small';msg.push(smessage[1]);}else{$('email').className='rs_edit_inp_small';}
if(isset($('hiddentickets'))&&$('hiddentickets').innerHTML==''){ret=false;msg.push(smessage[3]);}
if(isset($('rsepro_selected_tickets'))&&$('rsepro_selected_tickets').innerHTML==''){ret=false;msg.push(smessage[3]);}
if(!rse_validateEmail($('email').value)){ret=false;$('email').className='rs_edit_inp_error_small';msg.push(smessage[4]);}else{$('email').className='rs_edit_inp_small';}
if(ret)
return true;else{alert(msg.join("\n"));return false;}}
function rse_validateEmail(email){var regex=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;return regex.test(email);}
function rs_add_ticket(){var container=$('tickets');var hidden_tickets=$('hiddentickets');var ticket=isset($('RSEProTickets'))?$('RSEProTickets'):$('ticket');var ticket_number=parseInt($('from').value)==0?parseInt($('numberinp').value):parseInt($('number').value);var ticket_id=ticket.value;var ticket_name=ticket.options[ticket.selectedIndex].text;if(ticket_number==0)ticket_number=1;if(parseInt($('from').value)==1){var available_per_user=$('number').options.length;if(isset(document.getElementById('tickets'+ticket_id))){if(parseInt(document.getElementById('tickets'+ticket_id).value)+ticket_number>available_per_user){alert(smessage[6].replace('%d',available_per_user));return;}}}
if(isset($('hiddentickets'))&&typeof maxtickets!='undefined'&&typeof usedtickets!='undefined'){var total=0;$('hiddentickets').getElements('input').each(function(el){total+=parseInt(el.value);});total+=ticket_number;totalAvailable=parseInt(maxtickets)-parseInt(usedtickets);if(total>totalAvailable){alert(smessage[5]);return;}}
if(!isset(document.getElementById('tickets'+ticket_id))){input=document.createElement('input');input.setAttribute('type','hidden');input.setAttribute('name','tickets['+ticket_id+']');input.setAttribute('id','tickets'+ticket_id);input.setAttribute('value',ticket_number);hidden_tickets.appendChild(input);span=document.createElement('span');span.setAttribute('id','content'+ticket_id);span.innerHTML='<span id="ticketq'+ticket_id+'">'+ticket_number+'</span>'+' x '+ticket_name+' <a href="javascript:void(0);" onclick="rs_remove_ticket('+ticket_id+')"> ('+smessage[2]+')</a><br/>';container.appendChild(span);}else{$('ticketq'+ticket_id).innerHTML=parseInt($('ticketq'+ticket_id).innerHTML)+parseInt(ticket_number);$('tickets'+ticket_id).value=parseInt($('tickets'+ticket_id).value)+parseInt(ticket_number);}
rse_calculatetotal();}
function rs_remove_ticket(theid){if(isset($('tickets'+theid))){var container=$('tickets');var hidden_tickets=$('hiddentickets');container.removeChild($('content'+theid));hidden_tickets.removeChild($('tickets'+theid));rse_calculatetotal();}}
function rs_send_guests(){ret=true;if(document.getElementById('subject').value==''){ret=false;document.getElementById('subject').className='rs_edit_inp_error_small';}else{document.getElementById('subject').className='rs_edit_inp_small';}
var people=document.getElementById('subscribers');peopletrue=false;for(i=0;i<people.length;i++)
if(people[i].selected)
peopletrue=true;if(document.getElementById('denied').checked==false&&document.getElementById('pending').checked==false&&document.getElementById('accepted').checked==false&&!peopletrue){ret=false;document.getElementById('a_option').className='rs_error';document.getElementById('d_option').className='rs_error';document.getElementById('p_option').className='rs_error';}else{document.getElementById('a_option').className='';document.getElementById('d_option').className='';document.getElementById('p_option').className='';}
return ret;}
function rs_load(type){if(type=='gmail'){document.getElementById('rs_gmail_u').style.display='';document.getElementById('rs_gmail_p').style.display='';document.getElementById('rs_gmail_b').style.display='';document.getElementById('rs_yahoo_u').style.display='none';document.getElementById('rs_yahoo_p').style.display='none';document.getElementById('rs_yahoo_b').style.display='none';document.getElementById('ypassword').value='';document.getElementById('yusername').value='';}else{document.getElementById('rs_yahoo_u').style.display='';document.getElementById('rs_yahoo_p').style.display='';document.getElementById('rs_yahoo_b').style.display='';document.getElementById('rs_gmail_u').style.display='none';document.getElementById('rs_gmail_p').style.display='none';document.getElementById('rs_gmail_b').style.display='none';document.getElementById('gpassword').value='';document.getElementById('gusername').value='';}}
function rs_load_close(type){if(type=='gmail'){document.getElementById('rs_gmail_u').style.display='none';document.getElementById('rs_gmail_p').style.display='none';document.getElementById('rs_gmail_b').style.display='none';document.getElementById('gpassword').value='';document.getElementById('gusername').value='';}else{document.getElementById('rs_yahoo_u').style.display='none';document.getElementById('rs_yahoo_p').style.display='none';document.getElementById('rs_yahoo_b').style.display='none';document.getElementById('ypassword').value='';document.getElementById('yusername').value='';}}
function rs_invite(){ret=true;if(document.getElementById('from').value==''){ret=false;document.getElementById('from').className='rs_edit_inp_error_small';}else{document.getElementById('from').className='rs_edit_inp_small';}
if(document.getElementById('from_name').value==''){ret=false;document.getElementById('from_name').className='rs_edit_inp_error_small';}else{document.getElementById('from_name').className='rs_edit_inp_small';}
if(document.getElementById('emails').value==''){ret=false;document.getElementById('emails').className='rs_edit_txt_error';}else{document.getElementById('emails').className='rs_edit_txt';}
if(ret)
document.adminForm.submit();}
function rs_connect(type){var iuser='';var ipass='';if(type=='gmail'){iuser=document.getElementById('gusername').value;ipass=document.getElementById('gpassword').value;}else if(type=='yahoo'){iuser=document.getElementById('yusername').value;ipass=document.getElementById('ypassword').value;}
if(iuser==''||ipass==''){if(type=='gmail'){if(document.getElementById('gpassword').value=='')
document.getElementById('gpassword').className='rs_edit_inp_error_small';if(document.getElementById('gusername').value=='')
document.getElementById('gusername').className='rs_edit_inp_error_small';}else{if(document.getElementById('ypassword').value=='')
document.getElementById('ypassword').className='rs_edit_inp_error_small';if(document.getElementById('yusername').value=='')
document.getElementById('yusername').className='rs_edit_inp_error_small';}
alert(invitemessage[0]);return;}else{if(type=='gmail'){document.getElementById('gpassword').className='rs_edit_inp_small';document.getElementById('gusername').className='rs_edit_inp_small';}else{document.getElementById('ypassword').className='rs_edit_inp_small';document.getElementById('yusername').className='rs_edit_inp_small';}}
if(type=='gmail')
document.getElementById('img_gmail').style.display='';else
document.getElementById('img_yahoo').style.display='';rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=connect&type='+type+'&username='+iuser+'&password='+ipass+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);if(document.getElementById('emails').value=='')
document.getElementById('emails').value=response;else
document.getElementById('emails').value+="\n"+response;if(type=='gmail')
document.getElementById('img_gmail').style.display='none';else
document.getElementById('img_yahoo').style.display='none';}});req.send();}
function checkcaptcha(){rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=checkcaptcha&secret='+document.getElementById('secret').value+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);if(parseInt(response)){document.getElementById('secret').className='rs_edit_inp_small';rs_invite();}else{document.getElementById('secret').className='rs_edit_inp_error_small';}}});req.send();}
function reloadCaptcha(){document.getElementById('captcha').src=document.getElementById('captcha').src+'?'+Math.random();}
function rs_calendar_add_filter(name){if(name!=0){$('filter_from').value='categories';$('filter_condition').value='is';$('rseprosearch').value=name;}
$$('#rs_filters li input[value=categories]').each(function(el){el.getParent().dispose();});document.adminForm.submit();}
function cc_validate(card_message,ccv_message){var ret=true;var message='';var cc_number=document.getElementById('cc_number');var cc_ccv=document.getElementById('cc_ccv');var firstname=document.getElementById('firstname');var lastname=document.getElementById('lastname');if(cc_number.value.length<13||cc_number.value.length>16){ret=false;message+=card_message+"\n";cc_number.className+=" rs_error";}else{cc_number.className.replace(new RegExp(" rs_error\\b"),'');}
if(cc_ccv.value.length<3||cc_ccv.value.length>4){ret=false;message+=ccv_message+"\n";cc_ccv.className+=" rs_error";}else{cc_ccv.className.replace(new RegExp(" rs_error\\b"),'');}
if(firstname.value==''){ret=false;firstname.className+=" rs_error";}else{firstname.className.replace(new RegExp(" rs_error\\b"),'');}
if(lastname.value==''){ret=false;lastname.className+=" rs_error";}else{lastname.className.replace(new RegExp(" rs_error\\b"),'');}
if(message.length!=0)
alert(message);return ret;}
function rs_check_card(what){what.value=what.value.replace(/[^0-9]+/g,'');}
function rs_cc_form(){var has_error=false;var cc_number=document.getElementById('cc_number');var cc_length=cc_number.value.length;if(cc_length<14||cc_length>19){if(cc_number.className.indexOf(' rs_error')==-1)cc_number.className+=" rs_error";has_error=true;}else{cc_number.className=cc_number.className.replace(new RegExp(" rs_error\\b"),'');}
var csc_number=document.getElementById('cc_csc');if(csc_number.value.length<3){if(csc_number.className.indexOf(' rs_error')==-1)csc_number.className+=" rs_error";has_error=true;}else{csc_number.className=csc_number.className.replace(new RegExp(" rs_error\\b"),'');}
var cc_fname=document.getElementById('cc_fname');if(cc_fname.value.length==0){if(cc_fname.className.indexOf(' rs_error')==-1)cc_fname.className+=" rs_error";has_error=true;}else{cc_fname.className=cc_fname.className.replace(new RegExp(" rs_error\\b"),'');}
var cc_lname=document.getElementById('cc_lname');if(cc_lname.value.length==0){if(cc_lname.className.indexOf(' rs_error')==-1)cc_lname.className+=" rs_error";has_error=true;}else{cc_lname.className=cc_lname.className.replace(new RegExp(" rs_error\\b"),'');}
return has_error?false:true;}
function rs_calendar(root,month,year,module){document.getElementById('rscalendarmonth'+module).style.display='none';document.getElementById('rscalendar'+module).style.display='';var req=new Request({method:'post',url:root+'index.php?option=com_rseventspro',data:'view=calendar&layout=module&format=raw&month='+month+'&year='+year+'&mid='+module+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);document.getElementById('rs_calendar_module'+module).innerHTML=response;document.getElementById('rscalendarmonth'+module).style.display='';document.getElementById('rscalendar'+module).style.display='none';$$('.hasTip').each(function(el){var title=el.get('title');if(title){var parts=title.split('::',2);el.store('tip:title',parts[0]);el.store('tip:text',parts[1]);}});var JTooltips=new Tips($$('.hasTip'),{maxTitleChars:50,fixed:false});}});req.send();}
function rsepro_ajax_close(){RSEProSearch.slideOut();}
function rsepro_search(root,itemid,opener){rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=ajax&search='+$('rsepro_ajax').value+'&iid='+itemid+'&opener='+opener+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);if(response!=''){$('rsepro_ajax_list').set('html',response);RSEProSearch.slideIn();}else RSEProSearch.slideOut();}});req.send();}
function rs_check_dates(){if(isset(document.getElementById('enablestart'))){if(document.getElementById('enablestart').checked){document.getElementById('rsstart').disabled=false;document.getElementById('rsstart_img').style.display='';}else{document.getElementById('rsstart').disabled=true;document.getElementById('rsstart_img').style.display='none';}}
if(isset(document.getElementById('enableend'))){if(document.getElementById('enableend').checked){document.getElementById('rsend').disabled=false;document.getElementById('rsend_img').style.display='';}else{document.getElementById('rsend').disabled=true;document.getElementById('rsend_img').style.display='none';}}}
function rsepro_search_form_verification(){if(document.getElementById('rskeyword').value=='')
return false;return true;}
function rs_add_loc(){if(isset($('rs_location_window'))){$('rs_location_window').reveal({duration:'short'});$('rs_new_location').innerHTML=$('rs_location').value;}}
function show_more(){$('less').style.display='';$('more').style.display='none';$('rs_repeats').style.height='auto';}
function show_less(){$('less').style.display='none';$('more').style.display='';$('rs_repeats').style.height='70px';}
window.addEvent('domready',function(){if(isset($('rs_repeats_control')))
{if(parseInt($('rs_repeats').scrollHeight)>70)
$('rs_repeats_control').style.display='';}});var rs_tooltip=function(){var id='rs_tt';var top=3;var left=3;var maxw=400;var speed=10;var timer=20;var endalpha=95;var alpha=0;var tt,t,c,b,h;var ie=document.all?true:false;return{show:function(v,w){if(tt==null){tt=document.createElement('div');tt.setAttribute('id',id);t=document.createElement('div');t.setAttribute('id',id+'top');c=document.createElement('div');c.setAttribute('id',id+'cont');b=document.createElement('div');b.setAttribute('id',id+'bot');tt.appendChild(t);tt.appendChild(c);tt.appendChild(b);document.body.appendChild(tt);tt.style.opacity=0;tt.style.filter='alpha(opacity=0)';document.onmousemove=this.pos;}
tt.style.display='block';c.innerHTML=document.getElementById(v).innerHTML;tt.style.width=w?w+'px':'auto';if(!w&&ie){t.style.display='none';b.style.display='none';tt.style.width=tt.offsetWidth;t.style.display='block';b.style.display='block';}
if(tt.offsetWidth>maxw){tt.style.width=maxw+'px'}
h=parseInt(tt.offsetHeight)+top;clearInterval(tt.timer);tt.timer=setInterval(function(){rs_tooltip.fade(1)},timer);},pos:function(e){var u=ie?event.clientY+document.documentElement.scrollTop:e.pageY;var l=ie?event.clientX+document.documentElement.scrollLeft:e.pageX;tt.style.top=(u-h)+'px';tt.style.left=(l+left)+'px';},fade:function(d){var a=alpha;if((a!=endalpha&&d==1)||(a!=0&&d==-1)){var i=speed;if(endalpha-a<speed&&d==1){i=endalpha-a;}else if(alpha<speed&&d==-1){i=a;}
alpha=a+(i*d);tt.style.opacity=alpha*.01;tt.style.filter='alpha(opacity='+alpha+')';}else{clearInterval(tt.timer);if(d==-1){tt.style.display='none'}}},hide:function(){clearInterval(tt.timer);tt.timer=setInterval(function(){rs_tooltip.fade(-1)},timer);}};}();function rsepro_add_ticket(id,place,tname,tprice){if(window.dialogArguments){var thedocument=window.dialogArguments;}else{var thedocument=window.opener||window.parent;}
var seat_container=$('rsepro_seat_'+id+place);var selected_container=thedocument.$('rsepro_selected_tickets');var selected_view_container=thedocument.$('rsepro_selected_tickets_view');available_per_user=eval('ticket_limit_'+id);selected=thedocument.$$('#rsepro_selected_tickets input[name^=tickets['+id+']]').length;if(thedocument.multitickets==0){var ticketids=new Array();thedocument.$$('#rsepro_selected_tickets input[name^=tickets[]').each(function(el){theid=el.name.replace('tickets[','').replace('][]','');if(ticketids.indexOf(theid)==-1){ticketids.push(theid);}});thedocument.$$('#rsepro_selected_tickets input[name^=unlimited[]').each(function(el){theid=el.name.replace('unlimited[','').replace(']','');if(ticketids.indexOf(theid)==-1){ticketids.push(theid);}});if(ticketids.length>0&&ticketids.indexOf(id)==-1){if(!thedocument.$$('#rsepro_selected_tickets input[name^=unlimited[]').length){$$('input[id^=rsepro_unlimited_]').each(function(el){el.value='';});}
alert(thedocument.smessage[7]);return;}}
if(place==0){if(isset(thedocument.$('ticket'+id+place))){if($('rsepro_unlimited_'+id).value==0||$('rsepro_unlimited_'+id).value==''){selected_container.removeChild(thedocument.$('ticket'+id+place));}else{if(typeof thedocument.maxtickets!='undefined'&&typeof thedocument.usedtickets!='undefined'){var maxticketsAvailable=thedocument.maxtickets-thedocument.usedtickets;var thetotal=parseInt($$('.rsepro_selected').length);$$('input[id^=rsepro_unlimited_]').each(function(el){if(el.value!='')
thetotal+=parseInt(el.value);});if(thetotal>maxticketsAvailable){alert(thedocument.smessage[5]);return;}}
if($('rsepro_unlimited_'+id).value>available_per_user){$('rsepro_unlimited_'+id).value=available_per_user;alert(thedocument.smessage[6].replace('%d',available_per_user));return;}
thedocument.$('ticket'+id+place).value=$('rsepro_unlimited_'+id).value;}}else{if($('rsepro_unlimited_'+id).value!=0||$('rsepro_unlimited_'+id).value!=''){if(typeof thedocument.maxtickets!='undefined'&&typeof thedocument.usedtickets!='undefined'){var maxticketsAvailable=thedocument.maxtickets-thedocument.usedtickets;var thetotal=parseInt($$('.rsepro_selected').length);$$('input[id^=rsepro_unlimited_]').each(function(el){if(el.value!='')
thetotal+=parseInt(el.value);});if(thetotal>maxticketsAvailable){alert(thedocument.smessage[5]);return;}}
if($('rsepro_unlimited_'+id).value>available_per_user){alert(thedocument.smessage[6].replace('%d',available_per_user));$('rsepro_unlimited_'+id).value=available_per_user;return;}
if($('rsepro_unlimited_'+id).value>0){input=thedocument.document.createElement('input');input.setAttribute('type','hidden');input.setAttribute('name','unlimited['+id+']');input.setAttribute('id','ticket'+id+place);input.setAttribute('value',$('rsepro_unlimited_'+id).value);selected_container.appendChild(input);}}}}else{if(seat_container.hasClass('rsepro_selected')){seat_container.removeClass('rsepro_selected')
if(isset(thedocument.$('ticket'+id+place))){selected_container.removeChild(thedocument.$('ticket'+id+place));}}else{if(typeof thedocument.maxtickets!='undefined'&&typeof thedocument.usedtickets!='undefined'){var maxticketsAvailable=thedocument.maxtickets-thedocument.usedtickets;var thetotal=parseInt($$('.rsepro_selected').length);$$('input[id^=rsepro_unlimited_]').each(function(el){if(el.value!='')
thetotal+=parseInt(el.value);});if(thetotal>=maxticketsAvailable){alert(thedocument.smessage[5]);return;}}
if(selected+1>available_per_user){alert(thedocument.smessage[6].replace('%d',available_per_user));return;}
seat_container.addClass('rsepro_selected');input=thedocument.document.createElement('input');input.setAttribute('type','hidden');input.setAttribute('name','tickets['+id+'][]');input.setAttribute('id','ticket'+id+place);input.setAttribute('value',place);selected_container.appendChild(input);}}
if(!isset(thedocument.$('content'+id))){if(place==0){if($('rsepro_unlimited_'+id).value>0)
selected_view_container.innerHTML+='<span id="content'+id+'"><span id="rsepro_quantity'+id+'">'+$('rsepro_unlimited_'+id).value+'</span> x '+tname+' ('+tprice+') <br /> </span>';}else{selected_view_container.innerHTML+='<span id="content'+id+'"><span id="rsepro_quantity'+id+'">'+thedocument.$$('input[name^=tickets['+id+'][]]').length+'</span> x '+tname+' ('+tprice+') <span id="rsepro_seats'+id+'"></span><br /> </span>';}}else{if(place==0){if($('rsepro_unlimited_'+id).value==0)
selected_view_container.removeChild(thedocument.$('content'+id));else
thedocument.$('rsepro_quantity'+id).innerHTML=$('rsepro_unlimited_'+id).value;}else{if(thedocument.$$('input[name^=tickets['+id+'][]]').length==0)
selected_view_container.removeChild(thedocument.$('content'+id));else
thedocument.$('rsepro_quantity'+id).innerHTML=thedocument.$$('input[name^=tickets['+id+'][]]').length;}}
if(isset(thedocument.$('rsepro_seats'+id))){var seats=[];for(var t=0;t<thedocument.$$('input[name^=tickets['+id+'][]]').length;t++){seats.push(thedocument.$$('input[name^=tickets['+id+'][]]')[t].value);}
thedocument.$('rsepro_seats'+id).innerHTML=Joomla.JText._('COM_RSEVENTSPRO_SEATS').replace('%s',seats.join(', '));}
var total=0;thedocument.$$('span[id^=rsepro_quantity]').each(function(el){total+=parseInt(el.innerHTML);});if(total>0)
thedocument.$('rsepro_cart').innerHTML=total+' '+Joomla.JText._('COM_RSEVENTSPRO_TICKETS');else
thedocument.$('rsepro_cart').innerHTML=Joomla.JText._('COM_RSEVENTSPRO_SELECT_TICKETS');thedocument.rsepro_update_total();}
function rsepro_reset_tickets(text){if(window.dialogArguments){var thedocument=window.dialogArguments;}else{var thedocument=window.opener||window.parent;}
$$('.rsepro_selected').removeClass('rsepro_selected');$$('input[id^=rsepro_unlimited]').each(function(el){el.value='';});thedocument.$('rsepro_selected_tickets_view').innerHTML='';thedocument.$('rsepro_selected_tickets').innerHTML='';thedocument.$('rsepro_cart').innerHTML=text;thedocument.rsepro_update_total();}
function rsepro_update_total(){tickets='&dummy=1';$$('span[id^=rsepro_quantity]').each(function(el){tickets+='&tickets['+parseInt(el.id.replace('rsepro_quantity',''))+']='+parseInt(el.innerHTML);});rse_calculatetotal(tickets);}
function ajaxValidationRSEventsPro(task,formId,data){if(task=='beforeSend'){data.params.push('cid='+encodeURIComponent(document.getElementById('eventID').innerHTML));}}
function rsepro_validate_report(){if(document.getElementById('jform_report').value==''){document.getElementById('jform_report').addClass('invalid');return false;}
document.getElementById('jform_report').removeClass('invalid');return true;}


/*===============================
/sampo4/components/com_rseventspro/assets/js/dom.js
================================================================================*/;
String.implement({toDOM:function(){var wrapper=this.test('^<the|^<tf|^<tb|^<colg|^<ca')&&['<table>','</table>',1]||this.test('^<col')&&['<table><colgroup>','</colgroup><tbody></tbody></table>',2]||this.test('^<tr')&&['<table><tbody>','</tbody></table>',2]||this.test('^<th|^<td')&&['<table><tbody><tr>','</tr></tbody></table>',3]||this.test('^<li')&&['<ul>','</ul>',1]||this.test('^<dt|^<dd')&&['<dl>','</dl>',1]||this.test('^<le')&&['<fieldset>','</fieldset>',1]||this.test('^<opt')&&['<select multiple="multiple">','</select>',1]||['','',0];if(Browser.ie8||Browser.ie7){if(wrapper[0].test('table')){var el=new Element('div',{html:wrapper[0]+this+wrapper[1]}).getChildren();while(wrapper[2]--)el=el[0].getChildren();}else{var el=new Element('div',{html:wrapper[0]+this+wrapper[1]}).getElements('li');while(wrapper[2]--)el=el[0].getElements('li');}}else{var el=new Element('div',{html:wrapper[0]+this+wrapper[1]}).getChildren();while(wrapper[2]--)el=el[0].getChildren();}
return el;}});


/*===============================
/sampo4/plugins/system/t3/base-bs3/bootstrap/js/bootstrap.js
================================================================================*/;
/*!
 * Bootstrap v3.3.6 (http://getbootstrap.com)
 * Copyright 2011-2015 Twitter, Inc.
 * Licensed under the MIT license
 */
if(typeof jQuery==='undefined'){throw new Error('Bootstrap\'s JavaScript requires jQuery')}
+function($){'use strict';var version=$.fn.jquery.split(' ')[0].split('.')
if((version[0]<2&&version[1]<9)||(version[0]==1&&version[1]==9&&version[2]<1)||(version[0]>2)){throw new Error('Bootstrap\'s JavaScript requires jQuery version 1.9.1 or higher, but lower than version 3')}}(jQuery);+function($){'use strict';function transitionEnd(){var el=document.createElement('bootstrap')
var transEndEventNames={WebkitTransition:'webkitTransitionEnd',MozTransition:'transitionend',OTransition:'oTransitionEnd otransitionend',transition:'transitionend'}
for(var name in transEndEventNames){if(el.style[name]!==undefined){return{end:transEndEventNames[name]}}}
return false}
$.fn.emulateTransitionEnd=function(duration){var called=false
var $el=this
$(this).one('bsTransitionEnd',function(){called=true})
var callback=function(){if(!called)$($el).trigger($.support.transition.end)}
setTimeout(callback,duration)
return this}
$(function(){$.support.transition=transitionEnd()
if(!$.support.transition)return
$.event.special.bsTransitionEnd={bindType:$.support.transition.end,delegateType:$.support.transition.end,handle:function(e){if($(e.target).is(this))return e.handleObj.handler.apply(this,arguments)}}})}(jQuery);+function($){'use strict';var dismiss='[data-dismiss="alert"]'
var Alert=function(el){$(el).on('click',dismiss,this.close)}
Alert.VERSION='3.3.6'
Alert.TRANSITION_DURATION=150
Alert.prototype.close=function(e){var $this=$(this)
var selector=$this.attr('data-target')
if(!selector){selector=$this.attr('href')
selector=selector&&selector.replace(/.*(?=#[^\s]*$)/,'')}
var $parent=$(selector)
if(e)e.preventDefault()
if(!$parent.length){$parent=$this.closest('.alert')}
$parent.trigger(e=$.Event('close.bs.alert'))
if(e.isDefaultPrevented())return
$parent.removeClass('in')
function removeElement(){$parent.detach().trigger('closed.bs.alert').remove()}
$.support.transition&&$parent.hasClass('fade')?$parent.one('bsTransitionEnd',removeElement).emulateTransitionEnd(Alert.TRANSITION_DURATION):removeElement()}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.alert')
if(!data)$this.data('bs.alert',(data=new Alert(this)))
if(typeof option=='string')data[option].call($this)})}
var old=$.fn.alert
$.fn.alert=Plugin
$.fn.alert.Constructor=Alert
$.fn.alert.noConflict=function(){$.fn.alert=old
return this}
$(document).on('click.bs.alert.data-api',dismiss,Alert.prototype.close)}(jQuery);+function($){'use strict';var Button=function(element,options){this.$element=$(element)
this.options=$.extend({},Button.DEFAULTS,options)
this.isLoading=false}
Button.VERSION='3.3.6'
Button.DEFAULTS={loadingText:'loading...'}
Button.prototype.setState=function(state){var d='disabled'
var $el=this.$element
var val=$el.is('input')?'val':'html'
var data=$el.data()
state+='Text'
if(data.resetText==null)$el.data('resetText',$el[val]())
setTimeout($.proxy(function(){$el[val](data[state]==null?this.options[state]:data[state])
if(state=='loadingText'){this.isLoading=true
$el.addClass(d).attr(d,d)}else if(this.isLoading){this.isLoading=false
$el.removeClass(d).removeAttr(d)}},this),0)}
Button.prototype.toggle=function(){var changed=true
var $parent=this.$element.closest('[data-toggle="buttons"]')
if($parent.length){var $input=this.$element.find('input')
if($input.prop('type')=='radio'){if($input.prop('checked'))changed=false
$parent.find('.active').removeClass('active')
this.$element.addClass('active')}else if($input.prop('type')=='checkbox'){if(($input.prop('checked'))!==this.$element.hasClass('active'))changed=false
this.$element.toggleClass('active')}
$input.prop('checked',this.$element.hasClass('active'))
if(changed)$input.trigger('change')}else{this.$element.attr('aria-pressed',!this.$element.hasClass('active'))
this.$element.toggleClass('active')}}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.button')
var options=typeof option=='object'&&option
if(!data)$this.data('bs.button',(data=new Button(this,options)))
if(option=='toggle')data.toggle()
else if(option)data.setState(option)})}
var old=$.fn.button
$.fn.button=Plugin
$.fn.button.Constructor=Button
$.fn.button.noConflict=function(){$.fn.button=old
return this}
$(document).on('click.bs.button.data-api','[data-toggle^="button"]',function(e){var $btn=$(e.target)
if(!$btn.hasClass('btn'))$btn=$btn.closest('.btn')
Plugin.call($btn,'toggle')
if(!($(e.target).is('input[type="radio"]')||$(e.target).is('input[type="checkbox"]')))e.preventDefault()}).on('focus.bs.button.data-api blur.bs.button.data-api','[data-toggle^="button"]',function(e){$(e.target).closest('.btn').toggleClass('focus',/^focus(in)?$/.test(e.type))})}(jQuery);+function($){'use strict';var Carousel=function(element,options){this.$element=$(element)
this.$indicators=this.$element.find('.carousel-indicators')
this.options=options
this.paused=null
this.sliding=null
this.interval=null
this.$active=null
this.$items=null
this.options.keyboard&&this.$element.on('keydown.bs.carousel',$.proxy(this.keydown,this))
this.options.pause=='hover'&&!('ontouchstart'in document.documentElement)&&this.$element.on('mouseenter.bs.carousel',$.proxy(this.pause,this)).on('mouseleave.bs.carousel',$.proxy(this.cycle,this))}
Carousel.VERSION='3.3.6'
Carousel.TRANSITION_DURATION=600
Carousel.DEFAULTS={interval:5000,pause:'hover',wrap:true,keyboard:true}
Carousel.prototype.keydown=function(e){if(/input|textarea/i.test(e.target.tagName))return
switch(e.which){case 37:this.prev();break
case 39:this.next();break
default:return}
e.preventDefault()}
Carousel.prototype.cycle=function(e){e||(this.paused=false)
this.interval&&clearInterval(this.interval)
this.options.interval&&!this.paused&&(this.interval=setInterval($.proxy(this.next,this),this.options.interval))
return this}
Carousel.prototype.getItemIndex=function(item){this.$items=item.parent().children('.item')
return this.$items.index(item||this.$active)}
Carousel.prototype.getItemForDirection=function(direction,active){var activeIndex=this.getItemIndex(active)
var willWrap=(direction=='prev'&&activeIndex===0)||(direction=='next'&&activeIndex==(this.$items.length-1))
if(willWrap&&!this.options.wrap)return active
var delta=direction=='prev'?-1:1
var itemIndex=(activeIndex+delta)%this.$items.length
return this.$items.eq(itemIndex)}
Carousel.prototype.to=function(pos){var that=this
var activeIndex=this.getItemIndex(this.$active=this.$element.find('.item.active'))
if(pos>(this.$items.length-1)||pos<0)return
if(this.sliding)return this.$element.one('slid.bs.carousel',function(){that.to(pos)})
if(activeIndex==pos)return this.pause().cycle()
return this.slide(pos>activeIndex?'next':'prev',this.$items.eq(pos))}
Carousel.prototype.pause=function(e){e||(this.paused=true)
if(this.$element.find('.next, .prev').length&&$.support.transition){this.$element.trigger($.support.transition.end)
this.cycle(true)}
this.interval=clearInterval(this.interval)
return this}
Carousel.prototype.next=function(){if(this.sliding)return
return this.slide('next')}
Carousel.prototype.prev=function(){if(this.sliding)return
return this.slide('prev')}
Carousel.prototype.slide=function(type,next){var $active=this.$element.find('.item.active')
var $next=next||this.getItemForDirection(type,$active)
var isCycling=this.interval
var direction=type=='next'?'left':'right'
var that=this
if($next.hasClass('active'))return(this.sliding=false)
var relatedTarget=$next[0]
var slideEvent=$.Event('slide.bs.carousel',{relatedTarget:relatedTarget,direction:direction})
this.$element.trigger(slideEvent)
if(slideEvent.isDefaultPrevented())return
this.sliding=true
isCycling&&this.pause()
if(this.$indicators.length){this.$indicators.find('.active').removeClass('active')
var $nextIndicator=$(this.$indicators.children()[this.getItemIndex($next)])
$nextIndicator&&$nextIndicator.addClass('active')}
var slidEvent=$.Event('slid.bs.carousel',{relatedTarget:relatedTarget,direction:direction})
if($.support.transition&&this.$element.hasClass('slide')){$next.addClass(type)
$next[0].offsetWidth
$active.addClass(direction)
$next.addClass(direction)
$active.one('bsTransitionEnd',function(){$next.removeClass([type,direction].join(' ')).addClass('active')
$active.removeClass(['active',direction].join(' '))
that.sliding=false
setTimeout(function(){that.$element.trigger(slidEvent)},0)}).emulateTransitionEnd(Carousel.TRANSITION_DURATION)}else{$active.removeClass('active')
$next.addClass('active')
this.sliding=false
this.$element.trigger(slidEvent)}
isCycling&&this.cycle()
return this}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.carousel')
var options=$.extend({},Carousel.DEFAULTS,$this.data(),typeof option=='object'&&option)
var action=typeof option=='string'?option:options.slide
if(!data)$this.data('bs.carousel',(data=new Carousel(this,options)))
if(typeof option=='number')data.to(option)
else if(action)data[action]()
else if(options.interval)data.pause().cycle()})}
var old=$.fn.carousel
$.fn.carousel=Plugin
$.fn.carousel.Constructor=Carousel
$.fn.carousel.noConflict=function(){$.fn.carousel=old
return this}
var clickHandler=function(e){var href
var $this=$(this)
var $target=$($this.attr('data-target')||(href=$this.attr('href'))&&href.replace(/.*(?=#[^\s]+$)/,''))
if(!$target.hasClass('carousel'))return
var options=$.extend({},$target.data(),$this.data())
var slideIndex=$this.attr('data-slide-to')
if(slideIndex)options.interval=false
Plugin.call($target,options)
if(slideIndex){$target.data('bs.carousel').to(slideIndex)}
e.preventDefault()}
$(document).on('click.bs.carousel.data-api','[data-slide]',clickHandler).on('click.bs.carousel.data-api','[data-slide-to]',clickHandler)
$(window).on('load',function(){$('[data-ride="carousel"]').each(function(){var $carousel=$(this)
Plugin.call($carousel,$carousel.data())})})}(jQuery);+function($){'use strict';var Collapse=function(element,options){this.$element=$(element)
this.options=$.extend({},Collapse.DEFAULTS,options)
this.$trigger=$('[data-toggle="collapse"][href="#'+element.id+'"],'+'[data-toggle="collapse"][data-target="#'+element.id+'"]')
this.transitioning=null
if(this.options.parent){this.$parent=this.getParent()}else{this.addAriaAndCollapsedClass(this.$element,this.$trigger)}
if(this.options.toggle)this.toggle()}
Collapse.VERSION='3.3.6'
Collapse.TRANSITION_DURATION=350
Collapse.DEFAULTS={toggle:true}
Collapse.prototype.dimension=function(){var hasWidth=this.$element.hasClass('width')
return hasWidth?'width':'height'}
Collapse.prototype.show=function(){if(this.transitioning||this.$element.hasClass('in'))return
var activesData
var actives=this.$parent&&this.$parent.children('.panel').children('.in, .collapsing')
if(actives&&actives.length){activesData=actives.data('bs.collapse')
if(activesData&&activesData.transitioning)return}
var startEvent=$.Event('show.bs.collapse')
this.$element.trigger(startEvent)
if(startEvent.isDefaultPrevented())return
if(actives&&actives.length){Plugin.call(actives,'hide')
activesData||actives.data('bs.collapse',null)}
var dimension=this.dimension()
this.$element.removeClass('collapse').addClass('collapsing')[dimension](0).attr('aria-expanded',true)
this.$trigger.removeClass('collapsed').attr('aria-expanded',true)
this.transitioning=1
var complete=function(){this.$element.removeClass('collapsing').addClass('collapse in')[dimension]('')
this.transitioning=0
this.$element.trigger('shown.bs.collapse')}
if(!$.support.transition)return complete.call(this)
var scrollSize=$.camelCase(['scroll',dimension].join('-'))
this.$element.one('bsTransitionEnd',$.proxy(complete,this)).emulateTransitionEnd(Collapse.TRANSITION_DURATION)[dimension](this.$element[0][scrollSize])}
Collapse.prototype.hide=function(){if(this.transitioning||!this.$element.hasClass('in'))return
var startEvent=$.Event('hide.bs.collapse')
this.$element.trigger(startEvent)
if(startEvent.isDefaultPrevented())return
var dimension=this.dimension()
this.$element[dimension](this.$element[dimension]())[0].offsetHeight
this.$element.addClass('collapsing').removeClass('collapse in').attr('aria-expanded',false)
this.$trigger.addClass('collapsed').attr('aria-expanded',false)
this.transitioning=1
var complete=function(){this.transitioning=0
this.$element.removeClass('collapsing').addClass('collapse').trigger('hidden.bs.collapse')}
if(!$.support.transition)return complete.call(this)
this.$element
[dimension](0).one('bsTransitionEnd',$.proxy(complete,this)).emulateTransitionEnd(Collapse.TRANSITION_DURATION)}
Collapse.prototype.toggle=function(){this[this.$element.hasClass('in')?'hide':'show']()}
Collapse.prototype.getParent=function(){return $(this.options.parent).find('[data-toggle="collapse"][data-parent="'+this.options.parent+'"]').each($.proxy(function(i,element){var $element=$(element)
this.addAriaAndCollapsedClass(getTargetFromTrigger($element),$element)},this)).end()}
Collapse.prototype.addAriaAndCollapsedClass=function($element,$trigger){var isOpen=$element.hasClass('in')
$element.attr('aria-expanded',isOpen)
$trigger.toggleClass('collapsed',!isOpen).attr('aria-expanded',isOpen)}
function getTargetFromTrigger($trigger){var href
var target=$trigger.attr('data-target')||(href=$trigger.attr('href'))&&href.replace(/.*(?=#[^\s]+$)/,'')
return $(target)}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.collapse')
var options=$.extend({},Collapse.DEFAULTS,$this.data(),typeof option=='object'&&option)
if(!data&&options.toggle&&/show|hide/.test(option))options.toggle=false
if(!data)$this.data('bs.collapse',(data=new Collapse(this,options)))
if(typeof option=='string')data[option]()})}
var old=$.fn.collapse
$.fn.collapse=Plugin
$.fn.collapse.Constructor=Collapse
$.fn.collapse.noConflict=function(){$.fn.collapse=old
return this}
$(document).on('click.bs.collapse.data-api','[data-toggle="collapse"]',function(e){var $this=$(this)
if(!$this.attr('data-target'))e.preventDefault()
var $target=getTargetFromTrigger($this)
var data=$target.data('bs.collapse')
var option=data?'toggle':$this.data()
Plugin.call($target,option)})}(jQuery);+function($){'use strict';var backdrop='.dropdown-backdrop'
var toggle='[data-toggle="dropdown"]'
var Dropdown=function(element){$(element).on('click.bs.dropdown',this.toggle)}
Dropdown.VERSION='3.3.6'
function getParent($this){var selector=$this.attr('data-target')
if(!selector){selector=$this.attr('href')
selector=selector&&/#[A-Za-z]/.test(selector)&&selector.replace(/.*(?=#[^\s]*$)/,'')}
var $parent=selector&&$(selector)
return $parent&&$parent.length?$parent:$this.parent()}
function clearMenus(e){if(e&&e.which===3)return
$(backdrop).remove()
$(toggle).each(function(){var $this=$(this)
var $parent=getParent($this)
var relatedTarget={relatedTarget:this}
if(!$parent.hasClass('open'))return
if(e&&e.type=='click'&&/input|textarea/i.test(e.target.tagName)&&$.contains($parent[0],e.target))return
$parent.trigger(e=$.Event('hide.bs.dropdown',relatedTarget))
if(e.isDefaultPrevented())return
$this.attr('aria-expanded','false')
$parent.removeClass('open').trigger($.Event('hidden.bs.dropdown',relatedTarget))})}
Dropdown.prototype.toggle=function(e){var $this=$(this)
if($this.is('.disabled, :disabled'))return
var $parent=getParent($this)
var isActive=$parent.hasClass('open')
clearMenus()
if(!isActive){if('ontouchstart'in document.documentElement&&!$parent.closest('.navbar-nav').length){$(document.createElement('div')).addClass('dropdown-backdrop').insertAfter($(this)).on('click',clearMenus)}
var relatedTarget={relatedTarget:this}
$parent.trigger(e=$.Event('show.bs.dropdown',relatedTarget))
if(e.isDefaultPrevented())return
$this.trigger('focus').attr('aria-expanded','true')
$parent.toggleClass('open').trigger($.Event('shown.bs.dropdown',relatedTarget))}
return false}
Dropdown.prototype.keydown=function(e){if(!/(38|40|27|32)/.test(e.which)||/input|textarea/i.test(e.target.tagName))return
var $this=$(this)
e.preventDefault()
e.stopPropagation()
if($this.is('.disabled, :disabled'))return
var $parent=getParent($this)
var isActive=$parent.hasClass('open')
if(!isActive&&e.which!=27||isActive&&e.which==27){if(e.which==27)$parent.find(toggle).trigger('focus')
return $this.trigger('click')}
var desc=' li:not(.disabled):visible a'
var $items=$parent.find('.dropdown-menu'+desc)
if(!$items.length)return
var index=$items.index(e.target)
if(e.which==38&&index>0)index--
if(e.which==40&&index<$items.length-1)index++
if(!~index)index=0
$items.eq(index).trigger('focus')}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.dropdown')
if(!data)$this.data('bs.dropdown',(data=new Dropdown(this)))
if(typeof option=='string')data[option].call($this)})}
var old=$.fn.dropdown
$.fn.dropdown=Plugin
$.fn.dropdown.Constructor=Dropdown
$.fn.dropdown.noConflict=function(){$.fn.dropdown=old
return this}
$(document).on('click.bs.dropdown.data-api',clearMenus).on('click.bs.dropdown.data-api','.dropdown form',function(e){e.stopPropagation()}).on('click.bs.dropdown.data-api',toggle,Dropdown.prototype.toggle).on('keydown.bs.dropdown.data-api',toggle,Dropdown.prototype.keydown).on('keydown.bs.dropdown.data-api','.dropdown-menu',Dropdown.prototype.keydown)}(jQuery);+function($){'use strict';var Modal=function(element,options){this.options=options
this.$body=$(document.body)
this.$element=$(element)
this.$dialog=this.$element.find('.modal-dialog')
this.$backdrop=null
this.isShown=null
this.originalBodyPad=null
this.scrollbarWidth=0
this.ignoreBackdropClick=false
if(this.options.remote){this.$element.find('.modal-content').load(this.options.remote,$.proxy(function(){this.$element.trigger('loaded.bs.modal')},this))}}
Modal.VERSION='3.3.6'
Modal.TRANSITION_DURATION=300
Modal.BACKDROP_TRANSITION_DURATION=150
Modal.DEFAULTS={backdrop:true,keyboard:true,show:true}
Modal.prototype.toggle=function(_relatedTarget){return this.isShown?this.hide():this.show(_relatedTarget)}
Modal.prototype.show=function(_relatedTarget){var that=this
var e=$.Event('show.bs.modal',{relatedTarget:_relatedTarget})
this.$element.trigger(e)
if(this.isShown||e.isDefaultPrevented())return
this.isShown=true
this.checkScrollbar()
this.setScrollbar()
this.$body.addClass('modal-open')
this.escape()
this.resize()
this.$element.on('click.dismiss.bs.modal','[data-dismiss="modal"]',$.proxy(this.hide,this))
this.$dialog.on('mousedown.dismiss.bs.modal',function(){that.$element.one('mouseup.dismiss.bs.modal',function(e){if($(e.target).is(that.$element))that.ignoreBackdropClick=true})})
this.backdrop(function(){var transition=$.support.transition&&that.$element.hasClass('fade')
if(!that.$element.parent().length){that.$element.appendTo(that.$body)}
that.$element.show().scrollTop(0)
that.adjustDialog()
if(transition){that.$element[0].offsetWidth}
that.$element.addClass('in')
that.enforceFocus()
var e=$.Event('shown.bs.modal',{relatedTarget:_relatedTarget})
transition?that.$dialog.one('bsTransitionEnd',function(){that.$element.trigger('focus').trigger(e)}).emulateTransitionEnd(Modal.TRANSITION_DURATION):that.$element.trigger('focus').trigger(e)})}
Modal.prototype.hide=function(e){if(e)e.preventDefault()
e=$.Event('hide.bs.modal')
this.$element.trigger(e)
if(!this.isShown||e.isDefaultPrevented())return
this.isShown=false
this.escape()
this.resize()
$(document).off('focusin.bs.modal')
this.$element.removeClass('in').off('click.dismiss.bs.modal').off('mouseup.dismiss.bs.modal')
this.$dialog.off('mousedown.dismiss.bs.modal')
$.support.transition&&this.$element.hasClass('fade')?this.$element.one('bsTransitionEnd',$.proxy(this.hideModal,this)).emulateTransitionEnd(Modal.TRANSITION_DURATION):this.hideModal()}
Modal.prototype.enforceFocus=function(){$(document).off('focusin.bs.modal').on('focusin.bs.modal',$.proxy(function(e){if(this.$element[0]!==e.target&&!this.$element.has(e.target).length){this.$element.trigger('focus')}},this))}
Modal.prototype.escape=function(){if(this.isShown&&this.options.keyboard){this.$element.on('keydown.dismiss.bs.modal',$.proxy(function(e){e.which==27&&this.hide()},this))}else if(!this.isShown){this.$element.off('keydown.dismiss.bs.modal')}}
Modal.prototype.resize=function(){if(this.isShown){$(window).on('resize.bs.modal',$.proxy(this.handleUpdate,this))}else{$(window).off('resize.bs.modal')}}
Modal.prototype.hideModal=function(){var that=this
this.$element.hide()
this.backdrop(function(){that.$body.removeClass('modal-open')
that.resetAdjustments()
that.resetScrollbar()
that.$element.trigger('hidden.bs.modal')})}
Modal.prototype.removeBackdrop=function(){this.$backdrop&&this.$backdrop.remove()
this.$backdrop=null}
Modal.prototype.backdrop=function(callback){var that=this
var animate=this.$element.hasClass('fade')?'fade':''
if(this.isShown&&this.options.backdrop){var doAnimate=$.support.transition&&animate
this.$backdrop=$(document.createElement('div')).addClass('modal-backdrop '+animate).appendTo(this.$body)
this.$element.on('click.dismiss.bs.modal',$.proxy(function(e){if(this.ignoreBackdropClick){this.ignoreBackdropClick=false
return}
if(e.target!==e.currentTarget)return
this.options.backdrop=='static'?this.$element[0].focus():this.hide()},this))
if(doAnimate)this.$backdrop[0].offsetWidth
this.$backdrop.addClass('in')
if(!callback)return
doAnimate?this.$backdrop.one('bsTransitionEnd',callback).emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION):callback()}else if(!this.isShown&&this.$backdrop){this.$backdrop.removeClass('in')
var callbackRemove=function(){that.removeBackdrop()
callback&&callback()}
$.support.transition&&this.$element.hasClass('fade')?this.$backdrop.one('bsTransitionEnd',callbackRemove).emulateTransitionEnd(Modal.BACKDROP_TRANSITION_DURATION):callbackRemove()}else if(callback){callback()}}
Modal.prototype.handleUpdate=function(){this.adjustDialog()}
Modal.prototype.adjustDialog=function(){var modalIsOverflowing=this.$element[0].scrollHeight>document.documentElement.clientHeight
this.$element.css({paddingLeft:!this.bodyIsOverflowing&&modalIsOverflowing?this.scrollbarWidth:'',paddingRight:this.bodyIsOverflowing&&!modalIsOverflowing?this.scrollbarWidth:''})}
Modal.prototype.resetAdjustments=function(){this.$element.css({paddingLeft:'',paddingRight:''})}
Modal.prototype.checkScrollbar=function(){var fullWindowWidth=window.innerWidth
if(!fullWindowWidth){var documentElementRect=document.documentElement.getBoundingClientRect()
fullWindowWidth=documentElementRect.right-Math.abs(documentElementRect.left)}
this.bodyIsOverflowing=document.body.clientWidth<fullWindowWidth
this.scrollbarWidth=this.measureScrollbar()}
Modal.prototype.setScrollbar=function(){var bodyPad=parseInt((this.$body.css('padding-right')||0),10)
this.originalBodyPad=document.body.style.paddingRight||''
if(this.bodyIsOverflowing)this.$body.css('padding-right',bodyPad+this.scrollbarWidth)}
Modal.prototype.resetScrollbar=function(){this.$body.css('padding-right',this.originalBodyPad)}
Modal.prototype.measureScrollbar=function(){var scrollDiv=document.createElement('div')
scrollDiv.className='modal-scrollbar-measure'
this.$body.append(scrollDiv)
var scrollbarWidth=scrollDiv.offsetWidth-scrollDiv.clientWidth
this.$body[0].removeChild(scrollDiv)
return scrollbarWidth}
function Plugin(option,_relatedTarget){return this.each(function(){var $this=$(this)
var data=$this.data('bs.modal')
var options=$.extend({},Modal.DEFAULTS,$this.data(),typeof option=='object'&&option)
if(!data)$this.data('bs.modal',(data=new Modal(this,options)))
if(typeof option=='string')data[option](_relatedTarget)
else if(options.show)data.show(_relatedTarget)})}
var old=$.fn.modal
$.fn.modal=Plugin
$.fn.modal.Constructor=Modal
$.fn.modal.noConflict=function(){$.fn.modal=old
return this}
$(document).on('click.bs.modal.data-api','[data-toggle="modal"]',function(e){var $this=$(this)
var href=$this.attr('href')
var $target=$($this.attr('data-target')||(href&&href.replace(/.*(?=#[^\s]+$)/,'')))
var option=$target.data('bs.modal')?'toggle':$.extend({remote:!/#/.test(href)&&href},$target.data(),$this.data())
if($this.is('a'))e.preventDefault()
$target.one('show.bs.modal',function(showEvent){if(showEvent.isDefaultPrevented())return
$target.one('hidden.bs.modal',function(){$this.is(':visible')&&$this.trigger('focus')})})
Plugin.call($target,option,this)})}(jQuery);+function($){'use strict';var Tooltip=function(element,options){this.type=null
this.options=null
this.enabled=null
this.timeout=null
this.hoverState=null
this.$element=null
this.inState=null
this.init('tooltip',element,options)}
Tooltip.VERSION='3.3.6'
Tooltip.TRANSITION_DURATION=150
Tooltip.DEFAULTS={animation:true,placement:'top',selector:false,template:'<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:'hover focus',title:'',delay:0,html:false,container:false,viewport:{selector:'body',padding:0}}
Tooltip.prototype.init=function(type,element,options){this.enabled=true
this.type=type
this.$element=$(element)
this.options=this.getOptions(options)
this.$viewport=this.options.viewport&&$($.isFunction(this.options.viewport)?this.options.viewport.call(this,this.$element):(this.options.viewport.selector||this.options.viewport))
this.inState={click:false,hover:false,focus:false}
if(this.$element[0]instanceof document.constructor&&!this.options.selector){throw new Error('`selector` option must be specified when initializing '+this.type+' on the window.document object!')}
var triggers=this.options.trigger.split(' ')
for(var i=triggers.length;i--;){var trigger=triggers[i]
if(trigger=='click'){this.$element.on('click.'+this.type,this.options.selector,$.proxy(this.toggle,this))}else if(trigger!='manual'){var eventIn=trigger=='hover'?'mouseenter':'focusin'
var eventOut=trigger=='hover'?'mouseleave':'focusout'
this.$element.on(eventIn+'.'+this.type,this.options.selector,$.proxy(this.enter,this))
this.$element.on(eventOut+'.'+this.type,this.options.selector,$.proxy(this.leave,this))}}
this.options.selector?(this._options=$.extend({},this.options,{trigger:'manual',selector:''})):this.fixTitle()}
Tooltip.prototype.getDefaults=function(){return Tooltip.DEFAULTS}
Tooltip.prototype.getOptions=function(options){options=$.extend({},this.getDefaults(),this.$element.data(),options)
if(options.delay&&typeof options.delay=='number'){options.delay={show:options.delay,hide:options.delay}}
return options}
Tooltip.prototype.getDelegateOptions=function(){var options={}
var defaults=this.getDefaults()
this._options&&$.each(this._options,function(key,value){if(defaults[key]!=value)options[key]=value})
return options}
Tooltip.prototype.enter=function(obj){var self=obj instanceof this.constructor?obj:$(obj.currentTarget).data('bs.'+this.type)
if(!self){self=new this.constructor(obj.currentTarget,this.getDelegateOptions())
$(obj.currentTarget).data('bs.'+this.type,self)}
if(obj instanceof $.Event){self.inState[obj.type=='focusin'?'focus':'hover']=true}
if(self.tip().hasClass('in')||self.hoverState=='in'){self.hoverState='in'
return}
clearTimeout(self.timeout)
self.hoverState='in'
if(!self.options.delay||!self.options.delay.show)return self.show()
self.timeout=setTimeout(function(){if(self.hoverState=='in')self.show()},self.options.delay.show)}
Tooltip.prototype.isInStateTrue=function(){for(var key in this.inState){if(this.inState[key])return true}
return false}
Tooltip.prototype.leave=function(obj){var self=obj instanceof this.constructor?obj:$(obj.currentTarget).data('bs.'+this.type)
if(!self){self=new this.constructor(obj.currentTarget,this.getDelegateOptions())
$(obj.currentTarget).data('bs.'+this.type,self)}
if(obj instanceof $.Event){self.inState[obj.type=='focusout'?'focus':'hover']=false}
if(self.isInStateTrue())return
clearTimeout(self.timeout)
self.hoverState='out'
if(!self.options.delay||!self.options.delay.hide)return self.hide()
self.timeout=setTimeout(function(){if(self.hoverState=='out')self.hide()},self.options.delay.hide)}
Tooltip.prototype.show=function(){var e=$.Event('show.bs.'+this.type)
if(this.hasContent()&&this.enabled){this.$element.trigger(e)
var inDom=$.contains(this.$element[0].ownerDocument.documentElement,this.$element[0])
if(e.isDefaultPrevented()||!inDom)return
var that=this
var $tip=this.tip()
var tipId=this.getUID(this.type)
this.setContent()
$tip.attr('id',tipId)
this.$element.attr('aria-describedby',tipId)
if(this.options.animation)$tip.addClass('fade')
var placement=typeof this.options.placement=='function'?this.options.placement.call(this,$tip[0],this.$element[0]):this.options.placement
var autoToken=/\s?auto?\s?/i
var autoPlace=autoToken.test(placement)
if(autoPlace)placement=placement.replace(autoToken,'')||'top'
$tip.detach().css({top:0,left:0,display:'block'}).addClass(placement).data('bs.'+this.type,this)
this.options.container?$tip.appendTo(this.options.container):$tip.insertAfter(this.$element)
this.$element.trigger('inserted.bs.'+this.type)
var pos=this.getPosition()
var actualWidth=$tip[0].offsetWidth
var actualHeight=$tip[0].offsetHeight
if(autoPlace){var orgPlacement=placement
var viewportDim=this.getPosition(this.$viewport)
placement=placement=='bottom'&&pos.bottom+actualHeight>viewportDim.bottom?'top':placement=='top'&&pos.top-actualHeight<viewportDim.top?'bottom':placement=='right'&&pos.right+actualWidth>viewportDim.width?'left':placement=='left'&&pos.left-actualWidth<viewportDim.left?'right':placement
$tip.removeClass(orgPlacement).addClass(placement)}
var calculatedOffset=this.getCalculatedOffset(placement,pos,actualWidth,actualHeight)
this.applyPlacement(calculatedOffset,placement)
var complete=function(){var prevHoverState=that.hoverState
that.$element.trigger('shown.bs.'+that.type)
that.hoverState=null
if(prevHoverState=='out')that.leave(that)}
$.support.transition&&this.$tip.hasClass('fade')?$tip.one('bsTransitionEnd',complete).emulateTransitionEnd(Tooltip.TRANSITION_DURATION):complete()}}
Tooltip.prototype.applyPlacement=function(offset,placement){var $tip=this.tip()
var width=$tip[0].offsetWidth
var height=$tip[0].offsetHeight
var marginTop=parseInt($tip.css('margin-top'),10)
var marginLeft=parseInt($tip.css('margin-left'),10)
if(isNaN(marginTop))marginTop=0
if(isNaN(marginLeft))marginLeft=0
offset.top+=marginTop
offset.left+=marginLeft
$.offset.setOffset($tip[0],$.extend({using:function(props){$tip.css({top:Math.round(props.top),left:Math.round(props.left)})}},offset),0)
$tip.addClass('in')
var actualWidth=$tip[0].offsetWidth
var actualHeight=$tip[0].offsetHeight
if(placement=='top'&&actualHeight!=height){offset.top=offset.top+height-actualHeight}
var delta=this.getViewportAdjustedDelta(placement,offset,actualWidth,actualHeight)
if(delta.left)offset.left+=delta.left
else offset.top+=delta.top
var isVertical=/top|bottom/.test(placement)
var arrowDelta=isVertical?delta.left*2-width+actualWidth:delta.top*2-height+actualHeight
var arrowOffsetPosition=isVertical?'offsetWidth':'offsetHeight'
$tip.offset(offset)
this.replaceArrow(arrowDelta,$tip[0][arrowOffsetPosition],isVertical)}
Tooltip.prototype.replaceArrow=function(delta,dimension,isVertical){this.arrow().css(isVertical?'left':'top',50*(1-delta/dimension)+'%').css(isVertical?'top':'left','')}
Tooltip.prototype.setContent=function(){var $tip=this.tip()
var title=this.getTitle()
$tip.find('.tooltip-inner')[this.options.html?'html':'text'](title)
$tip.removeClass('fade in top bottom left right')}
Tooltip.prototype.hide=function(callback){var that=this
var $tip=$(this.$tip)
var e=$.Event('hide.bs.'+this.type)
function complete(){if(that.hoverState!='in')$tip.detach()
that.$element.removeAttr('aria-describedby').trigger('hidden.bs.'+that.type)
callback&&callback()}
this.$element.trigger(e)
if(e.isDefaultPrevented())return
$tip.removeClass('in')
$.support.transition&&$tip.hasClass('fade')?$tip.one('bsTransitionEnd',complete).emulateTransitionEnd(Tooltip.TRANSITION_DURATION):complete()
this.hoverState=null
return this}
Tooltip.prototype.fixTitle=function(){var $e=this.$element
if($e.attr('title')||typeof $e.attr('data-original-title')!='string'){$e.attr('data-original-title',$e.attr('title')||'').attr('title','')}}
Tooltip.prototype.hasContent=function(){return this.getTitle()}
Tooltip.prototype.getPosition=function($element){$element=$element||this.$element
var el=$element[0]
var isBody=el.tagName=='BODY'
var elRect=el.getBoundingClientRect()
if(elRect.width==null){elRect=$.extend({},elRect,{width:elRect.right-elRect.left,height:elRect.bottom-elRect.top})}
var elOffset=isBody?{top:0,left:0}:$element.offset()
var scroll={scroll:isBody?document.documentElement.scrollTop||document.body.scrollTop:$element.scrollTop()}
var outerDims=isBody?{width:$(window).width(),height:$(window).height()}:null
return $.extend({},elRect,scroll,outerDims,elOffset)}
Tooltip.prototype.getCalculatedOffset=function(placement,pos,actualWidth,actualHeight){return placement=='bottom'?{top:pos.top+pos.height,left:pos.left+pos.width/2-actualWidth/2}:placement=='top'?{top:pos.top-actualHeight,left:pos.left+pos.width/2-actualWidth/2}:placement=='left'?{top:pos.top+pos.height/2-actualHeight/2,left:pos.left-actualWidth}:{top:pos.top+pos.height/2-actualHeight/2,left:pos.left+pos.width}}
Tooltip.prototype.getViewportAdjustedDelta=function(placement,pos,actualWidth,actualHeight){var delta={top:0,left:0}
if(!this.$viewport)return delta
var viewportPadding=this.options.viewport&&this.options.viewport.padding||0
var viewportDimensions=this.getPosition(this.$viewport)
if(/right|left/.test(placement)){var topEdgeOffset=pos.top-viewportPadding-viewportDimensions.scroll
var bottomEdgeOffset=pos.top+viewportPadding-viewportDimensions.scroll+actualHeight
if(topEdgeOffset<viewportDimensions.top){delta.top=viewportDimensions.top-topEdgeOffset}else if(bottomEdgeOffset>viewportDimensions.top+viewportDimensions.height){delta.top=viewportDimensions.top+viewportDimensions.height-bottomEdgeOffset}}else{var leftEdgeOffset=pos.left-viewportPadding
var rightEdgeOffset=pos.left+viewportPadding+actualWidth
if(leftEdgeOffset<viewportDimensions.left){delta.left=viewportDimensions.left-leftEdgeOffset}else if(rightEdgeOffset>viewportDimensions.right){delta.left=viewportDimensions.left+viewportDimensions.width-rightEdgeOffset}}
return delta}
Tooltip.prototype.getTitle=function(){var title
var $e=this.$element
var o=this.options
title=$e.attr('data-original-title')||(typeof o.title=='function'?o.title.call($e[0]):o.title)
return title}
Tooltip.prototype.getUID=function(prefix){do prefix+=~~(Math.random()*1000000)
while(document.getElementById(prefix))
return prefix}
Tooltip.prototype.tip=function(){if(!this.$tip){this.$tip=$(this.options.template)
if(this.$tip.length!=1){throw new Error(this.type+' `template` option must consist of exactly 1 top-level element!')}}
return this.$tip}
Tooltip.prototype.arrow=function(){return(this.$arrow=this.$arrow||this.tip().find('.tooltip-arrow'))}
Tooltip.prototype.enable=function(){this.enabled=true}
Tooltip.prototype.disable=function(){this.enabled=false}
Tooltip.prototype.toggleEnabled=function(){this.enabled=!this.enabled}
Tooltip.prototype.toggle=function(e){var self=this
if(e){self=$(e.currentTarget).data('bs.'+this.type)
if(!self){self=new this.constructor(e.currentTarget,this.getDelegateOptions())
$(e.currentTarget).data('bs.'+this.type,self)}}
if(e){self.inState.click=!self.inState.click
if(self.isInStateTrue())self.enter(self)
else self.leave(self)}else{self.tip().hasClass('in')?self.leave(self):self.enter(self)}}
Tooltip.prototype.destroy=function(){var that=this
clearTimeout(this.timeout)
this.hide(function(){that.$element.off('.'+that.type).removeData('bs.'+that.type)
if(that.$tip){that.$tip.detach()}
that.$tip=null
that.$arrow=null
that.$viewport=null})}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.tooltip')
var options=typeof option=='object'&&option
if(!data&&/destroy|hide/.test(option))return
if(!data)$this.data('bs.tooltip',(data=new Tooltip(this,options)))
if(typeof option=='string')data[option]()})}
var old=$.fn.tooltip
$.fn.tooltip=Plugin
$.fn.tooltip.Constructor=Tooltip
$.fn.tooltip.noConflict=function(){$.fn.tooltip=old
return this}}(jQuery);+function($){'use strict';var Popover=function(element,options){this.init('popover',element,options)}
if(!$.fn.tooltip)throw new Error('Popover requires tooltip.js')
Popover.VERSION='3.3.6'
Popover.DEFAULTS=$.extend({},$.fn.tooltip.Constructor.DEFAULTS,{placement:'right',trigger:'click',content:'',template:'<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'})
Popover.prototype=$.extend({},$.fn.tooltip.Constructor.prototype)
Popover.prototype.constructor=Popover
Popover.prototype.getDefaults=function(){return Popover.DEFAULTS}
Popover.prototype.setContent=function(){var $tip=this.tip()
var title=this.getTitle()
var content=this.getContent()
$tip.find('.popover-title')[this.options.html?'html':'text'](title)
$tip.find('.popover-content').children().detach().end()[this.options.html?(typeof content=='string'?'html':'append'):'text'](content)
$tip.removeClass('fade top bottom left right in')
if(!$tip.find('.popover-title').html())$tip.find('.popover-title').hide()}
Popover.prototype.hasContent=function(){return this.getTitle()||this.getContent()}
Popover.prototype.getContent=function(){var $e=this.$element
var o=this.options
return $e.attr('data-content')||(typeof o.content=='function'?o.content.call($e[0]):o.content)}
Popover.prototype.arrow=function(){return(this.$arrow=this.$arrow||this.tip().find('.arrow'))}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.popover')
var options=typeof option=='object'&&option
if(!data&&/destroy|hide/.test(option))return
if(!data)$this.data('bs.popover',(data=new Popover(this,options)))
if(typeof option=='string')data[option]()})}
var old=$.fn.popover
$.fn.popover=Plugin
$.fn.popover.Constructor=Popover
$.fn.popover.noConflict=function(){$.fn.popover=old
return this}}(jQuery);+function($){'use strict';function ScrollSpy(element,options){this.$body=$(document.body)
this.$scrollElement=$(element).is(document.body)?$(window):$(element)
this.options=$.extend({},ScrollSpy.DEFAULTS,options)
this.selector=(this.options.target||'')+' .nav li > a'
this.offsets=[]
this.targets=[]
this.activeTarget=null
this.scrollHeight=0
this.$scrollElement.on('scroll.bs.scrollspy',$.proxy(this.process,this))
this.refresh()
this.process()}
ScrollSpy.VERSION='3.3.6'
ScrollSpy.DEFAULTS={offset:10}
ScrollSpy.prototype.getScrollHeight=function(){return this.$scrollElement[0].scrollHeight||Math.max(this.$body[0].scrollHeight,document.documentElement.scrollHeight)}
ScrollSpy.prototype.refresh=function(){var that=this
var offsetMethod='offset'
var offsetBase=0
this.offsets=[]
this.targets=[]
this.scrollHeight=this.getScrollHeight()
if(!$.isWindow(this.$scrollElement[0])){offsetMethod='position'
offsetBase=this.$scrollElement.scrollTop()}
this.$body.find(this.selector).map(function(){var $el=$(this)
var href=$el.data('target')||$el.attr('href')
var $href=/^#./.test(href)&&$(href)
return($href&&$href.length&&$href.is(':visible')&&[[$href[offsetMethod]().top+offsetBase,href]])||null}).sort(function(a,b){return a[0]-b[0]}).each(function(){that.offsets.push(this[0])
that.targets.push(this[1])})}
ScrollSpy.prototype.process=function(){var scrollTop=this.$scrollElement.scrollTop()+this.options.offset
var scrollHeight=this.getScrollHeight()
var maxScroll=this.options.offset+scrollHeight-this.$scrollElement.height()
var offsets=this.offsets
var targets=this.targets
var activeTarget=this.activeTarget
var i
if(this.scrollHeight!=scrollHeight){this.refresh()}
if(scrollTop>=maxScroll){return activeTarget!=(i=targets[targets.length-1])&&this.activate(i)}
if(activeTarget&&scrollTop<offsets[0]){this.activeTarget=null
return this.clear()}
for(i=offsets.length;i--;){activeTarget!=targets[i]&&scrollTop>=offsets[i]&&(offsets[i+1]===undefined||scrollTop<offsets[i+1])&&this.activate(targets[i])}}
ScrollSpy.prototype.activate=function(target){this.activeTarget=target
this.clear()
var selector=this.selector+'[data-target="'+target+'"],'+
this.selector+'[href="'+target+'"]'
var active=$(selector).parents('li').addClass('active')
if(active.parent('.dropdown-menu').length){active=active.closest('li.dropdown').addClass('active')}
active.trigger('activate.bs.scrollspy')}
ScrollSpy.prototype.clear=function(){$(this.selector).parentsUntil(this.options.target,'.active').removeClass('active')}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.scrollspy')
var options=typeof option=='object'&&option
if(!data)$this.data('bs.scrollspy',(data=new ScrollSpy(this,options)))
if(typeof option=='string')data[option]()})}
var old=$.fn.scrollspy
$.fn.scrollspy=Plugin
$.fn.scrollspy.Constructor=ScrollSpy
$.fn.scrollspy.noConflict=function(){$.fn.scrollspy=old
return this}
$(window).on('load.bs.scrollspy.data-api',function(){$('[data-spy="scroll"]').each(function(){var $spy=$(this)
Plugin.call($spy,$spy.data())})})}(jQuery);+function($){'use strict';var Tab=function(element){this.element=$(element)}
Tab.VERSION='3.3.6'
Tab.TRANSITION_DURATION=150
Tab.prototype.show=function(){var $this=this.element
var $ul=$this.closest('ul:not(.dropdown-menu)')
var selector=$this.data('target')
if(!selector){selector=$this.attr('href')
selector=selector&&selector.replace(/.*(?=#[^\s]*$)/,'')}
if($this.parent('li').hasClass('active'))return
var $previous=$ul.find('.active:last a')
var hideEvent=$.Event('hide.bs.tab',{relatedTarget:$this[0]})
var showEvent=$.Event('show.bs.tab',{relatedTarget:$previous[0]})
$previous.trigger(hideEvent)
$this.trigger(showEvent)
if(showEvent.isDefaultPrevented()||hideEvent.isDefaultPrevented())return
var $target=$(selector)
this.activate($this.closest('li'),$ul)
this.activate($target,$target.parent(),function(){$previous.trigger({type:'hidden.bs.tab',relatedTarget:$this[0]})
$this.trigger({type:'shown.bs.tab',relatedTarget:$previous[0]})})}
Tab.prototype.activate=function(element,container,callback){var $active=container.find('> .active')
var transition=callback&&$.support.transition&&($active.length&&$active.hasClass('fade')||!!container.find('> .fade').length)
function next(){$active.removeClass('active').find('> .dropdown-menu > .active').removeClass('active').end().find('[data-toggle="tab"]').attr('aria-expanded',false)
element.addClass('active').find('[data-toggle="tab"]').attr('aria-expanded',true)
if(transition){element[0].offsetWidth
element.addClass('in')}else{element.removeClass('fade')}
if(element.parent('.dropdown-menu').length){element.closest('li.dropdown').addClass('active').end().find('[data-toggle="tab"]').attr('aria-expanded',true)}
callback&&callback()}
$active.length&&transition?$active.one('bsTransitionEnd',next).emulateTransitionEnd(Tab.TRANSITION_DURATION):next()
$active.removeClass('in')}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.tab')
if(!data)$this.data('bs.tab',(data=new Tab(this)))
if(typeof option=='string')data[option]()})}
var old=$.fn.tab
$.fn.tab=Plugin
$.fn.tab.Constructor=Tab
$.fn.tab.noConflict=function(){$.fn.tab=old
return this}
var clickHandler=function(e){e.preventDefault()
Plugin.call($(this),'show')}
$(document).on('click.bs.tab.data-api','[data-toggle="tab"]',clickHandler).on('click.bs.tab.data-api','[data-toggle="pill"]',clickHandler)}(jQuery);+function($){'use strict';var Affix=function(element,options){this.options=$.extend({},Affix.DEFAULTS,options)
this.$target=$(this.options.target).on('scroll.bs.affix.data-api',$.proxy(this.checkPosition,this)).on('click.bs.affix.data-api',$.proxy(this.checkPositionWithEventLoop,this))
this.$element=$(element)
this.affixed=null
this.unpin=null
this.pinnedOffset=null
this.checkPosition()}
Affix.VERSION='3.3.6'
Affix.RESET='affix affix-top affix-bottom'
Affix.DEFAULTS={offset:0,target:window}
Affix.prototype.getState=function(scrollHeight,height,offsetTop,offsetBottom){var scrollTop=this.$target.scrollTop()
var position=this.$element.offset()
var targetHeight=this.$target.height()
if(offsetTop!=null&&this.affixed=='top')return scrollTop<offsetTop?'top':false
if(this.affixed=='bottom'){if(offsetTop!=null)return(scrollTop+this.unpin<=position.top)?false:'bottom'
return(scrollTop+targetHeight<=scrollHeight-offsetBottom)?false:'bottom'}
var initializing=this.affixed==null
var colliderTop=initializing?scrollTop:position.top
var colliderHeight=initializing?targetHeight:height
if(offsetTop!=null&&scrollTop<=offsetTop)return'top'
if(offsetBottom!=null&&(colliderTop+colliderHeight>=scrollHeight-offsetBottom))return'bottom'
return false}
Affix.prototype.getPinnedOffset=function(){if(this.pinnedOffset)return this.pinnedOffset
this.$element.removeClass(Affix.RESET).addClass('affix')
var scrollTop=this.$target.scrollTop()
var position=this.$element.offset()
return(this.pinnedOffset=position.top-scrollTop)}
Affix.prototype.checkPositionWithEventLoop=function(){setTimeout($.proxy(this.checkPosition,this),1)}
Affix.prototype.checkPosition=function(){if(!this.$element.is(':visible'))return
var height=this.$element.height()
var offset=this.options.offset
var offsetTop=offset.top
var offsetBottom=offset.bottom
var scrollHeight=Math.max($(document).height(),$(document.body).height())
if(typeof offset!='object')offsetBottom=offsetTop=offset
if(typeof offsetTop=='function')offsetTop=offset.top(this.$element)
if(typeof offsetBottom=='function')offsetBottom=offset.bottom(this.$element)
var affix=this.getState(scrollHeight,height,offsetTop,offsetBottom)
if(this.affixed!=affix){if(this.unpin!=null)this.$element.css('top','')
var affixType='affix'+(affix?'-'+affix:'')
var e=$.Event(affixType+'.bs.affix')
this.$element.trigger(e)
if(e.isDefaultPrevented())return
this.affixed=affix
this.unpin=affix=='bottom'?this.getPinnedOffset():null
this.$element.removeClass(Affix.RESET).addClass(affixType).trigger(affixType.replace('affix','affixed')+'.bs.affix')}
if(affix=='bottom'){this.$element.offset({top:scrollHeight-height-offsetBottom})}}
function Plugin(option){return this.each(function(){var $this=$(this)
var data=$this.data('bs.affix')
var options=typeof option=='object'&&option
if(!data)$this.data('bs.affix',(data=new Affix(this,options)))
if(typeof option=='string')data[option]()})}
var old=$.fn.affix
$.fn.affix=Plugin
$.fn.affix.Constructor=Affix
$.fn.affix.noConflict=function(){$.fn.affix=old
return this}
$(window).on('load',function(){$('[data-spy="affix"]').each(function(){var $spy=$(this)
var data=$spy.data()
data.offset=data.offset||{}
if(data.offsetBottom!=null)data.offset.bottom=data.offsetBottom
if(data.offsetTop!=null)data.offset.top=data.offsetTop
Plugin.call($spy,data)})})}(jQuery);


/*===============================
/sampo4/plugins/system/t3/base-bs3/js/jquery.tap.min.js
================================================================================*/;
!function(a,b){"use strict";var c,d,e,f="._tap",g="._tapActive",h="tap",i="clientX clientY screenX screenY pageX pageY".split(" "),j={count:0,event:0},k=function(a,c){var d=c.originalEvent,e=b.Event(d);e.type=a;for(var f=0,g=i.length;g>f;f++)e[i[f]]=c[i[f]];return e},l=function(a){if(a.isTrigger)return!1;var c=j.event,d=Math.abs(a.pageX-c.pageX),e=Math.abs(a.pageY-c.pageY),f=Math.max(d,e);return a.timeStamp-c.timeStamp<b.tap.TIME_DELTA&&f<b.tap.POSITION_DELTA&&(!c.touches||1===j.count)&&o.isTracking},m=function(a){if(!e)return!1;var c=Math.abs(a.pageX-e.pageX),d=Math.abs(a.pageY-e.pageY),f=Math.max(c,d);return Math.abs(a.timeStamp-e.timeStamp)<750&&f<b.tap.POSITION_DELTA},n=function(a){if(0===a.type.indexOf("touch")){a.touches=a.originalEvent.changedTouches;for(var b=a.touches[0],c=0,d=i.length;d>c;c++)a[i[c]]=b[i[c]]}a.timeStamp=Date.now?Date.now():+new Date},o={isEnabled:!1,isTracking:!1,enable:function(){o.isEnabled||(o.isEnabled=!0,c=b(a.body).on("touchstart"+f,o.onStart).on("mousedown"+f,o.onStart).on("click"+f,o.onClick))},disable:function(){o.isEnabled&&(o.isEnabled=!1,c.off(f))},onStart:function(a){a.isTrigger||(n(a),(!b.tap.LEFT_BUTTON_ONLY||a.touches||1===a.which)&&(a.touches&&(j.count=a.touches.length),o.isTracking||(a.touches||!m(a))&&(o.isTracking=!0,j.event=a,a.touches?(e=a,c.on("touchend"+f+g,o.onEnd).on("touchcancel"+f+g,o.onCancel)):c.on("mouseup"+f+g,o.onEnd))))},onEnd:function(a){var c;a.isTrigger||(n(a),l(a)&&(c=k(h,a),d=c,b(j.event.target).trigger(c)),o.onCancel(a))},onCancel:function(a){a&&"touchcancel"===a.type&&a.preventDefault(),o.isTracking=!1,c.off(g)},onClick:function(a){return!a.isTrigger&&d&&d.isDefaultPrevented()&&d.target===a.target&&d.pageX===a.pageX&&d.pageY===a.pageY&&a.timeStamp-d.timeStamp<750?(d=null,!1):void 0}};b(a).ready(o.enable),b.tap={POSITION_DELTA:10,TIME_DELTA:400,LEFT_BUTTON_ONLY:!0}}(document,jQuery);


/*===============================
/sampo4/plugins/system/t3/base-bs3/js/off-canvas.js
================================================================================*/;
jQuery(document).ready(function($){function getAndroidVersion(ua){var ua=ua||navigator.userAgent;var match=ua.match(/Android\s([0-9\.]*)/);return match?match[1]:false;};if(parseInt(getAndroidVersion())==4){$('#t3-mainnav').addClass('t3-mainnav-android');}
var JA_isLoading=false;if(/MSIE\s([\d.]+)/.test(navigator.userAgent)?new Number(RegExp.$1)<10:false){$('html').addClass('old-ie');}else if(/constructor/i.test(window.HTMLElement)){$('html').addClass('safari');}
var $wrapper=$('body'),$inner=$('.t3-wrapper'),$toggles=$('.off-canvas-toggle'),$offcanvas=$('.t3-off-canvas'),$close=$('.t3-off-canvas .close'),$btn=null,$nav=null,direction='left',$fixed=null;if(!$wrapper.length)return;$toggles.each(function(){var $this=$(this),$nav=$($this.data('nav')),effect=$this.data('effect'),direction=($('html').attr('dir')=='rtl'&&$this.data('pos')!='right')||($('html').attr('dir')!='rtl'&&$this.data('pos')=='right')?'right':'left';$nav.addClass(effect).addClass('off-canvas-'+direction);var inside_effect=['off-canvas-effect-3','off-canvas-effect-16','off-canvas-effect-7','off-canvas-effect-8','off-canvas-effect-14'];if($.inArray(effect,inside_effect)==-1){$inner.before($nav);}else{$inner.prepend($nav);}});$toggles.on('tap',function(e){stopBubble(e);if($wrapper.hasClass('off-canvas-open')){oc_hide(e);return false;}
$btn=$(this);$nav=$($btn.data('nav'));if(!$fixed)$fixed=$inner.find('*').filter(function(){return $(this).css("position")==='fixed';});else $fixed=$fixed.filter(function(){return $(this).css("position")==='fixed';}).add($inner.find('.affix'));$nav.addClass('off-canvas-current');direction=($('html').attr('dir')=='rtl'&&$btn.data('pos')!='right')||($('html').attr('dir')!='rtl'&&$btn.data('pos')=='right')?'right':'left';$offcanvas.height($(window).height());var events=$(window).data('events');if(events&&events.scroll&&events.scroll.length){var handlers=[];for(var i=0;i<events.scroll.length;i++){handlers[i]=events.scroll[i].handler;}
$(window).data('scroll-events',handlers);$(window).off('scroll');}
var scrollTop=($('html').scrollTop())?$('html').scrollTop():$('body').scrollTop();$('html').addClass('noscroll').css('top',-scrollTop).data('top',scrollTop);$('.t3-off-canvas').css('top',scrollTop);$fixed.each(function(){var $this=$(this),$parent=$this.parent(),mtop=0;while(!$parent.is($inner)&&$parent.css("position")==='static')$parent=$parent.parent();mtop=-$parent.offset().top;$this.css({'position':'absolute','margin-top':mtop});});$wrapper.scrollTop(scrollTop);$wrapper[0].className=$.trim($wrapper[0].className.replace(/\s*off\-canvas\-effect\-\d+\s*/g,' '))+' '+$btn.data('effect')+' '+'off-canvas-'+direction;setTimeout(oc_show,50);return false;});var oc_show=function(){if(JA_isLoading==true){return;}
JA_isLoading=true;$wrapper.addClass('off-canvas-open');$inner.on('click',oc_hide);$close.on('click',oc_hide);$offcanvas.on('click',handleClick);if($.browser.msie&&$.browser.version<10){var p1={},p2={};p1['padding-'+direction]=$('.t3-off-canvas').width();p2[direction]=0;$inner.animate(p1);$nav.animate(p2);}
setTimeout(function(){JA_isLoading=false;},200);};var oc_hide=function(){if(JA_isLoading==true){return;}
JA_isLoading=true;$inner.off('click',oc_hide);$close.off('click',oc_hide);$offcanvas.off('click',handleClick);setTimeout(function(){$wrapper.removeClass('off-canvas-open');},100);setTimeout(function(){$wrapper.removeClass($btn.data('effect')).removeClass('off-canvas-'+direction);$wrapper.scrollTop(0);$('html').removeClass('noscroll').css('top','');$('html,body').scrollTop($('html').data('top'));$nav.removeClass('off-canvas-current');$fixed.css({'position':'','margin-top':''});if($(window).data('scroll-events')){var handlers=$(window).data('scroll-events');for(var i=0;i<handlers.length;i++){$(window).on('scroll',handlers[i]);}
$(window).data('scroll-events',null);}
JA_isLoading=false;},700);if($('html').hasClass('old-ie')){var p1={},p2={};p1['padding-'+direction]=0;p2[direction]=-$('.t3-off-canvas').width();$inner.animate(p1);$nav.animate(p2);}};var handleClick=function(e){if($(e.target).closest('a').length){if(!e.target.href)return;var arr1=e.target.href.split('#'),arr2=location.href.split('#');if(arr1[0]==arr2[0]&&arr1.length>1&&arr1[1].length){oc_hide();setTimeout(function(){var anchor=$("a[name='"+arr1[1]+"']");if(!anchor.length)anchor=$('#'+arr1[1]);if(anchor.length)
$('html,body').animate({scrollTop:anchor.offset().top},'slow');},1000);}
return;}
stopBubble(e);return true;}
var stopBubble=function(e){e.stopPropagation();}
$(window).load(function(){setTimeout(function(){$fixed=$inner.find('*').filter(function(){return $(this).css("position")==='fixed';});},100);});})


/*===============================
/sampo4/plugins/system/t3/base-bs3/js/script.js
================================================================================*/;
!function($){if($.browser==undefined||$.browser.msie==undefined){$.browser={msie:false,version:0};if(match=navigator.userAgent.match(/MSIE ([0-9]{1,}[\.0-9]{0,})/)||navigator.userAgent.match(/Trident.*rv:([0-9]{1,}[\.0-9]{0,})/)){$.browser.msie=true;$.browser.version=match[1];}}
if($.browser.msie){$('html').addClass('ie'+Math.floor($.browser.version));}
$(document).ready(function(){if(!window.getComputedStyle){window.getComputedStyle=function(el,pseudo){this.el=el;this.getPropertyValue=function(prop){var re=/(\-([a-z]){1})/g;if(prop=='float')prop='styleFloat';if(re.test(prop)){prop=prop.replace(re,function(){return arguments[2].toUpperCase();});}
return el.currentStyle[prop]?el.currentStyle[prop]:null;}
return this;}}
var fromClass='body-data-holder',prop='content',$inspector=$('<div>').css('display','none').addClass(fromClass).appendTo($('body'));try{var computedStyle=window.getComputedStyle($inspector[0],':before');if(computedStyle){var attrs=computedStyle.getPropertyValue(prop);if(attrs){var matches=attrs.match(/([\da-z\-]+)/gi),data={};if(matches&&matches.length){for(var i=0;i<matches.length;i++){data[matches[i++]]=i<matches.length?matches[i]:null;}}
$('body').data(data);}}}finally{$inspector.remove();}});(function(){$.support.t3transform=(function(){var style=document.createElement('div').style,vendors=['t','webkitT','MozT','msT','OT'],transform,i=0,l=vendors.length;for(;i<l;i++){transform=vendors[i]+'ransform';if(transform in style){return transform;}}
return false;})();})();(function(){$('html').addClass('ontouchstart'in window?'touch':'no-touch');})();$(document).ready(function(){(function(){if(window.MooTools&&window.MooTools.More&&Element&&Element.implement){var mthide=Element.prototype.hide,mtshow=Element.prototype.show,mtslide=Element.prototype.slide;Element.implement({show:function(args){if(arguments.callee&&arguments.callee.caller&&arguments.callee.caller.toString().indexOf('isPropagationStopped')!==-1){return this;}
return $.isFunction(mtshow)&&mtshow.apply(this,args);},hide:function(){if(arguments.callee&&arguments.callee.caller&&arguments.callee.caller.toString().indexOf('isPropagationStopped')!==-1){return this;}
return $.isFunction(mthide)&&mthide.apply(this,arguments);},slide:function(args){if(arguments.callee&&arguments.callee.caller&&arguments.callee.caller.toString().indexOf('isPropagationStopped')!==-1){return this;}
return $.isFunction(mtslide)&&mtslide.apply(this,args);}})}})();$.fn.tooltip.Constructor&&$.fn.tooltip.Constructor.DEFAULTS&&($.fn.tooltip.Constructor.DEFAULTS.html=true);$.fn.popover.Constructor&&$.fn.popover.Constructor.DEFAULTS&&($.fn.popover.Constructor.DEFAULTS.html=true);$.fn.tooltip.defaults&&($.fn.tooltip.defaults.html=true);$.fn.popover.defaults&&($.fn.popover.defaults.html=true);(function(){if(window.jomsQuery&&jomsQuery.fn.collapse){$('[data-toggle="collapse"]').on('click',function(e){$($(this).attr('data-target')).eq(0).collapse('toggle');e.stopPropagation();return false;});jomsQuery('html, body').off('touchstart.dropdown.data-api');}})();(function(){if($.fn.chosen&&$(document.documentElement).attr('dir')=='rtl'){$('select').addClass('chzn-rtl');}})();});$(window).load(function(){if(!$(document.documentElement).hasClass('off-canvas-ready')&&($('.navbar-collapse-fixed-top').length||$('.navbar-collapse-fixed-bottom').length)){var btn=$('.btn-navbar[data-toggle="collapse"]');if(!btn.length){return;}
if(btn.data('target')){var nav=$(btn.data('target'));if(!nav.length){return;}
var fixedtop=nav.closest('.navbar-collapse-fixed-top').length;btn.on('click',function(){var wheight=(window.innerHeight||$(window).height());if(!$.support.transition){nav.parent().css('height',!btn.hasClass('collapsed')&&btn.data('t3-clicked')?'':wheight);btn.data('t3-clicked',1);}
nav.addClass('animate').css('max-height',wheight-
(fixedtop?(parseFloat(nav.css('top'))||0):(parseFloat(nav.css('bottom'))||0)));});nav.on('shown hidden',function(){nav.removeClass('animate');});}}});}(jQuery);


/*===============================
/sampo4/plugins/system/t3/base-bs3/js/menu.js
================================================================================*/;
;(function($){var T3Menu=function(elm,options){this.$menu=$(elm);if(!this.$menu.length){return;}
this.options=$.extend({},$.fn.t3menu.defaults,options);this.child_open=[];this.loaded=false;this.start();};T3Menu.prototype={constructor:T3Menu,start:function(){if(this.loaded){return;}
this.loaded=true;var self=this,options=this.options,$menu=this.$menu;this.$items=$menu.find('li');this.$items.each(function(idx,li){var $item=$(this),$child=$item.children('.dropdown-menu'),$link=$item.children('a'),item={$item:$item,child:$child.length,link:$link.length,clickable:!($link.length&&$child.length),mega:$item.hasClass('mega'),status:'close',timer:null,atimer:null};$item.data('t3menu.item',item);if($child.length&&!options.hover){$item.on('click',function(e){e.stopPropagation();if($item.hasClass('group')){return;}
if(item.status=='close'){e.preventDefault();self.show(item);}});}else{$item.on('click',function(e){if($(e.target).data('toggle'))return;e.stopPropagation()});}
$item.find('a > .caret').on('click tap',function(e){item.clickable=false;});if(options.hover){$item.on('mouseover',function(e){if($item.hasClass('group'))
return;var $target=$(e.target);if($target.data('show-processed'))
return;$target.data('show-processed',true);setTimeout(function(){$target.data('show-processed',false);},10);self.show(item);}).on('mouseleave',function(e){if($item.hasClass('group'))
return;var $target=$(e.target);if($target.data('hide-processed'))
return;$target.data('hide-processed',true);setTimeout(function(){$target.data('hide-processed',false);},10);self.hide(item,$target);});if($link.length&&$child.length){$link.on('click',function(e){if(item.clickable){e.stopPropagation();}
return item.clickable;});}}});$(document.body).on('tap hideall.t3menu',function(e){clearTimeout(self.timer);self.timer=setTimeout($.proxy(self.hide_alls,self),e.type=='tap'?500:self.options.hidedelay);});$menu.find('.mega-dropdown-menu').on('hideall.t3menu',function(e){e.stopPropagation();e.preventDefault();return false;});$menu.find('input, select, textarea, label').on('click tap',function(e){e.stopPropagation();});var $megatab=$menu.find('.mega-tab');if($megatab.length){$megatab.each(function(){var $tabul=$(this).find('>div>ul'),$tabItems=$tabul.children('.dropdown-submenu'),$tabs=$tabul.find('>li>.dropdown-menu'),tabheight=0,$parentItem=$(this).closest('li');$tabItems.data('mega-tab-item',1);var megatabs=$parentItem.data('mega-tabs')?$parentItem.data('mega-tabs'):[];megatabs.push($tabul);$parentItem.data('mega-tabs',megatabs);$tabItems.first().data('mega-tab-active',true).addClass('open');var $p=$tabul.parents('.dropdown-menu');$p.each(function(){var $this=$(this);$this.data('prev-style',$this.attr('style')).css({visibility:"visible",display:"block"});})
$tabs.each(function(){var $this=$(this),thisstyle=$this.attr('style');$this.css({visibility:"hidden",display:"block"});tabheight=Math.max(tabheight,$this.children().innerHeight());if(thisstyle){$this.attr('style',thisstyle);}else{$this.removeAttr('style');}});$tabul.css('min-height',tabheight);$p.each(function(){var $this=$(this);if($this.data('prev-style'))
$this.attr('style',$this.data('prev-style'));else
$this.removeAttr('style');$this.removeData('prev-style');})})}
$menu.find('.modal').appendTo('body');},show:function(item){if(item.$item.data('mega-tab-item')){item.$item.parent().children().removeClass('open').data('mega-tab-active',false);item.$item.addClass('open').data('mega-tab-active',true);}
if($.inArray(item,this.child_open)<this.child_open.length-1){this.hide_others(item);}
$(document.body).trigger('hideall.t3menu',[this]);clearTimeout(this.timer);clearTimeout(item.timer);clearTimeout(item.ftimer);clearTimeout(item.ctimer);if(item.status!='open'||!item.$item.hasClass('open')||!this.child_open.length){if(item.mega){clearTimeout(item.astimer);clearTimeout(item.atimer);this.position(item.$item);item.astimer=setTimeout(function(){item.$item.addClass('animating')},10);item.atimer=setTimeout(function(){item.$item.removeClass('animating')},this.options.duration+50);item.timer=setTimeout(function(){item.$item.addClass('open');},100);}else{item.$item.addClass('open');}
item.status='open';if(item.child&&$.inArray(item,this.child_open)==-1){this.child_open.push(item);}}
item.ctimer=setTimeout($.proxy(this.clickable,this,item),300);},hide:function(item,$target){clearTimeout(this.timer);clearTimeout(item.timer);clearTimeout(item.astimer);clearTimeout(item.atimer);clearTimeout(item.ftimer);if($target&&$target.is('input',item.$item)){return;}
if(item.mega){item.$item.addClass('animating');item.atimer=setTimeout(function(){item.$item.removeClass('animating')},this.options.duration);item.timer=setTimeout(function(){if(!item.$item.data('mega-tab-active'))
item.$item.removeClass('open')},100);}else{item.timer=setTimeout(function(){if(!item.$item.data('mega-tab-active'))
item.$item.removeClass('open');},100);}
item.status='close';for(var i=this.child_open.length;i--;){if(this.child_open[i]===item){this.child_open.splice(i,1);}}
item.ftimer=setTimeout($.proxy(this.hidden,this,item),this.options.duration);this.timer=setTimeout($.proxy(this.hide_alls,this),this.options.hidedelay);},hidden:function(item){if(item.status=='close'){item.clickable=false;}},hide_others:function(item){var self=this;$.each(this.child_open.slice(),function(idx,open){if(!item||(open!=item&&!open.$item.has(item.$item).length)){self.hide(open);}});},hide_alls:function(e,inst){if(!e||e.type=='tap'||(e.type=='hideall'&&this!=inst)){var self=this;$.each(this.child_open.slice(),function(idx,item){item&&self.hide(item);});}},clickable:function(item){item.clickable=true;},position:function($item){var sub=$item.children('.mega-dropdown-menu'),is_show=sub.is(':visible');if(!is_show){sub.show();}
var offset=$item.offset(),width=$item.outerWidth(),screen_width=$(window).width()
-this.options.sb_width,sub_width=sub.outerWidth(),level=$item.data('level');if(!is_show){sub.css('display','');}
sub.css({left:'',right:''});if(level==1){var align=$item.data('alignsub'),align_offset=0,align_delta=0,align_trans=0;if(align=='justify'){return;}
if(!align){align='left';}
if(align=='center'){align_offset=offset.left+(width/2);if(!$.support.t3transform){align_trans=-sub_width/2;sub.css(this.options.rtl?'right':'left',align_trans+width/2);}}else{align_offset=offset.left
+((align=='left'&&this.options.rtl||align=='right'&&!this.options.rtl)?width:0);}
if(this.options.rtl){if(align=='right'){if(align_offset+sub_width>screen_width){align_delta=screen_width-align_offset
-sub_width;sub.css('left',align_delta);if(screen_width<sub_width){sub.css('left',align_delta+sub_width
-screen_width);}}}else{if(align_offset<(align=='center'?sub_width/2:sub_width)){align_delta=align_offset
-(align=='center'?sub_width/2:sub_width);sub.css('right',align_delta+align_trans);}
if(align_offset
+(align=='center'?sub_width/2:0)
-align_delta>screen_width){sub.css('right',align_offset
+(align=='center'?(sub_width+width)/2:0)+align_trans
-screen_width);}}}else{if(align=='right'){if(align_offset<sub_width){align_delta=align_offset-sub_width;sub.css('right',align_delta);if(sub_width>screen_width){sub.css('right',sub_width-screen_width
+align_delta);}}}else{if(align_offset
+(align=='center'?sub_width/2:sub_width)>screen_width){align_delta=screen_width
-align_offset
-(align=='center'?sub_width/2:sub_width);sub.css('left',align_delta+align_trans);}
if(align_offset
-(align=='center'?sub_width/2:0)
+align_delta<0){sub.css('left',(align=='center'?(sub_width+width)/2:0)
+align_trans
-align_offset);}}}}else{if(this.options.rtl){if($item.closest('.mega-dropdown-menu').parent().hasClass('mega-align-right')){if(offset.left+width+sub_width>screen_width){$item.removeClass('mega-align-right');if(offset.left-sub_width<0){sub.css('right',offset.left+width
-sub_width);}}}else{if(offset.left-sub_width<0){$item.removeClass('mega-align-left').addClass('mega-align-right');if(offset.left+width+sub_width>screen_width){sub.css('left',screen_width-offset.left
-sub_width);}}}}else{if($item.closest('.mega-dropdown-menu').parent().hasClass('mega-align-right')){if(offset.left-sub_width<0){$item.removeClass('mega-align-right');if(offset.left+width+sub_width>screen_width){sub.css('left',screen_width-offset.left
-sub_width);}}}else{if(offset.left+width+sub_width>screen_width){$item.removeClass('mega-align-left').addClass('mega-align-right');if(offset.left-sub_width<0){sub.css('right',offset.left+width
-sub_width);}}}}}}};$.fn.t3menu=function(option){return this.each(function(){var $this=$(this),data=$this.data('megamenu'),options=typeof option=='object'&&option;if($this.parents('#off-canvas-nav').length)
return;if($this.parents('#t3-off-canvas').length)
return;if(!data){$this.data('megamenu',(data=new T3Menu(this,options)));}else{if(typeof option=='string'&&data[option]){data[option]()}}})};$.fn.t3menu.defaults={duration:400,timeout:100,hidedelay:200,hover:true,sb_width:20};$(document).ready(function(){var mm_duration=$('.t3-megamenu').data('duration')||0;if(mm_duration){$('<style type="text/css">'
+'.t3-megamenu.animate .animating > .mega-dropdown-menu,'
+'.t3-megamenu.animate.slide .animating > .mega-dropdown-menu > div {'
+'transition-duration: '
+mm_duration+'ms !important;'
+'-webkit-transition-duration: '
+mm_duration+'ms !important;'
+'}'+'</style>').appendTo('head');}
var mm_timeout=mm_duration?100+mm_duration:500,mm_rtl=$(document.documentElement).attr('dir')=='rtl',mm_trigger=$(document.documentElement).hasClass('mm-hover'),sb_width=(function(){var parent=$('<div style="width:50px;height:50px;overflow:auto"><div/></div>').appendTo('body'),child=parent.children(),width=child.innerWidth()
-child.height(100).innerWidth();parent.remove();return width;})();if(!$.support.transition){$('.t3-megamenu').removeClass('animate');mm_timeout=100;}
$('ul.nav').has('.dropdown-menu').t3menu({duration:mm_duration,timeout:mm_timeout,rtl:mm_rtl,sb_width:sb_width,hover:mm_trigger});$(window).load(function(){$('ul.nav').has('.dropdown-menu').t3menu({duration:mm_duration,timeout:mm_timeout,rtl:mm_rtl,sb_width:sb_width,hover:mm_trigger});});});})(jQuery);


/*===============================
/sampo4/templates/ja_edenite_ii/js/owl-carousel/owl.carousel.js
================================================================================*/;
if(typeof Object.create!=="function"){Object.create=function(obj){function F(){}
F.prototype=obj;return new F();};}
(function($,window,document){var Carousel={init:function(options,el){var base=this;base.$elem=$(el);base.options=$.extend({},$.fn.owlCarousel.options,base.$elem.data(),options);base.userOptions=options;base.loadContent();},loadContent:function(){var base=this,url;function getData(data){var i,content="";if(typeof base.options.jsonSuccess==="function"){base.options.jsonSuccess.apply(this,[data]);}else{for(i in data.owl){if(data.owl.hasOwnProperty(i)){content+=data.owl[i].item;}}
base.$elem.html(content);}
base.logIn();}
if(typeof base.options.beforeInit==="function"){base.options.beforeInit.apply(this,[base.$elem]);}
if(typeof base.options.jsonPath==="string"){url=base.options.jsonPath;$.getJSON(url,getData);}else{base.logIn();}},logIn:function(){var base=this;base.$elem.data("owl-originalStyles",base.$elem.attr("style")).data("owl-originalClasses",base.$elem.attr("class"));base.$elem.css({opacity:0});base.orignalItems=base.options.items;base.checkBrowser();base.wrapperWidth=0;base.checkVisible=null;base.setVars();},setVars:function(){var base=this;if(base.$elem.children().length===0){return false;}
base.baseClass();base.eventTypes();base.$userItems=base.$elem.children();base.itemsAmount=base.$userItems.length;base.wrapItems();base.$owlItems=base.$elem.find(".owl-item");base.$owlWrapper=base.$elem.find(".owl-wrapper");base.playDirection="next";base.prevItem=0;base.prevArr=[0];base.currentItem=0;base.customEvents();base.onStartup();},onStartup:function(){var base=this;base.updateItems();base.calculateAll();base.buildControls();base.updateControls();base.response();base.moveEvents();base.stopOnHover();base.owlStatus();if(base.options.transitionStyle!==false){base.transitionTypes(base.options.transitionStyle);}
if(base.options.autoPlay===true){base.options.autoPlay=5000;}
base.play();base.$elem.find(".owl-wrapper").css("display","block");if(!base.$elem.is(":visible")){base.watchVisibility();}else{base.$elem.css("opacity",1);}
base.onstartup=false;base.eachMoveUpdate();if(typeof base.options.afterInit==="function"){base.options.afterInit.apply(this,[base.$elem]);}},eachMoveUpdate:function(){var base=this;if(base.options.lazyLoad===true){base.lazyLoad();}
if(base.options.autoHeight===true){base.autoHeight();}
base.onVisibleItems();if(typeof base.options.afterAction==="function"){base.options.afterAction.apply(this,[base.$elem]);}},updateVars:function(){var base=this;if(typeof base.options.beforeUpdate==="function"){base.options.beforeUpdate.apply(this,[base.$elem]);}
base.watchVisibility();base.updateItems();base.calculateAll();base.updatePosition();base.updateControls();base.eachMoveUpdate();if(typeof base.options.afterUpdate==="function"){base.options.afterUpdate.apply(this,[base.$elem]);}},reload:function(){var base=this;window.setTimeout(function(){base.updateVars();},0);},watchVisibility:function(){var base=this;if(base.$elem.is(":visible")===false){base.$elem.css({opacity:0});window.clearInterval(base.autoPlayInterval);window.clearInterval(base.checkVisible);}else{return false;}
base.checkVisible=window.setInterval(function(){if(base.$elem.is(":visible")){base.reload();base.$elem.animate({opacity:1},200);window.clearInterval(base.checkVisible);}},500);},wrapItems:function(){var base=this;base.$userItems.wrapAll("<div class=\"owl-wrapper\">").wrap("<div class=\"owl-item\"></div>");base.$elem.find(".owl-wrapper").wrap("<div class=\"owl-wrapper-outer\">");base.wrapperOuter=base.$elem.find(".owl-wrapper-outer");base.$elem.css("display","block");},baseClass:function(){var base=this,hasBaseClass=base.$elem.hasClass(base.options.baseClass),hasThemeClass=base.$elem.hasClass(base.options.theme);if(!hasBaseClass){base.$elem.addClass(base.options.baseClass);}
if(!hasThemeClass){base.$elem.addClass(base.options.theme);}},updateItems:function(){var base=this,width,i;if(base.options.responsive===false){return false;}
if(base.options.singleItem===true){base.options.items=base.orignalItems=1;base.options.itemsCustom=false;base.options.itemsDesktop=false;base.options.itemsDesktopSmall=false;base.options.itemsTablet=false;base.options.itemsTabletSmall=false;base.options.itemsMobile=false;return false;}
width=$(base.options.responsiveBaseWidth).width();if(width>(base.options.itemsDesktop[0]||base.orignalItems)){base.options.items=base.orignalItems;}
if(base.options.itemsCustom!==false){base.options.itemsCustom.sort(function(a,b){return a[0]-b[0];});for(i=0;i<base.options.itemsCustom.length;i+=1){if(base.options.itemsCustom[i][0]<=width){base.options.items=base.options.itemsCustom[i][1];}}}else{if(width<=base.options.itemsDesktop[0]&&base.options.itemsDesktop!==false){base.options.items=base.options.itemsDesktop[1];}
if(width<=base.options.itemsDesktopSmall[0]&&base.options.itemsDesktopSmall!==false){base.options.items=base.options.itemsDesktopSmall[1];}
if(width<=base.options.itemsTablet[0]&&base.options.itemsTablet!==false){base.options.items=base.options.itemsTablet[1];}
if(width<=base.options.itemsTabletSmall[0]&&base.options.itemsTabletSmall!==false){base.options.items=base.options.itemsTabletSmall[1];}
if(width<=base.options.itemsMobile[0]&&base.options.itemsMobile!==false){base.options.items=base.options.itemsMobile[1];}}
if(base.options.items>base.itemsAmount&&base.options.itemsScaleUp===true){base.options.items=base.itemsAmount;}},response:function(){var base=this,smallDelay,lastWindowWidth;if(base.options.responsive!==true){return false;}
lastWindowWidth=$(window).width();base.resizer=function(){if($(window).width()!==lastWindowWidth){if(base.options.autoPlay!==false){window.clearInterval(base.autoPlayInterval);}
window.clearTimeout(smallDelay);smallDelay=window.setTimeout(function(){lastWindowWidth=$(window).width();base.updateVars();},base.options.responsiveRefreshRate);}};$(window).resize(base.resizer);},updatePosition:function(){var base=this;base.jumpTo(base.currentItem);if(base.options.autoPlay!==false){base.checkAp();}},appendItemsSizes:function(){var base=this,roundPages=0,lastItem=base.itemsAmount-base.options.items;base.$owlItems.each(function(index){var $this=$(this);$this.css({"width":base.itemWidth}).data("owl-item",Number(index));if(index%base.options.items===0||index===lastItem){if(!(index>lastItem)){roundPages+=1;}}
$this.data("owl-roundPages",roundPages);});},appendWrapperSizes:function(){var base=this,width=base.$owlItems.length*base.itemWidth;base.$owlWrapper.css({"width":width*2,"left":0});base.appendItemsSizes();},calculateAll:function(){var base=this;base.calculateWidth();base.appendWrapperSizes();base.loops();base.max();},calculateWidth:function(){var base=this;base.itemWidth=Math.round(base.$elem.width()/base.options.items);},max:function(){var base=this,maximum=((base.itemsAmount*base.itemWidth)-base.options.items*base.itemWidth)*-1;if(base.options.items>base.itemsAmount){base.maximumItem=0;maximum=0;base.maximumPixels=0;}else{base.maximumItem=base.itemsAmount-base.options.items;base.maximumPixels=maximum;}
return maximum;},min:function(){return 0;},loops:function(){var base=this,prev=0,elWidth=0,i,item,roundPageNum;base.positionsInArray=[0];base.pagesInArray=[];for(i=0;i<base.itemsAmount;i+=1){elWidth+=base.itemWidth;base.positionsInArray.push(-elWidth);if(base.options.scrollPerPage===true){item=$(base.$owlItems[i]);roundPageNum=item.data("owl-roundPages");if(roundPageNum!==prev){base.pagesInArray[prev]=base.positionsInArray[i];prev=roundPageNum;}}}},buildControls:function(){var base=this;if(base.options.navigation===true||base.options.pagination===true){base.owlControls=$("<div class=\"owl-controls\"/>").toggleClass("clickable",!base.browser.isTouch).appendTo(base.$elem);}
if(base.options.pagination===true){base.buildPagination();}
if(base.options.navigation===true){base.buildButtons();}},buildButtons:function(){var base=this,buttonsWrapper=$("<div class=\"owl-buttons\"/>");base.owlControls.append(buttonsWrapper);base.buttonPrev=$("<div/>",{"class":"owl-prev","html":base.options.navigationText[0]||""});base.buttonNext=$("<div/>",{"class":"owl-next","html":base.options.navigationText[1]||""});buttonsWrapper.append(base.buttonPrev).append(base.buttonNext);buttonsWrapper.on("touchstart.owlControls mousedown.owlControls","div[class^=\"owl\"]",function(event){event.preventDefault();});buttonsWrapper.on("touchend.owlControls mouseup.owlControls","div[class^=\"owl\"]",function(event){event.preventDefault();if($(this).hasClass("owl-next")){base.next();}else{base.prev();}});},buildPagination:function(){var base=this;base.paginationWrapper=$("<div class=\"owl-pagination\"/>");base.owlControls.append(base.paginationWrapper);base.paginationWrapper.on("touchend.owlControls mouseup.owlControls",".owl-page",function(event){event.preventDefault();if(Number($(this).data("owl-page"))!==base.currentItem){base.goTo(Number($(this).data("owl-page")),true);}});},updatePagination:function(){var base=this,counter,lastPage,lastItem,i,paginationButton,paginationButtonInner;if(base.options.pagination===false){return false;}
base.paginationWrapper.html("");counter=0;lastPage=base.itemsAmount-base.itemsAmount%base.options.items;for(i=0;i<base.itemsAmount;i+=1){if(i%base.options.items===0){counter+=1;if(lastPage===i){lastItem=base.itemsAmount-base.options.items;}
paginationButton=$("<div/>",{"class":"owl-page"});paginationButtonInner=$("<span></span>",{"text":base.options.paginationNumbers===true?counter:"","class":base.options.paginationNumbers===true?"owl-numbers":""});paginationButton.append(paginationButtonInner);paginationButton.data("owl-page",lastPage===i?lastItem:i);paginationButton.data("owl-roundPages",counter);base.paginationWrapper.append(paginationButton);}}
base.checkPagination();},checkPagination:function(){var base=this;if(base.options.pagination===false){return false;}
base.paginationWrapper.find(".owl-page").each(function(){if($(this).data("owl-roundPages")===$(base.$owlItems[base.currentItem]).data("owl-roundPages")){base.paginationWrapper.find(".owl-page").removeClass("active");$(this).addClass("active");}});},checkNavigation:function(){var base=this;if(base.options.navigation===false){return false;}
if(base.options.rewindNav===false){if(base.currentItem===0&&base.maximumItem===0){base.buttonPrev.addClass("disabled");base.buttonNext.addClass("disabled");}else if(base.currentItem===0&&base.maximumItem!==0){base.buttonPrev.addClass("disabled");base.buttonNext.removeClass("disabled");}else if(base.currentItem===base.maximumItem){base.buttonPrev.removeClass("disabled");base.buttonNext.addClass("disabled");}else if(base.currentItem!==0&&base.currentItem!==base.maximumItem){base.buttonPrev.removeClass("disabled");base.buttonNext.removeClass("disabled");}}},updateControls:function(){var base=this;base.updatePagination();base.checkNavigation();if(base.owlControls){if(base.options.items>=base.itemsAmount){base.owlControls.hide();}else{base.owlControls.show();}}},destroyControls:function(){var base=this;if(base.owlControls){base.owlControls.remove();}},next:function(speed){var base=this;if(base.isTransition){return false;}
base.currentItem+=base.options.scrollPerPage===true?base.options.items:1;if(base.currentItem>base.maximumItem+(base.options.scrollPerPage===true?(base.options.items-1):0)){if(base.options.rewindNav===true){base.currentItem=0;speed="rewind";}else{base.currentItem=base.maximumItem;return false;}}
base.goTo(base.currentItem,speed);},prev:function(speed){var base=this;if(base.isTransition){return false;}
if(base.options.scrollPerPage===true&&base.currentItem>0&&base.currentItem<base.options.items){base.currentItem=0;}else{base.currentItem-=base.options.scrollPerPage===true?base.options.items:1;}
if(base.currentItem<0){if(base.options.rewindNav===true){base.currentItem=base.maximumItem;speed="rewind";}else{base.currentItem=0;return false;}}
base.goTo(base.currentItem,speed);},goTo:function(position,speed,drag){var base=this,goToPixel;if(base.isTransition){return false;}
if(typeof base.options.beforeMove==="function"){base.options.beforeMove.apply(this,[base.$elem]);}
if(position>=base.maximumItem){position=base.maximumItem;}else if(position<=0){position=0;}
base.currentItem=base.owl.currentItem=position;if(base.options.transitionStyle!==false&&drag!=="drag"&&base.options.items===1&&base.browser.support3d===true){base.swapSpeed(0);if(base.browser.support3d===true){base.transition3d(base.positionsInArray[position]);}else{base.css2slide(base.positionsInArray[position],1);}
base.afterGo();base.singleItemTransition();return false;}
goToPixel=base.positionsInArray[position];if(base.browser.support3d===true){base.isCss3Finish=false;if(speed===true){base.swapSpeed("paginationSpeed");window.setTimeout(function(){base.isCss3Finish=true;},base.options.paginationSpeed);}else if(speed==="rewind"){base.swapSpeed(base.options.rewindSpeed);window.setTimeout(function(){base.isCss3Finish=true;},base.options.rewindSpeed);}else{base.swapSpeed("slideSpeed");window.setTimeout(function(){base.isCss3Finish=true;},base.options.slideSpeed);}
base.transition3d(goToPixel);}else{if(speed===true){base.css2slide(goToPixel,base.options.paginationSpeed);}else if(speed==="rewind"){base.css2slide(goToPixel,base.options.rewindSpeed);}else{base.css2slide(goToPixel,base.options.slideSpeed);}}
base.afterGo();},jumpTo:function(position){var base=this;if(typeof base.options.beforeMove==="function"){base.options.beforeMove.apply(this,[base.$elem]);}
if(position>=base.maximumItem||position===-1){position=base.maximumItem;}else if(position<=0){position=0;}
base.swapSpeed(0);if(base.browser.support3d===true){base.transition3d(base.positionsInArray[position]);}else{base.css2slide(base.positionsInArray[position],1);}
base.currentItem=base.owl.currentItem=position;base.afterGo();},afterGo:function(){var base=this;base.prevArr.push(base.currentItem);base.prevItem=base.owl.prevItem=base.prevArr[base.prevArr.length-2];base.prevArr.shift(0);if(base.prevItem!==base.currentItem){base.checkPagination();base.checkNavigation();base.eachMoveUpdate();if(base.options.autoPlay!==false){base.checkAp();}}
if(typeof base.options.afterMove==="function"&&base.prevItem!==base.currentItem){base.options.afterMove.apply(this,[base.$elem]);}},stop:function(){var base=this;base.apStatus="stop";window.clearInterval(base.autoPlayInterval);},checkAp:function(){var base=this;if(base.apStatus!=="stop"){base.play();}},play:function(){var base=this;base.apStatus="play";if(base.options.autoPlay===false){return false;}
window.clearInterval(base.autoPlayInterval);base.autoPlayInterval=window.setInterval(function(){base.next(true);},base.options.autoPlay);},swapSpeed:function(action){var base=this;if(action==="slideSpeed"){base.$owlWrapper.css(base.addCssSpeed(base.options.slideSpeed));}else if(action==="paginationSpeed"){base.$owlWrapper.css(base.addCssSpeed(base.options.paginationSpeed));}else if(typeof action!=="string"){base.$owlWrapper.css(base.addCssSpeed(action));}},addCssSpeed:function(speed){return{"-webkit-transition":"all "+speed+"ms ease","-moz-transition":"all "+speed+"ms ease","-o-transition":"all "+speed+"ms ease","transition":"all "+speed+"ms ease"};},removeTransition:function(){return{"-webkit-transition":"","-moz-transition":"","-o-transition":"","transition":""};},doTranslate:function(pixels){return{"-webkit-transform":"translate3d("+pixels+"px, 0px, 0px)","-moz-transform":"translate3d("+pixels+"px, 0px, 0px)","-o-transform":"translate3d("+pixels+"px, 0px, 0px)","-ms-transform":"translate3d("+pixels+"px, 0px, 0px)","transform":"translate3d("+pixels+"px, 0px,0px)"};},transition3d:function(value){var base=this;base.$owlWrapper.css(base.doTranslate(value));},css2move:function(value){var base=this;base.$owlWrapper.css({"left":value});},css2slide:function(value,speed){var base=this;base.isCssFinish=false;base.$owlWrapper.stop(true,true).animate({"left":value},{duration:speed||base.options.slideSpeed,complete:function(){base.isCssFinish=true;}});},checkBrowser:function(){var base=this,translate3D="translate3d(0px, 0px, 0px)",tempElem=document.createElement("div"),regex,asSupport,support3d,isTouch;tempElem.style.cssText="  -moz-transform:"+translate3D+"; -ms-transform:"+translate3D+"; -o-transform:"+translate3D+"; -webkit-transform:"+translate3D+"; transform:"+translate3D;regex=/translate3d\(0px, 0px, 0px\)/g;asSupport=tempElem.style.cssText.match(regex);support3d=(asSupport!==null&&asSupport.length===1);isTouch="ontouchstart"in window||window.navigator.msMaxTouchPoints;base.browser={"support3d":support3d,"isTouch":isTouch};},moveEvents:function(){var base=this;if(base.options.mouseDrag!==false||base.options.touchDrag!==false){base.gestures();base.disabledEvents();}},eventTypes:function(){var base=this,types=["s","e","x"];base.ev_types={};if(base.options.mouseDrag===true&&base.options.touchDrag===true){types=["touchstart.owl mousedown.owl","touchmove.owl mousemove.owl","touchend.owl touchcancel.owl mouseup.owl"];}else if(base.options.mouseDrag===false&&base.options.touchDrag===true){types=["touchstart.owl","touchmove.owl","touchend.owl touchcancel.owl"];}else if(base.options.mouseDrag===true&&base.options.touchDrag===false){types=["mousedown.owl","mousemove.owl","mouseup.owl"];}
base.ev_types.start=types[0];base.ev_types.move=types[1];base.ev_types.end=types[2];},disabledEvents:function(){var base=this;base.$elem.on("dragstart.owl",function(event){event.preventDefault();});base.$elem.on("mousedown.disableTextSelect",function(e){return $(e.target).is('input, textarea, select, option');});},gestures:function(){var base=this,locals={offsetX:0,offsetY:0,baseElWidth:0,relativePos:0,position:null,minSwipe:null,maxSwipe:null,sliding:null,dargging:null,targetElement:null};base.isCssFinish=true;function getTouches(event){if(event.touches!==undefined){return{x:event.touches[0].pageX,y:event.touches[0].pageY};}
if(event.touches===undefined){if(event.pageX!==undefined){return{x:event.pageX,y:event.pageY};}
if(event.pageX===undefined){return{x:event.clientX,y:event.clientY};}}}
function swapEvents(type){if(type==="on"){$(document).on(base.ev_types.move,dragMove);$(document).on(base.ev_types.end,dragEnd);}else if(type==="off"){$(document).off(base.ev_types.move);$(document).off(base.ev_types.end);}}
function dragStart(event){var ev=event.originalEvent||event||window.event,position;if(ev.which===3){return false;}
if(base.itemsAmount<=base.options.items){return;}
if(base.isCssFinish===false&&!base.options.dragBeforeAnimFinish){return false;}
if(base.isCss3Finish===false&&!base.options.dragBeforeAnimFinish){return false;}
if(base.options.autoPlay!==false){window.clearInterval(base.autoPlayInterval);}
if(base.browser.isTouch!==true&&!base.$owlWrapper.hasClass("grabbing")){base.$owlWrapper.addClass("grabbing");}
base.newPosX=0;base.newRelativeX=0;$(this).css(base.removeTransition());position=$(this).position();locals.relativePos=position.left;locals.offsetX=getTouches(ev).x-position.left;locals.offsetY=getTouches(ev).y-position.top;swapEvents("on");locals.sliding=false;locals.targetElement=ev.target||ev.srcElement;}
function dragMove(event){var ev=event.originalEvent||event||window.event,minSwipe,maxSwipe;base.newPosX=getTouches(ev).x-locals.offsetX;base.newPosY=getTouches(ev).y-locals.offsetY;base.newRelativeX=base.newPosX-locals.relativePos;if(typeof base.options.startDragging==="function"&&locals.dragging!==true&&base.newRelativeX!==0){locals.dragging=true;base.options.startDragging.apply(base,[base.$elem]);}
if((base.newRelativeX>8||base.newRelativeX<-8)&&(base.browser.isTouch===true)){if(ev.preventDefault!==undefined){ev.preventDefault();}else{ev.returnValue=false;}
locals.sliding=true;}
if((base.newPosY>10||base.newPosY<-10)&&locals.sliding===false){$(document).off("touchmove.owl");}
minSwipe=function(){return base.newRelativeX/5;};maxSwipe=function(){return base.maximumPixels+base.newRelativeX/5;};base.newPosX=Math.max(Math.min(base.newPosX,minSwipe()),maxSwipe());if(base.browser.support3d===true){base.transition3d(base.newPosX);}else{base.css2move(base.newPosX);}}
function dragEnd(event){var ev=event.originalEvent||event||window.event,newPosition,handlers,owlStopEvent;ev.target=ev.target||ev.srcElement;locals.dragging=false;if(base.browser.isTouch!==true){base.$owlWrapper.removeClass("grabbing");}
if(base.newRelativeX<0){base.dragDirection=base.owl.dragDirection="left";}else{base.dragDirection=base.owl.dragDirection="right";}
if(base.newRelativeX!==0){newPosition=base.getNewPosition();base.goTo(newPosition,false,"drag");if(locals.targetElement===ev.target&&base.browser.isTouch!==true){$(ev.target).on("click.disable",function(ev){ev.stopImmediatePropagation();ev.stopPropagation();ev.preventDefault();$(ev.target).off("click.disable");});handlers=$._data(ev.target,"events").click;owlStopEvent=handlers.pop();handlers.splice(0,0,owlStopEvent);}}
swapEvents("off");}
base.$elem.on(base.ev_types.start,".owl-wrapper",dragStart);},getNewPosition:function(){var base=this,newPosition=base.closestItem();if(newPosition>base.maximumItem){base.currentItem=base.maximumItem;newPosition=base.maximumItem;}else if(base.newPosX>=0){newPosition=0;base.currentItem=0;}
return newPosition;},closestItem:function(){var base=this,array=base.options.scrollPerPage===true?base.pagesInArray:base.positionsInArray,goal=base.newPosX,closest=null;$.each(array,function(i,v){if(goal-(base.itemWidth/20)>array[i+1]&&goal-(base.itemWidth/20)<v&&base.moveDirection()==="left"){closest=v;if(base.options.scrollPerPage===true){base.currentItem=$.inArray(closest,base.positionsInArray);}else{base.currentItem=i;}}else if(goal+(base.itemWidth/20)<v&&goal+(base.itemWidth/20)>(array[i+1]||array[i]-base.itemWidth)&&base.moveDirection()==="right"){if(base.options.scrollPerPage===true){closest=array[i+1]||array[array.length-1];base.currentItem=$.inArray(closest,base.positionsInArray);}else{closest=array[i+1];base.currentItem=i+1;}}});return base.currentItem;},moveDirection:function(){var base=this,direction;if(base.newRelativeX<0){direction="right";base.playDirection="next";}else{direction="left";base.playDirection="prev";}
return direction;},customEvents:function(){var base=this;base.$elem.on("owl.next",function(){base.next();});base.$elem.on("owl.prev",function(){base.prev();});base.$elem.on("owl.play",function(event,speed){base.options.autoPlay=speed;base.play();base.hoverStatus="play";});base.$elem.on("owl.stop",function(){base.stop();base.hoverStatus="stop";});base.$elem.on("owl.goTo",function(event,item){base.goTo(item);});base.$elem.on("owl.jumpTo",function(event,item){base.jumpTo(item);});},stopOnHover:function(){var base=this;if(base.options.stopOnHover===true&&base.browser.isTouch!==true&&base.options.autoPlay!==false){base.$elem.on("mouseover",function(){base.stop();});base.$elem.on("mouseout",function(){if(base.hoverStatus!=="stop"){base.play();}});}},lazyLoad:function(){var base=this,i,$item,itemNumber,$lazyImg,follow;if(base.options.lazyLoad===false){return false;}
for(i=0;i<base.itemsAmount;i+=1){$item=$(base.$owlItems[i]);if($item.data("owl-loaded")==="loaded"){continue;}
itemNumber=$item.data("owl-item");$lazyImg=$item.find(".lazyOwl");if(typeof $lazyImg.data("src")!=="string"){$item.data("owl-loaded","loaded");continue;}
if($item.data("owl-loaded")===undefined){$lazyImg.hide();$item.addClass("loading").data("owl-loaded","checked");}
if(base.options.lazyFollow===true){follow=itemNumber>=base.currentItem;}else{follow=true;}
if(follow&&itemNumber<base.currentItem+base.options.items&&$lazyImg.length){base.lazyPreload($item,$lazyImg);}}},lazyPreload:function($item,$lazyImg){var base=this,iterations=0,isBackgroundImg;if($lazyImg.prop("tagName")==="DIV"){$lazyImg.css("background-image","url("+$lazyImg.data("src")+")");isBackgroundImg=true;}else{$lazyImg[0].src=$lazyImg.data("src");}
function showImage(){$item.data("owl-loaded","loaded").removeClass("loading");$lazyImg.removeAttr("data-src");if(base.options.lazyEffect==="fade"){$lazyImg.fadeIn(400);}else{$lazyImg.show();}
if(typeof base.options.afterLazyLoad==="function"){base.options.afterLazyLoad.apply(this,[base.$elem]);}}
function checkLazyImage(){iterations+=1;if(base.completeImg($lazyImg.get(0))||isBackgroundImg===true){showImage();}else if(iterations<=100){window.setTimeout(checkLazyImage,100);}else{showImage();}}
checkLazyImage();},autoHeight:function(){var base=this,$currentimg=$(base.$owlItems[base.currentItem]).find("img"),iterations;function addHeight(){var $currentItem=$(base.$owlItems[base.currentItem]).height();base.wrapperOuter.css("height",$currentItem+"px");if(!base.wrapperOuter.hasClass("autoHeight")){window.setTimeout(function(){base.wrapperOuter.addClass("autoHeight");},0);}}
function checkImage(){iterations+=1;if(base.completeImg($currentimg.get(0))){addHeight();}else if(iterations<=100){window.setTimeout(checkImage,100);}else{base.wrapperOuter.css("height","");}}
if($currentimg.get(0)!==undefined){iterations=0;checkImage();}else{addHeight();}},completeImg:function(img){var naturalWidthType;if(!img.complete){return false;}
naturalWidthType=typeof img.naturalWidth;if(naturalWidthType!=="undefined"&&img.naturalWidth===0){return false;}
return true;},onVisibleItems:function(){var base=this,i;if(base.options.addClassActive===true){base.$owlItems.removeClass("active");}
base.visibleItems=[];for(i=base.currentItem;i<base.currentItem+base.options.items;i+=1){base.visibleItems.push(i);if(base.options.addClassActive===true){$(base.$owlItems[i]).addClass("active");}}
base.owl.visibleItems=base.visibleItems;},transitionTypes:function(className){var base=this;base.outClass="owl-"+className+"-out";base.inClass="owl-"+className+"-in";},singleItemTransition:function(){var base=this,outClass=base.outClass,inClass=base.inClass,$currentItem=base.$owlItems.eq(base.currentItem),$prevItem=base.$owlItems.eq(base.prevItem),prevPos=Math.abs(base.positionsInArray[base.currentItem])+base.positionsInArray[base.prevItem],origin=Math.abs(base.positionsInArray[base.currentItem])+base.itemWidth/2,animEnd='webkitAnimationEnd oAnimationEnd MSAnimationEnd animationend';base.isTransition=true;base.$owlWrapper.addClass('owl-origin').css({"-webkit-transform-origin":origin+"px","-moz-perspective-origin":origin+"px","perspective-origin":origin+"px"});function transStyles(prevPos){return{"position":"relative","left":prevPos+"px"};}
$prevItem.css(transStyles(prevPos,10)).addClass(outClass).on(animEnd,function(){base.endPrev=true;$prevItem.off(animEnd);base.clearTransStyle($prevItem,outClass);});$currentItem.addClass(inClass).on(animEnd,function(){base.endCurrent=true;$currentItem.off(animEnd);base.clearTransStyle($currentItem,inClass);});},clearTransStyle:function(item,classToRemove){var base=this;item.css({"position":"","left":""}).removeClass(classToRemove);if(base.endPrev&&base.endCurrent){base.$owlWrapper.removeClass('owl-origin');base.endPrev=false;base.endCurrent=false;base.isTransition=false;}},owlStatus:function(){var base=this;base.owl={"userOptions":base.userOptions,"baseElement":base.$elem,"userItems":base.$userItems,"owlItems":base.$owlItems,"currentItem":base.currentItem,"prevItem":base.prevItem,"visibleItems":base.visibleItems,"isTouch":base.browser.isTouch,"browser":base.browser,"dragDirection":base.dragDirection};},clearEvents:function(){var base=this;base.$elem.off(".owl owl mousedown.disableTextSelect");$(document).off(".owl owl");$(window).off("resize",base.resizer);},unWrap:function(){var base=this;if(base.$elem.children().length!==0){base.$owlWrapper.unwrap();base.$userItems.unwrap().unwrap();if(base.owlControls){base.owlControls.remove();}}
base.clearEvents();base.$elem.attr("style",base.$elem.data("owl-originalStyles")||"").attr("class",base.$elem.data("owl-originalClasses"));},destroy:function(){var base=this;base.stop();window.clearInterval(base.checkVisible);base.unWrap();base.$elem.removeData();},reinit:function(newOptions){var base=this,options=$.extend({},base.userOptions,newOptions);base.unWrap();base.init(options,base.$elem);},addItem:function(htmlString,targetPosition){var base=this,position;if(!htmlString){return false;}
if(base.$elem.children().length===0){base.$elem.append(htmlString);base.setVars();return false;}
base.unWrap();if(targetPosition===undefined||targetPosition===-1){position=-1;}else{position=targetPosition;}
if(position>=base.$userItems.length||position===-1){base.$userItems.eq(-1).after(htmlString);}else{base.$userItems.eq(position).before(htmlString);}
base.setVars();},removeItem:function(targetPosition){var base=this,position;if(base.$elem.children().length===0){return false;}
if(targetPosition===undefined||targetPosition===-1){position=-1;}else{position=targetPosition;}
base.unWrap();base.$userItems.eq(position).remove();base.setVars();}};$.fn.owlCarousel=function(options){return this.each(function(){if($(this).data("owl-init")===true){return false;}
$(this).data("owl-init",true);var carousel=Object.create(Carousel);carousel.init(options,this);$.data(this,"owlCarousel",carousel);});};$.fn.owlCarousel.options={items:5,itemsCustom:false,itemsDesktop:[1199,4],itemsDesktopSmall:[979,3],itemsTablet:[768,2],itemsTabletSmall:false,itemsMobile:[479,1],singleItem:false,itemsScaleUp:false,slideSpeed:200,paginationSpeed:800,rewindSpeed:1000,autoPlay:false,stopOnHover:false,navigation:false,navigationText:["prev","next"],rewindNav:true,scrollPerPage:false,pagination:true,paginationNumbers:false,responsive:true,responsiveRefreshRate:200,responsiveBaseWidth:window,baseClass:"owl-carousel",theme:"owl-theme",lazyLoad:false,lazyFollow:true,lazyEffect:"fade",autoHeight:false,jsonPath:false,jsonSuccess:false,dragBeforeAnimFinish:true,mouseDrag:true,touchDrag:true,addClassActive:false,transitionStyle:false,beforeUpdate:false,afterUpdate:false,beforeInit:false,afterInit:false,beforeMove:false,afterMove:false,afterAction:false,startDragging:false,afterLazyLoad:false};}(jQuery,window,document));


/*===============================
/sampo4/templates/ja_edenite_ii/js/script.js
================================================================================*/;



/*===============================
/sampo4/plugins/system/t3/base-bs3/js/nav-collapse.js
================================================================================*/;
jQuery(document).ready(function($){$('.t3-navbar').each(function(){var $navwrapper=$(this),$menu=null,$placeholder=null;if($navwrapper.find('.t3-megamenu').length){$menu=$navwrapper.find('ul.level0').clone(),$placeholder=$navwrapper.prev('.navbar-collapse');if(!$placeholder.length){$placeholder=$navwrapper.closest('.container, .t3-mainnav').find('.navbar-collapse:empty');}
var lis=$menu.find('li[data-id]'),liactive=lis.filter('.current');lis.removeClass('mega dropdown mega-align-left mega-align-right mega-align-center mega-align-adjust');lis.each(function(){var $li=$(this),$child=$li.find('>:first-child');if($child[0].nodeName=='DIV'){$child.find('>:first-child').prependTo($li);$child.remove();}
if($li.data('hidewcol')){$child.find('.caret').remove();$child.nextAll().remove();return;}
var subul=$li.find('ul.level'+$li.data('level'));if(subul.length){$ul=$('<ul class="level'+$li.data('level')+' dropdown-menu">');subul.each(function(){if($(this).parents('.mega-col-nav').data('hidewcol'))return;$(this).find('>li').appendTo($ul);});if($ul.children().length){$ul.appendTo($li);}}
$li.find('>div').remove();if(!$li.children('ul').length){$child.find('.caret').remove();}
var divider=$li.hasClass('divider');for(var x in $li.data()){$li.removeAttr('data-'+x)}
$child.removeAttr('class');for(var x in $child.data()){$child.removeAttr('data-'+x)}
if(divider){$li.addClass('divider');}});liactive.addClass('current active');}else{$menu=$navwrapper.find('ul.nav').clone();$placeholder=$('.t3-navbar-collapse:empty, .navbar-collapse:empty').eq(0);}
$menu.find('a[data-toggle="dropdown"]').removeAttr('data-toggle').removeAttr('data-target');$menu.find('> li > ul.dropdown-menu').prev('a').attr('data-toggle','dropdown').attr('data-target','#').parent('li').addClass(function(){return'dropdown'+($(this).data('level')>1?' dropdown-submenu':'');});$menu.appendTo($placeholder);});});


/*===============================
/sampo4/media/system/js/polyfill.event.js
================================================================================*/;
(function(e){"Window"in this||!function(e){e.constructor?e.Window=e.constructor:(e.Window=e.constructor=new Function("return function Window() {}")()).prototype=this}(this),"Document"in this||(this.HTMLDocument?this.Document=this.HTMLDocument:(this.Document=this.HTMLDocument=document.constructor=new Function("return function Document() {}")(),this.Document.prototype=document)),"Element"in this&&"HTMLElement"in this||!function(){function e(){return s--||clearTimeout(t),document.body&&!document.body.prototype&&/(complete|interactive)/.test(document.readyState)?(a(document,!0),t&&document.body.prototype&&clearTimeout(t),!!document.body.prototype):!1}if(window.Element&&!window.HTMLElement)return void(window.HTMLElement=window.Element);window.Element=window.HTMLElement=new Function("return function Element() {}")();var t,n=document.appendChild(document.createElement("body")),o=n.appendChild(document.createElement("iframe")),r=o.contentWindow.document,i=Element.prototype=r.appendChild(r.createElement("*")),c={},a=function(e,t){var n,o,r,i=e.childNodes||[],u=-1;if(1===e.nodeType&&e.constructor!==Element){e.constructor=Element;for(n in c)o=c[n],e[n]=o}for(;r=t&&i[++u];)a(r,t);return e},u=document.getElementsByTagName("*"),l=document.createElement,s=100;i.attachEvent("onpropertychange",function(e){for(var t,n=e.propertyName,o=!c.hasOwnProperty(n),r=i[n],a=c[n],l=-1;t=u[++l];)1===t.nodeType&&(o||t[n]===a)&&(t[n]=r);c[n]=r}),i.constructor=Element,i.hasAttribute||(i.hasAttribute=function(e){return null!==this.getAttribute(e)}),e(!0)||(document.onreadystatechange=e,t=setInterval(e,25)),document.createElement=function(e){var t=l(String(e).toLowerCase());return a(t)},document.removeChild(n)}(),"defineProperty"in Object&&function(){try{var e={};return Object.defineProperty(e,"test",{value:42}),!0}catch(t){return!1}}()||!function(e){var t=Object.prototype.hasOwnProperty("__defineGetter__"),n="Getters & setters cannot be defined on this javascript engine",o="A property cannot both have accessors and be writable or have a value";Object.defineProperty=function(r,i,c){if(e&&(r===window||r===document||r===Element.prototype||r instanceof Element))return e(r,i,c);if(null===r||!(r instanceof Object||"object"==typeof r))throw new TypeError("Object must be an object (Object.defineProperty polyfill)");if(!(c instanceof Object))throw new TypeError("Descriptor must be an object (Object.defineProperty polyfill)");var a=String(i),u="value"in c||"writable"in c,l="get"in c&&typeof c.get,s="set"in c&&typeof c.set;if(l){if("function"!==l)throw new TypeError("Getter expected a function (Object.defineProperty polyfill)");if(!t)throw new TypeError(n);if(u)throw new TypeError(o);r.__defineGetter__(a,c.get)}else r[a]=c.value;if(s){if("function"!==s)throw new TypeError("Setter expected a function (Object.defineProperty polyfill)");if(!t)throw new TypeError(n);if(u)throw new TypeError(o);r.__defineSetter__(a,c.set)}return"value"in c&&(r[a]=c.value),r}}(Object.defineProperty),function(e){if(!("Event"in e))return!1;if("function"==typeof e.Event)return!0;try{return new Event("click"),!0}catch(t){return!1}}(this)||!function(){function t(e,t){for(var n=-1,o=e.length;++n<o;)if(n in e&&e[n]===t)return n;return-1}var n={click:1,dblclick:1,keyup:1,keypress:1,keydown:1,mousedown:1,mouseup:1,mousemove:1,mouseover:1,mouseenter:1,mouseleave:1,mouseout:1,storage:1,storagecommit:1,textinput:1},o=window.Event&&window.Event.prototype||null;window.Event=Window.prototype.Event=function(t,n){if(!t)throw new Error("Not enough arguments");if("createEvent"in document){var o=document.createEvent("Event"),r=n&&n.bubbles!==e?n.bubbles:!1,i=n&&n.cancelable!==e?n.cancelable:!1;return o.initEvent(t,r,i),o}var o=document.createEventObject();return o.type=t,o.bubbles=n&&n.bubbles!==e?n.bubbles:!1,o.cancelable=n&&n.cancelable!==e?n.cancelable:!1,o},o&&Object.defineProperty(window.Event,"prototype",{configurable:!1,enumerable:!1,writable:!0,value:o}),"createEvent"in document||(window.addEventListener=Window.prototype.addEventListener=Document.prototype.addEventListener=Element.prototype.addEventListener=function(){var e=this,o=arguments[0],r=arguments[1];if(e===window&&o in n)throw new Error("In IE8 the event: "+o+" is not available on the window object. Please see https://github.com/Financial-Times/polyfill-service/issues/317 for more information.");e._events||(e._events={}),e._events[o]||(e._events[o]=function(n){var o,r=e._events[n.type].list,i=r.slice(),c=-1,a=i.length;for(n.preventDefault=function(){n.cancelable!==!1&&(n.returnValue=!1)},n.stopPropagation=function(){n.cancelBubble=!0},n.stopImmediatePropagation=function(){n.cancelBubble=!0,n.cancelImmediate=!0},n.currentTarget=e,n.relatedTarget=n.fromElement||null,n.target=n.target||n.srcElement||e,n.timeStamp=(new Date).getTime(),n.clientX&&(n.pageX=n.clientX+document.documentElement.scrollLeft,n.pageY=n.clientY+document.documentElement.scrollTop);++c<a&&!n.cancelImmediate;)c in i&&(o=i[c],-1!==t(r,o)&&"function"==typeof o&&o.call(e,n))},e._events[o].list=[],e.attachEvent&&e.attachEvent("on"+o,e._events[o])),e._events[o].list.push(r)},window.removeEventListener=Window.prototype.removeEventListener=Document.prototype.removeEventListener=Element.prototype.removeEventListener=function(){var e,n=this,o=arguments[0],r=arguments[1];n._events&&n._events[o]&&n._events[o].list&&(e=t(n._events[o].list,r),-1!==e&&(n._events[o].list.splice(e,1),n._events[o].list.length||(n.detachEvent&&n.detachEvent("on"+o,n._events[o]),delete n._events[o])))},window.dispatchEvent=Window.prototype.dispatchEvent=Document.prototype.dispatchEvent=Element.prototype.dispatchEvent=function(e){if(!arguments.length)throw new Error("Not enough arguments");if(!e||"string"!=typeof e.type)throw new Error("DOM Events Exception 0");var t=this,n=e.type;try{if(!e.bubbles){e.cancelBubble=!0;var o=function(e){e.cancelBubble=!0,(t||window).detachEvent("on"+n,o)};this.attachEvent("on"+n,o)}this.fireEvent("on"+n,e)}catch(r){e.target=t;do e.currentTarget=t,"_events"in t&&"function"==typeof t._events[n]&&t._events[n].call(t,e),"function"==typeof t["on"+n]&&t["on"+n].call(t,e),t=9===t.nodeType?t.parentWindow:t.parentNode;while(t&&!e.cancelBubble)}return!0},document.attachEvent("onreadystatechange",function(){"complete"===document.readyState&&document.dispatchEvent(new Event("DOMContentLoaded",{bubbles:!0}))}))}()}).call("object"==typeof window&&window||"object"==typeof self&&self||"object"==typeof global&&global||{});


/*===============================
/sampo4/media/system/js/keepalive.js
================================================================================*/;
!function(){"use strict";document.addEventListener("DOMContentLoaded",function(){var o=Joomla.getOptions("system.keepalive"),n=o&&o.uri?o.uri.replace(/&amp;/g,"&"):"",t=o&&o.interval?o.interval:45e3;if(""===n){var e=Joomla.getOptions("system.paths");n=(e?e.root+"/index.php":window.location.pathname)+"?option=com_ajax&format=json"}window.setInterval(function(){Joomla.request({url:n,onSuccess:function(){},onError:function(){}})},t)})}(window,document,Joomla);


/*===============================
/sampo4/media/system/js/calendar.js
================================================================================*/;
Calendar=function(d,c,f,a){this.activeDiv=null;this.currentDateEl=null;this.getDateStatus=null;this.getDateToolTip=null;this.getDateText=null;this.timeout=null;this.onSelected=f||null;this.onClose=a||null;this.dragging=false;this.hidden=false;this.minYear=1970;this.maxYear=2050;this.dateFormat=Calendar._TT.DEF_DATE_FORMAT;this.ttDateFormat=Calendar._TT.TT_DATE_FORMAT;this.isPopup=true;this.weekNumbers=true;this.firstDayOfWeek=typeof d=="number"?d:Calendar._FD;this.showsOtherMonths=false;this.dateStr=c;this.ar_days=null;this.showsTime=false;this.time24=true;this.yearStep=2;this.hiliteToday=true;this.multiple=null;this.table=null;this.element=null;this.tbody=null;this.firstdayname=null;this.monthsCombo=null;this.yearsCombo=null;this.hilitedMonth=null;this.activeMonth=null;this.hilitedYear=null;this.activeYear=null;this.dateClicked=false;if(typeof Calendar._SDN=="undefined"){if(typeof Calendar._SDN_len=="undefined"){Calendar._SDN_len=3}var b=new Array();for(var e=8;e>0;){b[--e]=Calendar._DN[e].substr(0,Calendar._SDN_len)}Calendar._SDN=b;if(typeof Calendar._SMN_len=="undefined"){Calendar._SMN_len=3}b=new Array();for(var e=12;e>0;){b[--e]=Calendar._MN[e].substr(0,Calendar._SMN_len)}Calendar._SMN=b}};Calendar._C=null;Calendar.is_ie=(/msie/i.test(navigator.userAgent)&&!/opera/i.test(navigator.userAgent));Calendar.is_ie5=(Calendar.is_ie&&/msie 5\.0/i.test(navigator.userAgent));Calendar.is_opera=/opera/i.test(navigator.userAgent);Calendar.is_khtml=/Konqueror|Safari|KHTML/i.test(navigator.userAgent);Calendar.getAbsolutePos=function(e){var a=0,d=0;var c=/^div$/i.test(e.tagName);if(c&&e.scrollLeft){a=e.scrollLeft}if(c&&e.scrollTop){d=e.scrollTop}var f={x:e.offsetLeft-a,y:e.offsetTop-d};if(e.offsetParent){var b=this.getAbsolutePos(e.offsetParent);f.x+=b.x;f.y+=b.y}return f};Calendar.isRelated=function(c,a){var d=a.relatedTarget;if(!d){var b=a.type;if(b=="mouseover"){d=a.fromElement}else{if(b=="mouseout"){d=a.toElement}}}while(d){if(d==c){return true}d=d.parentNode}return false};Calendar.removeClass=function(e,d){if(!(e&&e.className)){return}var a=e.className.split(" ");var b=new Array();for(var c=a.length;c>0;){if(a[--c]!=d){b[b.length]=a[c]}}e.className=b.join(" ")};Calendar.addClass=function(b,a){Calendar.removeClass(b,a);b.className+=" "+a};Calendar.getElement=function(a){var b=Calendar.is_ie?window.event.srcElement:a.currentTarget;while(b.nodeType!=1||/^div$/i.test(b.tagName)){b=b.parentNode}return b};Calendar.getTargetElement=function(a){var b=Calendar.is_ie?window.event.srcElement:a.target;while(b.nodeType!=1){b=b.parentNode}return b};Calendar.stopEvent=function(a){a||(a=window.event);if(Calendar.is_ie){a.cancelBubble=true;a.returnValue=false}else{a.preventDefault();a.stopPropagation()}return false};Calendar.addEvent=function(a,c,b){if(a.attachEvent){a.attachEvent("on"+c,b)}else{if(a.addEventListener){a.addEventListener(c,b,true)}else{a["on"+c]=b}}};Calendar.removeEvent=function(a,c,b){if(a.detachEvent){a.detachEvent("on"+c,b)}else{if(a.removeEventListener){a.removeEventListener(c,b,true)}else{a["on"+c]=null}}};Calendar.createElement=function(c,b){var a=null;if(document.createElementNS){a=document.createElementNS("http://www.w3.org/1999/xhtml",c)}else{a=document.createElement(c)}if(typeof b!="undefined"){b.appendChild(a)}return a};Calendar._add_evs=function(el){with(Calendar){addEvent(el,"mouseover",dayMouseOver);addEvent(el,"mousedown",dayMouseDown);addEvent(el,"mouseout",dayMouseOut);if(is_ie){addEvent(el,"dblclick",dayMouseDblClick);el.setAttribute("unselectable",true)}}};Calendar.findMonth=function(a){if(typeof a.month!="undefined"){return a}else{if(typeof a.parentNode.month!="undefined"){return a.parentNode}}return null};Calendar.findYear=function(a){if(typeof a.year!="undefined"){return a}else{if(typeof a.parentNode.year!="undefined"){return a.parentNode}}return null};Calendar.showMonthsCombo=function(){var e=Calendar._C;if(!e){return false}var e=e;var f=e.activeDiv;var d=e.monthsCombo;if(e.hilitedMonth){Calendar.removeClass(e.hilitedMonth,"hilite")}if(e.activeMonth){Calendar.removeClass(e.activeMonth,"active")}var c=e.monthsCombo.getElementsByTagName("div")[e.date.getMonth()];Calendar.addClass(c,"active");e.activeMonth=c;var b=d.style;b.display="block";if(f.navtype<0){b.left=f.offsetLeft+"px"}else{var a=d.offsetWidth;if(typeof a=="undefined"){a=50}b.left=(f.offsetLeft+f.offsetWidth-a)+"px"}b.top=(f.offsetTop+f.offsetHeight)+"px"};Calendar.showYearsCombo=function(d){var a=Calendar._C;if(!a){return false}var a=a;var c=a.activeDiv;var f=a.yearsCombo;if(a.hilitedYear){Calendar.removeClass(a.hilitedYear,"hilite")}if(a.activeYear){Calendar.removeClass(a.activeYear,"active")}a.activeYear=null;var b=a.date.getFullYear()+(d?1:-1);var j=f.firstChild;var h=false;for(var e=12;e>0;--e){if(b>=a.minYear&&b<=a.maxYear){j.innerHTML=b;j.year=b;j.style.display="block";h=true}else{j.style.display="none"}j=j.nextSibling;b+=d?a.yearStep:-a.yearStep}if(h){var k=f.style;k.display="block";if(c.navtype<0){k.left=c.offsetLeft+"px"}else{var g=f.offsetWidth;if(typeof g=="undefined"){g=50}k.left=(c.offsetLeft+c.offsetWidth-g)+"px"}k.top=(c.offsetTop+c.offsetHeight)+"px"}};Calendar.tableMouseUp=function(ev){var cal=Calendar._C;if(!cal){return false}if(cal.timeout){clearTimeout(cal.timeout)}var el=cal.activeDiv;if(!el){return false}var target=Calendar.getTargetElement(ev);ev||(ev=window.event);Calendar.removeClass(el,"active");if(target==el||target.parentNode==el){Calendar.cellClick(el,ev)}var mon=Calendar.findMonth(target);var date=null;if(mon){date=new Date(cal.date);if(mon.month!=date.getMonth()){date.setMonth(mon.month);cal.setDate(date);cal.dateClicked=false;cal.callHandler()}}else{var year=Calendar.findYear(target);if(year){date=new Date(cal.date);if(year.year!=date.getFullYear()){date.setFullYear(year.year);cal.setDate(date);cal.dateClicked=false;cal.callHandler()}}}with(Calendar){removeEvent(document,"mouseup",tableMouseUp);removeEvent(document,"mouseover",tableMouseOver);removeEvent(document,"mousemove",tableMouseOver);cal._hideCombos();_C=null;return stopEvent(ev)}};Calendar.tableMouseOver=function(n){var a=Calendar._C;if(!a){return}var c=a.activeDiv;var j=Calendar.getTargetElement(n);if(j==c||j.parentNode==c){Calendar.addClass(c,"hilite active");Calendar.addClass(c.parentNode,"rowhilite")}else{if(typeof c.navtype=="undefined"||(c.navtype!=50&&(c.navtype==0||Math.abs(c.navtype)>2))){Calendar.removeClass(c,"active")}Calendar.removeClass(c,"hilite");Calendar.removeClass(c.parentNode,"rowhilite")}n||(n=window.event);if(c.navtype==50&&j!=c){var m=Calendar.getAbsolutePos(c);var p=c.offsetWidth;var o=n.clientX;var q;var l=true;if(o>m.x+p){q=o-m.x-p;l=false}else{q=m.x-o}if(q<0){q=0}var f=c._range;var h=c._current;var g=Math.floor(q/10)%f.length;for(var e=f.length;--e>=0;){if(f[e]==h){break}}while(g-->0){if(l){if(--e<0){e=f.length-1}}else{if(++e>=f.length){e=0}}}var b=f[e];c.innerHTML=b;a.onUpdateTime()}var d=Calendar.findMonth(j);if(d){if(d.month!=a.date.getMonth()){if(a.hilitedMonth){Calendar.removeClass(a.hilitedMonth,"hilite")}Calendar.addClass(d,"hilite");a.hilitedMonth=d}else{if(a.hilitedMonth){Calendar.removeClass(a.hilitedMonth,"hilite")}}}else{if(a.hilitedMonth){Calendar.removeClass(a.hilitedMonth,"hilite")}var k=Calendar.findYear(j);if(k){if(k.year!=a.date.getFullYear()){if(a.hilitedYear){Calendar.removeClass(a.hilitedYear,"hilite")}Calendar.addClass(k,"hilite");a.hilitedYear=k}else{if(a.hilitedYear){Calendar.removeClass(a.hilitedYear,"hilite")}}}else{if(a.hilitedYear){Calendar.removeClass(a.hilitedYear,"hilite")}}}return Calendar.stopEvent(n)};Calendar.tableMouseDown=function(a){if(Calendar.getTargetElement(a)==Calendar.getElement(a)){return Calendar.stopEvent(a)}};Calendar.calDragIt=function(b){var c=Calendar._C;if(!(c&&c.dragging)){return false}var e;var d;if(Calendar.is_ie){d=window.event.clientY+document.body.scrollTop;e=window.event.clientX+document.body.scrollLeft}else{e=b.pageX;d=b.pageY}c.hideShowCovered();var a=c.element.style;a.left=(e-c.xOffs)+"px";a.top=(d-c.yOffs)+"px";return Calendar.stopEvent(b)};Calendar.calDragEnd=function(ev){var cal=Calendar._C;if(!cal){return false}cal.dragging=false;with(Calendar){removeEvent(document,"mousemove",calDragIt);removeEvent(document,"mouseup",calDragEnd);tableMouseUp(ev)}cal.hideShowCovered()};Calendar.dayMouseDown=function(ev){var el=Calendar.getElement(ev);if(el.disabled){return false}var cal=el.calendar;cal.activeDiv=el;Calendar._C=cal;if(el.navtype!=300){with(Calendar){if(el.navtype==50){el._current=el.innerHTML;addEvent(document,"mousemove",tableMouseOver)}else{addEvent(document,Calendar.is_ie5?"mousemove":"mouseover",tableMouseOver)}addClass(el,"hilite active");addEvent(document,"mouseup",tableMouseUp)}}else{if(cal.isPopup){cal._dragStart(ev)}}if(el.navtype==-1||el.navtype==1){if(cal.timeout){clearTimeout(cal.timeout)}cal.timeout=setTimeout("Calendar.showMonthsCombo()",250)}else{if(el.navtype==-2||el.navtype==2){if(cal.timeout){clearTimeout(cal.timeout)}cal.timeout=setTimeout((el.navtype>0)?"Calendar.showYearsCombo(true)":"Calendar.showYearsCombo(false)",250)}else{cal.timeout=null}}return Calendar.stopEvent(ev)};Calendar.dayMouseDblClick=function(a){Calendar.cellClick(Calendar.getElement(a),a||window.event);if(Calendar.is_ie){document.selection.empty()}};Calendar.dayMouseOver=function(b){var a=Calendar.getElement(b);if(Calendar.isRelated(a,b)||Calendar._C||a.disabled){return false}if(a.ttip){if(a.ttip.substr(0,1)=="_"){a.ttip=a.caldate.print(a.calendar.ttDateFormat)+a.ttip.substr(1)}a.calendar.tooltips.innerHTML=a.ttip}if(a.navtype!=300){Calendar.addClass(a,"hilite");if(a.caldate){Calendar.addClass(a.parentNode,"rowhilite");var c=a.calendar;if(c&&c.getDateToolTip){var e=a.caldate;window.status=e;a.title=c.getDateToolTip(e,e.getFullYear(),e.getMonth(),e.getDate())}}}return Calendar.stopEvent(b)};Calendar.dayMouseOut=function(ev){with(Calendar){var el=getElement(ev);if(isRelated(el,ev)||_C||el.disabled){return false}removeClass(el,"hilite");if(el.caldate){removeClass(el.parentNode,"rowhilite")}if(el.calendar){el.calendar.tooltips.innerHTML=_TT.SEL_DATE}}};Calendar.cellClick=function(e,o){var c=e.calendar;var h=false;var l=false;var f=null;if(typeof e.navtype=="undefined"){if(c.currentDateEl){Calendar.removeClass(c.currentDateEl,"selected");Calendar.addClass(e,"selected");h=(c.currentDateEl==e);if(!h){c.currentDateEl=e}}c.date.setDateOnly(e.caldate);f=c.date;var b=!(c.dateClicked=!e.otherMonth);if(!b&&!c.currentDateEl&&c.multiple){c._toggleMultipleDate(new Date(f))}else{l=!e.disabled}if(b){c._init(c.firstDayOfWeek,f)}}else{if(e.navtype==200){Calendar.removeClass(e,"hilite");c.callCloseHandler();return}f=new Date(c.date);if(e.navtype==0){f.setDateOnly(new Date())}c.dateClicked=false;var n=f.getFullYear();var g=f.getMonth();function a(q){var r=f.getDate();var i=f.getMonthDays(q);if(r>i){f.setDate(i)}f.setMonth(q)}switch(e.navtype){case 400:Calendar.removeClass(e,"hilite");var p=Calendar._TT.ABOUT;if(typeof p!="undefined"){p+=c.showsTime?Calendar._TT.ABOUT_TIME:""}else{p='Help and about box text is not translated into this language.\nIf you know this language and you feel generous please update\nthe corresponding file in "lang" subdir to match calendar-en.js\nand send it back to <mihai_bazon@yahoo.com> to get it into the distribution  ;-)\n\nThank you!\nhttp://dynarch.com/mishoo/calendar.epl\n'}alert(p);return;case-2:if(n>c.minYear){f.setFullYear(n-1)}break;case-1:if(g>0){a(g-1)}else{if(n-->c.minYear){f.setFullYear(n);a(11)}}break;case 1:if(g<11){a(g+1)}else{if(n<c.maxYear){f.setFullYear(n+1);a(0)}}break;case 2:if(n<c.maxYear){f.setFullYear(n+1)}break;case 100:c.setFirstDayOfWeek(e.fdow);return;case 50:var k=e._range;var m=e.innerHTML;for(var j=k.length;--j>=0;){if(k[j]==m){break}}if(o&&o.shiftKey){if(--j<0){j=k.length-1}}else{if(++j>=k.length){j=0}}var d=k[j];e.innerHTML=d;c.onUpdateTime();return;case 0:if((typeof c.getDateStatus=="function")&&c.getDateStatus(f,f.getFullYear(),f.getMonth(),f.getDate())){return false}break}if(!f.equalsTo(c.date)){c.setDate(f);l=true}else{if(e.navtype==0){l=h=true}}}if(l){o&&c.callHandler()}if(h){Calendar.removeClass(e,"hilite");o&&c.callCloseHandler()}};Calendar.prototype.create=function(n){var m=null;if(!n){m=document.getElementsByTagName("body")[0];this.isPopup=true}else{m=n;this.isPopup=false}this.date=this.dateStr?new Date(this.dateStr):new Date();var q=Calendar.createElement("table");this.table=q;q.cellSpacing=0;q.cellPadding=0;q.calendar=this;Calendar.addEvent(q,"mousedown",Calendar.tableMouseDown);var a=Calendar.createElement("div");this.element=a;a.className="calendar";if(this.isPopup){a.style.position="absolute";a.style.display="none"}a.appendChild(q);var k=Calendar.createElement("thead",q);var o=null;var r=null;var b=this;var e=function(s,j,i){o=Calendar.createElement("td",r);o.colSpan=j;o.className="button";if(i!=0&&Math.abs(i)<=2){o.className+=" nav"}Calendar._add_evs(o);o.calendar=b;o.navtype=i;o.innerHTML="<div unselectable='on'>"+s+"</div>";return o};r=Calendar.createElement("tr",k);var c=6;(this.isPopup)&&--c;(this.weekNumbers)&&++c;e("?",1,400).ttip=Calendar._TT.INFO;this.title=e("",c,300);this.title.className="title";if(this.isPopup){this.title.ttip=Calendar._TT.DRAG_TO_MOVE;this.title.style.cursor="move";e("&#x00d7;",1,200).ttip=Calendar._TT.CLOSE}r=Calendar.createElement("tr",k);r.className="headrow";this._nav_py=e("&#x00ab;",1,-2);this._nav_py.ttip=Calendar._TT.PREV_YEAR;this._nav_pm=e("&#x2039;",1,-1);this._nav_pm.ttip=Calendar._TT.PREV_MONTH;this._nav_now=e(Calendar._TT.TODAY,this.weekNumbers?4:3,0);this._nav_now.ttip=Calendar._TT.GO_TODAY;this._nav_nm=e("&#x203a;",1,1);this._nav_nm.ttip=Calendar._TT.NEXT_MONTH;this._nav_ny=e("&#x00bb;",1,2);this._nav_ny.ttip=Calendar._TT.NEXT_YEAR;r=Calendar.createElement("tr",k);r.className="daynames";if(this.weekNumbers){o=Calendar.createElement("td",r);o.className="name wn";o.innerHTML=Calendar._TT.WK}for(var h=7;h>0;--h){o=Calendar.createElement("td",r);if(!h){o.navtype=100;o.calendar=this;Calendar._add_evs(o)}}this.firstdayname=(this.weekNumbers)?r.firstChild.nextSibling:r.firstChild;this._displayWeekdays();var g=Calendar.createElement("tbody",q);this.tbody=g;for(h=6;h>0;--h){r=Calendar.createElement("tr",g);if(this.weekNumbers){o=Calendar.createElement("td",r)}for(var f=7;f>0;--f){o=Calendar.createElement("td",r);o.calendar=this;Calendar._add_evs(o)}}if(this.showsTime){r=Calendar.createElement("tr",g);r.className="time";o=Calendar.createElement("td",r);o.className="time";o.colSpan=2;o.innerHTML=Calendar._TT.TIME||"&#160;";o=Calendar.createElement("td",r);o.className="time";o.colSpan=this.weekNumbers?4:3;(function(){function t(C,E,D,F){var A=Calendar.createElement("span",o);A.className=C;A.innerHTML=E;A.calendar=b;A.ttip=Calendar._TT.TIME_PART;A.navtype=50;A._range=[];if(typeof D!="number"){A._range=D}else{for(var B=D;B<=F;++B){var z;if(B<10&&F>=10){z="0"+B}else{z=""+B}A._range[A._range.length]=z}}Calendar._add_evs(A);return A}var x=b.date.getHours();var i=b.date.getMinutes();var y=!b.time24;var j=(x>12);if(y&&j){x-=12}var v=t("hour",x,y?1:0,y?12:23);var u=Calendar.createElement("span",o);u.innerHTML=":";u.className="colon";var s=t("minute",i,0,59);var w=null;o=Calendar.createElement("td",r);o.className="time";o.colSpan=2;if(y){w=t("ampm",j?"pm":"am",["am","pm"])}else{o.innerHTML="&#160;"}b.onSetTime=function(){var A,z=this.date.getHours(),B=this.date.getMinutes();if(y){A=(z>=12);if(A){z-=12}if(z==0){z=12}w.innerHTML=A?"pm":"am"}v.innerHTML=(z<10)?("0"+z):z;s.innerHTML=(B<10)?("0"+B):B};b.onUpdateTime=function(){var A=this.date;var B=parseInt(v.innerHTML,10);if(y){if(/pm/i.test(w.innerHTML)&&B<12){B+=12}else{if(/am/i.test(w.innerHTML)&&B==12){B=0}}}var C=A.getDate();var z=A.getMonth();var D=A.getFullYear();A.setHours(B);A.setMinutes(parseInt(s.innerHTML,10));A.setFullYear(D);A.setMonth(z);A.setDate(C);this.dateClicked=false;this.callHandler()}})()}else{this.onSetTime=this.onUpdateTime=function(){}}var l=Calendar.createElement("tfoot",q);r=Calendar.createElement("tr",l);r.className="footrow";o=e(Calendar._TT.SEL_DATE,this.weekNumbers?8:7,300);o.className="ttip";if(this.isPopup){o.ttip=Calendar._TT.DRAG_TO_MOVE;o.style.cursor="move"}this.tooltips=o;a=Calendar.createElement("div",this.element);this.monthsCombo=a;a.className="combo";for(h=0;h<Calendar._MN.length;++h){var d=Calendar.createElement("div");d.className=Calendar.is_ie?"label-IEfix":"label";d.month=h;d.innerHTML=Calendar._SMN[h];a.appendChild(d)}a=Calendar.createElement("div",this.element);this.yearsCombo=a;a.className="combo";for(h=12;h>0;--h){var p=Calendar.createElement("div");p.className=Calendar.is_ie?"label-IEfix":"label";a.appendChild(p)}this._init(this.firstDayOfWeek,this.date);m.appendChild(this.element)};Calendar._keyEvent=function(k){var a=window._dynarch_popupCalendar;if(!a||a.multiple){return false}(Calendar.is_ie)&&(k=window.event);var i=(Calendar.is_ie||k.type=="keypress"),l=k.keyCode;if(k.ctrlKey){switch(l){case 37:i&&Calendar.cellClick(a._nav_pm);break;case 38:i&&Calendar.cellClick(a._nav_py);break;case 39:i&&Calendar.cellClick(a._nav_nm);break;case 40:i&&Calendar.cellClick(a._nav_ny);break;default:return false}}else{switch(l){case 32:Calendar.cellClick(a._nav_now);break;case 27:i&&a.callCloseHandler();break;case 37:case 38:case 39:case 40:if(i){var e,m,j,g,c,d;e=l==37||l==38;d=(l==37||l==39)?1:7;function b(){c=a.currentDateEl;var n=c.pos;m=n&15;j=n>>4;g=a.ar_days[j][m]}b();function f(){var n=new Date(a.date);n.setDate(n.getDate()-d);a.setDate(n)}function h(){var n=new Date(a.date);n.setDate(n.getDate()+d);a.setDate(n)}while(1){switch(l){case 37:if(--m>=0){g=a.ar_days[j][m]}else{m=6;l=38;continue}break;case 38:if(--j>=0){g=a.ar_days[j][m]}else{f();b()}break;case 39:if(++m<7){g=a.ar_days[j][m]}else{m=0;l=40;continue}break;case 40:if(++j<a.ar_days.length){g=a.ar_days[j][m]}else{h();b()}break}break}if(g){if(!g.disabled){Calendar.cellClick(g)}else{if(e){f()}else{h()}}}}break;case 13:if(i){Calendar.cellClick(a.currentDateEl,k)}break;default:return false}}return Calendar.stopEvent(k)};Calendar.prototype._init=function(m,w){var v=new Date(),q=v.getFullYear(),y=v.getMonth(),b=v.getDate();this.table.style.visibility="hidden";var h=w.getFullYear();if(h<this.minYear){h=this.minYear;w.setFullYear(h)}else{if(h>this.maxYear){h=this.maxYear;w.setFullYear(h)}}this.firstDayOfWeek=m;this.date=new Date(w);var x=w.getMonth();var A=w.getDate();var z=w.getMonthDays();w.setDate(1);var r=(w.getDay()-this.firstDayOfWeek)%7;if(r<0){r+=7}w.setDate(-r);w.setDate(w.getDate()+1);var e=this.tbody.firstChild;var k=Calendar._SMN[x];var o=this.ar_days=new Array();var n=Calendar._TT.WEEKEND;var d=this.multiple?(this.datesCells={}):null;for(var t=0;t<6;++t,e=e.nextSibling){var a=e.firstChild;if(this.weekNumbers){a.className="day wn";a.innerHTML=w.getWeekNumber();a=a.nextSibling}e.className="daysrow";var u=false,f,c=o[t]=[];for(var s=0;s<7;++s,a=a.nextSibling,w.setDate(f+1)){f=w.getDate();var g=w.getDay();a.className="day";a.pos=t<<4|s;c[s]=a;var l=(w.getMonth()==x);if(!l){if(this.showsOtherMonths){a.className+=" othermonth";a.otherMonth=true}else{a.className="emptycell";a.innerHTML="&#160;";a.disabled=true;continue}}else{a.otherMonth=false;u=true}a.disabled=false;a.innerHTML=this.getDateText?this.getDateText(w,f):f;if(d){d[w.print("%Y%m%d")]=a}if(this.getDateStatus){var p=this.getDateStatus(w,h,x,f);if(p===true){a.className+=" disabled";a.disabled=true}else{if(/disabled/i.test(p)){a.disabled=true}a.className+=" "+p}}if(!a.disabled){a.caldate=new Date(w);a.ttip="_";if(!this.multiple&&l&&f==A&&this.hiliteToday){a.className+=" selected";this.currentDateEl=a}if(w.getFullYear()==q&&w.getMonth()==y&&f==b){a.className+=" today";a.ttip+=Calendar._TT.PART_TODAY}if(n.indexOf(g.toString())!=-1){a.className+=a.otherMonth?" oweekend":" weekend"}}}if(!(u||this.showsOtherMonths)){e.className="emptyrow"}}this.title.innerHTML=Calendar._MN[x]+", "+h;this.onSetTime();this.table.style.visibility="visible";this._initMultipleDates()};Calendar.prototype._initMultipleDates=function(){if(this.multiple){for(var b in this.multiple){var a=this.datesCells[b];var c=this.multiple[b];if(!c){continue}if(a){a.className+=" selected"}}}};Calendar.prototype._toggleMultipleDate=function(b){if(this.multiple){var c=b.print("%Y%m%d");var a=this.datesCells[c];if(a){var e=this.multiple[c];if(!e){Calendar.addClass(a,"selected");this.multiple[c]=b}else{Calendar.removeClass(a,"selected");delete this.multiple[c]}}}};Calendar.prototype.setDateToolTipHandler=function(a){this.getDateToolTip=a};Calendar.prototype.setDate=function(a){if(!a.equalsTo(this.date)){this._init(this.firstDayOfWeek,a)}};Calendar.prototype.refresh=function(){this._init(this.firstDayOfWeek,this.date)};Calendar.prototype.setFirstDayOfWeek=function(a){this._init(a,this.date);this._displayWeekdays()};Calendar.prototype.setDateStatusHandler=Calendar.prototype.setDisabledHandler=function(a){this.getDateStatus=a};Calendar.prototype.setRange=function(b,c){this.minYear=b;this.maxYear=c};Calendar.prototype.callHandler=function(){if(this.onSelected){this.onSelected(this,this.date.print(this.dateFormat))}};Calendar.prototype.callCloseHandler=function(){if(this.onClose){this.onClose(this)}this.hideShowCovered()};Calendar.prototype.destroy=function(){var a=this.element.parentNode;a.removeChild(this.element);Calendar._C=null;window._dynarch_popupCalendar=null};Calendar.prototype.reparent=function(b){var a=this.element;a.parentNode.removeChild(a);b.appendChild(a)};Calendar._checkCalendar=function(b){var c=window._dynarch_popupCalendar;if(!c){return false}var a=Calendar.is_ie?Calendar.getElement(b):Calendar.getTargetElement(b);for(;a!=null&&a!=c.element;a=a.parentNode){}if(a==null){window._dynarch_popupCalendar.callCloseHandler();return Calendar.stopEvent(b)}};Calendar.prototype.show=function(){var e=this.table.getElementsByTagName("tr");for(var d=e.length;d>0;){var f=e[--d];Calendar.removeClass(f,"rowhilite");var c=f.getElementsByTagName("td");for(var b=c.length;b>0;){var a=c[--b];Calendar.removeClass(a,"hilite");Calendar.removeClass(a,"active")}}this.element.style.display="block";this.hidden=false;if(this.isPopup){window._dynarch_popupCalendar=this;Calendar.addEvent(document,"keydown",Calendar._keyEvent);Calendar.addEvent(document,"keypress",Calendar._keyEvent);Calendar.addEvent(document,"mousedown",Calendar._checkCalendar)}this.hideShowCovered()};Calendar.prototype.hide=function(){if(this.isPopup){Calendar.removeEvent(document,"keydown",Calendar._keyEvent);Calendar.removeEvent(document,"keypress",Calendar._keyEvent);Calendar.removeEvent(document,"mousedown",Calendar._checkCalendar)}this.element.style.display="none";this.hidden=true;this.hideShowCovered()};Calendar.prototype.showAt=function(a,c){var b=this.element.style;b.left=a+"px";b.top=c+"px";this.show()};Calendar.prototype.showAtElement=function(c,d){var a=this;var e=Calendar.getAbsolutePos(c);if(!d||typeof d!="string"){this.showAt(e.x,e.y+c.offsetHeight);return true}function b(i){if(i.x<0){i.x=0}if(i.y<0){i.y=0}var j=document.createElement("div");var h=j.style;h.position="absolute";h.right=h.bottom=h.width=h.height="0px";document.body.appendChild(j);var g=Calendar.getAbsolutePos(j);document.body.removeChild(j);if(Calendar.is_ie){g.y+=(document.documentElement&&document.documentElement.scrollTop)||document.body.scrollTop;g.x+=(document.documentElement&&document.documentElement.scrollLeft)||document.body.scrollLeft}else{g.y+=window.scrollY;g.x+=window.scrollX}var f=i.x+i.width-g.x;if(f>0){i.x-=f}f=i.y+i.height-g.y;if(f>0){i.y-=f}}this.element.style.display="block";Calendar.continuation_for_the_khtml_browser=function(){var f=a.element.offsetWidth;var i=a.element.offsetHeight;a.element.style.display="none";var g=d.substr(0,1);var j="l";if(d.length>1){j=d.substr(1,1)}switch(g){case"T":e.y-=i;break;case"B":e.y+=c.offsetHeight;break;case"C":e.y+=(c.offsetHeight-i)/2;break;case"t":e.y+=c.offsetHeight-i;break;case"b":break}switch(j){case"L":e.x-=f;break;case"R":e.x+=c.offsetWidth;break;case"C":e.x+=(c.offsetWidth-f)/2;break;case"l":e.x+=c.offsetWidth-f;break;case"r":break}e.width=f;e.height=i+40;a.monthsCombo.style.display="none";b(e);a.showAt(e.x,e.y)};if(Calendar.is_khtml){setTimeout("Calendar.continuation_for_the_khtml_browser()",10)}else{Calendar.continuation_for_the_khtml_browser()}};Calendar.prototype.setDateFormat=function(a){this.dateFormat=a};Calendar.prototype.setTtDateFormat=function(a){this.ttDateFormat=a};Calendar.prototype.parseDate=function(b,a){if(!a){a=this.dateFormat}this.setDate(Date.parseDate(b,a))};Calendar.prototype.hideShowCovered=function(){if(!Calendar.is_ie&&!Calendar.is_opera){return}function b(k){var i=k.style.visibility;if(!i){if(document.defaultView&&typeof(document.defaultView.getComputedStyle)=="function"){if(!Calendar.is_khtml){i=document.defaultView.getComputedStyle(k,"").getPropertyValue("visibility")}else{i=""}}else{if(k.currentStyle){i=k.currentStyle.visibility}else{i=""}}}return i}var s=new Array("applet","iframe","select");var c=this.element;var a=Calendar.getAbsolutePos(c);var f=a.x;var d=c.offsetWidth+f;var r=a.y;var q=c.offsetHeight+r;for(var h=s.length;h>0;){var g=document.getElementsByTagName(s[--h]);var e=null;for(var l=g.length;l>0;){e=g[--l];a=Calendar.getAbsolutePos(e);var o=a.x;var n=e.offsetWidth+o;var m=a.y;var j=e.offsetHeight+m;if(this.hidden||(o>d)||(n<f)||(m>q)||(j<r)){if(!e.__msh_save_visibility){e.__msh_save_visibility=b(e)}e.style.visibility=e.__msh_save_visibility}else{if(!e.__msh_save_visibility){e.__msh_save_visibility=b(e)}e.style.visibility="hidden"}}}};Calendar.prototype._displayWeekdays=function(){var b=this.firstDayOfWeek;var a=this.firstdayname;var d=Calendar._TT.WEEKEND;for(var c=0;c<7;++c){a.className="day name";var e=(c+b)%7;if(c){a.ttip=Calendar._TT.DAY_FIRST.replace("%s",Calendar._DN[e]);a.navtype=100;a.calendar=this;a.fdow=e;Calendar._add_evs(a)}if(d.indexOf(e.toString())!=-1){Calendar.addClass(a,"weekend")}a.innerHTML=Calendar._SDN[(c+b)%7];a=a.nextSibling}};Calendar.prototype._hideCombos=function(){this.monthsCombo.style.display="none";this.yearsCombo.style.display="none"};Calendar.prototype._dragStart=function(ev){if(this.dragging){return}this.dragging=true;var posX;var posY;if(Calendar.is_ie){posY=window.event.clientY+document.body.scrollTop;posX=window.event.clientX+document.body.scrollLeft}else{posY=ev.clientY+window.scrollY;posX=ev.clientX+window.scrollX}var st=this.element.style;this.xOffs=posX-parseInt(st.left);this.yOffs=posY-parseInt(st.top);with(Calendar){addEvent(document,"mousemove",calDragIt);addEvent(document,"mouseup",calDragEnd)}};Date._MD=new Array(31,28,31,30,31,30,31,31,30,31,30,31);Date.SECOND=1000;Date.MINUTE=60*Date.SECOND;Date.HOUR=60*Date.MINUTE;Date.DAY=24*Date.HOUR;Date.WEEK=7*Date.DAY;Date.parseDate=function(l,c){var n=new Date();var o=0;var e=-1;var k=0;var q=l.split(/\W+/);var p=c.match(/%./g);var h=0,g=0;var r=0;var f=0;for(h=0;h<q.length;++h){if(!q[h]){continue}switch(p[h]){case"%d":case"%e":k=parseInt(q[h],10);break;case"%m":e=parseInt(q[h],10)-1;break;case"%Y":case"%y":o=parseInt(q[h],10);(o<100)&&(o+=(o>29)?1900:2000);break;case"%b":case"%B":for(g=0;g<12;++g){if(Calendar._MN[g].substr(0,q[h].length).toLowerCase()==q[h].toLowerCase()){e=g;break}}break;case"%H":case"%I":case"%k":case"%l":r=parseInt(q[h],10);break;case"%P":case"%p":if(/pm/i.test(q[h])&&r<12){r+=12}else{if(/am/i.test(q[h])&&r>=12){r-=12}}break;case"%M":f=parseInt(q[h],10);break}}if(isNaN(o)){o=n.getFullYear()}if(isNaN(e)){e=n.getMonth()}if(isNaN(k)){k=n.getDate()}if(isNaN(r)){r=n.getHours()}if(isNaN(f)){f=n.getMinutes()}if(o!=0&&e!=-1&&k!=0){return new Date(o,e,k,r,f,0)}o=0;e=-1;k=0;for(h=0;h<q.length;++h){if(q[h].search(/[a-zA-Z]+/)!=-1){var s=-1;for(g=0;g<12;++g){if(Calendar._MN[g].substr(0,q[h].length).toLowerCase()==q[h].toLowerCase()){s=g;break}}if(s!=-1){if(e!=-1){k=e+1}e=s}}else{if(parseInt(q[h],10)<=12&&e==-1){e=q[h]-1}else{if(parseInt(q[h],10)>31&&o==0){o=parseInt(q[h],10);(o<100)&&(o+=(o>29)?1900:2000)}else{if(k==0){k=q[h]}}}}}if(o==0){o=n.getFullYear()}if(e!=-1&&k!=0){return new Date(o,e,k,r,f,0)}return n};Date.prototype.getMonthDays=function(b){var a=this.getFullYear();if(typeof b=="undefined"){b=this.getMonth()}if(((0==(a%4))&&((0!=(a%100))||(0==(a%400))))&&b==1){return 29}else{return Date._MD[b]}};Date.prototype.getDayOfYear=function(){var a=new Date(this.getFullYear(),this.getMonth(),this.getDate(),0,0,0);var c=new Date(this.getFullYear(),0,0,0,0,0);var b=a-c;return Math.floor(b/Date.DAY)};Date.prototype.getWeekNumber=function(){var c=new Date(this.getFullYear(),this.getMonth(),this.getDate(),0,0,0);var b=c.getDay();c.setDate(c.getDate()-(b+6)%7+3);var a=c.valueOf();c.setMonth(0);c.setDate(4);return Math.round((a-c.valueOf())/(7*86400000))+1};Date.prototype.equalsTo=function(a){return((this.getFullYear()==a.getFullYear())&&(this.getMonth()==a.getMonth())&&(this.getDate()==a.getDate())&&(this.getHours()==a.getHours())&&(this.getMinutes()==a.getMinutes()))};Date.prototype.setDateOnly=function(a){var b=new Date(a);this.setDate(1);this.setFullYear(b.getFullYear());this.setMonth(b.getMonth());this.setDate(b.getDate())};Date.prototype.print=function(l){var b=this.getMonth();var k=this.getDate();var n=this.getFullYear();var p=this.getWeekNumber();var q=this.getDay();var v={};var r=this.getHours();var c=(r>=12);var h=(c)?(r-12):r;var u=this.getDayOfYear();if(h==0){h=12}var e=this.getMinutes();var j=this.getSeconds();v["%a"]=Calendar._SDN[q];v["%A"]=Calendar._DN[q];v["%b"]=Calendar._SMN[b];v["%B"]=Calendar._MN[b];v["%C"]=1+Math.floor(n/100);v["%d"]=(k<10)?("0"+k):k;v["%e"]=k;v["%H"]=(r<10)?("0"+r):r;v["%I"]=(h<10)?("0"+h):h;v["%j"]=(u<100)?((u<10)?("00"+u):("0"+u)):u;v["%k"]=r;v["%l"]=h;v["%m"]=(b<9)?("0"+(1+b)):(1+b);v["%M"]=(e<10)?("0"+e):e;v["%n"]="\n";v["%p"]=c?"PM":"AM";v["%P"]=c?"pm":"am";v["%s"]=Math.floor(this.getTime()/1000);v["%S"]=(j<10)?("0"+j):j;v["%t"]="\t";v["%U"]=v["%W"]=v["%V"]=(p<10)?("0"+p):p;v["%u"]=q+1;v["%w"]=q;v["%y"]=(""+n).substr(2,2);v["%Y"]=n;v["%%"]="%";var t=/%./g;if(!Calendar.is_ie5&&!Calendar.is_khtml){return l.replace(t,function(a){return v[a]||a})}var o=l.match(t);for(var g=0;g<o.length;g++){var f=v[o[g]];if(f){t=new RegExp(o[g],"g");l=l.replace(t,f)}}return l};window._dynarch_popupCalendar=null;


/*===============================
/sampo4/media/system/js/calendar-setup.js
================================================================================*/;
Calendar.setup=function(g){function f(h,i){if(typeof g[h]=="undefined"){g[h]=i}}f("inputField",null);f("displayArea",null);f("button",null);f("eventName","click");f("ifFormat","%Y/%m/%d");f("daFormat","%Y/%m/%d");f("singleClick",true);f("disableFunc",null);f("dateStatusFunc",g.disableFunc);f("dateTooltipFunc",null);f("dateText",null);f("firstDay",null);f("align","Br");f("range",[1900,2999]);f("weekNumbers",true);f("flat",null);f("flatCallback",null);f("onSelect",null);f("onClose",null);f("onUpdate",null);f("date",null);f("showsTime",false);f("timeFormat","24");f("electric",true);f("step",2);f("position",null);f("cache",false);f("showOthers",false);f("multiple",null);var c=["inputField","displayArea","button"];for(var b in c){if(typeof g[c[b]]=="string"){g[c[b]]=document.getElementById(g[c[b]])}}if(!(g.flat||g.multiple||g.inputField||g.displayArea||g.button)){alert("Calendar.setup:\n  Nothing to setup (no fields found).  Please check your code");return false}function a(i){var h=i.params;var j=(i.dateClicked||h.electric);if(j&&h.inputField){h.inputField.value=i.date.print(h.ifFormat);if(typeof h.inputField.onchange=="function"){h.inputField.onchange()}}if(j&&h.displayArea){h.displayArea.innerHTML=i.date.print(h.daFormat)}if(j&&typeof h.onUpdate=="function"){h.onUpdate(i)}if(j&&h.flat){if(typeof h.flatCallback=="function"){h.flatCallback(i)}}if(j&&h.singleClick&&i.dateClicked){i.callCloseHandler()}}if(g.flat!=null){if(typeof g.flat=="string"){g.flat=document.getElementById(g.flat)}if(!g.flat){alert("Calendar.setup:\n  Flat specified but can't find parent.");return false}var e=new Calendar(g.firstDay,g.date,g.onSelect||a);e.setDateToolTipHandler(g.dateTooltipFunc);e.showsOtherMonths=g.showOthers;e.showsTime=g.showsTime;e.time24=(g.timeFormat=="24");e.params=g;e.weekNumbers=g.weekNumbers;e.setRange(g.range[0],g.range[1]);e.setDateStatusHandler(g.dateStatusFunc);e.getDateText=g.dateText;if(g.ifFormat){e.setDateFormat(g.ifFormat)}if(g.inputField&&typeof g.inputField.value=="string"){e.parseDate(g.inputField.value)}e.create(g.flat);e.show();return false}var d=g.button||g.displayArea||g.inputField;d["on"+g.eventName]=function(){var h=g.inputField||g.displayArea;var k=g.inputField?g.ifFormat:g.daFormat;var o=false;var m=window.calendar;if(h){g.date=Date.parseDate(h.value||h.innerHTML,k)}if(!(m&&g.cache)){window.calendar=m=new Calendar(g.firstDay,g.date,g.onSelect||a,g.onClose||function(i){i.hide()});m.setDateToolTipHandler(g.dateTooltipFunc);m.showsTime=g.showsTime;m.time24=(g.timeFormat=="24");m.weekNumbers=g.weekNumbers;o=true}else{if(g.date){m.setDate(g.date)}m.hide()}if(g.multiple){m.multiple={};for(var j=g.multiple.length;--j>=0;){var n=g.multiple[j];var l=n.print("%Y%m%d");m.multiple[l]=n}}m.showsOtherMonths=g.showOthers;m.yearStep=g.step;m.setRange(g.range[0],g.range[1]);m.params=g;m.setDateStatusHandler(g.dateStatusFunc);m.getDateText=g.dateText;m.setDateFormat(k);if(o){m.create()}m.refresh();if(!g.position){m.showAtElement(g.button||g.displayArea||g.inputField,g.align)}else{m.showAt(g.position[0],g.position[1])}return false};return e};


/*===============================
/sampo4/media/system/js/punycode.js
================================================================================*/;
/*! https://mths.be/punycode v1.4.1 by @mathias - do not update to v2 */
!function(a){function b(a){throw new RangeError(E[a])}function c(a,b){for(var c=a.length,d=[];c--;)d[c]=b(a[c]);return d}function d(a,b){var d=a.split("@"),e="";d.length>1&&(e=d[0]+"@",a=d[1]),a=a.replace(D,".");var f=a.split("."),g=c(f,b).join(".");return e+g}function e(a){for(var b,c,d=[],e=0,f=a.length;f>e;)b=a.charCodeAt(e++),b>=55296&&56319>=b&&f>e?(c=a.charCodeAt(e++),56320==(64512&c)?d.push(((1023&b)<<10)+(1023&c)+65536):(d.push(b),e--)):d.push(b);return d}function f(a){return c(a,function(a){var b="";return a>65535&&(a-=65536,b+=H(a>>>10&1023|55296),a=56320|1023&a),b+=H(a)}).join("")}function g(a){return 10>a-48?a-22:26>a-65?a-65:26>a-97?a-97:t}function h(a,b){return a+22+75*(26>a)-((0!=b)<<5)}function i(a,b,c){var d=0;for(a=c?G(a/x):a>>1,a+=G(a/b);a>F*v>>1;d+=t)a=G(a/F);return G(d+(F+1)*a/(a+w))}function j(a){var c,d,e,h,j,k,l,m,n,o,p=[],q=a.length,r=0,w=z,x=y;for(d=a.lastIndexOf(A),0>d&&(d=0),e=0;d>e;++e)a.charCodeAt(e)>=128&&b("not-basic"),p.push(a.charCodeAt(e));for(h=d>0?d+1:0;q>h;){for(j=r,k=1,l=t;h>=q&&b("invalid-input"),m=g(a.charCodeAt(h++)),(m>=t||m>G((s-r)/k))&&b("overflow"),r+=m*k,n=x>=l?u:l>=x+v?v:l-x,!(n>m);l+=t)o=t-n,k>G(s/o)&&b("overflow"),k*=o;c=p.length+1,x=i(r-j,c,0==j),G(r/c)>s-w&&b("overflow"),w+=G(r/c),r%=c,p.splice(r++,0,w)}return f(p)}function k(a){var c,d,f,g,j,k,l,m,n,o,p,q,r,w,x,B=[];for(a=e(a),q=a.length,c=z,d=0,j=y,k=0;q>k;++k)p=a[k],128>p&&B.push(H(p));for(f=g=B.length,g&&B.push(A);q>f;){for(l=s,k=0;q>k;++k)p=a[k],p>=c&&l>p&&(l=p);for(r=f+1,l-c>G((s-d)/r)&&b("overflow"),d+=(l-c)*r,c=l,k=0;q>k;++k)if(p=a[k],c>p&&++d>s&&b("overflow"),p==c){for(m=d,n=t;o=j>=n?u:n>=j+v?v:n-j,!(o>m);n+=t)x=m-o,w=t-o,B.push(H(h(o+x%w,0))),m=G(x/w);B.push(H(h(m,0))),j=i(d,r,f==g),d=0,++f}++d,++c}return B.join("")}function l(a){return d(a,function(a){return B.test(a)?j(a.slice(4).toLowerCase()):a})}function m(a){return d(a,function(a){return C.test(a)?"xn--"+k(a):a})}var n="object"==typeof exports&&exports&&!exports.nodeType&&exports,o="object"==typeof module&&module&&!module.nodeType&&module,p="object"==typeof global&&global;(p.global===p||p.window===p||p.self===p)&&(a=p);var q,r,s=2147483647,t=36,u=1,v=26,w=38,x=700,y=72,z=128,A="-",B=/^xn--/,C=/[^\x20-\x7E]/,D=/[\x2E\u3002\uFF0E\uFF61]/g,E={overflow:"Overflow: input needs wider integers to process","not-basic":"Illegal input >= 0x80 (not a basic code point)","invalid-input":"Invalid input"},F=t-u,G=Math.floor,H=String.fromCharCode;if(q={version:"1.4.1",ucs2:{decode:e,encode:f},decode:j,encode:k,toASCII:m,toUnicode:l},"function"==typeof define&&"object"==typeof define.amd&&define.amd)define("punycode",function(){return q});else if(n&&o)if(module.exports==n)o.exports=q;else for(r in q)q.hasOwnProperty(r)&&(n[r]=q[r]);else a.punycode=q}(this);


/*===============================
/sampo4/media/system/js/validate.js
================================================================================*/;
var JFormValidator=function(){"use strict";var t,e,a,r=function(e,a,r){r=""===r||r,t[e]={enabled:r,exec:a}},n=function(t,e){var a,r,n,i,l=e.data("label");void 0===l&&(a=e.attr("id"),r=e.get(0).form,i=jQuery(r),l=!!a&&((n=i.find("#"+a+"-lbl")).length?n:!!(n=i.find('label[for="'+a+'"]')).length&&n),e.data("label",l)),!1===t?(e.addClass("invalid").attr("aria-invalid","true"),l&&l.addClass("invalid")):(e.removeClass("invalid").attr("aria-invalid","false"),l&&l.removeClass("invalid"))},i=function(e){var a,r=jQuery(e);if(r.attr("disabled"))return n(!0,r),!0;if(r.attr("required")||r.hasClass("required"))if("fieldset"===r.prop("tagName").toLowerCase()&&(r.hasClass("radio")||r.hasClass("checkboxes"))){if(!r.find("input:checked").length)return n(!1,r),!1}else if(!r.val()||r.hasClass("placeholder")||"checkbox"===r.attr("type")&&!r.is(":checked"))return n(!1,r),!1;return a=r.attr("class")&&r.attr("class").match(/validate-([a-zA-Z0-9\_\-]+)/)?r.attr("class").match(/validate-([a-zA-Z0-9\_\-]+)/)[1]:"",r.attr("pattern")&&""!=r.attr("pattern")?r.val().length?(l=new RegExp("^"+r.attr("pattern")+"$").test(r.val()),n(l,r),l):r.attr("required")||r.hasClass("required")?(n(!1,r),!1):(n(!0,r),!0):""===a?(n(!0,r),!0):a&&"none"!==a&&t[a]&&r.val()&&!0!==t[a].exec(r.val(),r)?(n(!1,r),!1):(n(!0,r),!0)},l=function(t){var e,r,n,l,s,u,o=!0,d=[];for(s=0,u=(e=jQuery(t).find("input, textarea, select, fieldset")).length;s<u;s++)jQuery(e[s]).hasClass("novalidate")||!1===i(e[s])&&(o=!1,d.push(e[s]));if(jQuery.each(a,function(t,e){!0!==e.exec()&&(o=!1)}),!o&&d.length>0){for(r=Joomla.JText._("JLIB_FORM_FIELD_INVALID"),n={error:[]},s=d.length-1;s>=0;s--)(l=jQuery(d[s]).data("label"))&&n.error.push(r+l.text().replace("*",""));Joomla.renderMessages(n)}return o},s=function(t){for(var a,r=[],n=jQuery(t),s=0,u=(a=n.find("input, textarea, select, fieldset, button")).length;s<u;s++){var o=jQuery(a[s]),d=o.prop("tagName").toLowerCase();"input"!==d&&"button"!==d||"submit"!==o.attr("type")&&"image"!==o.attr("type")?"button"===d||"input"===d&&"button"===o.attr("type")||(o.hasClass("required")&&o.attr("aria-required","true").attr("required","required"),"fieldset"!==d&&(o.on("blur",function(){return i(this)}),o.hasClass("validate-email")&&e&&a[s].setAttribute("type","email")),r.push(o)):o.hasClass("validate")&&o.on("click",function(){return l(t)})}n.data("inputfields",r)};return function(){var n;t={},a=a||{},(n=document.createElement("input")).setAttribute("type","email"),e="text"!==n.type,r("username",function(t,e){return!new RegExp("[<|>|\"|'|%|;|(|)|&]","i").test(t)}),r("password",function(t,e){return/^\S[\S]{2,98}\S$/.test(t)}),r("numeric",function(t,e){return/^(\d|-)?(\d|,)*\.?\d*$/.test(t)}),r("email",function(t,e){return t=punycode.toASCII(t),/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(t)});for(var i=jQuery("form.form-validate"),l=0,u=i.length;l<u;l++)s(i[l])}(),{isValid:l,validate:i,setHandler:r,attachToForm:s,custom:a}};document.formvalidator=null,jQuery(function(){document.formvalidator=new JFormValidator});


/*===============================
http://localhost/sampo4/components/com_rseventspro/assets/js/scripts.js
================================================================================*/;
var rsepro_timer;function isset(){var a=arguments,l=a.length,i=0,undef;if(l===0){throw new Error('Empty isset');}
while(i!==l){if(a[i]===undef||a[i]===null){return false;}
i++;}
return true;}
function rse_calculatetotal(tickets){var params='task=total&idevent='+parseInt($('eventID').innerHTML);rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';if(!isset(tickets)){var ticketId=isset($('RSEProTickets'))?$('RSEProTickets').value:$('ticket').value;if($('from').value==0){var numberOfTickets=$('numberinp').value;}else{var numberOfTickets=$('number').value;}
if(isset($('hiddentickets'))){var ticketsstring='';$$('#hiddentickets input').each(function(el){ticketsstring+='&'+el.name+'='+el.value;});params+=ticketsstring;}else{params+='&tickets['+ticketId+']='+numberOfTickets;}}else{params+=tickets;}
if(isset($('coupon')))params+='&coupon='+$('coupon').value;if(isset($('RSEProCoupon')))params+='&coupon='+$('RSEProCoupon').value;if($$('select[name=payment]').length){params+='&payment='+$('payment').value;}else if($$('input[name=payment]').length){if(isset($$('input[name=payment]:checked')[0]))
params+='&payment='+$$('input[name=payment]:checked')[0].value;}
if($$('select[name^=form[RSEProPayment]]').length){params+='&payment='+$('RSEProPayment').value;}else if($$('input[name=form[RSEProPayment]]').length){if(isset($$('input[name=form[RSEProPayment]]:checked')[0]))
params+='&payment='+$$('input[name=form[RSEProPayment]]:checked')[0].value}
params+='&randomTime='+Math.random();var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:params,onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);response=response.split('|');if(response[0]!=0){$('grandtotalcontainer').style.display='';$('grandtotal').innerHTML=response[0];}else{$('grandtotalcontainer').style.display='none';$('grandtotal').innerHTML=0;}
if(response[1]!=''){$('paymentinfocontainer').style.display='';$('paymentinfo').innerHTML=response[1];}else{$('paymentinfocontainer').style.display='none';$('paymentinfo').innerHTML='';}}});req.send();}
function rs_stop(){clearTimeout(rsepro_timer);}
function rsepro_description_on(id){document.getElementById('rsehref'+id).style.display='none';document.getElementById('rsedescription'+id).className='rsepro_extra_on';}
function rsepro_description_off(id){document.getElementById('rsehref'+id).style.display='inline';document.getElementById('rsedescription'+id).className='rsepro_extra_off';}
function rs_add_option(theoption){$('rseprosearch').value=theoption;$('rs_results').style.display='none';}
function rs_add_filter(){if($('rseprosearch').value!='')
document.adminForm.submit();}
function rs_clear_filters(){$('rs_clear').value=1;document.adminForm.submit();}
function rs_remove_filter(key){$('rs_remove').value=key;document.adminForm.submit();}
function rse_verify_coupon(ide,coupon){if(coupon=='')
return false;rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=verify&id='+ide+'&coupon='+coupon,onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);alert(response);}});req.send();}
function rs_search(){if($('filter_from').value=='description')return;if($('rseprosearch').value=='')return;rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';rsepro_timer=setTimeout(function(){var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=filter&type='+$('filter_from').value+'&search='+$('rseprosearch').value+'&condition='+$('filter_condition').value,onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);if(response!=''){$('rs_results').innerHTML=response;$('rs_results').style.display='block';}else $('rs_results').style.display='none';}});req.send();},1000);}
function rspagination(tpl,limitstart,ide){$('rs_loader').style.display='';if(tpl=='day'||tpl=='week')
var params='&view=calendar&layout=items&tpl='+tpl+'&format=raw&limitstart='+limitstart;else
var params='view=rseventspro&layout=items&tpl='+tpl+'&format=raw&limitstart='+limitstart;if(isset($('parent'))&&parseInt($('parent').innerHTML)>0)params+='&parent='+parseInt($('parent').innerHTML);if(isset($('date'))&&$('date').innerHTML!='')params+='&date='+$('date').innerHTML;if(ide)params+='&id='+parseInt(ide);params+='&Itemid='+parseInt($('Itemid').innerHTML);params+='&randomTime='+Math.random();rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:params,onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);var code=response.toDOM();for(i=0;i<code.length;i++)
$('rs_events_container').appendChild(code[i]);$('rs_loader').style.display='none';if($$('#rs_events_container li').length>0&&(tpl=='events'||tpl=='locations'||tpl=='subscribers'||tpl=='day'||tpl=='week'))
{$$('#rs_events_container li').addEvents({mouseenter:function(){if(isset($(this).getElement('div.rs_options')))
$(this).getElement('div.rs_options').style.display='';},mouseleave:function(){if(isset($(this).getElement('div.rs_options')))
$(this).getElement('div.rs_options').style.display='none';}});}
if(($('rs_events_container').getElements('li').length)>=parseInt($('total').innerHTML))$('rsepro_loadmore').style.display='none';}});req.send();}
function rsepro_feedback(val,id){for(var i=1;i<=5;i++)
document.getElementById('rsepro_feedback_'+i).onclick=function(){return false;}
$('rs_loading_img').style.display='';rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=rseventspro.rate&id='+id+'&feedback='+val+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);response=response.split('|');$('rs_loading_img').style.display='none';if(parseInt(response[0])>0)
document.getElementById('rs_rating').innerHTML='<li id="rsepro_current_rating" class="rsepro_feedback_selected_'+parseInt(response[0])+'">&nbsp;</li>';$('rs_rating_info').innerHTML=response[1];window.setTimeout(function(){$('rs_rating_info').tween('opacity','0');},2000);}});req.send();}
function rs_get_ticket(val){$('rs_loader').style.display='';rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=tickets&id='+val+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);response=response.split('|');$('rs_loader').style.display='none';if(parseInt(response[0])==0){$('numberinp').style.display='';$('number').style.display='none';$('numberinp').value=1;$('from').value=0;}else{$('numberinp').style.display='none';$('number').style.display='';$('from').value=1;$('number').options.length=0;for(i=1;i<=parseInt(response[0]);i++)
$('number').options[i-1]=new Option(i,i);}
$('tdescription').innerHTML=response[1];rse_calculatetotal();}});req.send();}
function svalidation(){ret=true;msg=new Array();if($('name').value==''){ret=false;$('name').className='rs_edit_inp_error_small';msg.push(smessage[0]);}else{$('name').className='rs_edit_inp_small';}
if($('email').value==''){ret=false;$('email').className='rs_edit_inp_error_small';msg.push(smessage[1]);}else{$('email').className='rs_edit_inp_small';}
if(isset($('hiddentickets'))&&$('hiddentickets').innerHTML==''){ret=false;msg.push(smessage[3]);}
if(isset($('rsepro_selected_tickets'))&&$('rsepro_selected_tickets').innerHTML==''){ret=false;msg.push(smessage[3]);}
if(!rse_validateEmail($('email').value)){ret=false;$('email').className='rs_edit_inp_error_small';msg.push(smessage[4]);}else{$('email').className='rs_edit_inp_small';}
if(ret)
return true;else{alert(msg.join("\n"));return false;}}
function rse_validateEmail(email){var regex=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;return regex.test(email);}
function rs_add_ticket(){var container=$('tickets');var hidden_tickets=$('hiddentickets');var ticket=isset($('RSEProTickets'))?$('RSEProTickets'):$('ticket');var ticket_number=parseInt($('from').value)==0?parseInt($('numberinp').value):parseInt($('number').value);var ticket_id=ticket.value;var ticket_name=ticket.options[ticket.selectedIndex].text;if(ticket_number==0)ticket_number=1;if(parseInt($('from').value)==1){var available_per_user=$('number').options.length;if(isset(document.getElementById('tickets'+ticket_id))){if(parseInt(document.getElementById('tickets'+ticket_id).value)+ticket_number>available_per_user){alert(smessage[6].replace('%d',available_per_user));return;}}}
if(isset($('hiddentickets'))&&typeof maxtickets!='undefined'&&typeof usedtickets!='undefined'){var total=0;$('hiddentickets').getElements('input').each(function(el){total+=parseInt(el.value);});total+=ticket_number;totalAvailable=parseInt(maxtickets)-parseInt(usedtickets);if(total>totalAvailable){alert(smessage[5]);return;}}
if(!isset(document.getElementById('tickets'+ticket_id))){input=document.createElement('input');input.setAttribute('type','hidden');input.setAttribute('name','tickets['+ticket_id+']');input.setAttribute('id','tickets'+ticket_id);input.setAttribute('value',ticket_number);hidden_tickets.appendChild(input);span=document.createElement('span');span.setAttribute('id','content'+ticket_id);span.innerHTML='<span id="ticketq'+ticket_id+'">'+ticket_number+'</span>'+' x '+ticket_name+' <a href="javascript:void(0);" onclick="rs_remove_ticket('+ticket_id+')"> ('+smessage[2]+')</a><br/>';container.appendChild(span);}else{$('ticketq'+ticket_id).innerHTML=parseInt($('ticketq'+ticket_id).innerHTML)+parseInt(ticket_number);$('tickets'+ticket_id).value=parseInt($('tickets'+ticket_id).value)+parseInt(ticket_number);}
rse_calculatetotal();}
function rs_remove_ticket(theid){if(isset($('tickets'+theid))){var container=$('tickets');var hidden_tickets=$('hiddentickets');container.removeChild($('content'+theid));hidden_tickets.removeChild($('tickets'+theid));rse_calculatetotal();}}
function rs_send_guests(){ret=true;if(document.getElementById('subject').value==''){ret=false;document.getElementById('subject').className='rs_edit_inp_error_small';}else{document.getElementById('subject').className='rs_edit_inp_small';}
var people=document.getElementById('subscribers');peopletrue=false;for(i=0;i<people.length;i++)
if(people[i].selected)
peopletrue=true;if(document.getElementById('denied').checked==false&&document.getElementById('pending').checked==false&&document.getElementById('accepted').checked==false&&!peopletrue){ret=false;document.getElementById('a_option').className='rs_error';document.getElementById('d_option').className='rs_error';document.getElementById('p_option').className='rs_error';}else{document.getElementById('a_option').className='';document.getElementById('d_option').className='';document.getElementById('p_option').className='';}
return ret;}
function rs_load(type){if(type=='gmail'){document.getElementById('rs_gmail_u').style.display='';document.getElementById('rs_gmail_p').style.display='';document.getElementById('rs_gmail_b').style.display='';document.getElementById('rs_yahoo_u').style.display='none';document.getElementById('rs_yahoo_p').style.display='none';document.getElementById('rs_yahoo_b').style.display='none';document.getElementById('ypassword').value='';document.getElementById('yusername').value='';}else{document.getElementById('rs_yahoo_u').style.display='';document.getElementById('rs_yahoo_p').style.display='';document.getElementById('rs_yahoo_b').style.display='';document.getElementById('rs_gmail_u').style.display='none';document.getElementById('rs_gmail_p').style.display='none';document.getElementById('rs_gmail_b').style.display='none';document.getElementById('gpassword').value='';document.getElementById('gusername').value='';}}
function rs_load_close(type){if(type=='gmail'){document.getElementById('rs_gmail_u').style.display='none';document.getElementById('rs_gmail_p').style.display='none';document.getElementById('rs_gmail_b').style.display='none';document.getElementById('gpassword').value='';document.getElementById('gusername').value='';}else{document.getElementById('rs_yahoo_u').style.display='none';document.getElementById('rs_yahoo_p').style.display='none';document.getElementById('rs_yahoo_b').style.display='none';document.getElementById('ypassword').value='';document.getElementById('yusername').value='';}}
function rs_invite(){ret=true;if(document.getElementById('from').value==''){ret=false;document.getElementById('from').className='rs_edit_inp_error_small';}else{document.getElementById('from').className='rs_edit_inp_small';}
if(document.getElementById('from_name').value==''){ret=false;document.getElementById('from_name').className='rs_edit_inp_error_small';}else{document.getElementById('from_name').className='rs_edit_inp_small';}
if(document.getElementById('emails').value==''){ret=false;document.getElementById('emails').className='rs_edit_txt_error';}else{document.getElementById('emails').className='rs_edit_txt';}
if(ret)
document.adminForm.submit();}
function rs_connect(type){var iuser='';var ipass='';if(type=='gmail'){iuser=document.getElementById('gusername').value;ipass=document.getElementById('gpassword').value;}else if(type=='yahoo'){iuser=document.getElementById('yusername').value;ipass=document.getElementById('ypassword').value;}
if(iuser==''||ipass==''){if(type=='gmail'){if(document.getElementById('gpassword').value=='')
document.getElementById('gpassword').className='rs_edit_inp_error_small';if(document.getElementById('gusername').value=='')
document.getElementById('gusername').className='rs_edit_inp_error_small';}else{if(document.getElementById('ypassword').value=='')
document.getElementById('ypassword').className='rs_edit_inp_error_small';if(document.getElementById('yusername').value=='')
document.getElementById('yusername').className='rs_edit_inp_error_small';}
alert(invitemessage[0]);return;}else{if(type=='gmail'){document.getElementById('gpassword').className='rs_edit_inp_small';document.getElementById('gusername').className='rs_edit_inp_small';}else{document.getElementById('ypassword').className='rs_edit_inp_small';document.getElementById('yusername').className='rs_edit_inp_small';}}
if(type=='gmail')
document.getElementById('img_gmail').style.display='';else
document.getElementById('img_yahoo').style.display='';rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=connect&type='+type+'&username='+iuser+'&password='+ipass+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);if(document.getElementById('emails').value=='')
document.getElementById('emails').value=response;else
document.getElementById('emails').value+="\n"+response;if(type=='gmail')
document.getElementById('img_gmail').style.display='none';else
document.getElementById('img_yahoo').style.display='none';}});req.send();}
function checkcaptcha(){rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=checkcaptcha&secret='+document.getElementById('secret').value+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);if(parseInt(response)){document.getElementById('secret').className='rs_edit_inp_small';rs_invite();}else{document.getElementById('secret').className='rs_edit_inp_error_small';}}});req.send();}
function reloadCaptcha(){document.getElementById('captcha').src=document.getElementById('captcha').src+'?'+Math.random();}
function rs_calendar_add_filter(name){if(name!=0){$('filter_from').value='categories';$('filter_condition').value='is';$('rseprosearch').value=name;}
$$('#rs_filters li input[value=categories]').each(function(el){el.getParent().dispose();});document.adminForm.submit();}
function cc_validate(card_message,ccv_message){var ret=true;var message='';var cc_number=document.getElementById('cc_number');var cc_ccv=document.getElementById('cc_ccv');var firstname=document.getElementById('firstname');var lastname=document.getElementById('lastname');if(cc_number.value.length<13||cc_number.value.length>16){ret=false;message+=card_message+"\n";cc_number.className+=" rs_error";}else{cc_number.className.replace(new RegExp(" rs_error\\b"),'');}
if(cc_ccv.value.length<3||cc_ccv.value.length>4){ret=false;message+=ccv_message+"\n";cc_ccv.className+=" rs_error";}else{cc_ccv.className.replace(new RegExp(" rs_error\\b"),'');}
if(firstname.value==''){ret=false;firstname.className+=" rs_error";}else{firstname.className.replace(new RegExp(" rs_error\\b"),'');}
if(lastname.value==''){ret=false;lastname.className+=" rs_error";}else{lastname.className.replace(new RegExp(" rs_error\\b"),'');}
if(message.length!=0)
alert(message);return ret;}
function rs_check_card(what){what.value=what.value.replace(/[^0-9]+/g,'');}
function rs_cc_form(){var has_error=false;var cc_number=document.getElementById('cc_number');var cc_length=cc_number.value.length;if(cc_length<14||cc_length>19){if(cc_number.className.indexOf(' rs_error')==-1)cc_number.className+=" rs_error";has_error=true;}else{cc_number.className=cc_number.className.replace(new RegExp(" rs_error\\b"),'');}
var csc_number=document.getElementById('cc_csc');if(csc_number.value.length<3){if(csc_number.className.indexOf(' rs_error')==-1)csc_number.className+=" rs_error";has_error=true;}else{csc_number.className=csc_number.className.replace(new RegExp(" rs_error\\b"),'');}
var cc_fname=document.getElementById('cc_fname');if(cc_fname.value.length==0){if(cc_fname.className.indexOf(' rs_error')==-1)cc_fname.className+=" rs_error";has_error=true;}else{cc_fname.className=cc_fname.className.replace(new RegExp(" rs_error\\b"),'');}
var cc_lname=document.getElementById('cc_lname');if(cc_lname.value.length==0){if(cc_lname.className.indexOf(' rs_error')==-1)cc_lname.className+=" rs_error";has_error=true;}else{cc_lname.className=cc_lname.className.replace(new RegExp(" rs_error\\b"),'');}
return has_error?false:true;}
function rs_calendar(root,month,year,module){document.getElementById('rscalendarmonth'+module).style.display='none';document.getElementById('rscalendar'+module).style.display='';var req=new Request({method:'post',url:root+'index.php?option=com_rseventspro',data:'view=calendar&layout=module&format=raw&month='+month+'&year='+year+'&mid='+module+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);document.getElementById('rs_calendar_module'+module).innerHTML=response;document.getElementById('rscalendarmonth'+module).style.display='';document.getElementById('rscalendar'+module).style.display='none';$$('.hasTip').each(function(el){var title=el.get('title');if(title){var parts=title.split('::',2);el.store('tip:title',parts[0]);el.store('tip:text',parts[1]);}});var JTooltips=new Tips($$('.hasTip'),{maxTitleChars:50,fixed:false});}});req.send();}
function rsepro_ajax_close(){RSEProSearch.slideOut();}
function rsepro_search(root,itemid,opener){rse_root=typeof rsepro_root!='undefined'?rsepro_root:'';var req=new Request({method:'post',url:rse_root+'index.php?option=com_rseventspro',data:'task=ajax&search='+$('rsepro_ajax').value+'&iid='+itemid+'&opener='+opener+'&randomTime='+Math.random(),onSuccess:function(responseText,responseXML){var response=responseText;var start=response.indexOf('RS_DELIMITER0')+13;var end=response.indexOf('RS_DELIMITER1');response=response.substring(start,end);if(response!=''){$('rsepro_ajax_list').set('html',response);RSEProSearch.slideIn();}else RSEProSearch.slideOut();}});req.send();}
function rs_check_dates(){if(isset(document.getElementById('enablestart'))){if(document.getElementById('enablestart').checked){document.getElementById('rsstart').disabled=false;document.getElementById('rsstart_img').style.display='';}else{document.getElementById('rsstart').disabled=true;document.getElementById('rsstart_img').style.display='none';}}
if(isset(document.getElementById('enableend'))){if(document.getElementById('enableend').checked){document.getElementById('rsend').disabled=false;document.getElementById('rsend_img').style.display='';}else{document.getElementById('rsend').disabled=true;document.getElementById('rsend_img').style.display='none';}}}
function rsepro_search_form_verification(){if(document.getElementById('rskeyword').value=='')
return false;return true;}
function rs_add_loc(){if(isset($('rs_location_window'))){$('rs_location_window').reveal({duration:'short'});$('rs_new_location').innerHTML=$('rs_location').value;}}
function show_more(){$('less').style.display='';$('more').style.display='none';$('rs_repeats').style.height='auto';}
function show_less(){$('less').style.display='none';$('more').style.display='';$('rs_repeats').style.height='70px';}
window.addEvent('domready',function(){if(isset($('rs_repeats_control')))
{if(parseInt($('rs_repeats').scrollHeight)>70)
$('rs_repeats_control').style.display='';}});var rs_tooltip=function(){var id='rs_tt';var top=3;var left=3;var maxw=400;var speed=10;var timer=20;var endalpha=95;var alpha=0;var tt,t,c,b,h;var ie=document.all?true:false;return{show:function(v,w){if(tt==null){tt=document.createElement('div');tt.setAttribute('id',id);t=document.createElement('div');t.setAttribute('id',id+'top');c=document.createElement('div');c.setAttribute('id',id+'cont');b=document.createElement('div');b.setAttribute('id',id+'bot');tt.appendChild(t);tt.appendChild(c);tt.appendChild(b);document.body.appendChild(tt);tt.style.opacity=0;tt.style.filter='alpha(opacity=0)';document.onmousemove=this.pos;}
tt.style.display='block';c.innerHTML=document.getElementById(v).innerHTML;tt.style.width=w?w+'px':'auto';if(!w&&ie){t.style.display='none';b.style.display='none';tt.style.width=tt.offsetWidth;t.style.display='block';b.style.display='block';}
if(tt.offsetWidth>maxw){tt.style.width=maxw+'px'}
h=parseInt(tt.offsetHeight)+top;clearInterval(tt.timer);tt.timer=setInterval(function(){rs_tooltip.fade(1)},timer);},pos:function(e){var u=ie?event.clientY+document.documentElement.scrollTop:e.pageY;var l=ie?event.clientX+document.documentElement.scrollLeft:e.pageX;tt.style.top=(u-h)+'px';tt.style.left=(l+left)+'px';},fade:function(d){var a=alpha;if((a!=endalpha&&d==1)||(a!=0&&d==-1)){var i=speed;if(endalpha-a<speed&&d==1){i=endalpha-a;}else if(alpha<speed&&d==-1){i=a;}
alpha=a+(i*d);tt.style.opacity=alpha*.01;tt.style.filter='alpha(opacity='+alpha+')';}else{clearInterval(tt.timer);if(d==-1){tt.style.display='none'}}},hide:function(){clearInterval(tt.timer);tt.timer=setInterval(function(){rs_tooltip.fade(-1)},timer);}};}();function rsepro_add_ticket(id,place,tname,tprice){if(window.dialogArguments){var thedocument=window.dialogArguments;}else{var thedocument=window.opener||window.parent;}
var seat_container=$('rsepro_seat_'+id+place);var selected_container=thedocument.$('rsepro_selected_tickets');var selected_view_container=thedocument.$('rsepro_selected_tickets_view');available_per_user=eval('ticket_limit_'+id);selected=thedocument.$$('#rsepro_selected_tickets input[name^=tickets['+id+']]').length;if(thedocument.multitickets==0){var ticketids=new Array();thedocument.$$('#rsepro_selected_tickets input[name^=tickets[]').each(function(el){theid=el.name.replace('tickets[','').replace('][]','');if(ticketids.indexOf(theid)==-1){ticketids.push(theid);}});thedocument.$$('#rsepro_selected_tickets input[name^=unlimited[]').each(function(el){theid=el.name.replace('unlimited[','').replace(']','');if(ticketids.indexOf(theid)==-1){ticketids.push(theid);}});if(ticketids.length>0&&ticketids.indexOf(id)==-1){if(!thedocument.$$('#rsepro_selected_tickets input[name^=unlimited[]').length){$$('input[id^=rsepro_unlimited_]').each(function(el){el.value='';});}
alert(thedocument.smessage[7]);return;}}
if(place==0){if(isset(thedocument.$('ticket'+id+place))){if($('rsepro_unlimited_'+id).value==0||$('rsepro_unlimited_'+id).value==''){selected_container.removeChild(thedocument.$('ticket'+id+place));}else{if(typeof thedocument.maxtickets!='undefined'&&typeof thedocument.usedtickets!='undefined'){var maxticketsAvailable=thedocument.maxtickets-thedocument.usedtickets;var thetotal=parseInt($$('.rsepro_selected').length);$$('input[id^=rsepro_unlimited_]').each(function(el){if(el.value!='')
thetotal+=parseInt(el.value);});if(thetotal>maxticketsAvailable){alert(thedocument.smessage[5]);return;}}
if($('rsepro_unlimited_'+id).value>available_per_user){$('rsepro_unlimited_'+id).value=available_per_user;alert(thedocument.smessage[6].replace('%d',available_per_user));return;}
thedocument.$('ticket'+id+place).value=$('rsepro_unlimited_'+id).value;}}else{if($('rsepro_unlimited_'+id).value!=0||$('rsepro_unlimited_'+id).value!=''){if(typeof thedocument.maxtickets!='undefined'&&typeof thedocument.usedtickets!='undefined'){var maxticketsAvailable=thedocument.maxtickets-thedocument.usedtickets;var thetotal=parseInt($$('.rsepro_selected').length);$$('input[id^=rsepro_unlimited_]').each(function(el){if(el.value!='')
thetotal+=parseInt(el.value);});if(thetotal>maxticketsAvailable){alert(thedocument.smessage[5]);return;}}
if($('rsepro_unlimited_'+id).value>available_per_user){alert(thedocument.smessage[6].replace('%d',available_per_user));$('rsepro_unlimited_'+id).value=available_per_user;return;}
if($('rsepro_unlimited_'+id).value>0){input=thedocument.document.createElement('input');input.setAttribute('type','hidden');input.setAttribute('name','unlimited['+id+']');input.setAttribute('id','ticket'+id+place);input.setAttribute('value',$('rsepro_unlimited_'+id).value);selected_container.appendChild(input);}}}}else{if(seat_container.hasClass('rsepro_selected')){seat_container.removeClass('rsepro_selected')
if(isset(thedocument.$('ticket'+id+place))){selected_container.removeChild(thedocument.$('ticket'+id+place));}}else{if(typeof thedocument.maxtickets!='undefined'&&typeof thedocument.usedtickets!='undefined'){var maxticketsAvailable=thedocument.maxtickets-thedocument.usedtickets;var thetotal=parseInt($$('.rsepro_selected').length);$$('input[id^=rsepro_unlimited_]').each(function(el){if(el.value!='')
thetotal+=parseInt(el.value);});if(thetotal>=maxticketsAvailable){alert(thedocument.smessage[5]);return;}}
if(selected+1>available_per_user){alert(thedocument.smessage[6].replace('%d',available_per_user));return;}
seat_container.addClass('rsepro_selected');input=thedocument.document.createElement('input');input.setAttribute('type','hidden');input.setAttribute('name','tickets['+id+'][]');input.setAttribute('id','ticket'+id+place);input.setAttribute('value',place);selected_container.appendChild(input);}}
if(!isset(thedocument.$('content'+id))){if(place==0){if($('rsepro_unlimited_'+id).value>0)
selected_view_container.innerHTML+='<span id="content'+id+'"><span id="rsepro_quantity'+id+'">'+$('rsepro_unlimited_'+id).value+'</span> x '+tname+' ('+tprice+') <br /> </span>';}else{selected_view_container.innerHTML+='<span id="content'+id+'"><span id="rsepro_quantity'+id+'">'+thedocument.$$('input[name^=tickets['+id+'][]]').length+'</span> x '+tname+' ('+tprice+') <span id="rsepro_seats'+id+'"></span><br /> </span>';}}else{if(place==0){if($('rsepro_unlimited_'+id).value==0)
selected_view_container.removeChild(thedocument.$('content'+id));else
thedocument.$('rsepro_quantity'+id).innerHTML=$('rsepro_unlimited_'+id).value;}else{if(thedocument.$$('input[name^=tickets['+id+'][]]').length==0)
selected_view_container.removeChild(thedocument.$('content'+id));else
thedocument.$('rsepro_quantity'+id).innerHTML=thedocument.$$('input[name^=tickets['+id+'][]]').length;}}
if(isset(thedocument.$('rsepro_seats'+id))){var seats=[];for(var t=0;t<thedocument.$$('input[name^=tickets['+id+'][]]').length;t++){seats.push(thedocument.$$('input[name^=tickets['+id+'][]]')[t].value);}
thedocument.$('rsepro_seats'+id).innerHTML=Joomla.JText._('COM_RSEVENTSPRO_SEATS').replace('%s',seats.join(', '));}
var total=0;thedocument.$$('span[id^=rsepro_quantity]').each(function(el){total+=parseInt(el.innerHTML);});if(total>0)
thedocument.$('rsepro_cart').innerHTML=total+' '+Joomla.JText._('COM_RSEVENTSPRO_TICKETS');else
thedocument.$('rsepro_cart').innerHTML=Joomla.JText._('COM_RSEVENTSPRO_SELECT_TICKETS');thedocument.rsepro_update_total();}
function rsepro_reset_tickets(text){if(window.dialogArguments){var thedocument=window.dialogArguments;}else{var thedocument=window.opener||window.parent;}
$$('.rsepro_selected').removeClass('rsepro_selected');$$('input[id^=rsepro_unlimited]').each(function(el){el.value='';});thedocument.$('rsepro_selected_tickets_view').innerHTML='';thedocument.$('rsepro_selected_tickets').innerHTML='';thedocument.$('rsepro_cart').innerHTML=text;thedocument.rsepro_update_total();}
function rsepro_update_total(){tickets='&dummy=1';$$('span[id^=rsepro_quantity]').each(function(el){tickets+='&tickets['+parseInt(el.id.replace('rsepro_quantity',''))+']='+parseInt(el.innerHTML);});rse_calculatetotal(tickets);}
function ajaxValidationRSEventsPro(task,formId,data){if(task=='beforeSend'){data.params.push('cid='+encodeURIComponent(document.getElementById('eventID').innerHTML));}}
function rsepro_validate_report(){if(document.getElementById('jform_report').value==''){document.getElementById('jform_report').addClass('invalid');return false;}
document.getElementById('jform_report').removeClass('invalid');return true;}


/*===============================
/sampo4/media/system/js/html5fallback.js
================================================================================*/;
!function(a,b,c){"use strict";"function"!=typeof Object.create&&(Object.create=function(a){function b(){}return b.prototype=a,new b});var d=function(a,b){for(var c=["required","pattern","placeholder","autofocus","formnovalidate"],d=["email","url","number","range"],e={attributes:{},types:{}};b=c.pop();)e.attributes[b]=!!(b in a);for(;b=d.pop();)a.setAttribute("type",b),e.types[b]=a.type==b;return e}(b.createElement("input")),e={init:function(b,c){var d=this;d.elem=c,d.$elem=a(c),c.H5Form=d,d.options=a.extend({},a.fn.h5f.options,b),"form"===c.nodeName.toLowerCase()&&d.bindWithForm(d.elem,d.$elem)},bindWithForm:function(a,b){var i,e=this,f=!!b.attr("novalidate"),g=a.elements,h=g.length;for("onSubmit"===e.options.formValidationEvent&&b.on("submit",function(a){i=this.H5Form.donotValidate!==c&&this.H5Form.donotValidate,i||f||e.validateForm(e)?b.find(":input").each(function(){e.placeholder(e,this,"submit")}):(a.preventDefault(),this.donotValidate=!1)}),b.on("focusout focusin",function(a){e.placeholder(e,a.target,a.type)}),b.on("focusout change",e.validateField),b.find("fieldset").on("change",function(){e.validateField(this)}),d.attributes.formnovalidate||b.find(":submit[formnovalidate]").on("click",function(){e.donotValidate=!0});h--;)e.polyfill(g[h]),e.autofocus(e,g[h])},polyfill:function(a){if("form"===a.nodeName.toLowerCase())return!0;var b=a.form.H5Form;b.placeholder(b,a),b.numberType(b,a)},validateForm:function(){var f,g,a=this,b=a.elem,c=b.elements,d=c.length,e=!0;for(b.isValid=!0,f=0;f<d;f++)g=c[f],g.isRequired=!!g.required,g.isDisabled&&(g.isDisabled=!!g.disabled),g.isDisabled||(e=a.validateField(g),b.isValid&&!e&&a.setFocusOn(g),b.isValid=e&&b.isValid);return a.options.doRenderMessage&&a.renderErrorMessages(a,b),b.isValid},validateField:function(b){var j,k,l,e=b.target||b,f=!1,g=!1,h=!1,i=!1;return e.form===c?null:(j=e.form.H5Form,k=a(e),g=!!k.attr("required"),h=!!k.attr("disabled"),e.isDisabled||(f=!d.attributes.required&&g&&j.isValueMissing(j,e),i=!d.attributes.pattern&&j.matchPattern(j,e)),e.validityState={valueMissing:f,patternMismatch:i,valid:e.isDisabled||!(f||i)},d.attributes.required||(e.validityState.valueMissing?k.addClass(j.options.requiredClass):k.removeClass(j.options.requiredClass)),d.attributespattern||(e.validityState.patternMismatch?k.addClass(j.options.patternClass):k.removeClass(j.options.patternClass)),e.validityState.valid?(k.removeClass(j.options.invalidClass),l=j.findLabel(k),l.removeClass(j.options.invalidClass),l.attr("aria-invalid","false")):(k.addClass(j.options.invalidClass),l=j.findLabel(k),l.addClass(j.options.invalidClass),l.attr("aria-invalid","true")),e.validityState.valid)},isValueMissing:function(e,f){var k,l,m,g=a(f),h=f.type!==c?f.type:f.tagName.toLowerCase(),i=/^(checkbox|radio|fieldset)$/i.test(h),j=/^submit$/i.test(h);if(j)return!1;if(i){if("checkbox"===h)return!g.is(":checked");for(k="fieldset"===h?g.find("input"):b.getElementsByName(f.name),l=0,m=k.length;l<m;l++)if(a(k[l]).is(":checked"))return!1;return!0}return!(""!==g.val()&&(d.attributes.placeholder||!g.hasClass(e.options.placeholderClass)))},matchPattern:function(b,e){var j,k,f=a(e),g=f.attr("value"),h=f.attr("pattern"),i=f.attr("type");if(!d.attributes.placeholder&&f.attr("placeholder")&&f.hasClass(b.options.placeholderClass)||(g=f.attr("value")),""===g)return!1;if("email"===i){if(f.attr("multiple")===c)return!b.options.emailPatt.test(g);for(g=g.split(b.options.mutipleDelimiter),j=0,k=g.length;j<k;j++)if(!b.options.emailPatt.test(g[j].replace(/[ ]*/g,"")))return!0}else{if("url"===i)return!b.options.urlPatt.test(g);if("text"===i&&h!==c)return usrPatt=new RegExp("^(?:"+h+")$"),!usrPatt.test(g)}return!1},placeholder:function(b,e,f){var g=a(e),h=g.attr("placeholder"),i=/^(focusin|submit)$/i.test(f),j=/^(input|textarea)$/i.test(e.nodeName),k=/^password$/i.test(e.type),l=d.attributes.placeholder;l||!j||k||h===c||(""!==e.value||i?e.value===h&&i&&(e.value="",g.removeClass(b.options.placeholderClass)):(e.value=h,g.addClass(b.options.placeholderClass)))},numberType:function(b,c){var i,j,k,l,m,n,o,p,e=a(c),f=e.attr("type"),g=/^input$/i.test(c.nodeName),h=/^(number|range)$/i.test(f);if(!(!g||!h||"number"==f&&d.types.number||"range"==f&&d.types.range)){for(i=parseInt(e.attr("min")),j=parseInt(e.attr("max")),k=parseInt(e.attr("step")),l=parseInt(e.attr("value")),m=e.prop("attributes"),n=a("<select>"),i=isNaN(i)?-100:i,p=i;p<=j;p+=k)o=a('<option value="'+p+'">'+p+"</option>"),(l==p||l>p&&l<p+k)&&o.attr("selected",""),n.append(o);a.each(m,function(){n.attr(this.name,this.value)}),e.replaceWith(n)}},autofocus:function(b,c){var e=a(c),f=!!e.attr("autofocus"),g=/^(input|textarea|select|fieldset)$/i.test(c.nodeName),h=/^submit$/i.test(c.type),i=d.attributes.autofocus;!i&&g&&!h&&f&&a(function(){b.setFocusOn(c)})},findLabel:function(b){var d,c=a('label[for="'+b.attr("id")+'"]');return c.length<=0&&(d=b.parent(),"label"==d.get(0).tagName.toLowerCase()&&(c=d)),c},setFocusOn:function(b){"fieldset"===b.tagName.toLowerCase()?a(b).find(":first").focus():a(b).focus()},renderErrorMessages:function(b,c){for(var g,h,d=c.elements,e=d.length,f={errors:[]};e--;)g=a(d[e]),h=b.findLabel(g),g.hasClass(b.options.requiredClass)&&(f.errors[e]=h.text().replace("*","")+b.options.requiredMessage),g.hasClass(b.options.patternClass)&&(f.errors[e]=h.text().replace("*","")+b.options.patternMessage);f.errors.length>0&&Joomla.renderMessages(f)}};a.fn.h5f=function(a){return this.each(function(){Object.create(e).init(a,this)})},a.fn.h5f.options={invalidClass:"invalid",requiredClass:"required",requiredMessage:" is required.",placeholderClass:"placeholder",patternClass:"pattern",patternMessage:" doesn't match pattern.",doRenderMessage:!1,formValidationEvent:"onSubmit",emailPatt:/^[a-zA-Z0-9.!#$%&‚Äô*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/,urlPatt:/[a-z][\-\.+a-z]*:\/\//i},a(function(){a("form").h5f({doRenderMessage:!0,requiredClass:"musthavevalue"})})}(jQuery,document);