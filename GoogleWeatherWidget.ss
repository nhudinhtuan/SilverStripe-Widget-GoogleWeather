<div class="WeatherWidget">
	<% if getForecast %>
		<table width="100%">
			<% control getForecast %>
				<tr>
					<td>
						<center><img src="$icon" alt="weather icon" id="weather_widget_icon"/><center>
					</td>
					<td>
						<b>$date</b><br>
						$temp<br>
						$condition
					</td>
				</tr>
			<% end_control %>
		</table>
	<% else %>
		<center>no such location!</center>	
	<% end_if %>
</div>