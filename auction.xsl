<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
	<xsl:template match="/">
		<xsl:for-each select="listings/listing">
			<div class="listing">
				<table>
					<tr>
						<td>Item no:</td>
						<td><xsl:value-of select="item_num"/></td>
					</tr>
					<tr>
						<td>Item name:</td>
						<td><xsl:value-of select="item"/></td>
					</tr>
					<tr>
						<td>Category:</td>
						<td><xsl:value-of select="category"/></td>
					</tr>
					<tr>
						<td>Description:</td>
						<td><xsl:value-of select="description"/></td>
					</tr>
					<tr>
						<td>Buy It Now Price:</td>
						<td><xsl:value-of select="bPrice"/></td>
					</tr>
					<tr>
						<td>Bid Price:</td>
						<td><xsl:value-of select="bid"/></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<xsl:choose>
								<xsl:when test="@status = 'sold'">
									Sold
								</xsl:when>
								<xsl:when test="@status = 'failed'">
									Expired
								</xsl:when>
								<xsl:otherwise>
									<xsl:value-of select="timeremaining"/>
								</xsl:otherwise>
							</xsl:choose>
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<xsl:if test="@status = 'in_progress' and timeremaining != 'Expired'">
								<button class="place-bid-button">
									<xsl:attribute name="data-item-num">
										<xsl:value-of select="item_num"/>
									</xsl:attribute>
									Place Bid
								</button>
								<button class="buy-it-now-button">
									<xsl:attribute name="data-item-num">
										<xsl:value-of select="item_num"/>
									</xsl:attribute>
									Buy It Now
								</button>
							</xsl:if>
						</td>
					</tr>
				</table>
			</div>
		</xsl:for-each>
	</xsl:template>
</xsl:stylesheet>