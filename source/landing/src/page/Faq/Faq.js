export default {
  name: 'Faq',
  data() {
    return {
      answers: [
        {
          question: '¿Qué es ABRE?',
          answer:
            'Somos un equipo que trabaja junto a con creadores independientes para que objetos y experiencias existan y se distribuyan. Libros, discos, revistas, pinturas, exposiciones, eventos. Desarrollamos cam Para que el creador viva de lo que ama hacer —y no solo de lo que consiguió— y para que las comunidades puedan ser parte de la creación de los productos culturales que realmente quieren.',
        },
        {
          question: '¿Por qué ABRE?',
          answer:
            'Porque creemos necesario un modelo de creación y consumo de cultura alternativo que compense la asimetría instalada por el sistema editorial tradicional. Diseñamos un proceso más transparente, justo y democrático para los usuarios y sus comunidades.',
        },
        {
          question: '¿Qué es una comunidad?',
          answer:
            'Una comunidad es un grupo de usuarios en redes, en blogs, en internet, en la vida analógica, que comparte un interés y se agrupa en torno a una creación. Creemos que el creador es parte emergente de esa comunidad que expresa una visión del mundo compartida por muchos.',
        },
        {
          question: '¿Por qué financiamiento colectivo?',
          answer:
            'Creemos en el financiamiento colectivo como forma de empoderar el vínculo entre los creadores y su comunidades, participando todos en el proceso de materialización de objetos culturales pensados y creados por cada comunidad.',
        },
        {
          question: '¿Qué es el financiamiento colectivo?',
          answer:
            'Es la forma de financiar la producción cultural entre todos, comprando por adelantado. El mayor valor que tiene una comunidad con su creador es el vínculo. La comunidad banca el proyecto durante la campaña de financiamiento, el creador lleva adelante la producción y crea recompensas especiales, además del producto que están financiando, para los que hicieron que fuera posible.',
        },
        {
          question: '¿Por qué los que participan de la campaña reciben recompensas?',
          answer: 'Es una linda forma retribuirle a la comunidad la confianza depositada.',
        },
        {
          question: '¿Cómo es una campaña?',
          answer:
            'Durante tres o cuatro semanas, el creador pone en preventa el producto sin que este exista aún. Es un mes en el que el creador y la comunidad crean un producto significativo y a la vez comparten que para que exista, es importante bancarlo por adelantado. Es un mes en el que la comunidad no solo consume lo que el creador hace, sino que participa: financiando, difundiendo y comentando. <br><br> Una vez terminada la campaña, el producto entra en producción. <br><br> Una vez producido, distribuimos el producto a lo largo de todo el país para que llegue a manos de quienes compraron en preventa.',
        },
        {
          question: '¿Por qué comprar algo que voy a tener recién dentro de un mes?',
          answer:
            'Porque el producto solo es posible si participás. De otra forma no podría existir. Se materializa porque hay gente bancando. Es un paradigma de consumo distinto que, no solo hace más eficiente la producción, sino que amplifica el vínculo que tenés con el creador, incorporándote como actor necesario (y fundamental) del proceso cultural.',
        },
        {
          question: '¿Cómo me entero cuándo está disponible lo que compré?',
          answer:
            'Nosotros, ABRE, acompañamos al creador en hacer un producto que ame y de contarle todo el proceso a su comunidad. Informamos cada paso del proceso para que esa comunidad vea crecer lo que decidieron bancar. Y nos aseguramos de que cada comprador sepa que su pedido está listo vía mail, con seguimiento y amor. Mucho amor, porque nos importa.',
        },
      ],
    };
  },
  mounted() {
    setTimeout(() => window.scrollTo(0, 0), 500);
  },
};
