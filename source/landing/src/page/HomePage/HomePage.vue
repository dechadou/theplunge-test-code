<script src="./HomePage.js"></script>

<template>
	<div>
		<nav class="navbar fixed-top">
			<div class="container">
				<router-link to="/" class="logo">
					<svg class="logo logo1" enable-background="new 0 0 203.9 31.9" version="1.1" viewBox="0 0 203.9 31.9" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
						<path class="st0" d="M28.3,30.6l-1.6-4.9H15.2l-1.6,4.9H1.8l13-30H27l13,30H28.3z M20.9,9l-3.4,10.2h7L20.9,9z"/>
						<path class="st0" d="m94 27c-2.8 3.3-7.6 3.6-11.4 3.6h-23.1v-30h25.2c3 0 6.7 0.2 8.5 3.1 0.4 0.7 1 2 1 3.9 0 0.7 0 2.6-1.3 4.2-0.7 0.8-2 2-4.5 2.6 1.9 0.3 3.6 0.6 5.1 1.9 1.8 1.5 2.2 3.3 2.2 5.5 0.1 0.9 0 3.1-1.7 5.2zm-11.6-8.4h-12.1v5.2h11.3c0.1 0 0.9 0 1.5-0.1 1.4-0.3 2-1.3 2-2.6 0-2.5-2-2.5-2.7-2.5zm1.2-10.1c-0.6-0.9-1.6-1.1-2.5-1.1h-10.7v4.7h9.9c1.1 0 2.7 0.1 3.4-1.1 0.2-0.3 0.3-0.8 0.3-1.2 0-0.3 0-0.8-0.4-1.3z"/>
						<path class="st0" d="m139 30.6l-6.2-10.9h-5.7v10.9h-10.8v-30h22.1c4 0 7.9 0.3 10.3 4.1 0.6 0.9 1.5 2.7 1.5 5.4 0 1.4-0.3 5.4-4.1 7.5-0.7 0.4-1.3 0.6-2.8 1l7.5 12h-11.8zm0.1-21.9c-0.7-1.1-1.9-1.2-3.1-1.2h-8.9v5.5h8.1c2 0 3.1-0.3 3.8-1.1 0.6-0.7 0.6-1.4 0.6-1.7s-0.1-0.9-0.5-1.5z"/>
						<path class="st0" d="M170.5,30.6v-30h31.7v6.7h-20.9v4.9h19.3V19h-19.3v4.8h21.3v6.8H170.5z"/>
					</svg>
				</router-link>
				<ul class="navigation d-none d-md-block">
					<li><router-link to="/">Home</router-link></li>
					<li><router-link to="/nosotros">Nosotros</router-link></li>
					<li><router-link to="/faq">FAQ</router-link></li>
				</ul>
			</div>
		</nav>
		<section id="hero" v-bind:style="{ height: winHeight + 'px' }">
			<div class="fullscreen-bg">
				<video ref="topVid" loop muted autoplay playsinline class="fullscreen-bg__video">
					<source :src="`${$versionRoot}video/` + videoUrl + `_top.webm`" type="video/webm">
				</video>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-12 col-xl-9">
						<div class="slider">
							<h1 class="slide_item item_fade" v-for="heroText in heroTexts" v-html="heroText"></h1>
						</div>
					</div>
				</div>
				<svg class="flecha" enable-background="new 0 0 36.2 64.6" version="1.1" viewBox="0 0 36.2 64.6" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
					<line class="st0" x1="18.8" x2="18.8" y1="2.5" y2="62.5"/>
					<polyline class="st0" points="33.8 47.5 18.8 62.5 3.8 47.5"/>
				</svg>

			</div>
		</section>
		<section id="what-we-do">

				<div class="col-12 col-md-10 offset-md-1">
					<div class="row">
						<div class="item col-12 col-md-4">
							<div class="row no-gutters">
								<div class="col-4 col-md-6 offset-md-3">
									<svg enable-background="new 0 0 200 200" version="1.1" viewBox="0 0 200 200" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
										<g fill="none" stroke="#4D4D4D" stroke-miterlimit="10" stroke-width="1.5">
											<circle cx="100" cy="60.62" r="15"/>
											<line x1="100" x2="100" y1="45.62" y2="30.62"/>
												<line x1="150.72" x2="106.33" y1="169.38" y2="74.203"/>
											<line x1="93.667" x2="49.285" y1="74.203" y2="169.38"/>
											<path d="m147.73 108.35c-12.216 12.215-29.091 19.77-47.729 19.77-18.64 0-35.515-7.555-47.729-19.77"/>
										</g>
									</svg>
								</div>
								<div class="col-8 col-md-12 border-top">
									<h2>Diseño</h2>
									<p>Co-diseñamos el producto con el creador y su comunidad.</p>
								</div>
							</div>
						</div>
						<div class="item col-12 col-md-4">
							<div class="row no-gutters">
								<div class="col-4 col-md-6 offset-md-3">
									<svg enable-background="new 0 0 200 200" version="1.1" viewBox="0 0 200 200" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
										<g fill="none" stroke="#4D4D4D" stroke-miterlimit="10" stroke-width="1.5">
											<polyline points="35.048 130 100 167.5 164.95 130"/>
											<polyline points="35.048 100 100 137.5 164.95 100"/>
											<polygon points="100 32.5 164.95 70 100 107.5 35.048 70"/>
										</g>
									</svg>
								</div>
								<div class="col-8 col-md-12 border-top">
									<h2>Tecnología</h2>
									<p>Desarrollamos e implementamos todas las herramientas tecnológicas necesarias para hacer este proceso posible.</p>
								</div>
							</div>
						</div>
						<div class="item col-12 col-md-4">
							<div class="row no-gutters">
								<div class="col-4 col-md-6 offset-md-3">
									<svg enable-background="new 0 0 200 200" version="1.1" viewBox="0 0 200 200" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
										<g fill="none" stroke="#4D4D4D" stroke-miterlimit="10" stroke-width="1.5">
											<circle cx="118.75" cy="100" r="56.25"/>
											<circle cx="81.25" cy="100" r="56.25"/>
										</g>
									</svg>
								</div>
								<div class="col-8 col-md-12 border-top">
									<h2>Vínculo</h2>
									<p>Nos aseguramos de que los productos lleguen a todo el país.</p>
								</div>
							</div>
						</div>
					</div>
				</div>
		</section>
<section id="communities">
        <div class="slide-container partners scrollto flex" id="partners" v-carousel>
            <div class="partners-horizontal panel">
                <ul>
					<li class="item">
							<svg class="flecha" enable-background="new 0 0 36.2 64.6" version="1.1" viewBox="0 0 36.2 64.6" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
								<line class="st0" x1="18.8" x2="18.8" y1="2.5" y2="62.5"/>
								<polyline class="st0" points="33.8 47.5 18.8 62.5 3.8 47.5"/>
							</svg>
							<h1>Creadores &amp; Comunidades</h1>
							<p class="w-margin">Queremos un ecosistema cultural más rico, en donde cada vez más actores creen y más comunidades elijan.</p>
							<div class="d-none">
								<p>¿Sos Creador y te interesa <br>trabajar con nosotros?</p>
								<br><a href="#">Contactanos</a>
							</div>
					</li>
					<li class="item com">
						<h2>El Gato y la Caja</h2>
						<h5>Ciencia</h5>
						<img  :src="`${$versionRoot}image/El_Gato_y_La_Caja.jpg`">
						<div class="links">
							<div class="row">
								<div class="col-6">
									<a href="https://www.instagram.com/elgatoylacaja/" target="_blank">Instagram</a>
									<a href="https://www.facebook.com/elgatoylacaja/" target="_blank">Facebook</a>
									<a href="https://www.twitter.com/elgatoylacaja" target="_blank">Twitter</a>
								</div>
								<div class="col-6">
									<a href="https://www.elgatoylacaja.com.ar/tienda/" target="_blank" class="float-right">Tienda</a>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<p>EGLC es un proyecto de Investigación, Comunicación y Diseño en el que generan y comparten ciencia con el apoyo de una comunidad enorme que participa de experimentos reales.</p>
								</div>
							</div>
						</div>
					</li>
					<li class="item com">
						<h2>No Me Olvidé De Vos</h2>
						<h5>Literatura</h5>
						<img  :src="`${$versionRoot}image/nomeolvidedevos.jpg`">
						<div class="links">
							<div class="row">
								<div class="col-6">
									<a href="https://www.instagram.com/nomeolvidedevos/" target="_blank">Instagram</a>
									<a href="https://www.facebook.com/nomeolvidedevos/" target="_blank">Facebook</a>
									<a href="https://www.twitter.com/nomeolvidedevos/" target="_blank">Twitter</a>
								</div>
								<div class="col-6">
									<a href="http://www.nomeolvidedevos.com/libro/" target="_blank" class="float-right">Tienda</a>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<p>No Me Olvidé De Vos es un proyecto de cartas entre personas que todavía creen en las cartas. Gracias al apoyo de su comunidad, produjeron el año pasado NMODV volumen I y este año van por el II.</p>
								</div>
							</div>
						</div>
					</li>
					<li class="item com">
						<h2>La Cope</h2>
						<h5>Humor</h5>
						<img  :src="`${$versionRoot}image/La_Cope.jpg`">
						<div class="links">
							<div class="row">
								<div class="col-6">
									<a href="https://www.instagram.com/lacopecope/" target="_blank">Instagram</a>
									<a href="https://www.facebook.com/lacopecope/" target="_blank">Facebook</a>
									<a href="https://www.twitter.com/lacopecope/" target="_blank">Twitter</a>
								</div>
								<div class="col-6">
									<a href="https://www.lacopecope.com/" target="_blank" class="float-right">Tienda</a>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<p>Lía Copello conecta con su comunidad gracias a La Cope, una serie de tiras donde deciden no solo reirse de los estereotipos sino desafiarlos. Juntos hicieron esta agenda, parecida a ninguna otra.</p>
								</div>
							</div>
						</div>
					</li>
					<li class="item com">
						<h2>Pablo Molinari</h2>
						<h5>Humor</h5>
						<img  :src="`${$versionRoot}image/pablo_molinari.jpg`">
						<div class="links">
							<div class="row">
								<div class="col-6">
									<a href="https://www.instagram.com/molinerd/" target="_blank">Instagram</a>
									<a href="https://www.facebook.com/HolaPabloMolinari/" target="_blank">Facebook</a>
									<a href="https://www.twitter.com/pablomolinari/" target="_blank">Twitter</a>
								</div>
								<div class="col-6">
									<a href="http://www.pablomlnr.com/" target="_blank" class="float-right">Tienda</a>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<p>Pablo empezó con una cuenta de Instagram y ganas de hacer reir. Un año y millones de reproducciones después, él y su comunidad produjeron Un Libro de Humor Lleno de Gráficos.</p>
								</div>
							</div>
						</div>
					</li>
					<li class="item com">
						<h2>Natalia Rozenblum</h2>
						<h5>Literatura</h5>
						<img  :src="`${$versionRoot}image/Natalia_Rozenblum.jpg`">
						<div class="links">
							<div class="row">
								<div class="col-6">
									<a href="https://www.instagram.com/cuadernodeescritura/" target="_blank">Instagram</a>
									<a href="https://www.facebook.com/tallercuadernodeescritura/" target="_blank">Facebook</a>
								</div>
								<div class="col-6">
									<a href="https://www.abrecultura.com/cuadernodeescritura/" class="float-right">Tienda</a>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<p>A Natalia y su comunidad los conecta escribir. Por eso, desarrollaron juntos Cuaderno de Escritura, una herramienta para que puedan practicar, mejorar y descubrir nuevas formas de expresarse.</p>
								</div>
							</div>
						</div>
					</li>
					<li class="item com">
						<h2>Juan Cruz Balián</h2>
						<h5>Literatura</h5>
						<img  :src="`${$versionRoot}image/juan_cruz_balian.jpg`">
						<div class="links">
							<div class="row">
								<div class="col-6">
									<a href="https://www.facebook.com/juanxbalian" target="_blank">Facebook</a>
									<a href="https://www.twitter.com/Juanxbalian/" target="_blank">Twitter</a>
								</div>
								<div class="col-6">
									<a href=" https://www.elgatoylacaja.com.ar/producto/simple-e-imperfecto/" class="float-right">Tienda</a>
								</div>
							</div>
							<div class="row">
								<div class="col-12">
									<p>El futuro nos llena de preguntas a todos, pero Juancho las convierte en cuentos. Dentro del sitio de EGYLC, Simple e Imperfecto creció en lectores tanto que tuvo que pasar a ser un libro.</p>
								</div>
							</div>
						</div>
					</li>
                </ul>
            </div>
        </div>
</section>
		
		<section id="cuaderno-de-escritura">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="row">
							<div class="col-12 col-md-6">
								<img src="https://www.abrecultura.com/api/media/43" class="img-fluid">
							</div>
							<div class="col-12 col-md-5 desc">
								<h1>¡Cuaderno de Escritura ya existe gracias a su comunidad!</h1>
								<p>Una caja de herramientas con más de 100 disparadores creativos para desafiar a quienes ya escriben y acompañar a los que empiezan, y vos podés conseguir uno acá.</p>
								<div class="btn-box">
									<a href="https://www.abrecultura.com/cuadernodeescritura/" class="btn btn-primary btn-block">Ir a la tienda
										<svg class="icon-1U3EI3w" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20.71 10.71"><path fill="none" class="st0" stroke-miterlimit="10" d="M0 5.35h20M15 .35l5 5-5 5"></path></svg>
									</a>
								</div>
							</div>
							<div class="col-md-1"></div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section id="what-we-do-2">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<h1 class="first-title">Lo que hacemos</h1>
						<div class="row">
							<div class="col-12 col-md-4">
								<p>Productos de calidad con contenido desarrollado por los Creadores, y hecho realidad gracias a su Comunidad.</p>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="newsletter">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="row">
								<div class="col-12 col-lg-4 col-md-6 offset-lg-4 offset-md-3">
									<h1>¿Querés suscribirte
									<br>al Newsletter?</h1>
								</div>
							</div>
							<div class="row">
								<div class="col-12 col-md-6 offset-md-3">
									<form id="newsForm" @submit="newsForm"  method="post">
										<p v-html="newsError"></p>
										<input type="text" name="email" id="email" v-model="email" class="form-control" placeholder="Dejanos tu e-mail">
									</form>
								</div>
							</div>
							<div class="row">
								<div class="col-12 col-md-8 offset-md-2">
									<div class="row">
										<div class="col-12 col-md-6">
											<div class="btn-box">
												<a href="https://www.facebook.com/sharer/sharer.php?u=https://www.abrecultura.com/" target="_blank" class="btn btn-primary btn-block">Compartir en <i class="fab fa-facebook-square fa-fw"></i></a>
											</div>
										</div>
										<div class="col-12 col-md-6">
											<div class="btn-box">
												<a href="https://www.twitter.com/intent/tweet?url=https%3A%2F%2Fabrecultura.com%2F" target="_blank" class="btn btn-primary btn-block">Compartir en <i class="fab fa-twitter fa-fw"></i></a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<footer>
			<div class="fullscreen-bg">
				<video ref="botVid" loop muted autoplay playsinline  class="fullscreen-bg__video">
					<source :src="`${$versionRoot}video/` + videoUrl + `_bottom.webm`" type="video/webm">
				</video>
			</div>
			<div class="container">
				<div class="row">
					
					<div class="d-none d-md-block col-md-3">
						<router-link to="/">
							<svg class="logo logo3" enable-background="new 0 0 193.2 35.1" version="1.1" viewBox="0 0 193.2 35.1" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
								<path class="st0" d="M27.4,32.4l-1.5-4.6H15.1l-1.5,4.6H2.5L14.6,4.2h11.5l12.2,28.2H27.4z M20.4,12l-3.2,9.6h6.6L20.4,12z"/>
								<path class="st0" d="m89.2 29c-2.6 3.1-7.2 3.4-10.8 3.4h-21.6v-28.2h23.7c2.8 0 6.3 0.2 8 3 0.4 0.6 1 1.8 1 3.6 0 0.6 0 2.4-1.2 3.9-0.6 0.8-1.9 1.9-4.2 2.4 1.8 0.3 3.4 0.6 4.8 1.8 1.7 1.4 2.1 3.1 2.1 5.2-0.1 0.9-0.2 2.9-1.8 4.9zm-10.9-8h-11.4v5h10.6c0.1 0 0.9 0 1.4-0.1 1.3-0.3 1.9-1.2 1.9-2.5 0-2.3-1.8-2.4-2.5-2.4zm1.1-9.5c-0.5-0.9-1.5-1-2.4-1h-10v4.4h9.4c1 0 2.5 0.1 3.2-1.1 0.2-0.3 0.3-0.8 0.3-1.1-0.1-0.2-0.1-0.7-0.5-1.2z"/>
								<path class="st0" d="m131.6 32.4l-5.8-10.3h-5.4v10.3h-10.2v-28.2h20.8c3.8 0 7.4 0.3 9.7 3.9 0.5 0.8 1.4 2.5 1.4 5.1 0 1.3-0.3 5.1-3.8 7-0.7 0.4-1.2 0.6-2.6 1l7 11.3h-11.1zm0-20.7c-0.7-1-1.8-1.2-2.9-1.2h-8.4v5.2h7.6c1.9 0 2.9-0.2 3.6-1 0.5-0.6 0.6-1.3 0.6-1.6 0-0.2-0.1-0.8-0.5-1.4z"/>
								<path class="st0" d="M161.3,32.4V4.2H191v6.3h-19.7v4.6h18.2v6.4h-18.2V26h20v6.4H161.3z"/>
							</svg>
						</router-link>
					</div>
					<div class="col-12 col-md-3">
						<div class="row">
							<div class="col-6">
								<router-link to="/" class="d-block d-md-none">ABRE</router-link>
								<router-link to="/nosotros">Nosotros</router-link>
							</div>
							<div class="col-6 col-md-12">
								<router-link to="/faq">F.A.Q.</router-link>
								<router-link to="/contacto" class="d-none">Contacto</router-link>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-3">
						<div class="row second-row-margin">
							<div class="col-6 col-md-12">
								<p>Av. Cramer 730
								<br>Ciudad de Buenos Aires 
								<br>Argentina</p>
							</div>
							<div class="col-6 d-block d-md-none">
								<p>Escribinos a:
								<br><a href="mailto:hola@abrecultura.com">hola@abrecultura.com</a></p>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-3">
						<div class="d-none d-md-block">
							<p>Escribinos a:
							<br><a href="mailto:hola@abrecultura.com">hola@abrecultura.com</a></p>
						</div>
					</div>
				</div>
				<div class="row mt-3">
						<div class="col-6">
							<a class="icon" href="https://www.instagram.com/abre_cultura/" target="_blank"><i class="fab fa-instagram"></i></a>
							<a class="icon d-none" href="#"><i class="fab fa-twitter"></i></a>
							<a class="icon" href="https://www.facebook.com/abreculturapuntocom/" target="_blank"><i class="fab fa-facebook-f"></i></a>
						</div>
						<div class="col-6">
							<p class="copy float-right">ABRE&copy; 2018</p>
						</div>
					</div>
			</div>
		</footer>
	</div>
</template>
