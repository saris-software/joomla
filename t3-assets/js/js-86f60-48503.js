

/*===============================
/sampo4/media/jui/js/jquery.min.js
================================================================================*/;
/*! jQuery v1.12.4 | (c) jQuery Foundation | jquery.org/license */
!function(e,t){"object"==typeof module&&"object"==typeof module.exports?module.exports=e.document?t(e,!0):function(e){if(!e.document)throw new Error("jQuery requires a window with a document");return t(e)}:t(e)}("undefined"!=typeof window?window:this,function(e,t){var n=[],r=e.document,i=n.slice,o=n.concat,a=n.push,s=n.indexOf,u={},l=u.toString,c=u.hasOwnProperty,f={},d="1.12.4",p=function(e,t){return new p.fn.init(e,t)},h=/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,g=/^-ms-/,m=/-([\da-z])/gi,v=function(e,t){return t.toUpperCase()};function y(e){var t=!!e&&"length"in e&&e.length,n=p.type(e);return"function"!==n&&!p.isWindow(e)&&("array"===n||0===t||"number"==typeof t&&t>0&&t-1 in e)}p.fn=p.prototype={jquery:d,constructor:p,selector:"",length:0,toArray:function(){return i.call(this)},get:function(e){return null!=e?e<0?this[e+this.length]:this[e]:i.call(this)},pushStack:function(e){var t=p.merge(this.constructor(),e);return t.prevObject=this,t.context=this.context,t},each:function(e){return p.each(this,e)},map:function(e){return this.pushStack(p.map(this,function(t,n){return e.call(t,n,t)}))},slice:function(){return this.pushStack(i.apply(this,arguments))},first:function(){return this.eq(0)},last:function(){return this.eq(-1)},eq:function(e){var t=this.length,n=+e+(e<0?t:0);return this.pushStack(n>=0&&n<t?[this[n]]:[])},end:function(){return this.prevObject||this.constructor()},push:a,sort:n.sort,splice:n.splice},p.extend=p.fn.extend=function(){var e,t,n,r,i,o,a=arguments[0]||{},s=1,u=arguments.length,l=!1;for("boolean"==typeof a&&(l=a,a=arguments[s]||{},s++),"object"==typeof a||p.isFunction(a)||(a={}),s===u&&(a=this,s--);s<u;s++)if(null!=(i=arguments[s]))for(r in i)e=a[r],a!==(n=i[r])&&(l&&n&&(p.isPlainObject(n)||(t=p.isArray(n)))?(t?(t=!1,o=e&&p.isArray(e)?e:[]):o=e&&p.isPlainObject(e)?e:{},a[r]=p.extend(l,o,n)):void 0!==n&&(a[r]=n));return a},p.extend({expando:"jQuery"+(d+Math.random()).replace(/\D/g,""),isReady:!0,error:function(e){throw new Error(e)},noop:function(){},isFunction:function(e){return"function"===p.type(e)},isArray:Array.isArray||function(e){return"array"===p.type(e)},isWindow:function(e){return null!=e&&e==e.window},isNumeric:function(e){var t=e&&e.toString();return!p.isArray(e)&&t-parseFloat(t)+1>=0},isEmptyObject:function(e){var t;for(t in e)return!1;return!0},isPlainObject:function(e){var t;if(!e||"object"!==p.type(e)||e.nodeType||p.isWindow(e))return!1;try{if(e.constructor&&!c.call(e,"constructor")&&!c.call(e.constructor.prototype,"isPrototypeOf"))return!1}catch(e){return!1}if(!f.ownFirst)for(t in e)return c.call(e,t);for(t in e);return void 0===t||c.call(e,t)},type:function(e){return null==e?e+"":"object"==typeof e||"function"==typeof e?u[l.call(e)]||"object":typeof e},globalEval:function(t){t&&p.trim(t)&&(e.execScript||function(t){e.eval.call(e,t)})(t)},camelCase:function(e){return e.replace(g,"ms-").replace(m,v)},nodeName:function(e,t){return e.nodeName&&e.nodeName.toLowerCase()===t.toLowerCase()},each:function(e,t){var n,r=0;if(y(e))for(n=e.length;r<n&&!1!==t.call(e[r],r,e[r]);r++);else for(r in e)if(!1===t.call(e[r],r,e[r]))break;return e},trim:function(e){return null==e?"":(e+"").replace(h,"")},makeArray:function(e,t){var n=t||[];return null!=e&&(y(Object(e))?p.merge(n,"string"==typeof e?[e]:e):a.call(n,e)),n},inArray:function(e,t,n){var r;if(t){if(s)return s.call(t,e,n);for(r=t.length,n=n?n<0?Math.max(0,r+n):n:0;n<r;n++)if(n in t&&t[n]===e)return n}return-1},merge:function(e,t){for(var n=+t.length,r=0,i=e.length;r<n;)e[i++]=t[r++];if(n!=n)for(;void 0!==t[r];)e[i++]=t[r++];return e.length=i,e},grep:function(e,t,n){for(var r=[],i=0,o=e.length,a=!n;i<o;i++)!t(e[i],i)!==a&&r.push(e[i]);return r},map:function(e,t,n){var r,i,a=0,s=[];if(y(e))for(r=e.length;a<r;a++)null!=(i=t(e[a],a,n))&&s.push(i);else for(a in e)null!=(i=t(e[a],a,n))&&s.push(i);return o.apply([],s)},guid:1,proxy:function(e,t){var n,r,o;if("string"==typeof t&&(o=e[t],t=e,e=o),p.isFunction(e))return n=i.call(arguments,2),(r=function(){return e.apply(t||this,n.concat(i.call(arguments)))}).guid=e.guid=e.guid||p.guid++,r},now:function(){return+new Date},support:f}),"function"==typeof Symbol&&(p.fn[Symbol.iterator]=n[Symbol.iterator]),p.each("Boolean Number String Function Array Date RegExp Object Error Symbol".split(" "),function(e,t){u["[object "+t+"]"]=t.toLowerCase()});var x=function(e){var t,n,r,i,o,a,s,u,l,c,f,d,p,h,g,m,v,y,x,b="sizzle"+1*new Date,w=e.document,T=0,C=0,E=oe(),N=oe(),k=oe(),S=function(e,t){return e===t&&(f=!0),0},A=1<<31,D={}.hasOwnProperty,j=[],L=j.pop,H=j.push,q=j.push,_=j.slice,F=function(e,t){for(var n=0,r=e.length;n<r;n++)if(e[n]===t)return n;return-1},M="checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",O="[\\x20\\t\\r\\n\\f]",R="(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",P="\\["+O+"*("+R+")(?:"+O+"*([*^$|!~]?=)"+O+"*(?:'((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\"|("+R+"))|)"+O+"*\\]",B=":("+R+")(?:\\((('((?:\\\\.|[^\\\\'])*)'|\"((?:\\\\.|[^\\\\\"])*)\")|((?:\\\\.|[^\\\\()[\\]]|"+P+")*)|.*)\\)|)",W=new RegExp(O+"+","g"),I=new RegExp("^"+O+"+|((?:^|[^\\\\])(?:\\\\.)*)"+O+"+$","g"),$=new RegExp("^"+O+"*,"+O+"*"),z=new RegExp("^"+O+"*([>+~]|"+O+")"+O+"*"),X=new RegExp("="+O+"*([^\\]'\"]*?)"+O+"*\\]","g"),U=new RegExp(B),V=new RegExp("^"+R+"$"),Y={ID:new RegExp("^#("+R+")"),CLASS:new RegExp("^\\.("+R+")"),TAG:new RegExp("^("+R+"|[*])"),ATTR:new RegExp("^"+P),PSEUDO:new RegExp("^"+B),CHILD:new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\("+O+"*(even|odd|(([+-]|)(\\d*)n|)"+O+"*(?:([+-]|)"+O+"*(\\d+)|))"+O+"*\\)|)","i"),bool:new RegExp("^(?:"+M+")$","i"),needsContext:new RegExp("^"+O+"*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\("+O+"*((?:-\\d)?\\d*)"+O+"*\\)|)(?=[^-]|$)","i")},J=/^(?:input|select|textarea|button)$/i,G=/^h\d$/i,Q=/^[^{]+\{\s*\[native \w/,K=/^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,Z=/[+~]/,ee=/'|\\/g,te=new RegExp("\\\\([\\da-f]{1,6}"+O+"?|("+O+")|.)","ig"),ne=function(e,t,n){var r="0x"+t-65536;return r!=r||n?t:r<0?String.fromCharCode(r+65536):String.fromCharCode(r>>10|55296,1023&r|56320)},re=function(){d()};try{q.apply(j=_.call(w.childNodes),w.childNodes),j[w.childNodes.length].nodeType}catch(e){q={apply:j.length?function(e,t){H.apply(e,_.call(t))}:function(e,t){for(var n=e.length,r=0;e[n++]=t[r++];);e.length=n-1}}}function ie(e,t,r,i){var o,s,l,c,f,h,v,y,T=t&&t.ownerDocument,C=t?t.nodeType:9;if(r=r||[],"string"!=typeof e||!e||1!==C&&9!==C&&11!==C)return r;if(!i&&((t?t.ownerDocument||t:w)!==p&&d(t),t=t||p,g)){if(11!==C&&(h=K.exec(e)))if(o=h[1]){if(9===C){if(!(l=t.getElementById(o)))return r;if(l.id===o)return r.push(l),r}else if(T&&(l=T.getElementById(o))&&x(t,l)&&l.id===o)return r.push(l),r}else{if(h[2])return q.apply(r,t.getElementsByTagName(e)),r;if((o=h[3])&&n.getElementsByClassName&&t.getElementsByClassName)return q.apply(r,t.getElementsByClassName(o)),r}if(n.qsa&&!k[e+" "]&&(!m||!m.test(e))){if(1!==C)T=t,y=e;else if("object"!==t.nodeName.toLowerCase()){for((c=t.getAttribute("id"))?c=c.replace(ee,"\\$&"):t.setAttribute("id",c=b),s=(v=a(e)).length,f=V.test(c)?"#"+c:"[id='"+c+"']";s--;)v[s]=f+" "+ge(v[s]);y=v.join(","),T=Z.test(e)&&pe(t.parentNode)||t}if(y)try{return q.apply(r,T.querySelectorAll(y)),r}catch(e){}finally{c===b&&t.removeAttribute("id")}}}return u(e.replace(I,"$1"),t,r,i)}function oe(){var e=[];return function t(n,i){return e.push(n+" ")>r.cacheLength&&delete t[e.shift()],t[n+" "]=i}}function ae(e){return e[b]=!0,e}function se(e){var t=p.createElement("div");try{return!!e(t)}catch(e){return!1}finally{t.parentNode&&t.parentNode.removeChild(t),t=null}}function ue(e,t){for(var n=e.split("|"),i=n.length;i--;)r.attrHandle[n[i]]=t}function le(e,t){var n=t&&e,r=n&&1===e.nodeType&&1===t.nodeType&&(~t.sourceIndex||A)-(~e.sourceIndex||A);if(r)return r;if(n)for(;n=n.nextSibling;)if(n===t)return-1;return e?1:-1}function ce(e){return function(t){return"input"===t.nodeName.toLowerCase()&&t.type===e}}function fe(e){return function(t){var n=t.nodeName.toLowerCase();return("input"===n||"button"===n)&&t.type===e}}function de(e){return ae(function(t){return t=+t,ae(function(n,r){for(var i,o=e([],n.length,t),a=o.length;a--;)n[i=o[a]]&&(n[i]=!(r[i]=n[i]))})})}function pe(e){return e&&void 0!==e.getElementsByTagName&&e}n=ie.support={},o=ie.isXML=function(e){var t=e&&(e.ownerDocument||e).documentElement;return!!t&&"HTML"!==t.nodeName},d=ie.setDocument=function(e){var t,i,a=e?e.ownerDocument||e:w;return a!==p&&9===a.nodeType&&a.documentElement?(h=(p=a).documentElement,g=!o(p),(i=p.defaultView)&&i.top!==i&&(i.addEventListener?i.addEventListener("unload",re,!1):i.attachEvent&&i.attachEvent("onunload",re)),n.attributes=se(function(e){return e.className="i",!e.getAttribute("className")}),n.getElementsByTagName=se(function(e){return e.appendChild(p.createComment("")),!e.getElementsByTagName("*").length}),n.getElementsByClassName=Q.test(p.getElementsByClassName),n.getById=se(function(e){return h.appendChild(e).id=b,!p.getElementsByName||!p.getElementsByName(b).length}),n.getById?(r.find.ID=function(e,t){if(void 0!==t.getElementById&&g){var n=t.getElementById(e);return n?[n]:[]}},r.filter.ID=function(e){var t=e.replace(te,ne);return function(e){return e.getAttribute("id")===t}}):(delete r.find.ID,r.filter.ID=function(e){var t=e.replace(te,ne);return function(e){var n=void 0!==e.getAttributeNode&&e.getAttributeNode("id");return n&&n.value===t}}),r.find.TAG=n.getElementsByTagName?function(e,t){return void 0!==t.getElementsByTagName?t.getElementsByTagName(e):n.qsa?t.querySelectorAll(e):void 0}:function(e,t){var n,r=[],i=0,o=t.getElementsByTagName(e);if("*"===e){for(;n=o[i++];)1===n.nodeType&&r.push(n);return r}return o},r.find.CLASS=n.getElementsByClassName&&function(e,t){if(void 0!==t.getElementsByClassName&&g)return t.getElementsByClassName(e)},v=[],m=[],(n.qsa=Q.test(p.querySelectorAll))&&(se(function(e){h.appendChild(e).innerHTML="<a id='"+b+"'></a><select id='"+b+"-\r\\' msallowcapture=''><option selected=''></option></select>",e.querySelectorAll("[msallowcapture^='']").length&&m.push("[*^$]="+O+"*(?:''|\"\")"),e.querySelectorAll("[selected]").length||m.push("\\["+O+"*(?:value|"+M+")"),e.querySelectorAll("[id~="+b+"-]").length||m.push("~="),e.querySelectorAll(":checked").length||m.push(":checked"),e.querySelectorAll("a#"+b+"+*").length||m.push(".#.+[+~]")}),se(function(e){var t=p.createElement("input");t.setAttribute("type","hidden"),e.appendChild(t).setAttribute("name","D"),e.querySelectorAll("[name=d]").length&&m.push("name"+O+"*[*^$|!~]?="),e.querySelectorAll(":enabled").length||m.push(":enabled",":disabled"),e.querySelectorAll("*,:x"),m.push(",.*:")})),(n.matchesSelector=Q.test(y=h.matches||h.webkitMatchesSelector||h.mozMatchesSelector||h.oMatchesSelector||h.msMatchesSelector))&&se(function(e){n.disconnectedMatch=y.call(e,"div"),y.call(e,"[s!='']:x"),v.push("!=",B)}),m=m.length&&new RegExp(m.join("|")),v=v.length&&new RegExp(v.join("|")),t=Q.test(h.compareDocumentPosition),x=t||Q.test(h.contains)?function(e,t){var n=9===e.nodeType?e.documentElement:e,r=t&&t.parentNode;return e===r||!(!r||1!==r.nodeType||!(n.contains?n.contains(r):e.compareDocumentPosition&&16&e.compareDocumentPosition(r)))}:function(e,t){if(t)for(;t=t.parentNode;)if(t===e)return!0;return!1},S=t?function(e,t){if(e===t)return f=!0,0;var r=!e.compareDocumentPosition-!t.compareDocumentPosition;return r||(1&(r=(e.ownerDocument||e)===(t.ownerDocument||t)?e.compareDocumentPosition(t):1)||!n.sortDetached&&t.compareDocumentPosition(e)===r?e===p||e.ownerDocument===w&&x(w,e)?-1:t===p||t.ownerDocument===w&&x(w,t)?1:c?F(c,e)-F(c,t):0:4&r?-1:1)}:function(e,t){if(e===t)return f=!0,0;var n,r=0,i=e.parentNode,o=t.parentNode,a=[e],s=[t];if(!i||!o)return e===p?-1:t===p?1:i?-1:o?1:c?F(c,e)-F(c,t):0;if(i===o)return le(e,t);for(n=e;n=n.parentNode;)a.unshift(n);for(n=t;n=n.parentNode;)s.unshift(n);for(;a[r]===s[r];)r++;return r?le(a[r],s[r]):a[r]===w?-1:s[r]===w?1:0},p):p},ie.matches=function(e,t){return ie(e,null,null,t)},ie.matchesSelector=function(e,t){if((e.ownerDocument||e)!==p&&d(e),t=t.replace(X,"='$1']"),n.matchesSelector&&g&&!k[t+" "]&&(!v||!v.test(t))&&(!m||!m.test(t)))try{var r=y.call(e,t);if(r||n.disconnectedMatch||e.document&&11!==e.document.nodeType)return r}catch(e){}return ie(t,p,null,[e]).length>0},ie.contains=function(e,t){return(e.ownerDocument||e)!==p&&d(e),x(e,t)},ie.attr=function(e,t){(e.ownerDocument||e)!==p&&d(e);var i=r.attrHandle[t.toLowerCase()],o=i&&D.call(r.attrHandle,t.toLowerCase())?i(e,t,!g):void 0;return void 0!==o?o:n.attributes||!g?e.getAttribute(t):(o=e.getAttributeNode(t))&&o.specified?o.value:null},ie.error=function(e){throw new Error("Syntax error, unrecognized expression: "+e)},ie.uniqueSort=function(e){var t,r=[],i=0,o=0;if(f=!n.detectDuplicates,c=!n.sortStable&&e.slice(0),e.sort(S),f){for(;t=e[o++];)t===e[o]&&(i=r.push(o));for(;i--;)e.splice(r[i],1)}return c=null,e},i=ie.getText=function(e){var t,n="",r=0,o=e.nodeType;if(o){if(1===o||9===o||11===o){if("string"==typeof e.textContent)return e.textContent;for(e=e.firstChild;e;e=e.nextSibling)n+=i(e)}else if(3===o||4===o)return e.nodeValue}else for(;t=e[r++];)n+=i(t);return n},(r=ie.selectors={cacheLength:50,createPseudo:ae,match:Y,attrHandle:{},find:{},relative:{">":{dir:"parentNode",first:!0}," ":{dir:"parentNode"},"+":{dir:"previousSibling",first:!0},"~":{dir:"previousSibling"}},preFilter:{ATTR:function(e){return e[1]=e[1].replace(te,ne),e[3]=(e[3]||e[4]||e[5]||"").replace(te,ne),"~="===e[2]&&(e[3]=" "+e[3]+" "),e.slice(0,4)},CHILD:function(e){return e[1]=e[1].toLowerCase(),"nth"===e[1].slice(0,3)?(e[3]||ie.error(e[0]),e[4]=+(e[4]?e[5]+(e[6]||1):2*("even"===e[3]||"odd"===e[3])),e[5]=+(e[7]+e[8]||"odd"===e[3])):e[3]&&ie.error(e[0]),e},PSEUDO:function(e){var t,n=!e[6]&&e[2];return Y.CHILD.test(e[0])?null:(e[3]?e[2]=e[4]||e[5]||"":n&&U.test(n)&&(t=a(n,!0))&&(t=n.indexOf(")",n.length-t)-n.length)&&(e[0]=e[0].slice(0,t),e[2]=n.slice(0,t)),e.slice(0,3))}},filter:{TAG:function(e){var t=e.replace(te,ne).toLowerCase();return"*"===e?function(){return!0}:function(e){return e.nodeName&&e.nodeName.toLowerCase()===t}},CLASS:function(e){var t=E[e+" "];return t||(t=new RegExp("(^|"+O+")"+e+"("+O+"|$)"))&&E(e,function(e){return t.test("string"==typeof e.className&&e.className||void 0!==e.getAttribute&&e.getAttribute("class")||"")})},ATTR:function(e,t,n){return function(r){var i=ie.attr(r,e);return null==i?"!="===t:!t||(i+="","="===t?i===n:"!="===t?i!==n:"^="===t?n&&0===i.indexOf(n):"*="===t?n&&i.indexOf(n)>-1:"$="===t?n&&i.slice(-n.length)===n:"~="===t?(" "+i.replace(W," ")+" ").indexOf(n)>-1:"|="===t&&(i===n||i.slice(0,n.length+1)===n+"-"))}},CHILD:function(e,t,n,r,i){var o="nth"!==e.slice(0,3),a="last"!==e.slice(-4),s="of-type"===t;return 1===r&&0===i?function(e){return!!e.parentNode}:function(t,n,u){var l,c,f,d,p,h,g=o!==a?"nextSibling":"previousSibling",m=t.parentNode,v=s&&t.nodeName.toLowerCase(),y=!u&&!s,x=!1;if(m){if(o){for(;g;){for(d=t;d=d[g];)if(s?d.nodeName.toLowerCase()===v:1===d.nodeType)return!1;h=g="only"===e&&!h&&"nextSibling"}return!0}if(h=[a?m.firstChild:m.lastChild],a&&y){for(x=(p=(l=(c=(f=(d=m)[b]||(d[b]={}))[d.uniqueID]||(f[d.uniqueID]={}))[e]||[])[0]===T&&l[1])&&l[2],d=p&&m.childNodes[p];d=++p&&d&&d[g]||(x=p=0)||h.pop();)if(1===d.nodeType&&++x&&d===t){c[e]=[T,p,x];break}}else if(y&&(x=p=(l=(c=(f=(d=t)[b]||(d[b]={}))[d.uniqueID]||(f[d.uniqueID]={}))[e]||[])[0]===T&&l[1]),!1===x)for(;(d=++p&&d&&d[g]||(x=p=0)||h.pop())&&((s?d.nodeName.toLowerCase()!==v:1!==d.nodeType)||!++x||(y&&((c=(f=d[b]||(d[b]={}))[d.uniqueID]||(f[d.uniqueID]={}))[e]=[T,x]),d!==t)););return(x-=i)===r||x%r==0&&x/r>=0}}},PSEUDO:function(e,t){var n,i=r.pseudos[e]||r.setFilters[e.toLowerCase()]||ie.error("unsupported pseudo: "+e);return i[b]?i(t):i.length>1?(n=[e,e,"",t],r.setFilters.hasOwnProperty(e.toLowerCase())?ae(function(e,n){for(var r,o=i(e,t),a=o.length;a--;)e[r=F(e,o[a])]=!(n[r]=o[a])}):function(e){return i(e,0,n)}):i}},pseudos:{not:ae(function(e){var t=[],n=[],r=s(e.replace(I,"$1"));return r[b]?ae(function(e,t,n,i){for(var o,a=r(e,null,i,[]),s=e.length;s--;)(o=a[s])&&(e[s]=!(t[s]=o))}):function(e,i,o){return t[0]=e,r(t,null,o,n),t[0]=null,!n.pop()}}),has:ae(function(e){return function(t){return ie(e,t).length>0}}),contains:ae(function(e){return e=e.replace(te,ne),function(t){return(t.textContent||t.innerText||i(t)).indexOf(e)>-1}}),lang:ae(function(e){return V.test(e||"")||ie.error("unsupported lang: "+e),e=e.replace(te,ne).toLowerCase(),function(t){var n;do{if(n=g?t.lang:t.getAttribute("xml:lang")||t.getAttribute("lang"))return(n=n.toLowerCase())===e||0===n.indexOf(e+"-")}while((t=t.parentNode)&&1===t.nodeType);return!1}}),target:function(t){var n=e.location&&e.location.hash;return n&&n.slice(1)===t.id},root:function(e){return e===h},focus:function(e){return e===p.activeElement&&(!p.hasFocus||p.hasFocus())&&!!(e.type||e.href||~e.tabIndex)},enabled:function(e){return!1===e.disabled},disabled:function(e){return!0===e.disabled},checked:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&!!e.checked||"option"===t&&!!e.selected},selected:function(e){return e.parentNode&&e.parentNode.selectedIndex,!0===e.selected},empty:function(e){for(e=e.firstChild;e;e=e.nextSibling)if(e.nodeType<6)return!1;return!0},parent:function(e){return!r.pseudos.empty(e)},header:function(e){return G.test(e.nodeName)},input:function(e){return J.test(e.nodeName)},button:function(e){var t=e.nodeName.toLowerCase();return"input"===t&&"button"===e.type||"button"===t},text:function(e){var t;return"input"===e.nodeName.toLowerCase()&&"text"===e.type&&(null==(t=e.getAttribute("type"))||"text"===t.toLowerCase())},first:de(function(){return[0]}),last:de(function(e,t){return[t-1]}),eq:de(function(e,t,n){return[n<0?n+t:n]}),even:de(function(e,t){for(var n=0;n<t;n+=2)e.push(n);return e}),odd:de(function(e,t){for(var n=1;n<t;n+=2)e.push(n);return e}),lt:de(function(e,t,n){for(var r=n<0?n+t:n;--r>=0;)e.push(r);return e}),gt:de(function(e,t,n){for(var r=n<0?n+t:n;++r<t;)e.push(r);return e})}}).pseudos.nth=r.pseudos.eq;for(t in{radio:!0,checkbox:!0,file:!0,password:!0,image:!0})r.pseudos[t]=ce(t);for(t in{submit:!0,reset:!0})r.pseudos[t]=fe(t);function he(){}function ge(e){for(var t=0,n=e.length,r="";t<n;t++)r+=e[t].value;return r}function me(e,t,n){var r=t.dir,i=n&&"parentNode"===r,o=C++;return t.first?function(t,n,o){for(;t=t[r];)if(1===t.nodeType||i)return e(t,n,o)}:function(t,n,a){var s,u,l,c=[T,o];if(a){for(;t=t[r];)if((1===t.nodeType||i)&&e(t,n,a))return!0}else for(;t=t[r];)if(1===t.nodeType||i){if((s=(u=(l=t[b]||(t[b]={}))[t.uniqueID]||(l[t.uniqueID]={}))[r])&&s[0]===T&&s[1]===o)return c[2]=s[2];if(u[r]=c,c[2]=e(t,n,a))return!0}}}function ve(e){return e.length>1?function(t,n,r){for(var i=e.length;i--;)if(!e[i](t,n,r))return!1;return!0}:e[0]}function ye(e,t,n,r,i){for(var o,a=[],s=0,u=e.length,l=null!=t;s<u;s++)(o=e[s])&&(n&&!n(o,r,i)||(a.push(o),l&&t.push(s)));return a}function xe(e,t,n,r,i,o){return r&&!r[b]&&(r=xe(r)),i&&!i[b]&&(i=xe(i,o)),ae(function(o,a,s,u){var l,c,f,d=[],p=[],h=a.length,g=o||function(e,t,n){for(var r=0,i=t.length;r<i;r++)ie(e,t[r],n);return n}(t||"*",s.nodeType?[s]:s,[]),m=!e||!o&&t?g:ye(g,d,e,s,u),v=n?i||(o?e:h||r)?[]:a:m;if(n&&n(m,v,s,u),r)for(l=ye(v,p),r(l,[],s,u),c=l.length;c--;)(f=l[c])&&(v[p[c]]=!(m[p[c]]=f));if(o){if(i||e){if(i){for(l=[],c=v.length;c--;)(f=v[c])&&l.push(m[c]=f);i(null,v=[],l,u)}for(c=v.length;c--;)(f=v[c])&&(l=i?F(o,f):d[c])>-1&&(o[l]=!(a[l]=f))}}else v=ye(v===a?v.splice(h,v.length):v),i?i(null,a,v,u):q.apply(a,v)})}function be(e){for(var t,n,i,o=e.length,a=r.relative[e[0].type],s=a||r.relative[" "],u=a?1:0,c=me(function(e){return e===t},s,!0),f=me(function(e){return F(t,e)>-1},s,!0),d=[function(e,n,r){var i=!a&&(r||n!==l)||((t=n).nodeType?c(e,n,r):f(e,n,r));return t=null,i}];u<o;u++)if(n=r.relative[e[u].type])d=[me(ve(d),n)];else{if((n=r.filter[e[u].type].apply(null,e[u].matches))[b]){for(i=++u;i<o&&!r.relative[e[i].type];i++);return xe(u>1&&ve(d),u>1&&ge(e.slice(0,u-1).concat({value:" "===e[u-2].type?"*":""})).replace(I,"$1"),n,u<i&&be(e.slice(u,i)),i<o&&be(e=e.slice(i)),i<o&&ge(e))}d.push(n)}return ve(d)}return he.prototype=r.filters=r.pseudos,r.setFilters=new he,a=ie.tokenize=function(e,t){var n,i,o,a,s,u,l,c=N[e+" "];if(c)return t?0:c.slice(0);for(s=e,u=[],l=r.preFilter;s;){n&&!(i=$.exec(s))||(i&&(s=s.slice(i[0].length)||s),u.push(o=[])),n=!1,(i=z.exec(s))&&(n=i.shift(),o.push({value:n,type:i[0].replace(I," ")}),s=s.slice(n.length));for(a in r.filter)!(i=Y[a].exec(s))||l[a]&&!(i=l[a](i))||(n=i.shift(),o.push({value:n,type:a,matches:i}),s=s.slice(n.length));if(!n)break}return t?s.length:s?ie.error(e):N(e,u).slice(0)},s=ie.compile=function(e,t){var n,i,o,s,u,c,f=[],h=[],m=k[e+" "];if(!m){for(t||(t=a(e)),n=t.length;n--;)(m=be(t[n]))[b]?f.push(m):h.push(m);(m=k(e,(i=h,s=(o=f).length>0,u=i.length>0,c=function(e,t,n,a,c){var f,h,m,v=0,y="0",x=e&&[],b=[],w=l,C=e||u&&r.find.TAG("*",c),E=T+=null==w?1:Math.random()||.1,N=C.length;for(c&&(l=t===p||t||c);y!==N&&null!=(f=C[y]);y++){if(u&&f){for(h=0,t||f.ownerDocument===p||(d(f),n=!g);m=i[h++];)if(m(f,t||p,n)){a.push(f);break}c&&(T=E)}s&&((f=!m&&f)&&v--,e&&x.push(f))}if(v+=y,s&&y!==v){for(h=0;m=o[h++];)m(x,b,t,n);if(e){if(v>0)for(;y--;)x[y]||b[y]||(b[y]=L.call(a));b=ye(b)}q.apply(a,b),c&&!e&&b.length>0&&v+o.length>1&&ie.uniqueSort(a)}return c&&(T=E,l=w),x},s?ae(c):c))).selector=e}return m},u=ie.select=function(e,t,i,o){var u,l,c,f,d,p="function"==typeof e&&e,h=!o&&a(e=p.selector||e);if(i=i||[],1===h.length){if((l=h[0]=h[0].slice(0)).length>2&&"ID"===(c=l[0]).type&&n.getById&&9===t.nodeType&&g&&r.relative[l[1].type]){if(!(t=(r.find.ID(c.matches[0].replace(te,ne),t)||[])[0]))return i;p&&(t=t.parentNode),e=e.slice(l.shift().value.length)}for(u=Y.needsContext.test(e)?0:l.length;u--&&(c=l[u],!r.relative[f=c.type]);)if((d=r.find[f])&&(o=d(c.matches[0].replace(te,ne),Z.test(l[0].type)&&pe(t.parentNode)||t))){if(l.splice(u,1),!(e=o.length&&ge(l)))return q.apply(i,o),i;break}}return(p||s(e,h))(o,t,!g,i,!t||Z.test(e)&&pe(t.parentNode)||t),i},n.sortStable=b.split("").sort(S).join("")===b,n.detectDuplicates=!!f,d(),n.sortDetached=se(function(e){return 1&e.compareDocumentPosition(p.createElement("div"))}),se(function(e){return e.innerHTML="<a href='#'></a>","#"===e.firstChild.getAttribute("href")})||ue("type|href|height|width",function(e,t,n){if(!n)return e.getAttribute(t,"type"===t.toLowerCase()?1:2)}),n.attributes&&se(function(e){return e.innerHTML="<input/>",e.firstChild.setAttribute("value",""),""===e.firstChild.getAttribute("value")})||ue("value",function(e,t,n){if(!n&&"input"===e.nodeName.toLowerCase())return e.defaultValue}),se(function(e){return null==e.getAttribute("disabled")})||ue(M,function(e,t,n){var r;if(!n)return!0===e[t]?t.toLowerCase():(r=e.getAttributeNode(t))&&r.specified?r.value:null}),ie}(e);p.find=x,p.expr=x.selectors,p.expr[":"]=p.expr.pseudos,p.uniqueSort=p.unique=x.uniqueSort,p.text=x.getText,p.isXMLDoc=x.isXML,p.contains=x.contains;var b=function(e,t,n){for(var r=[],i=void 0!==n;(e=e[t])&&9!==e.nodeType;)if(1===e.nodeType){if(i&&p(e).is(n))break;r.push(e)}return r},w=function(e,t){for(var n=[];e;e=e.nextSibling)1===e.nodeType&&e!==t&&n.push(e);return n},T=p.expr.match.needsContext,C=/^<([\w-]+)\s*\/?>(?:<\/\1>|)$/,E=/^.[^:#\[\.,]*$/;function N(e,t,n){if(p.isFunction(t))return p.grep(e,function(e,r){return!!t.call(e,r,e)!==n});if(t.nodeType)return p.grep(e,function(e){return e===t!==n});if("string"==typeof t){if(E.test(t))return p.filter(t,e,n);t=p.filter(t,e)}return p.grep(e,function(e){return p.inArray(e,t)>-1!==n})}p.filter=function(e,t,n){var r=t[0];return n&&(e=":not("+e+")"),1===t.length&&1===r.nodeType?p.find.matchesSelector(r,e)?[r]:[]:p.find.matches(e,p.grep(t,function(e){return 1===e.nodeType}))},p.fn.extend({find:function(e){var t,n=[],r=this,i=r.length;if("string"!=typeof e)return this.pushStack(p(e).filter(function(){for(t=0;t<i;t++)if(p.contains(r[t],this))return!0}));for(t=0;t<i;t++)p.find(e,r[t],n);return(n=this.pushStack(i>1?p.unique(n):n)).selector=this.selector?this.selector+" "+e:e,n},filter:function(e){return this.pushStack(N(this,e||[],!1))},not:function(e){return this.pushStack(N(this,e||[],!0))},is:function(e){return!!N(this,"string"==typeof e&&T.test(e)?p(e):e||[],!1).length}});var k,S=/^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/;(p.fn.init=function(e,t,n){var i,o;if(!e)return this;if(n=n||k,"string"==typeof e){if(!(i="<"===e.charAt(0)&&">"===e.charAt(e.length-1)&&e.length>=3?[null,e,null]:S.exec(e))||!i[1]&&t)return!t||t.jquery?(t||n).find(e):this.constructor(t).find(e);if(i[1]){if(t=t instanceof p?t[0]:t,p.merge(this,p.parseHTML(i[1],t&&t.nodeType?t.ownerDocument||t:r,!0)),C.test(i[1])&&p.isPlainObject(t))for(i in t)p.isFunction(this[i])?this[i](t[i]):this.attr(i,t[i]);return this}if((o=r.getElementById(i[2]))&&o.parentNode){if(o.id!==i[2])return k.find(e);this.length=1,this[0]=o}return this.context=r,this.selector=e,this}return e.nodeType?(this.context=this[0]=e,this.length=1,this):p.isFunction(e)?void 0!==n.ready?n.ready(e):e(p):(void 0!==e.selector&&(this.selector=e.selector,this.context=e.context),p.makeArray(e,this))}).prototype=p.fn,k=p(r);var A=/^(?:parents|prev(?:Until|All))/,D={children:!0,contents:!0,next:!0,prev:!0};function j(e,t){do{e=e[t]}while(e&&1!==e.nodeType);return e}p.fn.extend({has:function(e){var t,n=p(e,this),r=n.length;return this.filter(function(){for(t=0;t<r;t++)if(p.contains(this,n[t]))return!0})},closest:function(e,t){for(var n,r=0,i=this.length,o=[],a=T.test(e)||"string"!=typeof e?p(e,t||this.context):0;r<i;r++)for(n=this[r];n&&n!==t;n=n.parentNode)if(n.nodeType<11&&(a?a.index(n)>-1:1===n.nodeType&&p.find.matchesSelector(n,e))){o.push(n);break}return this.pushStack(o.length>1?p.uniqueSort(o):o)},index:function(e){return e?"string"==typeof e?p.inArray(this[0],p(e)):p.inArray(e.jquery?e[0]:e,this):this[0]&&this[0].parentNode?this.first().prevAll().length:-1},add:function(e,t){return this.pushStack(p.uniqueSort(p.merge(this.get(),p(e,t))))},addBack:function(e){return this.add(null==e?this.prevObject:this.prevObject.filter(e))}}),p.each({parent:function(e){var t=e.parentNode;return t&&11!==t.nodeType?t:null},parents:function(e){return b(e,"parentNode")},parentsUntil:function(e,t,n){return b(e,"parentNode",n)},next:function(e){return j(e,"nextSibling")},prev:function(e){return j(e,"previousSibling")},nextAll:function(e){return b(e,"nextSibling")},prevAll:function(e){return b(e,"previousSibling")},nextUntil:function(e,t,n){return b(e,"nextSibling",n)},prevUntil:function(e,t,n){return b(e,"previousSibling",n)},siblings:function(e){return w((e.parentNode||{}).firstChild,e)},children:function(e){return w(e.firstChild)},contents:function(e){return p.nodeName(e,"iframe")?e.contentDocument||e.contentWindow.document:p.merge([],e.childNodes)}},function(e,t){p.fn[e]=function(n,r){var i=p.map(this,t,n);return"Until"!==e.slice(-5)&&(r=n),r&&"string"==typeof r&&(i=p.filter(r,i)),this.length>1&&(D[e]||(i=p.uniqueSort(i)),A.test(e)&&(i=i.reverse())),this.pushStack(i)}});var L,H,q=/\S+/g;function _(){r.addEventListener?(r.removeEventListener("DOMContentLoaded",F),e.removeEventListener("load",F)):(r.detachEvent("onreadystatechange",F),e.detachEvent("onload",F))}function F(){(r.addEventListener||"load"===e.event.type||"complete"===r.readyState)&&(_(),p.ready())}p.Callbacks=function(e){var t,n;e="string"==typeof e?(t=e,n={},p.each(t.match(q)||[],function(e,t){n[t]=!0}),n):p.extend({},e);var r,i,o,a,s=[],u=[],l=-1,c=function(){for(a=e.once,o=r=!0;u.length;l=-1)for(i=u.shift();++l<s.length;)!1===s[l].apply(i[0],i[1])&&e.stopOnFalse&&(l=s.length,i=!1);e.memory||(i=!1),r=!1,a&&(s=i?[]:"")},f={add:function(){return s&&(i&&!r&&(l=s.length-1,u.push(i)),function t(n){p.each(n,function(n,r){p.isFunction(r)?e.unique&&f.has(r)||s.push(r):r&&r.length&&"string"!==p.type(r)&&t(r)})}(arguments),i&&!r&&c()),this},remove:function(){return p.each(arguments,function(e,t){for(var n;(n=p.inArray(t,s,n))>-1;)s.splice(n,1),n<=l&&l--}),this},has:function(e){return e?p.inArray(e,s)>-1:s.length>0},empty:function(){return s&&(s=[]),this},disable:function(){return a=u=[],s=i="",this},disabled:function(){return!s},lock:function(){return a=!0,i||f.disable(),this},locked:function(){return!!a},fireWith:function(e,t){return a||(t=[e,(t=t||[]).slice?t.slice():t],u.push(t),r||c()),this},fire:function(){return f.fireWith(this,arguments),this},fired:function(){return!!o}};return f},p.extend({Deferred:function(e){var t=[["resolve","done",p.Callbacks("once memory"),"resolved"],["reject","fail",p.Callbacks("once memory"),"rejected"],["notify","progress",p.Callbacks("memory")]],n="pending",r={state:function(){return n},always:function(){return i.done(arguments).fail(arguments),this},then:function(){var e=arguments;return p.Deferred(function(n){p.each(t,function(t,o){var a=p.isFunction(e[t])&&e[t];i[o[1]](function(){var e=a&&a.apply(this,arguments);e&&p.isFunction(e.promise)?e.promise().progress(n.notify).done(n.resolve).fail(n.reject):n[o[0]+"With"](this===r?n.promise():this,a?[e]:arguments)})}),e=null}).promise()},promise:function(e){return null!=e?p.extend(e,r):r}},i={};return r.pipe=r.then,p.each(t,function(e,o){var a=o[2],s=o[3];r[o[1]]=a.add,s&&a.add(function(){n=s},t[1^e][2].disable,t[2][2].lock),i[o[0]]=function(){return i[o[0]+"With"](this===i?r:this,arguments),this},i[o[0]+"With"]=a.fireWith}),r.promise(i),e&&e.call(i,i),i},when:function(e){var t,n,r,o=0,a=i.call(arguments),s=a.length,u=1!==s||e&&p.isFunction(e.promise)?s:0,l=1===u?e:p.Deferred(),c=function(e,n,r){return function(o){n[e]=this,r[e]=arguments.length>1?i.call(arguments):o,r===t?l.notifyWith(n,r):--u||l.resolveWith(n,r)}};if(s>1)for(t=new Array(s),n=new Array(s),r=new Array(s);o<s;o++)a[o]&&p.isFunction(a[o].promise)?a[o].promise().progress(c(o,n,t)).done(c(o,r,a)).fail(l.reject):--u;return u||l.resolveWith(r,a),l.promise()}}),p.fn.ready=function(e){return p.ready.promise().done(e),this},p.extend({isReady:!1,readyWait:1,holdReady:function(e){e?p.readyWait++:p.ready(!0)},ready:function(e){(!0===e?--p.readyWait:p.isReady)||(p.isReady=!0,!0!==e&&--p.readyWait>0||(L.resolveWith(r,[p]),p.fn.triggerHandler&&(p(r).triggerHandler("ready"),p(r).off("ready"))))}}),p.ready.promise=function(t){if(!L)if(L=p.Deferred(),"complete"===r.readyState||"loading"!==r.readyState&&!r.documentElement.doScroll)e.setTimeout(p.ready);else if(r.addEventListener)r.addEventListener("DOMContentLoaded",F),e.addEventListener("load",F);else{r.attachEvent("onreadystatechange",F),e.attachEvent("onload",F);var n=!1;try{n=null==e.frameElement&&r.documentElement}catch(e){}n&&n.doScroll&&function t(){if(!p.isReady){try{n.doScroll("left")}catch(n){return e.setTimeout(t,50)}_(),p.ready()}}()}return L.promise(t)},p.ready.promise();for(H in p(f))break;f.ownFirst="0"===H,f.inlineBlockNeedsLayout=!1,p(function(){var e,t,n,i;(n=r.getElementsByTagName("body")[0])&&n.style&&(t=r.createElement("div"),(i=r.createElement("div")).style.cssText="position:absolute;border:0;width:0;height:0;top:0;left:-9999px",n.appendChild(i).appendChild(t),void 0!==t.style.zoom&&(t.style.cssText="display:inline;margin:0;border:0;padding:1px;width:1px;zoom:1",f.inlineBlockNeedsLayout=e=3===t.offsetWidth,e&&(n.style.zoom=1)),n.removeChild(i))}),function(){var e=r.createElement("div");f.deleteExpando=!0;try{delete e.test}catch(e){f.deleteExpando=!1}e=null}();var M,O=function(e){var t=p.noData[(e.nodeName+" ").toLowerCase()],n=+e.nodeType||1;return(1===n||9===n)&&(!t||!0!==t&&e.getAttribute("classid")===t)},R=/^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,P=/([A-Z])/g;function B(e,t,n){if(void 0===n&&1===e.nodeType){var r="data-"+t.replace(P,"-$1").toLowerCase();if("string"==typeof(n=e.getAttribute(r))){try{n="true"===n||"false"!==n&&("null"===n?null:+n+""===n?+n:R.test(n)?p.parseJSON(n):n)}catch(e){}p.data(e,t,n)}else n=void 0}return n}function W(e){var t;for(t in e)if(("data"!==t||!p.isEmptyObject(e[t]))&&"toJSON"!==t)return!1;return!0}function I(e,t,r,i){if(O(e)){var o,a,s=p.expando,u=e.nodeType,l=u?p.cache:e,c=u?e[s]:e[s]&&s;if(c&&l[c]&&(i||l[c].data)||void 0!==r||"string"!=typeof t)return c||(c=u?e[s]=n.pop()||p.guid++:s),l[c]||(l[c]=u?{}:{toJSON:p.noop}),"object"!=typeof t&&"function"!=typeof t||(i?l[c]=p.extend(l[c],t):l[c].data=p.extend(l[c].data,t)),a=l[c],i||(a.data||(a.data={}),a=a.data),void 0!==r&&(a[p.camelCase(t)]=r),"string"==typeof t?null==(o=a[t])&&(o=a[p.camelCase(t)]):o=a,o}}function $(e,t,n){if(O(e)){var r,i,o=e.nodeType,a=o?p.cache:e,s=o?e[p.expando]:p.expando;if(a[s]){if(t&&(r=n?a[s]:a[s].data)){i=(t=p.isArray(t)?t.concat(p.map(t,p.camelCase)):t in r?[t]:(t=p.camelCase(t))in r?[t]:t.split(" ")).length;for(;i--;)delete r[t[i]];if(n?!W(r):!p.isEmptyObject(r))return}(n||(delete a[s].data,W(a[s])))&&(o?p.cleanData([e],!0):f.deleteExpando||a!=a.window?delete a[s]:a[s]=void 0)}}}p.extend({cache:{},noData:{"applet ":!0,"embed ":!0,"object ":"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"},hasData:function(e){return!!(e=e.nodeType?p.cache[e[p.expando]]:e[p.expando])&&!W(e)},data:function(e,t,n){return I(e,t,n)},removeData:function(e,t){return $(e,t)},_data:function(e,t,n){return I(e,t,n,!0)},_removeData:function(e,t){return $(e,t,!0)}}),p.fn.extend({data:function(e,t){var n,r,i,o=this[0],a=o&&o.attributes;if(void 0===e){if(this.length&&(i=p.data(o),1===o.nodeType&&!p._data(o,"parsedAttrs"))){for(n=a.length;n--;)a[n]&&0===(r=a[n].name).indexOf("data-")&&B(o,r=p.camelCase(r.slice(5)),i[r]);p._data(o,"parsedAttrs",!0)}return i}return"object"==typeof e?this.each(function(){p.data(this,e)}):arguments.length>1?this.each(function(){p.data(this,e,t)}):o?B(o,e,p.data(o,e)):void 0},removeData:function(e){return this.each(function(){p.removeData(this,e)})}}),p.extend({queue:function(e,t,n){var r;if(e)return t=(t||"fx")+"queue",r=p._data(e,t),n&&(!r||p.isArray(n)?r=p._data(e,t,p.makeArray(n)):r.push(n)),r||[]},dequeue:function(e,t){t=t||"fx";var n=p.queue(e,t),r=n.length,i=n.shift(),o=p._queueHooks(e,t);"inprogress"===i&&(i=n.shift(),r--),i&&("fx"===t&&n.unshift("inprogress"),delete o.stop,i.call(e,function(){p.dequeue(e,t)},o)),!r&&o&&o.empty.fire()},_queueHooks:function(e,t){var n=t+"queueHooks";return p._data(e,n)||p._data(e,n,{empty:p.Callbacks("once memory").add(function(){p._removeData(e,t+"queue"),p._removeData(e,n)})})}}),p.fn.extend({queue:function(e,t){var n=2;return"string"!=typeof e&&(t=e,e="fx",n--),arguments.length<n?p.queue(this[0],e):void 0===t?this:this.each(function(){var n=p.queue(this,e,t);p._queueHooks(this,e),"fx"===e&&"inprogress"!==n[0]&&p.dequeue(this,e)})},dequeue:function(e){return this.each(function(){p.dequeue(this,e)})},clearQueue:function(e){return this.queue(e||"fx",[])},promise:function(e,t){var n,r=1,i=p.Deferred(),o=this,a=this.length,s=function(){--r||i.resolveWith(o,[o])};for("string"!=typeof e&&(t=e,e=void 0),e=e||"fx";a--;)(n=p._data(o[a],e+"queueHooks"))&&n.empty&&(r++,n.empty.add(s));return s(),i.promise(t)}}),f.shrinkWrapBlocks=function(){return null!=M?M:(M=!1,(t=r.getElementsByTagName("body")[0])&&t.style?(e=r.createElement("div"),(n=r.createElement("div")).style.cssText="position:absolute;border:0;width:0;height:0;top:0;left:-9999px",t.appendChild(n).appendChild(e),void 0!==e.style.zoom&&(e.style.cssText="-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:1px;width:1px;zoom:1",e.appendChild(r.createElement("div")).style.width="5px",M=3!==e.offsetWidth),t.removeChild(n),M):void 0);var e,t,n};var z=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,X=new RegExp("^(?:([+-])=|)("+z+")([a-z%]*)$","i"),U=["Top","Right","Bottom","Left"],V=function(e,t){return e=t||e,"none"===p.css(e,"display")||!p.contains(e.ownerDocument,e)};function Y(e,t,n,r){var i,o=1,a=20,s=r?function(){return r.cur()}:function(){return p.css(e,t,"")},u=s(),l=n&&n[3]||(p.cssNumber[t]?"":"px"),c=(p.cssNumber[t]||"px"!==l&&+u)&&X.exec(p.css(e,t));if(c&&c[3]!==l){l=l||c[3],n=n||[],c=+u||1;do{c/=o=o||".5",p.style(e,t,c+l)}while(o!==(o=s()/u)&&1!==o&&--a)}return n&&(c=+c||+u||0,i=n[1]?c+(n[1]+1)*n[2]:+n[2],r&&(r.unit=l,r.start=c,r.end=i)),i}var J,G,Q,K=function(e,t,n,r,i,o,a){var s=0,u=e.length,l=null==n;if("object"===p.type(n)){i=!0;for(s in n)K(e,t,s,n[s],!0,o,a)}else if(void 0!==r&&(i=!0,p.isFunction(r)||(a=!0),l&&(a?(t.call(e,r),t=null):(l=t,t=function(e,t,n){return l.call(p(e),n)})),t))for(;s<u;s++)t(e[s],n,a?r:r.call(e[s],s,t(e[s],n)));return i?e:l?t.call(e):u?t(e[0],n):o},Z=/^(?:checkbox|radio)$/i,ee=/<([\w:-]+)/,te=/^$|\/(?:java|ecma)script/i,ne=/^\s+/,re="abbr|article|aside|audio|bdi|canvas|data|datalist|details|dialog|figcaption|figure|footer|header|hgroup|main|mark|meter|nav|output|picture|progress|section|summary|template|time|video";function ie(e){var t=re.split("|"),n=e.createDocumentFragment();if(n.createElement)for(;t.length;)n.createElement(t.pop());return n}J=r.createElement("div"),G=r.createDocumentFragment(),Q=r.createElement("input"),J.innerHTML="  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",f.leadingWhitespace=3===J.firstChild.nodeType,f.tbody=!J.getElementsByTagName("tbody").length,f.htmlSerialize=!!J.getElementsByTagName("link").length,f.html5Clone="<:nav></:nav>"!==r.createElement("nav").cloneNode(!0).outerHTML,Q.type="checkbox",Q.checked=!0,G.appendChild(Q),f.appendChecked=Q.checked,J.innerHTML="<textarea>x</textarea>",f.noCloneChecked=!!J.cloneNode(!0).lastChild.defaultValue,G.appendChild(J),(Q=r.createElement("input")).setAttribute("type","radio"),Q.setAttribute("checked","checked"),Q.setAttribute("name","t"),J.appendChild(Q),f.checkClone=J.cloneNode(!0).cloneNode(!0).lastChild.checked,f.noCloneEvent=!!J.addEventListener,J[p.expando]=1,f.attributes=!J.getAttribute(p.expando);var oe={option:[1,"<select multiple='multiple'>","</select>"],legend:[1,"<fieldset>","</fieldset>"],area:[1,"<map>","</map>"],param:[1,"<object>","</object>"],thead:[1,"<table>","</table>"],tr:[2,"<table><tbody>","</tbody></table>"],col:[2,"<table><tbody></tbody><colgroup>","</colgroup></table>"],td:[3,"<table><tbody><tr>","</tr></tbody></table>"],_default:f.htmlSerialize?[0,"",""]:[1,"X<div>","</div>"]};function ae(e,t){var n,r,i=0,o=void 0!==e.getElementsByTagName?e.getElementsByTagName(t||"*"):void 0!==e.querySelectorAll?e.querySelectorAll(t||"*"):void 0;if(!o)for(o=[],n=e.childNodes||e;null!=(r=n[i]);i++)!t||p.nodeName(r,t)?o.push(r):p.merge(o,ae(r,t));return void 0===t||t&&p.nodeName(e,t)?p.merge([e],o):o}function se(e,t){for(var n,r=0;null!=(n=e[r]);r++)p._data(n,"globalEval",!t||p._data(t[r],"globalEval"))}oe.optgroup=oe.option,oe.tbody=oe.tfoot=oe.colgroup=oe.caption=oe.thead,oe.th=oe.td;var ue=/<|&#?\w+;/,le=/<tbody/i;function ce(e){Z.test(e.type)&&(e.defaultChecked=e.checked)}function fe(e,t,n,r,i){for(var o,a,s,u,l,c,d,h=e.length,g=ie(t),m=[],v=0;v<h;v++)if((a=e[v])||0===a)if("object"===p.type(a))p.merge(m,a.nodeType?[a]:a);else if(ue.test(a)){for(u=u||g.appendChild(t.createElement("div")),l=(ee.exec(a)||["",""])[1].toLowerCase(),d=oe[l]||oe._default,u.innerHTML=d[1]+p.htmlPrefilter(a)+d[2],o=d[0];o--;)u=u.lastChild;if(!f.leadingWhitespace&&ne.test(a)&&m.push(t.createTextNode(ne.exec(a)[0])),!f.tbody)for(o=(a="table"!==l||le.test(a)?"<table>"!==d[1]||le.test(a)?0:u:u.firstChild)&&a.childNodes.length;o--;)p.nodeName(c=a.childNodes[o],"tbody")&&!c.childNodes.length&&a.removeChild(c);for(p.merge(m,u.childNodes),u.textContent="";u.firstChild;)u.removeChild(u.firstChild);u=g.lastChild}else m.push(t.createTextNode(a));for(u&&g.removeChild(u),f.appendChecked||p.grep(ae(m,"input"),ce),v=0;a=m[v++];)if(r&&p.inArray(a,r)>-1)i&&i.push(a);else if(s=p.contains(a.ownerDocument,a),u=ae(g.appendChild(a),"script"),s&&se(u),n)for(o=0;a=u[o++];)te.test(a.type||"")&&n.push(a);return u=null,g}!function(){var t,n,i=r.createElement("div");for(t in{submit:!0,change:!0,focusin:!0})n="on"+t,(f[t]=n in e)||(i.setAttribute(n,"t"),f[t]=!1===i.attributes[n].expando);i=null}();var de=/^(?:input|select|textarea)$/i,pe=/^key/,he=/^(?:mouse|pointer|contextmenu|drag|drop)|click/,ge=/^(?:focusinfocus|focusoutblur)$/,me=/^([^.]*)(?:\.(.+)|)/;function ve(){return!0}function ye(){return!1}function xe(){try{return r.activeElement}catch(e){}}function be(e,t,n,r,i,o){var a,s;if("object"==typeof t){"string"!=typeof n&&(r=r||n,n=void 0);for(s in t)be(e,s,n,r,t[s],o);return e}if(null==r&&null==i?(i=n,r=n=void 0):null==i&&("string"==typeof n?(i=r,r=void 0):(i=r,r=n,n=void 0)),!1===i)i=ye;else if(!i)return e;return 1===o&&(a=i,(i=function(e){return p().off(e),a.apply(this,arguments)}).guid=a.guid||(a.guid=p.guid++)),e.each(function(){p.event.add(this,t,i,r,n)})}p.event={global:{},add:function(e,t,n,r,i){var o,a,s,u,l,c,f,d,h,g,m,v=p._data(e);if(v){for(n.handler&&(n=(u=n).handler,i=u.selector),n.guid||(n.guid=p.guid++),(a=v.events)||(a=v.events={}),(c=v.handle)||((c=v.handle=function(e){return void 0===p||e&&p.event.triggered===e.type?void 0:p.event.dispatch.apply(c.elem,arguments)}).elem=e),s=(t=(t||"").match(q)||[""]).length;s--;)h=m=(o=me.exec(t[s])||[])[1],g=(o[2]||"").split(".").sort(),h&&(l=p.event.special[h]||{},h=(i?l.delegateType:l.bindType)||h,l=p.event.special[h]||{},f=p.extend({type:h,origType:m,data:r,handler:n,guid:n.guid,selector:i,needsContext:i&&p.expr.match.needsContext.test(i),namespace:g.join(".")},u),(d=a[h])||((d=a[h]=[]).delegateCount=0,l.setup&&!1!==l.setup.call(e,r,g,c)||(e.addEventListener?e.addEventListener(h,c,!1):e.attachEvent&&e.attachEvent("on"+h,c))),l.add&&(l.add.call(e,f),f.handler.guid||(f.handler.guid=n.guid)),i?d.splice(d.delegateCount++,0,f):d.push(f),p.event.global[h]=!0);e=null}},remove:function(e,t,n,r,i){var o,a,s,u,l,c,f,d,h,g,m,v=p.hasData(e)&&p._data(e);if(v&&(c=v.events)){for(l=(t=(t||"").match(q)||[""]).length;l--;)if(h=m=(s=me.exec(t[l])||[])[1],g=(s[2]||"").split(".").sort(),h){for(f=p.event.special[h]||{},d=c[h=(r?f.delegateType:f.bindType)||h]||[],s=s[2]&&new RegExp("(^|\\.)"+g.join("\\.(?:.*\\.|)")+"(\\.|$)"),u=o=d.length;o--;)a=d[o],!i&&m!==a.origType||n&&n.guid!==a.guid||s&&!s.test(a.namespace)||r&&r!==a.selector&&("**"!==r||!a.selector)||(d.splice(o,1),a.selector&&d.delegateCount--,f.remove&&f.remove.call(e,a));u&&!d.length&&(f.teardown&&!1!==f.teardown.call(e,g,v.handle)||p.removeEvent(e,h,v.handle),delete c[h])}else for(h in c)p.event.remove(e,h+t[l],n,r,!0);p.isEmptyObject(c)&&(delete v.handle,p._removeData(e,"events"))}},trigger:function(t,n,i,o){var a,s,u,l,f,d,h,g=[i||r],m=c.call(t,"type")?t.type:t,v=c.call(t,"namespace")?t.namespace.split("."):[];if(u=d=i=i||r,3!==i.nodeType&&8!==i.nodeType&&!ge.test(m+p.event.triggered)&&(m.indexOf(".")>-1&&(m=(v=m.split(".")).shift(),v.sort()),s=m.indexOf(":")<0&&"on"+m,(t=t[p.expando]?t:new p.Event(m,"object"==typeof t&&t)).isTrigger=o?2:3,t.namespace=v.join("."),t.rnamespace=t.namespace?new RegExp("(^|\\.)"+v.join("\\.(?:.*\\.|)")+"(\\.|$)"):null,t.result=void 0,t.target||(t.target=i),n=null==n?[t]:p.makeArray(n,[t]),f=p.event.special[m]||{},o||!f.trigger||!1!==f.trigger.apply(i,n))){if(!o&&!f.noBubble&&!p.isWindow(i)){for(l=f.delegateType||m,ge.test(l+m)||(u=u.parentNode);u;u=u.parentNode)g.push(u),d=u;d===(i.ownerDocument||r)&&g.push(d.defaultView||d.parentWindow||e)}for(h=0;(u=g[h++])&&!t.isPropagationStopped();)t.type=h>1?l:f.bindType||m,(a=(p._data(u,"events")||{})[t.type]&&p._data(u,"handle"))&&a.apply(u,n),(a=s&&u[s])&&a.apply&&O(u)&&(t.result=a.apply(u,n),!1===t.result&&t.preventDefault());if(t.type=m,!o&&!t.isDefaultPrevented()&&(!f._default||!1===f._default.apply(g.pop(),n))&&O(i)&&s&&i[m]&&!p.isWindow(i)){(d=i[s])&&(i[s]=null),p.event.triggered=m;try{i[m]()}catch(e){}p.event.triggered=void 0,d&&(i[s]=d)}return t.result}},dispatch:function(e){e=p.event.fix(e);var t,n,r,o,a,s,u=i.call(arguments),l=(p._data(this,"events")||{})[e.type]||[],c=p.event.special[e.type]||{};if(u[0]=e,e.delegateTarget=this,!c.preDispatch||!1!==c.preDispatch.call(this,e)){for(s=p.event.handlers.call(this,e,l),t=0;(o=s[t++])&&!e.isPropagationStopped();)for(e.currentTarget=o.elem,n=0;(a=o.handlers[n++])&&!e.isImmediatePropagationStopped();)e.rnamespace&&!e.rnamespace.test(a.namespace)||(e.handleObj=a,e.data=a.data,void 0!==(r=((p.event.special[a.origType]||{}).handle||a.handler).apply(o.elem,u))&&!1===(e.result=r)&&(e.preventDefault(),e.stopPropagation()));return c.postDispatch&&c.postDispatch.call(this,e),e.result}},handlers:function(e,t){var n,r,i,o,a=[],s=t.delegateCount,u=e.target;if(s&&u.nodeType&&("click"!==e.type||isNaN(e.button)||e.button<1))for(;u!=this;u=u.parentNode||this)if(1===u.nodeType&&(!0!==u.disabled||"click"!==e.type)){for(r=[],n=0;n<s;n++)void 0===r[i=(o=t[n]).selector+" "]&&(r[i]=o.needsContext?p(i,this).index(u)>-1:p.find(i,this,null,[u]).length),r[i]&&r.push(o);r.length&&a.push({elem:u,handlers:r})}return s<t.length&&a.push({elem:this,handlers:t.slice(s)}),a},fix:function(e){if(e[p.expando])return e;var t,n,i,o=e.type,a=e,s=this.fixHooks[o];for(s||(this.fixHooks[o]=s=he.test(o)?this.mouseHooks:pe.test(o)?this.keyHooks:{}),i=s.props?this.props.concat(s.props):this.props,e=new p.Event(a),t=i.length;t--;)e[n=i[t]]=a[n];return e.target||(e.target=a.srcElement||r),3===e.target.nodeType&&(e.target=e.target.parentNode),e.metaKey=!!e.metaKey,s.filter?s.filter(e,a):e},props:"altKey bubbles cancelable ctrlKey currentTarget detail eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),fixHooks:{},keyHooks:{props:"char charCode key keyCode".split(" "),filter:function(e,t){return null==e.which&&(e.which=null!=t.charCode?t.charCode:t.keyCode),e}},mouseHooks:{props:"button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),filter:function(e,t){var n,i,o,a=t.button,s=t.fromElement;return null==e.pageX&&null!=t.clientX&&(o=(i=e.target.ownerDocument||r).documentElement,n=i.body,e.pageX=t.clientX+(o&&o.scrollLeft||n&&n.scrollLeft||0)-(o&&o.clientLeft||n&&n.clientLeft||0),e.pageY=t.clientY+(o&&o.scrollTop||n&&n.scrollTop||0)-(o&&o.clientTop||n&&n.clientTop||0)),!e.relatedTarget&&s&&(e.relatedTarget=s===e.target?t.toElement:s),e.which||void 0===a||(e.which=1&a?1:2&a?3:4&a?2:0),e}},special:{load:{noBubble:!0},focus:{trigger:function(){if(this!==xe()&&this.focus)try{return this.focus(),!1}catch(e){}},delegateType:"focusin"},blur:{trigger:function(){if(this===xe()&&this.blur)return this.blur(),!1},delegateType:"focusout"},click:{trigger:function(){if(p.nodeName(this,"input")&&"checkbox"===this.type&&this.click)return this.click(),!1},_default:function(e){return p.nodeName(e.target,"a")}},beforeunload:{postDispatch:function(e){void 0!==e.result&&e.originalEvent&&(e.originalEvent.returnValue=e.result)}}},simulate:function(e,t,n){var r=p.extend(new p.Event,n,{type:e,isSimulated:!0});p.event.trigger(r,null,t),r.isDefaultPrevented()&&n.preventDefault()}},p.removeEvent=r.removeEventListener?function(e,t,n){e.removeEventListener&&e.removeEventListener(t,n)}:function(e,t,n){var r="on"+t;e.detachEvent&&(void 0===e[r]&&(e[r]=null),e.detachEvent(r,n))},p.Event=function(e,t){if(!(this instanceof p.Event))return new p.Event(e,t);e&&e.type?(this.originalEvent=e,this.type=e.type,this.isDefaultPrevented=e.defaultPrevented||void 0===e.defaultPrevented&&!1===e.returnValue?ve:ye):this.type=e,t&&p.extend(this,t),this.timeStamp=e&&e.timeStamp||p.now(),this[p.expando]=!0},p.Event.prototype={constructor:p.Event,isDefaultPrevented:ye,isPropagationStopped:ye,isImmediatePropagationStopped:ye,preventDefault:function(){var e=this.originalEvent;this.isDefaultPrevented=ve,e&&(e.preventDefault?e.preventDefault():e.returnValue=!1)},stopPropagation:function(){var e=this.originalEvent;this.isPropagationStopped=ve,e&&!this.isSimulated&&(e.stopPropagation&&e.stopPropagation(),e.cancelBubble=!0)},stopImmediatePropagation:function(){var e=this.originalEvent;this.isImmediatePropagationStopped=ve,e&&e.stopImmediatePropagation&&e.stopImmediatePropagation(),this.stopPropagation()}},p.each({mouseenter:"mouseover",mouseleave:"mouseout",pointerenter:"pointerover",pointerleave:"pointerout"},function(e,t){p.event.special[e]={delegateType:t,bindType:t,handle:function(e){var n,r=e.relatedTarget,i=e.handleObj;return r&&(r===this||p.contains(this,r))||(e.type=i.origType,n=i.handler.apply(this,arguments),e.type=t),n}}}),f.submit||(p.event.special.submit={setup:function(){if(p.nodeName(this,"form"))return!1;p.event.add(this,"click._submit keypress._submit",function(e){var t=e.target,n=p.nodeName(t,"input")||p.nodeName(t,"button")?p.prop(t,"form"):void 0;n&&!p._data(n,"submit")&&(p.event.add(n,"submit._submit",function(e){e._submitBubble=!0}),p._data(n,"submit",!0))})},postDispatch:function(e){e._submitBubble&&(delete e._submitBubble,this.parentNode&&!e.isTrigger&&p.event.simulate("submit",this.parentNode,e))},teardown:function(){if(p.nodeName(this,"form"))return!1;p.event.remove(this,"._submit")}}),f.change||(p.event.special.change={setup:function(){if(de.test(this.nodeName))return"checkbox"!==this.type&&"radio"!==this.type||(p.event.add(this,"propertychange._change",function(e){"checked"===e.originalEvent.propertyName&&(this._justChanged=!0)}),p.event.add(this,"click._change",function(e){this._justChanged&&!e.isTrigger&&(this._justChanged=!1),p.event.simulate("change",this,e)})),!1;p.event.add(this,"beforeactivate._change",function(e){var t=e.target;de.test(t.nodeName)&&!p._data(t,"change")&&(p.event.add(t,"change._change",function(e){!this.parentNode||e.isSimulated||e.isTrigger||p.event.simulate("change",this.parentNode,e)}),p._data(t,"change",!0))})},handle:function(e){var t=e.target;if(this!==t||e.isSimulated||e.isTrigger||"radio"!==t.type&&"checkbox"!==t.type)return e.handleObj.handler.apply(this,arguments)},teardown:function(){return p.event.remove(this,"._change"),!de.test(this.nodeName)}}),f.focusin||p.each({focus:"focusin",blur:"focusout"},function(e,t){var n=function(e){p.event.simulate(t,e.target,p.event.fix(e))};p.event.special[t]={setup:function(){var r=this.ownerDocument||this,i=p._data(r,t);i||r.addEventListener(e,n,!0),p._data(r,t,(i||0)+1)},teardown:function(){var r=this.ownerDocument||this,i=p._data(r,t)-1;i?p._data(r,t,i):(r.removeEventListener(e,n,!0),p._removeData(r,t))}}}),p.fn.extend({on:function(e,t,n,r){return be(this,e,t,n,r)},one:function(e,t,n,r){return be(this,e,t,n,r,1)},off:function(e,t,n){var r,i;if(e&&e.preventDefault&&e.handleObj)return r=e.handleObj,p(e.delegateTarget).off(r.namespace?r.origType+"."+r.namespace:r.origType,r.selector,r.handler),this;if("object"==typeof e){for(i in e)this.off(i,t,e[i]);return this}return!1!==t&&"function"!=typeof t||(n=t,t=void 0),!1===n&&(n=ye),this.each(function(){p.event.remove(this,e,n,t)})},trigger:function(e,t){return this.each(function(){p.event.trigger(e,t,this)})},triggerHandler:function(e,t){var n=this[0];if(n)return p.event.trigger(e,t,n,!0)}});var we=/ jQuery\d+="(?:null|\d+)"/g,Te=new RegExp("<(?:"+re+")[\\s/>]","i"),Ce=/<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:-]+)[^>]*)\/>/gi,Ee=/<script|<style|<link/i,Ne=/checked\s*(?:[^=]|=\s*.checked.)/i,ke=/^true\/(.*)/,Se=/^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,Ae=ie(r).appendChild(r.createElement("div"));function De(e,t){return p.nodeName(e,"table")&&p.nodeName(11!==t.nodeType?t:t.firstChild,"tr")?e.getElementsByTagName("tbody")[0]||e.appendChild(e.ownerDocument.createElement("tbody")):e}function je(e){return e.type=(null!==p.find.attr(e,"type"))+"/"+e.type,e}function Le(e){var t=ke.exec(e.type);return t?e.type=t[1]:e.removeAttribute("type"),e}function He(e,t){if(1===t.nodeType&&p.hasData(e)){var n,r,i,o=p._data(e),a=p._data(t,o),s=o.events;if(s){delete a.handle,a.events={};for(n in s)for(r=0,i=s[n].length;r<i;r++)p.event.add(t,n,s[n][r])}a.data&&(a.data=p.extend({},a.data))}}function qe(e,t){var n,r,i;if(1===t.nodeType){if(n=t.nodeName.toLowerCase(),!f.noCloneEvent&&t[p.expando]){i=p._data(t);for(r in i.events)p.removeEvent(t,r,i.handle);t.removeAttribute(p.expando)}"script"===n&&t.text!==e.text?(je(t).text=e.text,Le(t)):"object"===n?(t.parentNode&&(t.outerHTML=e.outerHTML),f.html5Clone&&e.innerHTML&&!p.trim(t.innerHTML)&&(t.innerHTML=e.innerHTML)):"input"===n&&Z.test(e.type)?(t.defaultChecked=t.checked=e.checked,t.value!==e.value&&(t.value=e.value)):"option"===n?t.defaultSelected=t.selected=e.defaultSelected:"input"!==n&&"textarea"!==n||(t.defaultValue=e.defaultValue)}}function _e(e,t,n,r){t=o.apply([],t);var i,a,s,u,l,c,d=0,h=e.length,g=h-1,m=t[0],v=p.isFunction(m);if(v||h>1&&"string"==typeof m&&!f.checkClone&&Ne.test(m))return e.each(function(i){var o=e.eq(i);v&&(t[0]=m.call(this,i,o.html())),_e(o,t,n,r)});if(h&&(i=(c=fe(t,e[0].ownerDocument,!1,e,r)).firstChild,1===c.childNodes.length&&(c=i),i||r)){for(s=(u=p.map(ae(c,"script"),je)).length;d<h;d++)a=c,d!==g&&(a=p.clone(a,!0,!0),s&&p.merge(u,ae(a,"script"))),n.call(e[d],a,d);if(s)for(l=u[u.length-1].ownerDocument,p.map(u,Le),d=0;d<s;d++)a=u[d],te.test(a.type||"")&&!p._data(a,"globalEval")&&p.contains(l,a)&&(a.src?p._evalUrl&&p._evalUrl(a.src):p.globalEval((a.text||a.textContent||a.innerHTML||"").replace(Se,"")));c=i=null}return e}function Fe(e,t,n){for(var r,i=t?p.filter(t,e):e,o=0;null!=(r=i[o]);o++)n||1!==r.nodeType||p.cleanData(ae(r)),r.parentNode&&(n&&p.contains(r.ownerDocument,r)&&se(ae(r,"script")),r.parentNode.removeChild(r));return e}p.extend({htmlPrefilter:function(e){return e.replace(Ce,"<$1></$2>")},clone:function(e,t,n){var r,i,o,a,s,u=p.contains(e.ownerDocument,e);if(f.html5Clone||p.isXMLDoc(e)||!Te.test("<"+e.nodeName+">")?o=e.cloneNode(!0):(Ae.innerHTML=e.outerHTML,Ae.removeChild(o=Ae.firstChild)),!(f.noCloneEvent&&f.noCloneChecked||1!==e.nodeType&&11!==e.nodeType||p.isXMLDoc(e)))for(r=ae(o),s=ae(e),a=0;null!=(i=s[a]);++a)r[a]&&qe(i,r[a]);if(t)if(n)for(s=s||ae(e),r=r||ae(o),a=0;null!=(i=s[a]);a++)He(i,r[a]);else He(e,o);return(r=ae(o,"script")).length>0&&se(r,!u&&ae(e,"script")),r=s=i=null,o},cleanData:function(e,t){for(var r,i,o,a,s=0,u=p.expando,l=p.cache,c=f.attributes,d=p.event.special;null!=(r=e[s]);s++)if((t||O(r))&&(a=(o=r[u])&&l[o])){if(a.events)for(i in a.events)d[i]?p.event.remove(r,i):p.removeEvent(r,i,a.handle);l[o]&&(delete l[o],c||void 0===r.removeAttribute?r[u]=void 0:r.removeAttribute(u),n.push(o))}}}),p.fn.extend({domManip:_e,detach:function(e){return Fe(this,e,!0)},remove:function(e){return Fe(this,e)},text:function(e){return K(this,function(e){return void 0===e?p.text(this):this.empty().append((this[0]&&this[0].ownerDocument||r).createTextNode(e))},null,e,arguments.length)},append:function(){return _e(this,arguments,function(e){1!==this.nodeType&&11!==this.nodeType&&9!==this.nodeType||De(this,e).appendChild(e)})},prepend:function(){return _e(this,arguments,function(e){if(1===this.nodeType||11===this.nodeType||9===this.nodeType){var t=De(this,e);t.insertBefore(e,t.firstChild)}})},before:function(){return _e(this,arguments,function(e){this.parentNode&&this.parentNode.insertBefore(e,this)})},after:function(){return _e(this,arguments,function(e){this.parentNode&&this.parentNode.insertBefore(e,this.nextSibling)})},empty:function(){for(var e,t=0;null!=(e=this[t]);t++){for(1===e.nodeType&&p.cleanData(ae(e,!1));e.firstChild;)e.removeChild(e.firstChild);e.options&&p.nodeName(e,"select")&&(e.options.length=0)}return this},clone:function(e,t){return e=null!=e&&e,t=null==t?e:t,this.map(function(){return p.clone(this,e,t)})},html:function(e){return K(this,function(e){var t=this[0]||{},n=0,r=this.length;if(void 0===e)return 1===t.nodeType?t.innerHTML.replace(we,""):void 0;if("string"==typeof e&&!Ee.test(e)&&(f.htmlSerialize||!Te.test(e))&&(f.leadingWhitespace||!ne.test(e))&&!oe[(ee.exec(e)||["",""])[1].toLowerCase()]){e=p.htmlPrefilter(e);try{for(;n<r;n++)1===(t=this[n]||{}).nodeType&&(p.cleanData(ae(t,!1)),t.innerHTML=e);t=0}catch(e){}}t&&this.empty().append(e)},null,e,arguments.length)},replaceWith:function(){var e=[];return _e(this,arguments,function(t){var n=this.parentNode;p.inArray(this,e)<0&&(p.cleanData(ae(this)),n&&n.replaceChild(t,this))},e)}}),p.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(e,t){p.fn[e]=function(e){for(var n,r=0,i=[],o=p(e),s=o.length-1;r<=s;r++)n=r===s?this:this.clone(!0),p(o[r])[t](n),a.apply(i,n.get());return this.pushStack(i)}});var Me,Oe={HTML:"block",BODY:"block"};function Re(e,t){var n=p(t.createElement(e)).appendTo(t.body),r=p.css(n[0],"display");return n.detach(),r}function Pe(e){var t=r,n=Oe[e];return n||("none"!==(n=Re(e,t))&&n||((t=((Me=(Me||p("<iframe frameborder='0' width='0' height='0'/>")).appendTo(t.documentElement))[0].contentWindow||Me[0].contentDocument).document).write(),t.close(),n=Re(e,t),Me.detach()),Oe[e]=n),n}var Be=/^margin/,We=new RegExp("^("+z+")(?!px)[a-z%]+$","i"),Ie=function(e,t,n,r){var i,o,a={};for(o in t)a[o]=e.style[o],e.style[o]=t[o];i=n.apply(e,r||[]);for(o in t)e.style[o]=a[o];return i},$e=r.documentElement;!function(){var t,n,i,o,a,s,u=r.createElement("div"),l=r.createElement("div");function c(){var c,f,d=r.documentElement;d.appendChild(u),l.style.cssText="-webkit-box-sizing:border-box;box-sizing:border-box;position:relative;display:block;margin:auto;border:1px;padding:1px;top:1%;width:50%",t=i=s=!1,n=a=!0,e.getComputedStyle&&(f=e.getComputedStyle(l),t="1%"!==(f||{}).top,s="2px"===(f||{}).marginLeft,i="4px"===(f||{width:"4px"}).width,l.style.marginRight="50%",n="4px"===(f||{marginRight:"4px"}).marginRight,(c=l.appendChild(r.createElement("div"))).style.cssText=l.style.cssText="-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;margin:0;border:0;padding:0",c.style.marginRight=c.style.width="0",l.style.width="1px",a=!parseFloat((e.getComputedStyle(c)||{}).marginRight),l.removeChild(c)),l.style.display="none",(o=0===l.getClientRects().length)&&(l.style.display="",l.innerHTML="<table><tr><td></td><td>t</td></tr></table>",l.childNodes[0].style.borderCollapse="separate",(c=l.getElementsByTagName("td"))[0].style.cssText="margin:0;border:0;padding:0;display:none",(o=0===c[0].offsetHeight)&&(c[0].style.display="",c[1].style.display="none",o=0===c[0].offsetHeight)),d.removeChild(u)}l.style&&(l.style.cssText="float:left;opacity:.5",f.opacity="0.5"===l.style.opacity,f.cssFloat=!!l.style.cssFloat,l.style.backgroundClip="content-box",l.cloneNode(!0).style.backgroundClip="",f.clearCloneStyle="content-box"===l.style.backgroundClip,(u=r.createElement("div")).style.cssText="border:0;width:8px;height:0;top:0;left:-9999px;padding:0;margin-top:1px;position:absolute",l.innerHTML="",u.appendChild(l),f.boxSizing=""===l.style.boxSizing||""===l.style.MozBoxSizing||""===l.style.WebkitBoxSizing,p.extend(f,{reliableHiddenOffsets:function(){return null==t&&c(),o},boxSizingReliable:function(){return null==t&&c(),i},pixelMarginRight:function(){return null==t&&c(),n},pixelPosition:function(){return null==t&&c(),t},reliableMarginRight:function(){return null==t&&c(),a},reliableMarginLeft:function(){return null==t&&c(),s}}))}();var ze,Xe,Ue=/^(top|right|bottom|left)$/;function Ve(e,t){return{get:function(){if(!e())return(this.get=t).apply(this,arguments);delete this.get}}}e.getComputedStyle?(ze=function(t){var n=t.ownerDocument.defaultView;return n&&n.opener||(n=e),n.getComputedStyle(t)},Xe=function(e,t,n){var r,i,o,a,s=e.style;return""!==(a=(n=n||ze(e))?n.getPropertyValue(t)||n[t]:void 0)&&void 0!==a||p.contains(e.ownerDocument,e)||(a=p.style(e,t)),n&&!f.pixelMarginRight()&&We.test(a)&&Be.test(t)&&(r=s.width,i=s.minWidth,o=s.maxWidth,s.minWidth=s.maxWidth=s.width=a,a=n.width,s.width=r,s.minWidth=i,s.maxWidth=o),void 0===a?a:a+""}):$e.currentStyle&&(ze=function(e){return e.currentStyle},Xe=function(e,t,n){var r,i,o,a,s=e.style;return null==(a=(n=n||ze(e))?n[t]:void 0)&&s&&s[t]&&(a=s[t]),We.test(a)&&!Ue.test(t)&&(r=s.left,(o=(i=e.runtimeStyle)&&i.left)&&(i.left=e.currentStyle.left),s.left="fontSize"===t?"1em":a,a=s.pixelLeft+"px",s.left=r,o&&(i.left=o)),void 0===a?a:a+""||"auto"});var Ye=/alpha\([^)]*\)/i,Je=/opacity\s*=\s*([^)]*)/i,Ge=/^(none|table(?!-c[ea]).+)/,Qe=new RegExp("^("+z+")(.*)$","i"),Ke={position:"absolute",visibility:"hidden",display:"block"},Ze={letterSpacing:"0",fontWeight:"400"},et=["Webkit","O","Moz","ms"],tt=r.createElement("div").style;function nt(e){if(e in tt)return e;for(var t=e.charAt(0).toUpperCase()+e.slice(1),n=et.length;n--;)if((e=et[n]+t)in tt)return e}function rt(e,t){for(var n,r,i,o=[],a=0,s=e.length;a<s;a++)(r=e[a]).style&&(o[a]=p._data(r,"olddisplay"),n=r.style.display,t?(o[a]||"none"!==n||(r.style.display=""),""===r.style.display&&V(r)&&(o[a]=p._data(r,"olddisplay",Pe(r.nodeName)))):(i=V(r),(n&&"none"!==n||!i)&&p._data(r,"olddisplay",i?n:p.css(r,"display"))));for(a=0;a<s;a++)(r=e[a]).style&&(t&&"none"!==r.style.display&&""!==r.style.display||(r.style.display=t?o[a]||"":"none"));return e}function it(e,t,n){var r=Qe.exec(t);return r?Math.max(0,r[1]-(n||0))+(r[2]||"px"):t}function ot(e,t,n,r,i){for(var o=n===(r?"border":"content")?4:"width"===t?1:0,a=0;o<4;o+=2)"margin"===n&&(a+=p.css(e,n+U[o],!0,i)),r?("content"===n&&(a-=p.css(e,"padding"+U[o],!0,i)),"margin"!==n&&(a-=p.css(e,"border"+U[o]+"Width",!0,i))):(a+=p.css(e,"padding"+U[o],!0,i),"padding"!==n&&(a+=p.css(e,"border"+U[o]+"Width",!0,i)));return a}function at(e,t,n){var r=!0,i="width"===t?e.offsetWidth:e.offsetHeight,o=ze(e),a=f.boxSizing&&"border-box"===p.css(e,"boxSizing",!1,o);if(i<=0||null==i){if(((i=Xe(e,t,o))<0||null==i)&&(i=e.style[t]),We.test(i))return i;r=a&&(f.boxSizingReliable()||i===e.style[t]),i=parseFloat(i)||0}return i+ot(e,t,n||(a?"border":"content"),r,o)+"px"}function st(e,t,n,r,i){return new st.prototype.init(e,t,n,r,i)}p.extend({cssHooks:{opacity:{get:function(e,t){if(t){var n=Xe(e,"opacity");return""===n?"1":n}}}},cssNumber:{animationIterationCount:!0,columnCount:!0,fillOpacity:!0,flexGrow:!0,flexShrink:!0,fontWeight:!0,lineHeight:!0,opacity:!0,order:!0,orphans:!0,widows:!0,zIndex:!0,zoom:!0},cssProps:{float:f.cssFloat?"cssFloat":"styleFloat"},style:function(e,t,n,r){if(e&&3!==e.nodeType&&8!==e.nodeType&&e.style){var i,o,a,s=p.camelCase(t),u=e.style;if(t=p.cssProps[s]||(p.cssProps[s]=nt(s)||s),a=p.cssHooks[t]||p.cssHooks[s],void 0===n)return a&&"get"in a&&void 0!==(i=a.get(e,!1,r))?i:u[t];if("string"===(o=typeof n)&&(i=X.exec(n))&&i[1]&&(n=Y(e,t,i),o="number"),null!=n&&n==n&&("number"===o&&(n+=i&&i[3]||(p.cssNumber[s]?"":"px")),f.clearCloneStyle||""!==n||0!==t.indexOf("background")||(u[t]="inherit"),!(a&&"set"in a&&void 0===(n=a.set(e,n,r)))))try{u[t]=n}catch(e){}}},css:function(e,t,n,r){var i,o,a,s=p.camelCase(t);return t=p.cssProps[s]||(p.cssProps[s]=nt(s)||s),(a=p.cssHooks[t]||p.cssHooks[s])&&"get"in a&&(o=a.get(e,!0,n)),void 0===o&&(o=Xe(e,t,r)),"normal"===o&&t in Ze&&(o=Ze[t]),""===n||n?(i=parseFloat(o),!0===n||isFinite(i)?i||0:o):o}}),p.each(["height","width"],function(e,t){p.cssHooks[t]={get:function(e,n,r){if(n)return Ge.test(p.css(e,"display"))&&0===e.offsetWidth?Ie(e,Ke,function(){return at(e,t,r)}):at(e,t,r)},set:function(e,n,r){var i=r&&ze(e);return it(0,n,r?ot(e,t,r,f.boxSizing&&"border-box"===p.css(e,"boxSizing",!1,i),i):0)}}}),f.opacity||(p.cssHooks.opacity={get:function(e,t){return Je.test((t&&e.currentStyle?e.currentStyle.filter:e.style.filter)||"")?.01*parseFloat(RegExp.$1)+"":t?"1":""},set:function(e,t){var n=e.style,r=e.currentStyle,i=p.isNumeric(t)?"alpha(opacity="+100*t+")":"",o=r&&r.filter||n.filter||"";n.zoom=1,(t>=1||""===t)&&""===p.trim(o.replace(Ye,""))&&n.removeAttribute&&(n.removeAttribute("filter"),""===t||r&&!r.filter)||(n.filter=Ye.test(o)?o.replace(Ye,i):o+" "+i)}}),p.cssHooks.marginRight=Ve(f.reliableMarginRight,function(e,t){if(t)return Ie(e,{display:"inline-block"},Xe,[e,"marginRight"])}),p.cssHooks.marginLeft=Ve(f.reliableMarginLeft,function(e,t){if(t)return(parseFloat(Xe(e,"marginLeft"))||(p.contains(e.ownerDocument,e)?e.getBoundingClientRect().left-Ie(e,{marginLeft:0},function(){return e.getBoundingClientRect().left}):0))+"px"}),p.each({margin:"",padding:"",border:"Width"},function(e,t){p.cssHooks[e+t]={expand:function(n){for(var r=0,i={},o="string"==typeof n?n.split(" "):[n];r<4;r++)i[e+U[r]+t]=o[r]||o[r-2]||o[0];return i}},Be.test(e)||(p.cssHooks[e+t].set=it)}),p.fn.extend({css:function(e,t){return K(this,function(e,t,n){var r,i,o={},a=0;if(p.isArray(t)){for(r=ze(e),i=t.length;a<i;a++)o[t[a]]=p.css(e,t[a],!1,r);return o}return void 0!==n?p.style(e,t,n):p.css(e,t)},e,t,arguments.length>1)},show:function(){return rt(this,!0)},hide:function(){return rt(this)},toggle:function(e){return"boolean"==typeof e?e?this.show():this.hide():this.each(function(){V(this)?p(this).show():p(this).hide()})}}),p.Tween=st,st.prototype={constructor:st,init:function(e,t,n,r,i,o){this.elem=e,this.prop=n,this.easing=i||p.easing._default,this.options=t,this.start=this.now=this.cur(),this.end=r,this.unit=o||(p.cssNumber[n]?"":"px")},cur:function(){var e=st.propHooks[this.prop];return e&&e.get?e.get(this):st.propHooks._default.get(this)},run:function(e){var t,n=st.propHooks[this.prop];return this.options.duration?this.pos=t=p.easing[this.easing](e,this.options.duration*e,0,1,this.options.duration):this.pos=t=e,this.now=(this.end-this.start)*t+this.start,this.options.step&&this.options.step.call(this.elem,this.now,this),n&&n.set?n.set(this):st.propHooks._default.set(this),this}},st.prototype.init.prototype=st.prototype,st.propHooks={_default:{get:function(e){var t;return 1!==e.elem.nodeType||null!=e.elem[e.prop]&&null==e.elem.style[e.prop]?e.elem[e.prop]:(t=p.css(e.elem,e.prop,""))&&"auto"!==t?t:0},set:function(e){p.fx.step[e.prop]?p.fx.step[e.prop](e):1!==e.elem.nodeType||null==e.elem.style[p.cssProps[e.prop]]&&!p.cssHooks[e.prop]?e.elem[e.prop]=e.now:p.style(e.elem,e.prop,e.now+e.unit)}}},st.propHooks.scrollTop=st.propHooks.scrollLeft={set:function(e){e.elem.nodeType&&e.elem.parentNode&&(e.elem[e.prop]=e.now)}},p.easing={linear:function(e){return e},swing:function(e){return.5-Math.cos(e*Math.PI)/2},_default:"swing"},p.fx=st.prototype.init,p.fx.step={};var ut,lt,ct,ft,dt,pt,ht,gt=/^(?:toggle|show|hide)$/,mt=/queueHooks$/;function vt(){return e.setTimeout(function(){ut=void 0}),ut=p.now()}function yt(e,t){var n,r={height:e},i=0;for(t=t?1:0;i<4;i+=2-t)r["margin"+(n=U[i])]=r["padding"+n]=e;return t&&(r.opacity=r.width=e),r}function xt(e,t,n){for(var r,i=(bt.tweeners[t]||[]).concat(bt.tweeners["*"]),o=0,a=i.length;o<a;o++)if(r=i[o].call(n,t,e))return r}function bt(e,t,n){var r,i,o=0,a=bt.prefilters.length,s=p.Deferred().always(function(){delete u.elem}),u=function(){if(i)return!1;for(var t=ut||vt(),n=Math.max(0,l.startTime+l.duration-t),r=1-(n/l.duration||0),o=0,a=l.tweens.length;o<a;o++)l.tweens[o].run(r);return s.notifyWith(e,[l,r,n]),r<1&&a?n:(s.resolveWith(e,[l]),!1)},l=s.promise({elem:e,props:p.extend({},t),opts:p.extend(!0,{specialEasing:{},easing:p.easing._default},n),originalProperties:t,originalOptions:n,startTime:ut||vt(),duration:n.duration,tweens:[],createTween:function(t,n){var r=p.Tween(e,l.opts,t,n,l.opts.specialEasing[t]||l.opts.easing);return l.tweens.push(r),r},stop:function(t){var n=0,r=t?l.tweens.length:0;if(i)return this;for(i=!0;n<r;n++)l.tweens[n].run(1);return t?(s.notifyWith(e,[l,1,0]),s.resolveWith(e,[l,t])):s.rejectWith(e,[l,t]),this}}),c=l.props;for(!function(e,t){var n,r,i,o,a;for(n in e)if(i=t[r=p.camelCase(n)],o=e[n],p.isArray(o)&&(i=o[1],o=e[n]=o[0]),n!==r&&(e[r]=o,delete e[n]),(a=p.cssHooks[r])&&"expand"in a){o=a.expand(o),delete e[r];for(n in o)n in e||(e[n]=o[n],t[n]=i)}else t[r]=i}(c,l.opts.specialEasing);o<a;o++)if(r=bt.prefilters[o].call(l,e,c,l.opts))return p.isFunction(r.stop)&&(p._queueHooks(l.elem,l.opts.queue).stop=p.proxy(r.stop,r)),r;return p.map(c,xt,l),p.isFunction(l.opts.start)&&l.opts.start.call(e,l),p.fx.timer(p.extend(u,{elem:e,anim:l,queue:l.opts.queue})),l.progress(l.opts.progress).done(l.opts.done,l.opts.complete).fail(l.opts.fail).always(l.opts.always)}p.Animation=p.extend(bt,{tweeners:{"*":[function(e,t){var n=this.createTween(e,t);return Y(n.elem,e,X.exec(t),n),n}]},tweener:function(e,t){p.isFunction(e)?(t=e,e=["*"]):e=e.match(q);for(var n,r=0,i=e.length;r<i;r++)n=e[r],bt.tweeners[n]=bt.tweeners[n]||[],bt.tweeners[n].unshift(t)},prefilters:[function(e,t,n){var r,i,o,a,s,u,l,c=this,d={},h=e.style,g=e.nodeType&&V(e),m=p._data(e,"fxshow");n.queue||(null==(s=p._queueHooks(e,"fx")).unqueued&&(s.unqueued=0,u=s.empty.fire,s.empty.fire=function(){s.unqueued||u()}),s.unqueued++,c.always(function(){c.always(function(){s.unqueued--,p.queue(e,"fx").length||s.empty.fire()})})),1===e.nodeType&&("height"in t||"width"in t)&&(n.overflow=[h.overflow,h.overflowX,h.overflowY],"inline"===("none"===(l=p.css(e,"display"))?p._data(e,"olddisplay")||Pe(e.nodeName):l)&&"none"===p.css(e,"float")&&(f.inlineBlockNeedsLayout&&"inline"!==Pe(e.nodeName)?h.zoom=1:h.display="inline-block")),n.overflow&&(h.overflow="hidden",f.shrinkWrapBlocks()||c.always(function(){h.overflow=n.overflow[0],h.overflowX=n.overflow[1],h.overflowY=n.overflow[2]}));for(r in t)if(i=t[r],gt.exec(i)){if(delete t[r],o=o||"toggle"===i,i===(g?"hide":"show")){if("show"!==i||!m||void 0===m[r])continue;g=!0}d[r]=m&&m[r]||p.style(e,r)}else l=void 0;if(p.isEmptyObject(d))"inline"===("none"===l?Pe(e.nodeName):l)&&(h.display=l);else{m?"hidden"in m&&(g=m.hidden):m=p._data(e,"fxshow",{}),o&&(m.hidden=!g),g?p(e).show():c.done(function(){p(e).hide()}),c.done(function(){var t;p._removeData(e,"fxshow");for(t in d)p.style(e,t,d[t])});for(r in d)a=xt(g?m[r]:0,r,c),r in m||(m[r]=a.start,g&&(a.end=a.start,a.start="width"===r||"height"===r?1:0))}}],prefilter:function(e,t){t?bt.prefilters.unshift(e):bt.prefilters.push(e)}}),p.speed=function(e,t,n){var r=e&&"object"==typeof e?p.extend({},e):{complete:n||!n&&t||p.isFunction(e)&&e,duration:e,easing:n&&t||t&&!p.isFunction(t)&&t};return r.duration=p.fx.off?0:"number"==typeof r.duration?r.duration:r.duration in p.fx.speeds?p.fx.speeds[r.duration]:p.fx.speeds._default,null!=r.queue&&!0!==r.queue||(r.queue="fx"),r.old=r.complete,r.complete=function(){p.isFunction(r.old)&&r.old.call(this),r.queue&&p.dequeue(this,r.queue)},r},p.fn.extend({fadeTo:function(e,t,n,r){return this.filter(V).css("opacity",0).show().end().animate({opacity:t},e,n,r)},animate:function(e,t,n,r){var i=p.isEmptyObject(e),o=p.speed(t,n,r),a=function(){var t=bt(this,p.extend({},e),o);(i||p._data(this,"finish"))&&t.stop(!0)};return a.finish=a,i||!1===o.queue?this.each(a):this.queue(o.queue,a)},stop:function(e,t,n){var r=function(e){var t=e.stop;delete e.stop,t(n)};return"string"!=typeof e&&(n=t,t=e,e=void 0),t&&!1!==e&&this.queue(e||"fx",[]),this.each(function(){var t=!0,i=null!=e&&e+"queueHooks",o=p.timers,a=p._data(this);if(i)a[i]&&a[i].stop&&r(a[i]);else for(i in a)a[i]&&a[i].stop&&mt.test(i)&&r(a[i]);for(i=o.length;i--;)o[i].elem!==this||null!=e&&o[i].queue!==e||(o[i].anim.stop(n),t=!1,o.splice(i,1));!t&&n||p.dequeue(this,e)})},finish:function(e){return!1!==e&&(e=e||"fx"),this.each(function(){var t,n=p._data(this),r=n[e+"queue"],i=n[e+"queueHooks"],o=p.timers,a=r?r.length:0;for(n.finish=!0,p.queue(this,e,[]),i&&i.stop&&i.stop.call(this,!0),t=o.length;t--;)o[t].elem===this&&o[t].queue===e&&(o[t].anim.stop(!0),o.splice(t,1));for(t=0;t<a;t++)r[t]&&r[t].finish&&r[t].finish.call(this);delete n.finish})}}),p.each(["toggle","show","hide"],function(e,t){var n=p.fn[t];p.fn[t]=function(e,r,i){return null==e||"boolean"==typeof e?n.apply(this,arguments):this.animate(yt(t,!0),e,r,i)}}),p.each({slideDown:yt("show"),slideUp:yt("hide"),slideToggle:yt("toggle"),fadeIn:{opacity:"show"},fadeOut:{opacity:"hide"},fadeToggle:{opacity:"toggle"}},function(e,t){p.fn[e]=function(e,n,r){return this.animate(t,e,n,r)}}),p.timers=[],p.fx.tick=function(){var e,t=p.timers,n=0;for(ut=p.now();n<t.length;n++)(e=t[n])()||t[n]!==e||t.splice(n--,1);t.length||p.fx.stop(),ut=void 0},p.fx.timer=function(e){p.timers.push(e),e()?p.fx.start():p.timers.pop()},p.fx.interval=13,p.fx.start=function(){lt||(lt=e.setInterval(p.fx.tick,p.fx.interval))},p.fx.stop=function(){e.clearInterval(lt),lt=null},p.fx.speeds={slow:600,fast:200,_default:400},p.fn.delay=function(t,n){return t=p.fx&&p.fx.speeds[t]||t,n=n||"fx",this.queue(n,function(n,r){var i=e.setTimeout(n,t);r.stop=function(){e.clearTimeout(i)}})},ft=r.createElement("input"),dt=r.createElement("div"),pt=r.createElement("select"),ht=pt.appendChild(r.createElement("option")),(dt=r.createElement("div")).setAttribute("className","t"),dt.innerHTML="  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>",ct=dt.getElementsByTagName("a")[0],ft.setAttribute("type","checkbox"),dt.appendChild(ft),(ct=dt.getElementsByTagName("a")[0]).style.cssText="top:1px",f.getSetAttribute="t"!==dt.className,f.style=/top/.test(ct.getAttribute("style")),f.hrefNormalized="/a"===ct.getAttribute("href"),f.checkOn=!!ft.value,f.optSelected=ht.selected,f.enctype=!!r.createElement("form").enctype,pt.disabled=!0,f.optDisabled=!ht.disabled,(ft=r.createElement("input")).setAttribute("value",""),f.input=""===ft.getAttribute("value"),ft.value="t",ft.setAttribute("type","radio"),f.radioValue="t"===ft.value;var wt=/\r/g,Tt=/[\x20\t\r\n\f]+/g;p.fn.extend({val:function(e){var t,n,r,i=this[0];return arguments.length?(r=p.isFunction(e),this.each(function(n){var i;1===this.nodeType&&(null==(i=r?e.call(this,n,p(this).val()):e)?i="":"number"==typeof i?i+="":p.isArray(i)&&(i=p.map(i,function(e){return null==e?"":e+""})),(t=p.valHooks[this.type]||p.valHooks[this.nodeName.toLowerCase()])&&"set"in t&&void 0!==t.set(this,i,"value")||(this.value=i))})):i?(t=p.valHooks[i.type]||p.valHooks[i.nodeName.toLowerCase()])&&"get"in t&&void 0!==(n=t.get(i,"value"))?n:"string"==typeof(n=i.value)?n.replace(wt,""):null==n?"":n:void 0}}),p.extend({valHooks:{option:{get:function(e){var t=p.find.attr(e,"value");return null!=t?t:p.trim(p.text(e)).replace(Tt," ")}},select:{get:function(e){for(var t,n,r=e.options,i=e.selectedIndex,o="select-one"===e.type||i<0,a=o?null:[],s=o?i+1:r.length,u=i<0?s:o?i:0;u<s;u++)if(((n=r[u]).selected||u===i)&&(f.optDisabled?!n.disabled:null===n.getAttribute("disabled"))&&(!n.parentNode.disabled||!p.nodeName(n.parentNode,"optgroup"))){if(t=p(n).val(),o)return t;a.push(t)}return a},set:function(e,t){for(var n,r,i=e.options,o=p.makeArray(t),a=i.length;a--;)if(r=i[a],p.inArray(p.valHooks.option.get(r),o)>-1)try{r.selected=n=!0}catch(e){r.scrollHeight}else r.selected=!1;return n||(e.selectedIndex=-1),i}}}}),p.each(["radio","checkbox"],function(){p.valHooks[this]={set:function(e,t){if(p.isArray(t))return e.checked=p.inArray(p(e).val(),t)>-1}},f.checkOn||(p.valHooks[this].get=function(e){return null===e.getAttribute("value")?"on":e.value})});var Ct,Et,Nt=p.expr.attrHandle,kt=/^(?:checked|selected)$/i,St=f.getSetAttribute,At=f.input;p.fn.extend({attr:function(e,t){return K(this,p.attr,e,t,arguments.length>1)},removeAttr:function(e){return this.each(function(){p.removeAttr(this,e)})}}),p.extend({attr:function(e,t,n){var r,i,o=e.nodeType;if(3!==o&&8!==o&&2!==o)return void 0===e.getAttribute?p.prop(e,t,n):(1===o&&p.isXMLDoc(e)||(t=t.toLowerCase(),i=p.attrHooks[t]||(p.expr.match.bool.test(t)?Et:Ct)),void 0!==n?null===n?void p.removeAttr(e,t):i&&"set"in i&&void 0!==(r=i.set(e,n,t))?r:(e.setAttribute(t,n+""),n):i&&"get"in i&&null!==(r=i.get(e,t))?r:null==(r=p.find.attr(e,t))?void 0:r)},attrHooks:{type:{set:function(e,t){if(!f.radioValue&&"radio"===t&&p.nodeName(e,"input")){var n=e.value;return e.setAttribute("type",t),n&&(e.value=n),t}}}},removeAttr:function(e,t){var n,r,i=0,o=t&&t.match(q);if(o&&1===e.nodeType)for(;n=o[i++];)r=p.propFix[n]||n,p.expr.match.bool.test(n)?At&&St||!kt.test(n)?e[r]=!1:e[p.camelCase("default-"+n)]=e[r]=!1:p.attr(e,n,""),e.removeAttribute(St?n:r)}}),Et={set:function(e,t,n){return!1===t?p.removeAttr(e,n):At&&St||!kt.test(n)?e.setAttribute(!St&&p.propFix[n]||n,n):e[p.camelCase("default-"+n)]=e[n]=!0,n}},p.each(p.expr.match.bool.source.match(/\w+/g),function(e,t){var n=Nt[t]||p.find.attr;At&&St||!kt.test(t)?Nt[t]=function(e,t,r){var i,o;return r||(o=Nt[t],Nt[t]=i,i=null!=n(e,t,r)?t.toLowerCase():null,Nt[t]=o),i}:Nt[t]=function(e,t,n){if(!n)return e[p.camelCase("default-"+t)]?t.toLowerCase():null}}),At&&St||(p.attrHooks.value={set:function(e,t,n){if(!p.nodeName(e,"input"))return Ct&&Ct.set(e,t,n);e.defaultValue=t}}),St||(Ct={set:function(e,t,n){var r=e.getAttributeNode(n);if(r||e.setAttributeNode(r=e.ownerDocument.createAttribute(n)),r.value=t+="","value"===n||t===e.getAttribute(n))return t}},Nt.id=Nt.name=Nt.coords=function(e,t,n){var r;if(!n)return(r=e.getAttributeNode(t))&&""!==r.value?r.value:null},p.valHooks.button={get:function(e,t){var n=e.getAttributeNode(t);if(n&&n.specified)return n.value},set:Ct.set},p.attrHooks.contenteditable={set:function(e,t,n){Ct.set(e,""!==t&&t,n)}},p.each(["width","height"],function(e,t){p.attrHooks[t]={set:function(e,n){if(""===n)return e.setAttribute(t,"auto"),n}}})),f.style||(p.attrHooks.style={get:function(e){return e.style.cssText||void 0},set:function(e,t){return e.style.cssText=t+""}});var Dt=/^(?:input|select|textarea|button|object)$/i,jt=/^(?:a|area)$/i;p.fn.extend({prop:function(e,t){return K(this,p.prop,e,t,arguments.length>1)},removeProp:function(e){return e=p.propFix[e]||e,this.each(function(){try{this[e]=void 0,delete this[e]}catch(e){}})}}),p.extend({prop:function(e,t,n){var r,i,o=e.nodeType;if(3!==o&&8!==o&&2!==o)return 1===o&&p.isXMLDoc(e)||(t=p.propFix[t]||t,i=p.propHooks[t]),void 0!==n?i&&"set"in i&&void 0!==(r=i.set(e,n,t))?r:e[t]=n:i&&"get"in i&&null!==(r=i.get(e,t))?r:e[t]},propHooks:{tabIndex:{get:function(e){var t=p.find.attr(e,"tabindex");return t?parseInt(t,10):Dt.test(e.nodeName)||jt.test(e.nodeName)&&e.href?0:-1}}},propFix:{for:"htmlFor",class:"className"}}),f.hrefNormalized||p.each(["href","src"],function(e,t){p.propHooks[t]={get:function(e){return e.getAttribute(t,4)}}}),f.optSelected||(p.propHooks.selected={get:function(e){var t=e.parentNode;return t&&(t.selectedIndex,t.parentNode&&t.parentNode.selectedIndex),null},set:function(e){var t=e.parentNode;t&&(t.selectedIndex,t.parentNode&&t.parentNode.selectedIndex)}}),p.each(["tabIndex","readOnly","maxLength","cellSpacing","cellPadding","rowSpan","colSpan","useMap","frameBorder","contentEditable"],function(){p.propFix[this.toLowerCase()]=this}),f.enctype||(p.propFix.enctype="encoding");var Lt=/[\t\r\n\f]/g;function Ht(e){return p.attr(e,"class")||""}p.fn.extend({addClass:function(e){var t,n,r,i,o,a,s,u=0;if(p.isFunction(e))return this.each(function(t){p(this).addClass(e.call(this,t,Ht(this)))});if("string"==typeof e&&e)for(t=e.match(q)||[];n=this[u++];)if(i=Ht(n),r=1===n.nodeType&&(" "+i+" ").replace(Lt," ")){for(a=0;o=t[a++];)r.indexOf(" "+o+" ")<0&&(r+=o+" ");i!==(s=p.trim(r))&&p.attr(n,"class",s)}return this},removeClass:function(e){var t,n,r,i,o,a,s,u=0;if(p.isFunction(e))return this.each(function(t){p(this).removeClass(e.call(this,t,Ht(this)))});if(!arguments.length)return this.attr("class","");if("string"==typeof e&&e)for(t=e.match(q)||[];n=this[u++];)if(i=Ht(n),r=1===n.nodeType&&(" "+i+" ").replace(Lt," ")){for(a=0;o=t[a++];)for(;r.indexOf(" "+o+" ")>-1;)r=r.replace(" "+o+" "," ");i!==(s=p.trim(r))&&p.attr(n,"class",s)}return this},toggleClass:function(e,t){var n=typeof e;return"boolean"==typeof t&&"string"===n?t?this.addClass(e):this.removeClass(e):p.isFunction(e)?this.each(function(n){p(this).toggleClass(e.call(this,n,Ht(this),t),t)}):this.each(function(){var t,r,i,o;if("string"===n)for(r=0,i=p(this),o=e.match(q)||[];t=o[r++];)i.hasClass(t)?i.removeClass(t):i.addClass(t);else void 0!==e&&"boolean"!==n||((t=Ht(this))&&p._data(this,"__className__",t),p.attr(this,"class",t||!1===e?"":p._data(this,"__className__")||""))})},hasClass:function(e){var t,n,r=0;for(t=" "+e+" ";n=this[r++];)if(1===n.nodeType&&(" "+Ht(n)+" ").replace(Lt," ").indexOf(t)>-1)return!0;return!1}}),p.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "),function(e,t){p.fn[t]=function(e,n){return arguments.length>0?this.on(t,null,e,n):this.trigger(t)}}),p.fn.extend({hover:function(e,t){return this.mouseenter(e).mouseleave(t||e)}});var qt=e.location,_t=p.now(),Ft=/\?/,Mt=/(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;p.parseJSON=function(t){if(e.JSON&&e.JSON.parse)return e.JSON.parse(t+"");var n,r=null,i=p.trim(t+"");return i&&!p.trim(i.replace(Mt,function(e,t,i,o){return n&&t&&(r=0),0===r?e:(n=i||t,r+=!o-!i,"")}))?Function("return "+i)():p.error("Invalid JSON: "+t)},p.parseXML=function(t){var n,r;if(!t||"string"!=typeof t)return null;try{e.DOMParser?(r=new e.DOMParser,n=r.parseFromString(t,"text/xml")):((n=new e.ActiveXObject("Microsoft.XMLDOM")).async="false",n.loadXML(t))}catch(e){n=void 0}return n&&n.documentElement&&!n.getElementsByTagName("parsererror").length||p.error("Invalid XML: "+t),n};var Ot=/#.*$/,Rt=/([?&])_=[^&]*/,Pt=/^(.*?):[ \t]*([^\r\n]*)\r?$/gm,Bt=/^(?:GET|HEAD)$/,Wt=/^\/\//,It=/^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,$t={},zt={},Xt="*/".concat("*"),Ut=qt.href,Vt=It.exec(Ut.toLowerCase())||[];function Yt(e){return function(t,n){"string"!=typeof t&&(n=t,t="*");var r,i=0,o=t.toLowerCase().match(q)||[];if(p.isFunction(n))for(;r=o[i++];)"+"===r.charAt(0)?(r=r.slice(1)||"*",(e[r]=e[r]||[]).unshift(n)):(e[r]=e[r]||[]).push(n)}}function Jt(e,t,n,r){var i={},o=e===zt;function a(s){var u;return i[s]=!0,p.each(e[s]||[],function(e,s){var l=s(t,n,r);return"string"!=typeof l||o||i[l]?o?!(u=l):void 0:(t.dataTypes.unshift(l),a(l),!1)}),u}return a(t.dataTypes[0])||!i["*"]&&a("*")}function Gt(e,t){var n,r,i=p.ajaxSettings.flatOptions||{};for(r in t)void 0!==t[r]&&((i[r]?e:n||(n={}))[r]=t[r]);return n&&p.extend(!0,e,n),e}p.extend({active:0,lastModified:{},etag:{},ajaxSettings:{url:Ut,type:"GET",isLocal:/^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(Vt[1]),global:!0,processData:!0,async:!0,contentType:"application/x-www-form-urlencoded; charset=UTF-8",accepts:{"*":Xt,text:"text/plain",html:"text/html",xml:"application/xml, text/xml",json:"application/json, text/javascript"},contents:{xml:/\bxml\b/,html:/\bhtml/,json:/\bjson\b/},responseFields:{xml:"responseXML",text:"responseText",json:"responseJSON"},converters:{"* text":String,"text html":!0,"text json":p.parseJSON,"text xml":p.parseXML},flatOptions:{url:!0,context:!0}},ajaxSetup:function(e,t){return t?Gt(Gt(e,p.ajaxSettings),t):Gt(p.ajaxSettings,e)},ajaxPrefilter:Yt($t),ajaxTransport:Yt(zt),ajax:function(t,n){"object"==typeof t&&(n=t,t=void 0),n=n||{};var r,i,o,a,s,u,l,c,f=p.ajaxSetup({},n),d=f.context||f,h=f.context&&(d.nodeType||d.jquery)?p(d):p.event,g=p.Deferred(),m=p.Callbacks("once memory"),v=f.statusCode||{},y={},x={},b=0,w="canceled",T={readyState:0,getResponseHeader:function(e){var t;if(2===b){if(!c)for(c={};t=Pt.exec(a);)c[t[1].toLowerCase()]=t[2];t=c[e.toLowerCase()]}return null==t?null:t},getAllResponseHeaders:function(){return 2===b?a:null},setRequestHeader:function(e,t){var n=e.toLowerCase();return b||(e=x[n]=x[n]||e,y[e]=t),this},overrideMimeType:function(e){return b||(f.mimeType=e),this},statusCode:function(e){var t;if(e)if(b<2)for(t in e)v[t]=[v[t],e[t]];else T.always(e[T.status]);return this},abort:function(e){var t=e||w;return l&&l.abort(t),C(0,t),this}};if(g.promise(T).complete=m.add,T.success=T.done,T.error=T.fail,f.url=((t||f.url||Ut)+"").replace(Ot,"").replace(Wt,Vt[1]+"//"),f.type=n.method||n.type||f.method||f.type,f.dataTypes=p.trim(f.dataType||"*").toLowerCase().match(q)||[""],null==f.crossDomain&&(r=It.exec(f.url.toLowerCase()),f.crossDomain=!(!r||r[1]===Vt[1]&&r[2]===Vt[2]&&(r[3]||("http:"===r[1]?"80":"443"))===(Vt[3]||("http:"===Vt[1]?"80":"443")))),f.data&&f.processData&&"string"!=typeof f.data&&(f.data=p.param(f.data,f.traditional)),Jt($t,f,n,T),2===b)return T;(u=p.event&&f.global)&&0==p.active++&&p.event.trigger("ajaxStart"),f.type=f.type.toUpperCase(),f.hasContent=!Bt.test(f.type),o=f.url,f.hasContent||(f.data&&(o=f.url+=(Ft.test(o)?"&":"?")+f.data,delete f.data),!1===f.cache&&(f.url=Rt.test(o)?o.replace(Rt,"$1_="+_t++):o+(Ft.test(o)?"&":"?")+"_="+_t++)),f.ifModified&&(p.lastModified[o]&&T.setRequestHeader("If-Modified-Since",p.lastModified[o]),p.etag[o]&&T.setRequestHeader("If-None-Match",p.etag[o])),(f.data&&f.hasContent&&!1!==f.contentType||n.contentType)&&T.setRequestHeader("Content-Type",f.contentType),T.setRequestHeader("Accept",f.dataTypes[0]&&f.accepts[f.dataTypes[0]]?f.accepts[f.dataTypes[0]]+("*"!==f.dataTypes[0]?", "+Xt+"; q=0.01":""):f.accepts["*"]);for(i in f.headers)T.setRequestHeader(i,f.headers[i]);if(f.beforeSend&&(!1===f.beforeSend.call(d,T,f)||2===b))return T.abort();w="abort";for(i in{success:1,error:1,complete:1})T[i](f[i]);if(l=Jt(zt,f,n,T)){if(T.readyState=1,u&&h.trigger("ajaxSend",[T,f]),2===b)return T;f.async&&f.timeout>0&&(s=e.setTimeout(function(){T.abort("timeout")},f.timeout));try{b=1,l.send(y,C)}catch(e){if(!(b<2))throw e;C(-1,e)}}else C(-1,"No Transport");function C(t,n,r,i){var c,y,x,w,C,E=n;2!==b&&(b=2,s&&e.clearTimeout(s),l=void 0,a=i||"",T.readyState=t>0?4:0,c=t>=200&&t<300||304===t,r&&(w=function(e,t,n){for(var r,i,o,a,s=e.contents,u=e.dataTypes;"*"===u[0];)u.shift(),void 0===i&&(i=e.mimeType||t.getResponseHeader("Content-Type"));if(i)for(a in s)if(s[a]&&s[a].test(i)){u.unshift(a);break}if(u[0]in n)o=u[0];else{for(a in n){if(!u[0]||e.converters[a+" "+u[0]]){o=a;break}r||(r=a)}o=o||r}if(o)return o!==u[0]&&u.unshift(o),n[o]}(f,T,r)),w=function(e,t,n,r){var i,o,a,s,u,l={},c=e.dataTypes.slice();if(c[1])for(a in e.converters)l[a.toLowerCase()]=e.converters[a];for(o=c.shift();o;)if(e.responseFields[o]&&(n[e.responseFields[o]]=t),!u&&r&&e.dataFilter&&(t=e.dataFilter(t,e.dataType)),u=o,o=c.shift())if("*"===o)o=u;else if("*"!==u&&u!==o){if(e.crossDomain&&"script"===o)continue;if(!(a=l[u+" "+o]||l["* "+o]))for(i in l)if((s=i.split(" "))[1]===o&&(a=l[u+" "+s[0]]||l["* "+s[0]])){!0===a?a=l[i]:!0!==l[i]&&(o=s[0],c.unshift(s[1]));break}if(!0!==a)if(a&&e.throws)t=a(t);else try{t=a(t)}catch(e){return{state:"parsererror",error:a?e:"No conversion from "+u+" to "+o}}}return{state:"success",data:t}}(f,w,T,c),c?(f.ifModified&&((C=T.getResponseHeader("Last-Modified"))&&(p.lastModified[o]=C),(C=T.getResponseHeader("etag"))&&(p.etag[o]=C)),204===t||"HEAD"===f.type?E="nocontent":304===t?E="notmodified":(E=w.state,y=w.data,c=!(x=w.error))):(x=E,!t&&E||(E="error",t<0&&(t=0))),T.status=t,T.statusText=(n||E)+"",c?g.resolveWith(d,[y,E,T]):g.rejectWith(d,[T,E,x]),T.statusCode(v),v=void 0,u&&h.trigger(c?"ajaxSuccess":"ajaxError",[T,f,c?y:x]),m.fireWith(d,[T,E]),u&&(h.trigger("ajaxComplete",[T,f]),--p.active||p.event.trigger("ajaxStop")))}return T},getJSON:function(e,t,n){return p.get(e,t,n,"json")},getScript:function(e,t){return p.get(e,void 0,t,"script")}}),p.each(["get","post"],function(e,t){p[t]=function(e,n,r,i){return p.isFunction(n)&&(i=i||r,r=n,n=void 0),p.ajax(p.extend({url:e,type:t,dataType:i,data:n,success:r},p.isPlainObject(e)&&e))}}),p._evalUrl=function(e){return p.ajax({url:e,type:"GET",dataType:"script",cache:!0,async:!1,global:!1,throws:!0})},p.fn.extend({wrapAll:function(e){if(p.isFunction(e))return this.each(function(t){p(this).wrapAll(e.call(this,t))});if(this[0]){var t=p(e,this[0].ownerDocument).eq(0).clone(!0);this[0].parentNode&&t.insertBefore(this[0]),t.map(function(){for(var e=this;e.firstChild&&1===e.firstChild.nodeType;)e=e.firstChild;return e}).append(this)}return this},wrapInner:function(e){return p.isFunction(e)?this.each(function(t){p(this).wrapInner(e.call(this,t))}):this.each(function(){var t=p(this),n=t.contents();n.length?n.wrapAll(e):t.append(e)})},wrap:function(e){var t=p.isFunction(e);return this.each(function(n){p(this).wrapAll(t?e.call(this,n):e)})},unwrap:function(){return this.parent().each(function(){p.nodeName(this,"body")||p(this).replaceWith(this.childNodes)}).end()}}),p.expr.filters.hidden=function(e){return f.reliableHiddenOffsets()?e.offsetWidth<=0&&e.offsetHeight<=0&&!e.getClientRects().length:function(e){if(!p.contains(e.ownerDocument||r,e))return!0;for(;e&&1===e.nodeType;){if("none"===((t=e).style&&t.style.display||p.css(t,"display"))||"hidden"===e.type)return!0;e=e.parentNode}var t;return!1}(e)},p.expr.filters.visible=function(e){return!p.expr.filters.hidden(e)};var Qt=/%20/g,Kt=/\[\]$/,Zt=/\r?\n/g,en=/^(?:submit|button|image|reset|file)$/i,tn=/^(?:input|select|textarea|keygen)/i;function nn(e,t,n,r){var i;if(p.isArray(t))p.each(t,function(t,i){n||Kt.test(e)?r(e,i):nn(e+"["+("object"==typeof i&&null!=i?t:"")+"]",i,n,r)});else if(n||"object"!==p.type(t))r(e,t);else for(i in t)nn(e+"["+i+"]",t[i],n,r)}p.param=function(e,t){var n,r=[],i=function(e,t){t=p.isFunction(t)?t():null==t?"":t,r[r.length]=encodeURIComponent(e)+"="+encodeURIComponent(t)};if(void 0===t&&(t=p.ajaxSettings&&p.ajaxSettings.traditional),p.isArray(e)||e.jquery&&!p.isPlainObject(e))p.each(e,function(){i(this.name,this.value)});else for(n in e)nn(n,e[n],t,i);return r.join("&").replace(Qt,"+")},p.fn.extend({serialize:function(){return p.param(this.serializeArray())},serializeArray:function(){return this.map(function(){var e=p.prop(this,"elements");return e?p.makeArray(e):this}).filter(function(){var e=this.type;return this.name&&!p(this).is(":disabled")&&tn.test(this.nodeName)&&!en.test(e)&&(this.checked||!Z.test(e))}).map(function(e,t){var n=p(this).val();return null==n?null:p.isArray(n)?p.map(n,function(e){return{name:t.name,value:e.replace(Zt,"\r\n")}}):{name:t.name,value:n.replace(Zt,"\r\n")}}).get()}}),p.ajaxSettings.xhr=void 0!==e.ActiveXObject?function(){return this.isLocal?un():r.documentMode>8?sn():/^(get|post|head|put|delete|options)$/i.test(this.type)&&sn()||un()}:sn;var rn=0,on={},an=p.ajaxSettings.xhr();function sn(){try{return new e.XMLHttpRequest}catch(e){}}function un(){try{return new e.ActiveXObject("Microsoft.XMLHTTP")}catch(e){}}e.attachEvent&&e.attachEvent("onunload",function(){for(var e in on)on[e](void 0,!0)}),f.cors=!!an&&"withCredentials"in an,(an=f.ajax=!!an)&&p.ajaxTransport(function(t){var n;if(!t.crossDomain||f.cors)return{send:function(r,i){var o,a=t.xhr(),s=++rn;if(a.open(t.type,t.url,t.async,t.username,t.password),t.xhrFields)for(o in t.xhrFields)a[o]=t.xhrFields[o];t.mimeType&&a.overrideMimeType&&a.overrideMimeType(t.mimeType),t.crossDomain||r["X-Requested-With"]||(r["X-Requested-With"]="XMLHttpRequest");for(o in r)void 0!==r[o]&&a.setRequestHeader(o,r[o]+"");a.send(t.hasContent&&t.data||null),n=function(e,r){var o,u,l;if(n&&(r||4===a.readyState))if(delete on[s],n=void 0,a.onreadystatechange=p.noop,r)4!==a.readyState&&a.abort();else{l={},o=a.status,"string"==typeof a.responseText&&(l.text=a.responseText);try{u=a.statusText}catch(e){u=""}o||!t.isLocal||t.crossDomain?1223===o&&(o=204):o=l.text?200:404}l&&i(o,u,l,a.getAllResponseHeaders())},t.async?4===a.readyState?e.setTimeout(n):a.onreadystatechange=on[s]=n:n()},abort:function(){n&&n(void 0,!0)}}}),p.ajaxSetup({accepts:{script:"text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"},contents:{script:/\b(?:java|ecma)script\b/},converters:{"text script":function(e){return p.globalEval(e),e}}}),p.ajaxPrefilter("script",function(e){void 0===e.cache&&(e.cache=!1),e.crossDomain&&(e.type="GET",e.global=!1)}),p.ajaxTransport("script",function(e){if(e.crossDomain){var t,n=r.head||p("head")[0]||r.documentElement;return{send:function(i,o){(t=r.createElement("script")).async=!0,e.scriptCharset&&(t.charset=e.scriptCharset),t.src=e.url,t.onload=t.onreadystatechange=function(e,n){(n||!t.readyState||/loaded|complete/.test(t.readyState))&&(t.onload=t.onreadystatechange=null,t.parentNode&&t.parentNode.removeChild(t),t=null,n||o(200,"success"))},n.insertBefore(t,n.firstChild)},abort:function(){t&&t.onload(void 0,!0)}}}});var ln=[],cn=/(=)\?(?=&|$)|\?\?/;p.ajaxSetup({jsonp:"callback",jsonpCallback:function(){var e=ln.pop()||p.expando+"_"+_t++;return this[e]=!0,e}}),p.ajaxPrefilter("json jsonp",function(t,n,r){var i,o,a,s=!1!==t.jsonp&&(cn.test(t.url)?"url":"string"==typeof t.data&&0===(t.contentType||"").indexOf("application/x-www-form-urlencoded")&&cn.test(t.data)&&"data");if(s||"jsonp"===t.dataTypes[0])return i=t.jsonpCallback=p.isFunction(t.jsonpCallback)?t.jsonpCallback():t.jsonpCallback,s?t[s]=t[s].replace(cn,"$1"+i):!1!==t.jsonp&&(t.url+=(Ft.test(t.url)?"&":"?")+t.jsonp+"="+i),t.converters["script json"]=function(){return a||p.error(i+" was not called"),a[0]},t.dataTypes[0]="json",o=e[i],e[i]=function(){a=arguments},r.always(function(){void 0===o?p(e).removeProp(i):e[i]=o,t[i]&&(t.jsonpCallback=n.jsonpCallback,ln.push(i)),a&&p.isFunction(o)&&o(a[0]),a=o=void 0}),"script"}),p.parseHTML=function(e,t,n){if(!e||"string"!=typeof e)return null;"boolean"==typeof t&&(n=t,t=!1),t=t||r;var i=C.exec(e),o=!n&&[];return i?[t.createElement(i[1])]:(i=fe([e],t,o),o&&o.length&&p(o).remove(),p.merge([],i.childNodes))};var fn=p.fn.load;function dn(e){return p.isWindow(e)?e:9===e.nodeType&&(e.defaultView||e.parentWindow)}p.fn.load=function(e,t,n){if("string"!=typeof e&&fn)return fn.apply(this,arguments);var r,i,o,a=this,s=e.indexOf(" ");return s>-1&&(r=p.trim(e.slice(s,e.length)),e=e.slice(0,s)),p.isFunction(t)?(n=t,t=void 0):t&&"object"==typeof t&&(i="POST"),a.length>0&&p.ajax({url:e,type:i||"GET",dataType:"html",data:t}).done(function(e){o=arguments,a.html(r?p("<div>").append(p.parseHTML(e)).find(r):e)}).always(n&&function(e,t){a.each(function(){n.apply(this,o||[e.responseText,t,e])})}),this},p.each(["ajaxStart","ajaxStop","ajaxComplete","ajaxError","ajaxSuccess","ajaxSend"],function(e,t){p.fn[t]=function(e){return this.on(t,e)}}),p.expr.filters.animated=function(e){return p.grep(p.timers,function(t){return e===t.elem}).length},p.offset={setOffset:function(e,t,n){var r,i,o,a,s,u,l=p.css(e,"position"),c=p(e),f={};"static"===l&&(e.style.position="relative"),s=c.offset(),o=p.css(e,"top"),u=p.css(e,"left"),("absolute"===l||"fixed"===l)&&p.inArray("auto",[o,u])>-1?(a=(r=c.position()).top,i=r.left):(a=parseFloat(o)||0,i=parseFloat(u)||0),p.isFunction(t)&&(t=t.call(e,n,p.extend({},s))),null!=t.top&&(f.top=t.top-s.top+a),null!=t.left&&(f.left=t.left-s.left+i),"using"in t?t.using.call(e,f):c.css(f)}},p.fn.extend({offset:function(e){if(arguments.length)return void 0===e?this:this.each(function(t){p.offset.setOffset(this,e,t)});var t,n,r={top:0,left:0},i=this[0],o=i&&i.ownerDocument;return o?(t=o.documentElement,p.contains(t,i)?(void 0!==i.getBoundingClientRect&&(r=i.getBoundingClientRect()),n=dn(o),{top:r.top+(n.pageYOffset||t.scrollTop)-(t.clientTop||0),left:r.left+(n.pageXOffset||t.scrollLeft)-(t.clientLeft||0)}):r):void 0},position:function(){if(this[0]){var e,t,n={top:0,left:0},r=this[0];return"fixed"===p.css(r,"position")?t=r.getBoundingClientRect():(e=this.offsetParent(),t=this.offset(),p.nodeName(e[0],"html")||(n=e.offset()),n.top+=p.css(e[0],"borderTopWidth",!0),n.left+=p.css(e[0],"borderLeftWidth",!0)),{top:t.top-n.top-p.css(r,"marginTop",!0),left:t.left-n.left-p.css(r,"marginLeft",!0)}}},offsetParent:function(){return this.map(function(){for(var e=this.offsetParent;e&&!p.nodeName(e,"html")&&"static"===p.css(e,"position");)e=e.offsetParent;return e||$e})}}),p.each({scrollLeft:"pageXOffset",scrollTop:"pageYOffset"},function(e,t){var n=/Y/.test(t);p.fn[e]=function(r){return K(this,function(e,r,i){var o=dn(e);if(void 0===i)return o?t in o?o[t]:o.document.documentElement[r]:e[r];o?o.scrollTo(n?p(o).scrollLeft():i,n?i:p(o).scrollTop()):e[r]=i},e,r,arguments.length,null)}}),p.each(["top","left"],function(e,t){p.cssHooks[t]=Ve(f.pixelPosition,function(e,n){if(n)return n=Xe(e,t),We.test(n)?p(e).position()[t]+"px":n})}),p.each({Height:"height",Width:"width"},function(e,t){p.each({padding:"inner"+e,content:t,"":"outer"+e},function(n,r){p.fn[r]=function(r,i){var o=arguments.length&&(n||"boolean"!=typeof r),a=n||(!0===r||!0===i?"margin":"border");return K(this,function(t,n,r){var i;return p.isWindow(t)?t.document.documentElement["client"+e]:9===t.nodeType?(i=t.documentElement,Math.max(t.body["scroll"+e],i["scroll"+e],t.body["offset"+e],i["offset"+e],i["client"+e])):void 0===r?p.css(t,n,a):p.style(t,n,r,a)},t,o?r:void 0,o,null)}})}),p.fn.extend({bind:function(e,t,n){return this.on(e,null,t,n)},unbind:function(e,t){return this.off(e,null,t)},delegate:function(e,t,n,r){return this.on(t,e,n,r)},undelegate:function(e,t,n){return 1===arguments.length?this.off(e,"**"):this.off(t,e||"**",n)}}),p.fn.size=function(){return this.length},p.fn.andSelf=p.fn.addBack,"function"==typeof define&&define.amd&&define("jquery",[],function(){return p});var pn=e.jQuery,hn=e.$;return p.noConflict=function(t){return e.$===p&&(e.$=hn),t&&e.jQuery===p&&(e.jQuery=pn),p},t||(e.jQuery=e.$=p),p});


/*===============================
/sampo4/media/jui/js/jquery-noconflict.js
================================================================================*/;
jQuery.noConflict();


/*===============================
/sampo4/media/jui/js/jquery-migrate.min.js
================================================================================*/;
/*! jQuery Migrate v1.4.1 | (c) jQuery Foundation and other contributors | jquery.org/license */
"undefined"==typeof jQuery.migrateMute&&(jQuery.migrateMute=!0),function(a,b,c){function d(c){var d=b.console;f[c]||(f[c]=!0,a.migrateWarnings.push(c),d&&d.warn&&!a.migrateMute&&(d.warn("JQMIGRATE: "+c),a.migrateTrace&&d.trace&&d.trace()))}function e(b,c,e,f){if(Object.defineProperty)try{return void Object.defineProperty(b,c,{configurable:!0,enumerable:!0,get:function(){return d(f),e},set:function(a){d(f),e=a}})}catch(g){}a._definePropertyBroken=!0,b[c]=e}a.migrateVersion="1.4.1";var f={};a.migrateWarnings=[],b.console&&b.console.log&&b.console.log("JQMIGRATE: Migrate is installed"+(a.migrateMute?"":" with logging active")+", version "+a.migrateVersion),a.migrateTrace===c&&(a.migrateTrace=!0),a.migrateReset=function(){f={},a.migrateWarnings.length=0},"BackCompat"===document.compatMode&&d("jQuery is not compatible with Quirks Mode");var g=a("<input/>",{size:1}).attr("size")&&a.attrFn,h=a.attr,i=a.attrHooks.value&&a.attrHooks.value.get||function(){return null},j=a.attrHooks.value&&a.attrHooks.value.set||function(){return c},k=/^(?:input|button)$/i,l=/^[238]$/,m=/^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,n=/^(?:checked|selected)$/i;e(a,"attrFn",g||{},"jQuery.attrFn is deprecated"),a.attr=function(b,e,f,i){var j=e.toLowerCase(),o=b&&b.nodeType;return i&&(h.length<4&&d("jQuery.fn.attr( props, pass ) is deprecated"),b&&!l.test(o)&&(g?e in g:a.isFunction(a.fn[e])))?a(b)[e](f):("type"===e&&f!==c&&k.test(b.nodeName)&&b.parentNode&&d("Can't change the 'type' of an input or button in IE 6/7/8"),!a.attrHooks[j]&&m.test(j)&&(a.attrHooks[j]={get:function(b,d){var e,f=a.prop(b,d);return f===!0||"boolean"!=typeof f&&(e=b.getAttributeNode(d))&&e.nodeValue!==!1?d.toLowerCase():c},set:function(b,c,d){var e;return c===!1?a.removeAttr(b,d):(e=a.propFix[d]||d,e in b&&(b[e]=!0),b.setAttribute(d,d.toLowerCase())),d}},n.test(j)&&d("jQuery.fn.attr('"+j+"') might use property instead of attribute")),h.call(a,b,e,f))},a.attrHooks.value={get:function(a,b){var c=(a.nodeName||"").toLowerCase();return"button"===c?i.apply(this,arguments):("input"!==c&&"option"!==c&&d("jQuery.fn.attr('value') no longer gets properties"),b in a?a.value:null)},set:function(a,b){var c=(a.nodeName||"").toLowerCase();return"button"===c?j.apply(this,arguments):("input"!==c&&"option"!==c&&d("jQuery.fn.attr('value', val) no longer sets properties"),void(a.value=b))}};var o,p,q=a.fn.init,r=a.find,s=a.parseJSON,t=/^\s*</,u=/\[(\s*[-\w]+\s*)([~|^$*]?=)\s*([-\w#]*?#[-\w#]*)\s*\]/,v=/\[(\s*[-\w]+\s*)([~|^$*]?=)\s*([-\w#]*?#[-\w#]*)\s*\]/g,w=/^([^<]*)(<[\w\W]+>)([^>]*)$/;a.fn.init=function(b,e,f){var g,h;return b&&"string"==typeof b&&!a.isPlainObject(e)&&(g=w.exec(a.trim(b)))&&g[0]&&(t.test(b)||d("$(html) HTML strings must start with '<' character"),g[3]&&d("$(html) HTML text after last tag is ignored"),"#"===g[0].charAt(0)&&(d("HTML string cannot start with a '#' character"),a.error("JQMIGRATE: Invalid selector string (XSS)")),e&&e.context&&e.context.nodeType&&(e=e.context),a.parseHTML)?q.call(this,a.parseHTML(g[2],e&&e.ownerDocument||e||document,!0),e,f):(h=q.apply(this,arguments),b&&b.selector!==c?(h.selector=b.selector,h.context=b.context):(h.selector="string"==typeof b?b:"",b&&(h.context=b.nodeType?b:e||document)),h)},a.fn.init.prototype=a.fn,a.find=function(a){var b=Array.prototype.slice.call(arguments);if("string"==typeof a&&u.test(a))try{document.querySelector(a)}catch(c){a=a.replace(v,function(a,b,c,d){return"["+b+c+'"'+d+'"]'});try{document.querySelector(a),d("Attribute selector with '#' must be quoted: "+b[0]),b[0]=a}catch(e){d("Attribute selector with '#' was not fixed: "+b[0])}}return r.apply(this,b)};var x;for(x in r)Object.prototype.hasOwnProperty.call(r,x)&&(a.find[x]=r[x]);a.parseJSON=function(a){return a?s.apply(this,arguments):(d("jQuery.parseJSON requires a valid JSON string"),null)},a.uaMatch=function(a){a=a.toLowerCase();var b=/(chrome)[ \/]([\w.]+)/.exec(a)||/(webkit)[ \/]([\w.]+)/.exec(a)||/(opera)(?:.*version|)[ \/]([\w.]+)/.exec(a)||/(msie) ([\w.]+)/.exec(a)||a.indexOf("compatible")<0&&/(mozilla)(?:.*? rv:([\w.]+)|)/.exec(a)||[];return{browser:b[1]||"",version:b[2]||"0"}},a.browser||(o=a.uaMatch(navigator.userAgent),p={},o.browser&&(p[o.browser]=!0,p.version=o.version),p.chrome?p.webkit=!0:p.webkit&&(p.safari=!0),a.browser=p),e(a,"browser",a.browser,"jQuery.browser is deprecated"),a.boxModel=a.support.boxModel="CSS1Compat"===document.compatMode,e(a,"boxModel",a.boxModel,"jQuery.boxModel is deprecated"),e(a.support,"boxModel",a.support.boxModel,"jQuery.support.boxModel is deprecated"),a.sub=function(){function b(a,c){return new b.fn.init(a,c)}a.extend(!0,b,this),b.superclass=this,b.fn=b.prototype=this(),b.fn.constructor=b,b.sub=this.sub,b.fn.init=function(d,e){var f=a.fn.init.call(this,d,e,c);return f instanceof b?f:b(f)},b.fn.init.prototype=b.fn;var c=b(document);return d("jQuery.sub() is deprecated"),b},a.fn.size=function(){return d("jQuery.fn.size() is deprecated; use the .length property"),this.length};var y=!1;a.swap&&a.each(["height","width","reliableMarginRight"],function(b,c){var d=a.cssHooks[c]&&a.cssHooks[c].get;d&&(a.cssHooks[c].get=function(){var a;return y=!0,a=d.apply(this,arguments),y=!1,a})}),a.swap=function(a,b,c,e){var f,g,h={};y||d("jQuery.swap() is undocumented and deprecated");for(g in b)h[g]=a.style[g],a.style[g]=b[g];f=c.apply(a,e||[]);for(g in b)a.style[g]=h[g];return f},a.ajaxSetup({converters:{"text json":a.parseJSON}});var z=a.fn.data;a.fn.data=function(b){var e,f,g=this[0];return!g||"events"!==b||1!==arguments.length||(e=a.data(g,b),f=a._data(g,b),e!==c&&e!==f||f===c)?z.apply(this,arguments):(d("Use of jQuery.fn.data('events') is deprecated"),f)};var A=/\/(java|ecma)script/i;a.clean||(a.clean=function(b,c,e,f){c=c||document,c=!c.nodeType&&c[0]||c,c=c.ownerDocument||c,d("jQuery.clean() is deprecated");var g,h,i,j,k=[];if(a.merge(k,a.buildFragment(b,c).childNodes),e)for(i=function(a){return!a.type||A.test(a.type)?f?f.push(a.parentNode?a.parentNode.removeChild(a):a):e.appendChild(a):void 0},g=0;null!=(h=k[g]);g++)a.nodeName(h,"script")&&i(h)||(e.appendChild(h),"undefined"!=typeof h.getElementsByTagName&&(j=a.grep(a.merge([],h.getElementsByTagName("script")),i),k.splice.apply(k,[g+1,0].concat(j)),g+=j.length));return k});var B=a.event.add,C=a.event.remove,D=a.event.trigger,E=a.fn.toggle,F=a.fn.live,G=a.fn.die,H=a.fn.load,I="ajaxStart|ajaxStop|ajaxSend|ajaxComplete|ajaxError|ajaxSuccess",J=new RegExp("\\b(?:"+I+")\\b"),K=/(?:^|\s)hover(\.\S+|)\b/,L=function(b){return"string"!=typeof b||a.event.special.hover?b:(K.test(b)&&d("'hover' pseudo-event is deprecated, use 'mouseenter mouseleave'"),b&&b.replace(K,"mouseenter$1 mouseleave$1"))};a.event.props&&"attrChange"!==a.event.props[0]&&a.event.props.unshift("attrChange","attrName","relatedNode","srcElement"),a.event.dispatch&&e(a.event,"handle",a.event.dispatch,"jQuery.event.handle is undocumented and deprecated"),a.event.add=function(a,b,c,e,f){a!==document&&J.test(b)&&d("AJAX events should be attached to document: "+b),B.call(this,a,L(b||""),c,e,f)},a.event.remove=function(a,b,c,d,e){C.call(this,a,L(b)||"",c,d,e)},a.each(["load","unload","error"],function(b,c){a.fn[c]=function(){var a=Array.prototype.slice.call(arguments,0);return"load"===c&&"string"==typeof a[0]?H.apply(this,a):(d("jQuery.fn."+c+"() is deprecated"),a.splice(0,0,c),arguments.length?this.bind.apply(this,a):(this.triggerHandler.apply(this,a),this))}}),a.fn.toggle=function(b,c){if(!a.isFunction(b)||!a.isFunction(c))return E.apply(this,arguments);d("jQuery.fn.toggle(handler, handler...) is deprecated");var e=arguments,f=b.guid||a.guid++,g=0,h=function(c){var d=(a._data(this,"lastToggle"+b.guid)||0)%g;return a._data(this,"lastToggle"+b.guid,d+1),c.preventDefault(),e[d].apply(this,arguments)||!1};for(h.guid=f;g<e.length;)e[g++].guid=f;return this.click(h)},a.fn.live=function(b,c,e){return d("jQuery.fn.live() is deprecated"),F?F.apply(this,arguments):(a(this.context).on(b,this.selector,c,e),this)},a.fn.die=function(b,c){return d("jQuery.fn.die() is deprecated"),G?G.apply(this,arguments):(a(this.context).off(b,this.selector||"**",c),this)},a.event.trigger=function(a,b,c,e){return c||J.test(a)||d("Global events are undocumented and deprecated"),D.call(this,a,b,c||document,e)},a.each(I.split("|"),function(b,c){a.event.special[c]={setup:function(){var b=this;return b!==document&&(a.event.add(document,c+"."+a.guid,function(){a.event.trigger(c,Array.prototype.slice.call(arguments,1),b,!0)}),a._data(this,c,a.guid++)),!1},teardown:function(){return this!==document&&a.event.remove(document,c+"."+a._data(this,c)),!1}}}),a.event.special.ready={setup:function(){this===document&&d("'ready' event is deprecated")}};var M=a.fn.andSelf||a.fn.addBack,N=a.fn.find;if(a.fn.andSelf=function(){return d("jQuery.fn.andSelf() replaced by jQuery.fn.addBack()"),M.apply(this,arguments)},a.fn.find=function(a){var b=N.apply(this,arguments);return b.context=this.context,b.selector=this.selector?this.selector+" "+a:a,b},a.Callbacks){var O=a.Deferred,P=[["resolve","done",a.Callbacks("once memory"),a.Callbacks("once memory"),"resolved"],["reject","fail",a.Callbacks("once memory"),a.Callbacks("once memory"),"rejected"],["notify","progress",a.Callbacks("memory"),a.Callbacks("memory")]];a.Deferred=function(b){var c=O(),e=c.promise();return c.pipe=e.pipe=function(){var b=arguments;return d("deferred.pipe() is deprecated"),a.Deferred(function(d){a.each(P,function(f,g){var h=a.isFunction(b[f])&&b[f];c[g[1]](function(){var b=h&&h.apply(this,arguments);b&&a.isFunction(b.promise)?b.promise().done(d.resolve).fail(d.reject).progress(d.notify):d[g[0]+"With"](this===e?d.promise():this,h?[b]:arguments)})}),b=null}).promise()},c.isResolved=function(){return d("deferred.isResolved is deprecated"),"resolved"===c.state()},c.isRejected=function(){return d("deferred.isRejected is deprecated"),"rejected"===c.state()},b&&b.call(c,c),c}}}(jQuery,window);


/*===============================
/sampo4/media/system/js/caption.js
================================================================================*/;
var JCaption=function(c){var e,b,a=function(f){e=jQuery.noConflict();b=f;e(b).each(function(g,h){d(h)})},d=function(i){var h=e(i),f=h.attr("title"),j=h.attr("width")||i.width,l=h.attr("align")||h.css("float")||i.style.styleFloat||"none",g=e("<p/>",{text:f,"class":b.replace(".","_")}),k=e("<div/>",{"class":b.replace(".","_")+" "+l,css:{"float":l,width:j}});h.before(k);k.append(h);if(f!==""){k.append(g)}};a(c)};


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
/sampo4/media/djextensions/jquery-easing/jquery.easing.min.js
================================================================================*/;
/*
 * jQuery Easing v1.3.2 - http://gsgd.co.uk/sandbox/jquery/easing/
 * Open source under the BSD License.
 * Copyright © 2008 George McGinley Smith
 * All rights reserved.
 * https://raw.github.com/gdsmith/jquery-easing/master/LICENSE
*/
(function(h){h.easing.jswing=h.easing.swing;h.extend(h.easing,{def:"easeOutQuad",swing:function(e,a,c,b,d){return h.easing[h.easing.def](e,a,c,b,d)},easeInQuad:function(e,a,c,b,d){return b*(a/=d)*a+c},easeOutQuad:function(e,a,c,b,d){return-b*(a/=d)*(a-2)+c},easeInOutQuad:function(e,a,c,b,d){return 1>(a/=d/2)?b/2*a*a+c:-b/2*(--a*(a-2)-1)+c},easeInCubic:function(e,a,c,b,d){return b*(a/=d)*a*a+c},easeOutCubic:function(e,a,c,b,d){return b*((a=a/d-1)*a*a+1)+c},easeInOutCubic:function(e,a,c,b,d){return 1>
(a/=d/2)?b/2*a*a*a+c:b/2*((a-=2)*a*a+2)+c},easeInQuart:function(e,a,c,b,d){return b*(a/=d)*a*a*a+c},easeOutQuart:function(e,a,c,b,d){return-b*((a=a/d-1)*a*a*a-1)+c},easeInOutQuart:function(e,a,c,b,d){return 1>(a/=d/2)?b/2*a*a*a*a+c:-b/2*((a-=2)*a*a*a-2)+c},easeInQuint:function(e,a,c,b,d){return b*(a/=d)*a*a*a*a+c},easeOutQuint:function(e,a,c,b,d){return b*((a=a/d-1)*a*a*a*a+1)+c},easeInOutQuint:function(e,a,c,b,d){return 1>(a/=d/2)?b/2*a*a*a*a*a+c:b/2*((a-=2)*a*a*a*a+2)+c},easeInSine:function(e,a,
c,b,d){return-b*Math.cos(a/d*(Math.PI/2))+b+c},easeOutSine:function(e,a,c,b,d){return b*Math.sin(a/d*(Math.PI/2))+c},easeInOutSine:function(e,a,c,b,d){return-b/2*(Math.cos(Math.PI*a/d)-1)+c},easeInExpo:function(e,a,c,b,d){return 0==a?c:b*Math.pow(2,10*(a/d-1))+c},easeOutExpo:function(e,a,c,b,d){return a==d?c+b:b*(-Math.pow(2,-10*a/d)+1)+c},easeInOutExpo:function(e,a,c,b,d){return 0==a?c:a==d?c+b:1>(a/=d/2)?b/2*Math.pow(2,10*(a-1))+c:b/2*(-Math.pow(2,-10*--a)+2)+c},easeInCirc:function(e,a,c,b,d){return-b*
(Math.sqrt(1-(a/=d)*a)-1)+c},easeOutCirc:function(e,a,c,b,d){return b*Math.sqrt(1-(a=a/d-1)*a)+c},easeInOutCirc:function(e,a,c,b,d){return 1>(a/=d/2)?-b/2*(Math.sqrt(1-a*a)-1)+c:b/2*(Math.sqrt(1-(a-=2)*a)+1)+c},easeInElastic:function(e,a,c,b,d){e=1.70158;var f=0,g=b;if(0==a)return c;if(1==(a/=d))return c+b;f||(f=.3*d);g<Math.abs(b)?(g=b,e=f/4):e=f/(2*Math.PI)*Math.asin(b/g);return-(g*Math.pow(2,10*--a)*Math.sin(2*(a*d-e)*Math.PI/f))+c},easeOutElastic:function(e,a,c,b,d){e=1.70158;var f=0,g=b;if(0==
a)return c;if(1==(a/=d))return c+b;f||(f=.3*d);g<Math.abs(b)?(g=b,e=f/4):e=f/(2*Math.PI)*Math.asin(b/g);return g*Math.pow(2,-10*a)*Math.sin(2*(a*d-e)*Math.PI/f)+b+c},easeInOutElastic:function(e,a,c,b,d){e=1.70158;var f=0,g=b;if(0==a)return c;if(2==(a/=d/2))return c+b;f||(f=.3*d*1.5);g<Math.abs(b)?(g=b,e=f/4):e=f/(2*Math.PI)*Math.asin(b/g);return 1>a?-.5*g*Math.pow(2,10*--a)*Math.sin(2*(a*d-e)*Math.PI/f)+c:g*Math.pow(2,-10*--a)*Math.sin(2*(a*d-e)*Math.PI/f)*.5+b+c},easeInBack:function(e,a,c,b,d,f){void 0==
f&&(f=1.70158);return b*(a/=d)*a*((f+1)*a-f)+c},easeOutBack:function(e,a,c,b,d,f){void 0==f&&(f=1.70158);return b*((a=a/d-1)*a*((f+1)*a+f)+1)+c},easeInOutBack:function(e,a,c,b,d,f){void 0==f&&(f=1.70158);return 1>(a/=d/2)?b/2*a*a*(((f*=1.525)+1)*a-f)+c:b/2*((a-=2)*a*(((f*=1.525)+1)*a+f)+2)+c},easeInBounce:function(e,a,c,b,d){return b-h.easing.easeOutBounce(e,d-a,0,b,d)+c},easeOutBounce:function(e,a,c,b,d){return(a/=d)<1/2.75?7.5625*b*a*a+c:a<2/2.75?b*(7.5625*(a-=1.5/2.75)*a+.75)+c:a<2.5/2.75?b*(7.5625*
(a-=2.25/2.75)*a+.9375)+c:b*(7.5625*(a-=2.625/2.75)*a+.984375)+c},easeInOutBounce:function(e,a,c,b,d){return a<d/2?.5*h.easing.easeInBounce(e,2*a,0,b,d)+c:.5*h.easing.easeOutBounce(e,2*a-d,0,b,d)+.5*b+c}})})(jQuery);