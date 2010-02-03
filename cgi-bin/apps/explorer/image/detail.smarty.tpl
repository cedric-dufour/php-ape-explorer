{if $Display.image}<DIV CLASS="APE-thumbnail" STYLE="FLOAT:left;">{$Output.image}</DIV>{/if}
<DIV STYLE="FLOAT:left;PADDING-LEFT:20px;">
<H2>{if $Value.iptc_name}{$Output.iptc_name} ({$Output.name}){else}{$Output.name}{/if}</H2>
{if ( $Display.iptc_headline && ( $Value.iptc_headline || !$Global.HideEmpty ) ) || ( $Display.iptc_caption && ( $Value.iptc_caption || !$Global.HideEmpty ) )}
<BLOCKQUOTE>
{if ( $Display.iptc_headline && ( $Value.iptc_headline || !$Global.HideEmpty ) )}<P CLASS="value"><B>{$Output.iptc_headline}</B></P>{/if}
{if ( $Display.iptc_caption && ( $Value.iptc_caption || !$Global.HideEmpty ) )}<P CLASS="value"><I>{$Output.iptc_caption}</I></P>{/if}
</BLOCKQUOTE>
{/if}
<BR/>
{if ( $Display.iptc_author && ( $Value.iptc_author || !$Global.HideEmpty ) ) || ( $Display.iptc_copyright && ( $Value.iptc_copyright || !$Global.HideEmpty ) ) || ( $Display.iptc_category && ( $Value.iptc_category || !$Global.HideEmpty ) ) || ( $Display.iptc_subcategories && ( $Value.iptc_subcategories || !$Global.HideEmpty ) ) || ( $Display.iptc_keywords && ( $Value.iptc_keywords || !$Global.HideEmpty ) )}
<H3>Image authoring:</H3>
<BLOCKQUOTE>
{if $Display.iptc_author && ( $Value.iptc_author || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.iptc_author|escape}">{$Name.iptc_author|escape}:</SPAN><SPAN CLASS="value">{$Output.iptc_author}</SPAN><BR/>{/if}
{if $Display.iptc_copyright && ( $Value.iptc_copyright || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.iptc_copyright|escape}">{$Name.iptc_copyright|escape}:</SPAN><SPAN CLASS="value">{$Output.iptc_copyright}</SPAN><BR/>{/if}
{if $Display.iptc_category && ( $Value.iptc_category || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.iptc_category|escape}">{$Name.iptc_category|escape}:</SPAN><SPAN CLASS="value">{$Output.iptc_category}</SPAN><BR/>{/if}
{if $Display.iptc_subcategories && ( $Value.iptc_subcategories || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.iptc_subcategories|escape}">{$Name.iptc_subcategories|escape}:</SPAN><SPAN CLASS="value">{$Output.iptc_subcategories}</SPAN><BR/>{/if}
{if $Display.iptc_keywords && ( $Value.iptc_keywords || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.iptc_keywords|escape}">{$Name.iptc_keywords|escape}:</SPAN><SPAN CLASS="value">{$Output.iptc_keywords}</SPAN><BR/>{/if}
</BLOCKQUOTE>
{/if}
{if ( $Display.width && ( $Value.width || !$Global.HideEmpty ) ) || ( $Display.height && ( $Value.height || !$Global.HideEmpty ) ) || ( $Display.size && ( $Value.size || !$Global.HideEmpty ) ) || ( $Display.format && ( $Value.format || !$Global.HideEmpty ) )}
<H3>Technical data:</H3>
<BLOCKQUOTE>
{if $Display.width && ( $Value.width || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.width|escape}">{$Name.width|escape}:</SPAN><SPAN CLASS="value">{$Output.width}</SPAN><BR/>{/if}
{if $Display.height && ( $Value.height || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.height|escape}">{$Name.height|escape}:</SPAN><SPAN CLASS="value">{$Output.height}</SPAN><BR/>{/if}
{if $Display.size && ( $Value.size || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.size|escape}">{$Name.size|escape}:</SPAN><SPAN CLASS="value">{$Output.size}</SPAN><BR/>{/if}
{if $Display.format && ( $Value.format || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.format|escape}">{$Name.format|escape}:</SPAN><SPAN CLASS="value">{$Output.format}</SPAN><BR/>{/if}
</BLOCKQUOTE>
{/if}
{if ( $Display.exif_timestamp && ( $Value.exif_timestamp || !$Global.HideEmpty ) ) || ( $Display.exif_camerabrand && ( $Value.exif_camerabrand || !$Global.HideEmpty ) ) || ( $Display.exif_cameramodel && ( $Value.exif_cameramodel || !$Global.HideEmpty ) ) || ( $Display.exif_exposure && ( $Value.exif_exposure || !$Global.HideEmpty ) ) || ( $Display.exif_aperture && ( $Value.exif_aperture || !$Global.HideEmpty ) ) || ( $Display.exif_sensitivity && ( $Value.exif_sensitivity || !$Global.HideEmpty ) )}
<H3>Shot data:</H3>
<BLOCKQUOTE>
{if $Display.exif_timestamp && ( $Value.exif_timestamp || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.exif_timestamp|escape}">{$Name.exif_timestamp|escape}:</SPAN><SPAN CLASS="value">{$Output.exif_timestamp}</SPAN><BR/>{/if}
{if $Display.exif_camerabrand && ( $Value.exif_camerabrand || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.exif_camerabrand|escape}">{$Name.exif_camerabrand|escape}:</SPAN><SPAN CLASS="value">{$Output.exif_camerabrand}</SPAN><BR/>{/if}
{if $Display.exif_cameramodel && ( $Value.exif_cameramodel || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.exif_cameramodel|escape}">{$Name.exif_cameramodel|escape}:</SPAN><SPAN CLASS="value">{$Output.exif_cameramodel}</SPAN><BR/>{/if}
{if $Display.exif_exposure && ( $Value.exif_exposure || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.exif_exposure|escape}">{$Name.exif_exposure|escape}:</SPAN><SPAN CLASS="value">{$Output.exif_exposure}</SPAN><BR/>{/if}
{if $Display.exif_aperture && ( $Value.exif_aperture || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.exif_aperture|escape}">{$Name.exif_aperture|escape}:</SPAN><SPAN CLASS="value">{$Output.exif_aperture}</SPAN><BR/>{/if}
{if $Display.exif_sensitivity && ( $Value.exif_sensitivity || !$Global.HideEmpty )}<P><SPAN CLASS="label" TITLE="{$Description.exif_sensitivity|escape}">{$Name.exif_sensitivity|escape}:</SPAN><SPAN CLASS="value">{$Output.exif_sensitivity}</SPAN><BR/>{/if}
</BLOCKQUOTE>
{/if}
</DIV>
