<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/rss">
	<html>
	<head>
		<style type="text/css">
			body{background-color:#FFF;font-family:Verdana,Arial,SunSans-Regular,Sans-Serif;font-size:small;line-height:1.4em;margin:0;padding:0}a:visited{color:#666}a:hover{color:red}#logo{border-top:.2em solid #235B9C;background-color:#4483C7;padding:.2em .4em .4em;color:#FFF;font-family:"Trebuchet MS",Verdana,Arial,SunSans-Regular,Sans-Serif;font-size:240%}#logo a:link,#logo a:visited{text-decoration:none;color:#FFF}.Snippet{margin-bottom:30px;border:1px solid #538620;background-color:#FFE}.Snippet .title{background-color:#538620;color:#FFF;font-family:Tahoma,"Lucida Sans Unicode",Verdana,sans-serif;font-size:86%;letter-spacing:.1em;margin:0;padding:.3em 1em}.Snippet .titleWithLine{background-color:#FFF;border-bottom:1px solid #538620;color:#538620;font-family:Tahoma,"Lucida Sans Unicode",Verdana,sans-serif;font-size:86%;letter-spacing:.1em;margin:0;padding:.3em 1em}.Snippet table{font-size:86%}.Snippet ol li,.Snippet ul li{margin-bottom:.4em;margin-left:-.5em;font-size:86%;line-height:145%}#Snippet ul li{list-style-type:square}.Snippet dl{margin-left:1em}.Snippet dl dd{margin:0;font-family:Tahoma,Verdana,Arial,Helvetica,sans-serif;display:block;padding:.4em .2em .4em .6em;color:#FFF;border-bottom:1px solid #000;font-weight:700;font-size:120%;background-color:#4483C7}.Snippet dl dt{border:1px solid #4483C7;font-size:86%;line-height:145%;margin-bottom:.6em;padding:.4em}.Snippet dl dt .comments{color:#000;font-size:110%;border-bottom:1px solid #CCC;display:block}.Snippet .updated{color:#666;font-size:80%;padding:0 1em;text-align:center}.Snippet dl dd a,.Snippet dl dd a:hover,.Snippet dl dd a:visited{color:#FFF}#generator span{color: #da3114;font-weight: 700;}
		</style>
	</head>
	<body>
		<div id="logo">
			<xsl:element name="a">
				<xsl:attribute name="href">
					<xsl:value-of select="channel/link" />
				</xsl:attribute>
				<xsl:value-of select="channel/title" />
			</xsl:element>
		</div>
		<div id="generator">
			<xsl:element name="span">
				Generated by <a target="_blank" href="http://storejextensions.org/extensions/jsitemap_professional.html"><xsl:value-of select="channel/generator" /></a>
			</xsl:element>
		</div>
		<div class="Snippet" style="border-width:0; background-color:#FFF; margin:1em">
			<div class="titleWithLine">
				<xsl:value-of select="channel/description" /><br />
				<xsl:value-of select="channel/webMaster" /><br />
				
				<xsl:if test="channel/image/url != ''">
					<xsl:variable name="imagelink"><xsl:value-of select="channel/image/link" /></xsl:variable>
					<xsl:variable name="imageurl"><xsl:value-of select="channel/image/url" /></xsl:variable>
					<a target='_blank' href='{$imagelink}'><img src='{$imageurl}'/></a>
				</xsl:if>
			</div>
			<dl style="padding-right:1em">
				<xsl:for-each select="channel/item">
					<dd>
						<xsl:element name="a">
							<xsl:attribute name="href">
								<xsl:value-of select="link"/>
							</xsl:attribute>
							<xsl:value-of select="title"/>
						</xsl:element>
					</dd>
					<dt>
						<xsl:value-of select="description" /><br /><br />
						<span class="comments"><xsl:value-of select="author" /></span>
						<span class="comments"><xsl:value-of select="category" /></span>
						<span class="comments"><xsl:value-of select="pubDate" /></span>
					</dt>
				</xsl:for-each>
			</dl>
		</div>
		<div id="footer">
			<xsl:value-of select="channel/copyright" />
		</div>
	</body>
	</html>
</xsl:template>
</xsl:stylesheet>