<script src="./Faq.js"></script>

<template>
	<div>
		<nav class="navbar fixed-top">
			<div class="container">
				<router-link to="/" class="logo">
					<svg class="logo logo3" enable-background="new 0 0 197.5 38" version="1.1" viewBox="0 0 197.5 38" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
							<path class="st0" d="M29.2,33.1l-1.5-4.6H16.9l-1.5,4.6H4.2L16.4,4.8H28l12.2,28.2H29.2z M22.2,12.7L19,22.2h6.6L22.2,12.7z"/>
							<path class="st0" d="m91 29.7c-2.6 3.1-7.2 3.4-10.8 3.4h-21.6v-28.3h23.7c2.8 0 6.3 0.2 8 3 0.4 0.6 1 1.8 1 3.6 0 0.6 0 2.4-1.2 3.9-0.6 0.8-1.9 1.9-4.2 2.4 1.8 0.3 3.4 0.6 4.8 1.8 1.7 1.4 2.1 3.1 2.1 5.2-0.1 0.9-0.2 3-1.8 5zm-10.9-8h-11.4v4.9h10.6c0.1 0 0.9 0 1.4-0.1 1.3-0.3 1.9-1.2 1.9-2.5 0-2.3-1.8-2.3-2.5-2.3zm1.1-9.5c-0.5-0.9-1.5-1-2.4-1h-10v4.4h9.4c1 0 2.5 0.1 3.2-1.1 0.2-0.3 0.3-0.8 0.3-1.1-0.1-0.2-0.1-0.7-0.5-1.2z"/>
							<path class="st0" d="m133.4 33.1l-5.8-10.3h-5.4v10.3h-10.2v-28.3h20.8c3.8 0 7.4 0.3 9.7 3.9 0.5 0.8 1.4 2.5 1.4 5.1 0 1.3-0.3 5.1-3.8 7-0.7 0.4-1.2 0.6-2.6 1l7 11.3h-11.1zm0-20.7c-0.7-1-1.8-1.2-2.9-1.2h-8.4v5.2h7.6c1.9 0 2.9-0.2 3.6-1 0.5-0.6 0.6-1.3 0.6-1.6 0-0.2-0.1-0.9-0.5-1.4z"/>
							<path class="st0" d="M163,33.1V4.8h29.8v6.3h-19.7v4.6h18.2v6.4h-18.2v4.5h20v6.4H163z"/>
					</svg>
				</router-link>
				<ul class="navigation d-none d-md-block">
					<li><router-link to="/">Home</router-link></li>
					<li><router-link to="/nosotros">Nosotros</router-link></li>
					<li><router-link to="/faq">FAQ</router-link></li>
				</ul>
			</div>
		</nav>
		<section id="faq">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<h1>Preguntas
							<br>Frecuentes
						</h1>
						<div class="row">
							<div class="col-12 col-md-7">
								<div class="row">
									<div class="col-12 question-answer" v-for="answer in answers">
										<h2>{{answer.question}}</h2>
										<p v-html="answer.answer"></p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<footer>
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

