<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
	<xsl:for-each select="traffic_data/t_alert">
		<xsl:variable name="loc" select="@location"/>
		<xsl:variable name="lat" select="@lat"/>
		<xsl:variable name="lon" select="@lon"/>
		<xsl:variable name="name" select="@title"/>
		<a href="javascript:goToTraffic('{$loc}', {$lat}, {$lon}, {position() +2}, '{$name}');"><xsl:value-of select="@title"/></a>
	</xsl:for-each>
</xsl:template>

</xsl:stylesheet>