import HomePage from 'page/HomePage';
import Nosotros from 'page/Nosotros';
import Faq from 'page/Faq';
import PasarABuscar from 'page/PasarABuscar';
import PagePaths from 'data/enum/PagePaths';
import PageNames from 'data/enum/PageNames';

export default [
  {
    path: PagePaths.HOME,
    component: HomePage,
    name: PageNames.HOME,
  },
  {
    path: PagePaths.NOSOTROS,
    component: Nosotros,
    name: PageNames.NOSOTROS,
  },
  {
    path: PagePaths.FAQ,
    component: Faq,
    name: PageNames.FAQ,
  },
  {
    path: PagePaths.PASARABUSCAR,
    component: PasarABuscar,
    name: PageNames.PASARABUSCAR,
  },
];
