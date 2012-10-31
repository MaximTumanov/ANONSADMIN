<style type="text/css">
	.red{color: red;}
	.yellow{background: yellow; color: #000;}
	.green{color: green;}
</style>
<form action="index.php?option=<?php echo $this->component?>&view=<?php echo $this->view?>" method="post" name="adminForm">
<div id="editcell">
	<table class="adminlist">
		<tr>
			<td>
				<div style="padding: 25px">
					<table>
						<tr>
							<td valign="top">
								<b style="color: red">Все-все поля обязательные!</b>
								<p><b>Что, извините, будем парсить?</b></p>
								<p><b style="color: red">ссылки включая http по одной в строке</b></p>
								<textarea style="width: 400px; height: 100px" name="links"></textarea>
								
								<p><b>А организатор-то кто?</b></p>
								<?php echo $this->placeList?>
							</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
</div>

<input type="hidden" name="option" value="<?php echo $this->component?>" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="<?php echo $this->view?>" />
<input type="hidden" name="task" value="" />
</form>