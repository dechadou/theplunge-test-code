/* global ScrollMagic */
/* global TimelineMax */
/* global Linear */
import axios from 'axios';

export default {
  name: 'HomePage',
  data() {
    return {
      videoUrl: '',
      winHeight: 0,
      heroTexts: [
        'Estamos convencidos de que hay otra forma de producir cultura y de que democratizar este proceso es, además de necesario y urgente, <i>posible</i>.',
        'Desarrollamos productos culturales sin intermediarios entre creadores y comunidades para que juntos puedan imaginar y materializar productos y experiencias <i>únicas</i> .',
        'Trabajamos con los creadores para que puedan hacer más y mejor, y con las <i>comunidades</i> para que puedan participar en la creación de productos increíbles.',
      ],
      contador: 0,
      email: null,
      newsError: null,
      slide_index: 0,
      slide_prev: 0,
      interval: null,
      slides: document.getElementsByClassName('slide_item'),
      slideTime: 7000,
    };
  },
  methods: {
    carousel(index) {
      this.slides[this.slide_prev].style.display = 'none';
      this.slides[index].classList.add('item_fade');
      this.slides[index].classList.remove('item_fade_out');
      this.slides[index].style.display = 'block';
      this.slide_index = (index + 1) % this.slides.length;
      this.slide_prev = index;
      setTimeout(() => {
        this.slides[index].classList.remove('item_fade');
        this.slides[index].classList.add('item_fade_out');
      }, this.slideTime - 700);
      this.interval = setTimeout(this.carousel, this.slideTime, this.slide_index);
    },
    newsForm(e) {
      e.preventDefault();
      if (!this.validateEmail(this.email)) {
        this.newsError = '<span class="text-danger">Ingresá un Email válido</span>';
        return;
      }
      this.newsError = '<span class="text-success">Enviando...</span>';
      axios
        .post('https://www-dev.abrecultura.com/api/auth/authenticate', {
          app_id: 'abre_5ab00b6600200',
          app_secret: 'abre_5ab00b66002a5',
        })
        .then(response => {
          axios
            .post(
              'https://www.abrecultura.com/api/users/subscribe',
              { email: this.email },
              { headers: { Token: `Bearer: ${response.data.data.accessToken}` } },
            )
            .then(() => {
              this.newsError = '<span class="text-success">Gracias por suscribirte!</span>';
            })
            .catch(() => {
              this.newsError =
                '<span class="text-warning">No pudimos guardar la información. Intentá mas tarde.</span>';
            });
        })
        .catch(() => {
          this.newsError =
            '<span class="text-warning">No pudimos guardar la información. Intentá mas tarde.</span>';
        });
    },
    validateEmail(mail) {
      return /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(mail);
    },
    screenSizeChanged(width, height) {
      this.winHeight = height;
      if (width > 768) {
        this.videoUrl = 'Desktop';
      } else {
        this.videoUrl = 'Mobile';
      }
    },
  },
  mounted() {
    const that = this;

    this.carousel(this.slide_index);
    this.screenSizeChanged(window.innerWidth, window.innerHeight);

    this.$refs.topVid.poster = `${this.$versionRoot}video/${this.videoUrl}_bg.jpg`;
    this.$refs.topVid.play();
    this.$refs.botVid.play();

    this.$nextTick(() => {
      window.addEventListener('resize', () => {
        that.screenSizeChanged(window.innerWidth);
      });

      window.addEventListener(
        'ontouchstart',
        () => {
          this.$refs.topVid.play();
          this.$refs.botVid.play();
        },
        false,
      );
    });
  },
  directives: {
    carousel: {
      inserted() {
        if (window.innerHeight > 600) {
          let stagePadding;
          let stageWidth;

          if (window.innerWidth >= 1200) {
            stagePadding = '5%';
            stageWidth = '-52%';
          } else if (window.innerWidth >= 992) {
            stagePadding = '5%';
            stageWidth = '-60%';
          } else if (window.innerWidth >= 768) {
            stagePadding = '5%';
            stageWidth = '-70%';
          } else {
            stagePadding = '0';
            stageWidth = '-85%';
          }

          new ScrollMagic.Scene({
            triggerElement: '#partners',
            triggerHook: 'onLeave',
            duration: '300%',
          })
            .setPin('#partners')
            .setTween(
              new TimelineMax().fromTo(
                'div.partners-horizontal.panel',
                1,
                { x: stagePadding },
                { x: stageWidth, ease: Linear.easeNone },
              ),
            )
            .addTo(new ScrollMagic.Controller());
        }
      },
    },
  },
};
