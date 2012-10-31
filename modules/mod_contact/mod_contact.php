<?php 
	$email = $params->get('email');
	$tel   = $params->get('tel');
	$cod   = $params->get('cod');
?>
		<div id="footer">
        	<div class="copyright small left">
            	<p class="bold">© 2011 Anons.dp.ua</p>
            	<p class="line-normal">Полное или частичное воспроизведение материалов сайта запрещенно. </p>
            	<p class="line-normal"><a href="mailtto:<?php echo $email?>"><?php echo $email?></a></p>
            </div>
        	<div class="more-links">
        	
				<div class="align_center">
				    <div class="align_center_to_left">
				        <div class="align_center_to_right">
			            	<p class="small">
			                    <a href="#">События</a>
			                    <a href="#">Места</a>
			                    <a href="#">Отчеты</a>
			                    <a href="#">Быстрый поиск</a>
			                    <a href="#">Реклама</a>
			                    <a href="#">Партнеры</a>
			                    <a href="#">Контактная информация</a>
			                </p>
				        </div>
				    </div>
				</div>
				
            </div>
        </div>