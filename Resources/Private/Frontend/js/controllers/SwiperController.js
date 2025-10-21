import Swiper from 'swiper'
import { Autoplay, Keyboard, Navigation, Pagination } from 'swiper/modules'

export default class SwiperController {
    #DOM = { swiperSelector: '.swiper' }

    constructor () {
        this.initializeSliders()
    }

    initializeSliders () {
        document.querySelectorAll(this.#DOM.swiperSelector).forEach((swiperElement, index) => {
            const uniqueId = `swiper-${index}-${Date.now()}`
            swiperElement.dataset.swiperId = uniqueId

            const swiperSlides = swiperElement.querySelectorAll('.swiper-slide')

            if (swiperSlides.length === 0) {
                console.warn(`Swiper element with ID ${uniqueId} has no slides. Skipping initialization.`)
                return;
            }

            if (swiperSlides.length === 1) {
                swiperElement.classList.add('single')
                this.hideSliderControls(swiperElement)
                return
            }

            const swiperOptions = this.getSwiperOptions(swiperElement, uniqueId)
            if (!swiperOptions) return

            const swiper = new Swiper(swiperElement, {
                ...swiperOptions,
            })
        })
    }

    getSwiperOptions (swiperElement, uniqueId) {
        const inlineOptions = swiperElement.dataset.swiper ? JSON.parse(swiperElement.dataset.swiper) : {}

        const paginationButton = swiperElement.querySelector('.swiper-pagination')

        if (paginationButton) paginationButton.classList.add(`swiper-pagination-${uniqueId}`)


        let pagination = {
            el: `.swiper-pagination-${uniqueId}`,
            clickable: true,
        }

        const breakpoints = this.breakPointsConfig();

        const defaultOptions = {
            modules: [Autoplay, Navigation, Pagination, Keyboard],
            pagination,
            keyboard: {
                enabled: true,
                onlyInViewport: true,
            },
            breakpoints,
            slidesPerView: 1,
            spaceBetween: 10,
        }

        return { ...defaultOptions, ...inlineOptions }
    }

    hideSliderControls (swiperElement) {
        const controlContainer = swiperElement.querySelector('.swiper-control-container')
        if (controlContainer) {
            controlContainer.style.display = 'none'
        }
    }

    breakPointsConfig () {
        return {
            // when window width is >= 1280px
            1170: {
                slidesPerView: 3,
                spaceBetween: 24,
            },
            800: {
                slidesPerView: 2,
                spaceBetween: 24,
            },
        }
    }
}
