/**
 * Initialize the Swiper component that controls
 * the highlights on the homepage
 *
 * @see https://swiperjs.com/
 */
import Swiper from 'swiper';
import { A11y, Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const init = () => {
  const swiper = new Swiper('.homepage-block-highlights .swiper', {
    modules: [
      A11y,
      Navigation,
      Pagination,
    ],
    ally: {
      prevSlideMessage: individualizeTheme.prevSlide,
      nextSlideMessage: individualizeTheme.nextSlide,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
    }
  });

  return swiper
}

export default {
  init
}