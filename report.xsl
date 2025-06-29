<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template match="/">
		<table>
			<tr>
				<th>item num</th>
				<th>ownerId</th>
				<th>item</th>
				<th>category</th>
				<th>start price</th>
				<th>reserve price</th>
				<th>buy-it-now price</th>
				<th>day</th>
				<th>hour</th>
				<th>minute</th>
				<th>start date</th>
				<th>start time</th>
				<th>bid</th>
				<th>bidderId</th>
				<th>status</th>
				<th>revenue</th>
			</tr>
			<xsl:for-each select="listings/listing">
				<tr>
					<td><xsl:value-of select="item_num"/></td>
					<td><xsl:value-of select="ownerId"/></td>
					<td><xsl:value-of select="item"/></td>
					<td><xsl:value-of select="category"/></td>
					<td><xsl:value-of select="sPrice"/></td>
					<td><xsl:value-of select="rPrice"/></td>
					<td><xsl:value-of select="bPrice"/></td>
					<td><xsl:value-of select="day"/></td>
					<td><xsl:value-of select="hour"/></td>
					<td><xsl:value-of select="minute"/></td>
					<td><xsl:value-of select="startDate"/></td>
					<td><xsl:value-of select="startTime"/></td>
					<td><xsl:value-of select="bid"/></td>
					<td><xsl:value-of select="bidderId"/></td>
					<td><xsl:value-of select="@status"/></td>
					<td><xsl:value-of select="revenue"/></td>
				</tr>
			</xsl:for-each>
		</table>
	</xsl:template>
</xsl:stylesheet>