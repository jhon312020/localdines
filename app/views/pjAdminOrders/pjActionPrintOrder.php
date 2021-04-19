<?php

// print_r("<pre>" + $tpl['template_arr'] + "</pre>");
// exit;

if(isset($tpl['template_arr']))
{ 
	
	?>
	<table class="table">
		<tbody>
			<tr>
				<td><?php echo $tpl['template_arr'];?></td>
			</tr>
		</tbody>
	</table>
	<?php
} 
?>