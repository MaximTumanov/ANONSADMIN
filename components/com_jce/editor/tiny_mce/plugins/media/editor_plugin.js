(function(){var each=tinymce.each,extend=tinymce.extend,JSON=tinymce.util.JSON;var Node=tinymce.html.Node;var Styles=new tinymce.html.Styles({url_converter:function(url){return self.convertUrl(url)}});function toArray(obj){var undef,out,i;if(obj&&!obj.splice){out=[];for(i=0;true;i++){if(obj[i])out[i]=obj[i];else break}return out}return obj};function ucfirst(s){return s.charAt(0).toUpperCase()+s.substring(1)};var mediaTypes={"Flash":{classid:"CLSID:D27CDB6E-AE6D-11CF-96B8-444553540000",type:"application/x-shockwave-flash",codebase:"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,1,53,64"},"ShockWave":{classid:"CLSID:166B1BCA-3F9C-11CF-8075-444553540000",type:"application/x-director",codebase:"http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=10,2,0,023"},"WindowsMedia":{classid:"CLSID:6BF52A52-394A-11D3-B153-00C04F79FAA6",type:"application/x-mplayer2",codebase:"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=10,00,00,3646"},"QuickTime":{classid:"CLSID:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B",type:"video/quicktime",codebase:"http://www.apple.com/qtactivex/qtplugin.cab#version=7,3,0,0"},"DivX":{classid:"CLSID:67DABFBF-D0AB-41FA-9C46-CC0F21721616",type:"video/divx",codebase:"http://go.divx.com/plugin/DivXBrowserPlugin.cab"},"RealMedia":{classid:"CLSID:CFCDAA03-8BE4-11CF-B84B-0020AFBBCCFA",type:"audio/x-pn-realaudio-plugin"},"Java":{classid:"CLSID:8AD9C840-044E-11D1-B3E9-00805F499D93",type:"application/x-java-applet",codebase:"http://java.sun.com/products/plugin/autodl/jinstall-1_5_0-windows-i586.cab#Version=1,5,0,0"},"Silverlight":{classid:"CLSID:DFEAF541-F3E1-4C24-ACAC-99C30715084A",type:"application/x-silverlight-2"},"Video":{type:'video/mp4'},"Audio":{type:'audio/mp3'}};tinymce.create('tinymce.plugins.MediaPlugin',{init:function(ed,url){var self=this,lookup={};var cbase={"Flash":{codebase:"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version="+ed.getParam('media_version_flash','10,1,53,64')},"ShockWave":{codebase:"http://download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version="+ed.getParam('media_version_shockwave','10,2,0,023')},"WindowsMedia":{codebase:"http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version="+ed.getParam('media_version_windowsmedia','10,00,00,3646')},"QuickTime":{codebase:"http://www.apple.com/qtactivex/qtplugin.cab#version="+ed.getParam('media_version_quicktime','7,3,0,0')},"Java":{codebase:"http://java.sun.com/products/plugin/autodl/jinstall-1_5_0-windows-i586.cab#Version="+ed.getParam('media_version_java','1,5,0,0')}};each(cbase,function(v,k){extend(mediaTypes[k],v)});this.mimes={};(function(data){var items=data.split(/,/),i,y,ext;for(i=0;i<items.length;i+=2){ext=items[i+1].split(/ /);for(y=0;y<ext.length;y++){self.mimes[ext[y]]=items[i]}}})("application/x-director,dcr"+"video/divx,divx"+"application/pdf,pdf,"+"application/x-shockwave-flash,swf swfl,"+"audio/mpeg,mpga mpega mp2 mp3,"+"audio/ogg,ogg spx oga,"+"audio/x-wav,wav,"+"video/mpeg,mpeg mpg mpe,"+"video/mp4,mp4 m4v,"+"video/ogg,ogg ogv,"+"video/webm,webm,"+"video/quicktime,qt mov,"+"video/x-flv,flv,"+"video/vnd.rn-realvideo,rv"+"video/3gpp,3gp"+"video/x-matroska,mkv");self.editor=ed;self.url=url;scriptRegExp='';each(mediaTypes,function(v,k){v.name=k;if(v.classid){lookup[v.classid]=v}if(v.type){lookup[v.type]=v}lookup['mceItem'+k]=v;lookup[k.toLowerCase()]=v});self.lookup=lookup;function isMedia(n){return n&&n.nodeName=='IMG'&&/mceItem(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia|DivX|Silverlight|Audio|Video|Generic|Iframe)/.test(n.className)};ed.onPreInit.add(function(){ed.schema.addValidElements('object[id|style|width|height|classid|codebase|*],param[name|value],embed[id|style|width|height|type|src|*],video[*],audio[*],source[*]');ed.schema.addValidElements('iframe[longdesc|name|src|frameborder|marginwidth|marginheight|scrolling|align|width|height|allowtransparency|*]');ed.schema.addCustomElements('comment');var invalid=tinymce.explode(ed.settings.invalid_elements,',');ed.parser.addNodeFilter('object,embed,video,audio,script,iframe',function(nodes){for(var i=0,len=nodes.length;i<len;i++){if(tinymce.inArray(invalid,nodes[i])==-1){self.toImage(nodes[i])}else{nodes[i].remove()}}});ed.serializer.addNodeFilter('img',function(nodes,name,args){for(var i=0,len=nodes.length;i<len;i++){var node=nodes[i];if(/mceItem(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia|DivX|Silverlight|Audio|Video|Generic|Iframe)/.test(node.attr('class')||'')){self.restoreElement(node,args)}}})});ed.onInit.add(function(){if(ed.theme&&ed.theme.onResolveName){ed.theme.onResolveName.add(function(theme,o){if(o.name==='img'&&/mceItem(Object|Embed|Audio|Video|Generic)/.test(o.node.className)){o.name='media'}if(o.name==='img'&&/mceItemIframe/.test(o.node.className)){o.name='iframe'}})}if(!ed.settings.compress.css)ed.dom.loadCSS(url+"/css/content.css")});ed.onBeforeSetContent.add(function(ed,o){var h=o.content;h=h.replace(/<(audio|embed|object|video|iframe)([^>]*?)>([\w\W]+?)<\/\1>/gi,function(a,b,c,d){d=d.replace(/<!--\[if([^\]]*)\]>(<!)?-->/gi,'<![if$1]>');d=d.replace(/<!\[if([^\]]+)\]>/gi,function(a,b){return'<comment data-comment-condition="[if'+b+']">'});d=d.replace(/<!(--<!)?\[endif\](--)?>/gi,'</comment>');return'<'+b+c+'>'+d+'</'+b+'>'});o.content=h})},getInfo:function(){return{longname:'Media',author:'Ryan Demmer',authorurl:'http://www.joomlacontenteditor.net',infourl:'http://www.joomlacontenteditor.net',version:'2.0.9'}},convertUrl:function(url,force_absolute){var self=this,ed=self.editor,settings=ed.settings,converter=settings.url_converter,scope=settings.url_converter_scope||self;if(!url)return url;if(force_absolute)return ed.documentBaseURI.toAbsolute(url);return converter.call(scope,url,'src','object')},createTemplate:function(n,o){var self=this,ed=this.editor,dom=ed.dom,nn,hc,cn,html;hc=n.firstChild;nn=n.name;o=o||{};function is_child(n){return/^(audio|embed|object|video|iframe)$/.test(n.parent.name)}if(/^(audio|embed|object|param|source|video|iframe)$/.test(nn)){var at=this.serializeAttributes(n);switch(nn){case'audio':case'embed':case'object':case'video':case'iframe':case'param':if(hc||is_child(n)){if(typeof o[nn]=='undefined'){o[nn]={}}extend(o[nn],at);o=o[nn]}else{extend(o,at)}break;case'source':if(typeof o[nn]=='undefined'){o[nn]=[]}o[nn].push(at);break}if(hc){cn=n.firstChild;while(cn){self.createTemplate(cn,o);cn=cn.next}}}else{if(nn=='comment'){if(v=n.attr('data-comment-condition')){if(typeof o[nn]=='undefined'){o[nn]={}}extend(o[nn],{'data-comment-condition':v});if(hc){cn=n.firstChild;o=o[nn];while(cn){self.createTemplate(cn,o);cn=cn.next}}}else{v=new tinymce.html.Serializer({inner:true,validate:false}).serialize(n);if(typeof o[nn]=='undefined'){o[nn]=[tinymce.trim(v)]}else{o[nn].push(tinymce.trim(v))}}}else{html=new tinymce.html.Serializer().serialize(n)}}if(html){if(typeof o.html=='undefined'){o.html=[]}o.html.push(html)}return o},toImage:function(n){var self=this,ed=this.editor,type,name,o={},data={},classid='',styles,matches;if(!n.parent||/^(object|audio|video|embed|iframe)$/.test(n.parent.name))return;var img=new Node('img',1);if(n.name==='script'){if(n.firstChild)matches=/(JCEMediaObject|write(Flash|ShockWave|QuickTime|RealMedia|WindowsMedia|DivX))/i.exec(n.firstChild.value);if(!matches)return;type=matches[1].toLowerCase();data=JSON.parse(matches[2]);w=data.width;h=data.height;name='object'}else{name=n.name;var style=Styles.parse(n.attr('style'));var w=n.attr('width')||style.width||'';var h=n.attr('height')||style.height||'';if(name=='embed'&&type=='application/x-shockwave-flash'){name='object';each(['bgcolor','flashvars','wmode','allowfullscreen','allowscriptaccess','quality'],function(k){var v=n.attr(k);if(v){if(k=='flashvars'){v=encodeURIComponent(v)}data[k]=v}})}data=this.createTemplate(n);each(['audio','embed','object','video','iframe'],function(el){each(n.getAll(el),function(node){node.remove()})});if(n.attr('classid')){classid=n.attr('classid').toUpperCase()}if(name=='object'){if(!data.data){var param=data.param;if(param){data.data=param.src||param.url||param.movie||param.source}}var lookup=this.lookup[classid]||this.lookup[n.attr('type')]||this.lookup[name]||this.lookup['flash'];type=lookup.name||''}else{if(!data.src){if(data.source){data.src=data.source[0].src}}}var style=Styles.parse(n.attr('style'));each(['bgcolor','align','border','vspace','hspace'],function(na){var v=n.attr(na);if(v){switch(na){case'bgcolor':style['background-color']=v;break;case'align':if(/^(left|right)$/.test(v)){style['float']=v}else{style['vertical-align']=v}break;case'vspace':style['margin-top']=v;style['margin-bottom']=v;break;case'hspace':style['margin-left']=v;style['margin-right']=v;break;default:style[na]=v;break}}});each(['id','lang','dir','tabindex','xml:lang','style','title'],function(at){img.attr(at,n.attr(at))});if(styles=ed.dom.serializeStyle(style)){img.attr('style',styles)}}o[name]=data;n.replace(img);var classes=[];if(n.attr('class')){classes=n.attr('class').split(' ')}classes.push('mceItem'+ucfirst(name)+(type?' mceItem'+ucfirst(type):''));if(name=='audio'){var agent=navigator.userAgent.match(/(Opera|Chrome|Safari|Gecko)/);if(agent){classes.push('mceItemAgent'+ucfirst(agent[0]))}}img.attr({src:this.url+'/img/trans.gif',width:w,height:h,'class':classes.join(' '),'data-mce-json':JSON.serialize(o)})},serializeAttributes:function(n){var self=this,dom=this.editor.dom,attribs={},ti='';if(n!='iframe'||n!='param'){var type=n.attr('type'),src=n.attr('src')||n.attr('data');if(!type&&src){var ext;if(/\.([a-z0-9]{2,4})/.test(src)){ext=/\.([a-z0-9]{2,4})/.exec(src);ext=ext[1]||''}if(ext){attribs.type=this.mimes[ext]}}}if(n.name=='param'){k=n.attr('name');v=n.attr('value');if(k&&v!=''){if(k=='flashvars'){v=encodeURIComponent(v)}}attribs[k]=v}else{for(k in n.attributes.map){v=n.attributes.map[k];switch(k){case'poster':case'src':case'data':attribs[k]=self.convertUrl(v);break;case'autoplay':case'controls':case'loop':attribs[k]=k;break;case'allowfullscreen':attribs[k]=(v=='')?true:v;break;case'type':attribs[k]=v.replace(/"/g,"'");break;default:attribs[k]=v;break}}}if(n.name=='embed'&&n.parent.name=='object'){var params=n.parent.getAll('param');if(params){each(params,function(p){k=p.attr('name');v=p.attr('value');if(k&&v!=''){if(k=='flashvars'){v=encodeURIComponent(v)}}attribs[k]=v})}}return attribs},createNodes:function(data,el){var self=this,ed=this.editor,s;function createNode(o,el){each(o,function(v,k){var nn=el.name;if(tinymce.is(v,'object')){if(/(param|source)/.test(nn)&&/(audio|embed|object|video)/.test(k)){el=el.parent}if(k=='comment'){node=new Node('#comment',8);node.value=v['data-comment-condition']+'>';delete v['data-comment-condition'];el.append(node);createNode(v,el);node=new Node('#comment',8);node.value='<![endif]';el.append(node)}else{if(v instanceof Array){each(v,function(s){if(tinymce.is(s,'string')){self.setAttribs(el,data,k,s)}else{node=new Node(k,1);if(k=='source'){node.shortEnded=true}createNode(s,node);el.append(node)}})}else{if(k=='param'){for(n in v){param=new Node(k,1);param.shortEnded=true;self.setAttribs(param,data,n,v[n]);el.append(param)}}else{node=new Node(k,1);el.append(node);createNode(v,node)}}}}else{if(nn=='#comment'){comment=new Node('#comment',8);comment.value=dom.decode(v);el.append(comment)}else{self.setAttribs(el,data,k,v)}}})}createNode(data,el);return el},setAttribs:function(n,data,k,v){var ed=this.editor,dom=ed.dom,nn=n.name;if(v==null||typeof v=='undefined'){return}if(nn=='param'){switch(k){case'flashvars':v=decodeURIComponent(v);break;case'src':case'movie':case'source':case'url':v=this.convertUrl(v);break}n.attr('name',k);n.attr('value',v.toString())}else{switch(k){case'width':case'height':v=data[k]||v;n.attr(k,v.toString());break;case'class':var cls=tinymce.explode(' ',n.attr('class'));if(tinymce.inArray(cls,v)==-1){cls.push(tinymce.trim(v))}n.attr('class',tinymce.trim(cls.join(' ',v)));break;case'type':n.attr(k,v.replace(/(&(quot|apos);|")/g,"'"));break;case'flashvars':n.attr(k,decodeURIComponent(v));break;case'src':case'data':case'source':n.attr(k,this.convertUrl(v));break;case'html':var html=new Node('#text',3);html.raw=true;html.value=(n.value?n.value:'')+dom.decode(v);n.append(html);break;default:if(!k||typeof v=='undefined'){return}n.attr(k,v.toString());break}}},getMimeType:function(s){var props,type,ext,cl=s.match(/mceItem(Flash|ShockWave|WindowsMedia|QuickTime|RealMedia|DivX|PDF|Silverlight|IFrame)/);if(cl){props=mediaTypes[cl[1]];if(props){type=props.type}}if(!props||type){if(/\.([a-z0-9]{2,4})/.test(s)){ext=s.substring(s.length,s.lastIndexOf('.')+1).toLowerCase();type=this.mimes[ext]}}return type},restoreElement:function(n,args){var self=this,ed=this.editor,dom=ed.dom,cl,props;var data=JSON.parse(n.attr('data-mce-json'));var name=this.getNodeName(n.attr('class'));var parent=new Node(name,1);var root=data[name];var src=root.src||root.data||'';var params=root.param||'';each(['width','height'],function(k){v=n.attr(k);if(v&&name!='audio'){if(!root[k]||root[k]!=v){root[k]=v}}each(['object','embed','video'],function(s){if(root[s]&&!root[s][k]){root[s][k]=v}})});each(['id','lang','dir','tabindex','xml:lang','style','title','class'],function(at){v=n.attr(at);if(at=='class'){v=tinymce.trim(v.replace(/\s?mceItem([\w]+)/g,''))}if(/[\w\d]+/.test(v)){root[at]=v}});var strict=ed.getParam('media_strict',true)&&/mceItemFlash/.test(n.attr('class'));if(name=='object'){if(!root.type){root.type=this.getMimeType(n.attr('class'))||this.getMimeType(src)}params=params||{};delete root.src;if(strict){root.data=src;extend(params,{'movie':src});delete params.src;delete root.embed;delete root.classid;delete root.codebase}else{if(!root.embed){root.embed={width:root.width,height:root.height,src:src,type:root.type||this.getMimeType(n.attr('class'))||this.getMimeType(src)}}var lookup=this.lookup[root.type]||this.lookup[name]||this.lookup['flash'];if(!root.classid){root.classid=lookup.classid}if(!root.codebase){root.codebase=lookup.codebase}for(k in params){if(/^(movie|source|url)$/.test(k)){root.embed.src=params[k]}else{root.embed[k]=params[k]}}var k='src';if(/mceItemWindowsMedia/.test(n.attr('class'))){k='url'}if(/mceItemSilverLight/.test(n.attr('class'))){k='source'}params[k]=src;var props=this.lookup[name];extend(root,props);delete root.data;delete root.type}}else{if(root.src&&root.source){delete root.src}}if(params){root.param=params}n.replace(this.createNodes(root,parent))},getNodeName:function(s){s=/mceItem(Audio|Embed|Object|Video|Iframe)/.exec(s);if(s){return s[1].toLowerCase()}}});tinymce.PluginManager.add('media',tinymce.plugins.MediaPlugin)})();