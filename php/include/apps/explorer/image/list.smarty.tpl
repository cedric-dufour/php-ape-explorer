{if $Entry.Row}<HR/>{/if}
<TABLE STYLE="WIDTH:100%;" CELLSPACING="0"><TR>
  <TD STYLE="VERTICAL-ALIGN:top;"><INPUT TYPE="checkbox" NAME="__PK" VALUE="{$Entry.PrimaryKey|escape}" /></TD>
  {if $Display.image}<TD STYLE="PADDING-LEFT:5px;">{$Output.image}</TD>{/if}
  <TD STYLE="WIDTH:95%;PADDING-LEFT:5px;VERTICAL-ALIGN:top;">
    <H3>{if $Value.iptc_name}{$Output.iptc_name} ({$Output.name}){else}{$Output.name}{/if}{if $Entry.AuthorizedDetail} - <A HREF="javascript:;" ONCLICK="javascript:{$Global.Form}_do('detail','{$Entry.PrimaryKey|escape}');">zoom</A>{/if}</H3>
    <P>
    {if ( $Display.iptc_author && ( $Value.iptc_author || !$Global.HideEmpty ) ) || ( $Display.iptc_copyright && ( $Value.iptc_copyright || !$Global.HideEmpty ) ) }
    {if $Display.iptc_author && ( $Value.iptc_author || !$Global.HideEmpty )}{$Output.iptc_author}{/if}
    {if $Display.iptc_copyright && ( $Value.iptc_copyright || !$Global.HideEmpty )}&nbsp;&copy;{$Output.iptc_copyright}{/if}
    <BR/>
    {/if}
    {if ( $Display.width && ( $Value.width || !$Global.HideEmpty ) ) || ( $Display.height && ( $Value.height || !$Global.HideEmpty ) ) || ( $Display.size && ( $Value.size || !$Global.HideEmpty ) ) }
    {if $Display.width && ( $Value.width || !$Global.HideEmpty )}&nbsp;&nbsp;x:{$Output.width}{/if}
    {if $Display.height && ( $Value.height || !$Global.HideEmpty )}&nbsp;&nbsp;y:{$Output.height}{/if}
    {if $Display.size && ( $Value.size || !$Global.HideEmpty )}&nbsp;&nbsp;s:{$Output.size}{/if}
    <BR/>
    {/if}
    </P>
  </TD>
</TR></TABLE>
