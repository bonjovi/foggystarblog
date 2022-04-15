$(function() {
    // $("#feedback-form").modal({
    //     fadeDuration: 100
    // });

    // $('.start').hide();
    // $('.masks').show();

    // $('.masks__item').on('click', function() {
    //     $('.masks__item').removeClass('active').addClass('transparent');
    //     $(this).addClass('active').removeClass('transparent');
    //     $('.masks__button').addClass('active');
    //     $('.masks__bigtitle').hide();
    //     $('.footer').addClass('footer--small');
    // });

    const swiper = new Swiper('.swiper', {
        slidesPerView: 'auto',
        direction: 'horizontal',
        loop: false,
        spaceBetween: 30,
        pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
        },
        breakpoints: {
            320: {
                spaceBetween: 40,
                centeredSlides: true
            },
            1120: {

            }
        }
    });
    
    const drop = document.querySelectorAll('.drop-js');

    if (drop) {

        drop.forEach(item => {

            const title = item.querySelector('.drop-title-js')
            const titleSpan = title.querySelector('span')
            const content = item.querySelector('.drop-content-js')
            const contentItem = item.querySelectorAll('.drop-content-js .item')

            if (title) {
                title.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (item.classList.contains('active')) {
                        item.classList.remove('active')
                        content.style.height = 0 + 'px'
                    } else {
                        item.classList.add('active')
                        content.style.height = 200 + 'px'
                    }
                    
                })
            }
            if (contentItem) {
                contentItem.forEach(elem => {
                    elem.addEventListener('click', (e) => {
                        titleSpan.textContent = elem.textContent
                        item.classList.remove('active')
                        content.style.height = 0 + 'px'
                    })
                })
            }
        })
    }


    $('.start__button').on('click', function() {
        $('.start').hide();
        $('.masks').addClass('active');
        $('.blur').addClass('active');
        // $('body').addClass('active-blur');
    });

    $('.start__random').on('click', function(e) {
        e.preventDefault();

        setTimeout(() => {
            $('.start__random').addClass('opacity-all');
            document.querySelectorAll('.masks__item')[0].classList.add('rotated-mask-1');
            document.querySelectorAll('.masks__item')[0].classList.remove('transparent');
            document.querySelectorAll('.masks__item')[1].classList.add('rotated-mask-2');
            document.querySelectorAll('.masks__item')[2].classList.add('rotated-mask-3');
            document.querySelectorAll('.masks__item')[3].classList.add('rotated-mask-4');
            document.querySelectorAll('.masks__item')[4].classList.add('rotated-mask-5');
            document.querySelectorAll('.masks__item')[0].classList.add('opacity-all');
            document.querySelectorAll('.masks__item')[1].classList.add('opacity-all');
            document.querySelectorAll('.masks__item')[2].classList.add('opacity-all');
            document.querySelectorAll('.masks__item')[3].classList.add('opacity-all');
            document.querySelectorAll('.masks__item')[4].classList.add('opacity-all');
            document.querySelector('.swiper-pagination').classList.add('op-swp');
        }, 0);
        setTimeout(() => {
            document.querySelectorAll('.masks__item')[0].classList.remove('transparent');
            document.querySelector('.swiper-pagination').style.opacity = '0'
        }, 1000);
        
        setTimeout(() => {
           
            document.querySelectorAll('.masks__item')[0].classList.add('visible-zero');
            document.querySelectorAll('.masks__item')[0].classList.remove('opacity-all');
        }, 2250);

        setTimeout(() => {
            document.querySelectorAll('.masks__item')[0].classList.add('active');
            document.querySelectorAll('.masks__items')[0].classList.add('hide-right');
        }, 3000);
        setTimeout(() => {
            document.querySelectorAll('.masks__item')[0].classList.add('active-shadow');

            swiper.slideTo(0, 1000)

            document.querySelector('.swiper').style.pointerEvents = 'none'

        }, 3500);
        setTimeout(() => {
            $('.masks__bigtitle').hide();
            $('.masks__fin').show();
            $('.masks__button').show();
            $('.masks__button').addClass('active');
            $('.start__random').hide();
        }, 4100);
    });

    $('.masks__button').on('click', function() {
        document.body.style.overflow = 'hidden'
        document.querySelectorAll('.mask-showe')[0].classList.add('active-zooming');
        
        setTimeout(() => {
            window.location.href = "https://foggystarproject.com/en/signup";
            // $('.feedback').addClass('active');
            // $('.blur').addClass('active');
            // $('.masks').hide();
        }, 1000);
        setTimeout(() => {
            document.body.style.overflow = ''
            $('.mask-showe').hide();
        }, 2000);
    });

    const formsSub = document.querySelector('.form-sub')
    formsSub.addEventListener('submit', (e) => {
        e.preventDefault()


        const titleDrop = formsSub.querySelector('.drop-title-js span').textContent
        const email = formsSub.querySelector('.email').value
        const password = formsSub.querySelector('.password').value
        const promocode = formsSub.querySelector('.promocode').value

        const formData = new FormData()
        formData.append('currency', titleDrop.trim().toLowerCase())
        formData.append('email', email.trim().toLowerCase())
        formData.append('password', password)
        formData.append('promocode', promocode)

        const sendReg = async () => {
            let response = await fetch('send.php', {
                method: 'POST',
                body: formData
            });

            if (response.status === 200) {
                
            }

            window.location.href = "https://foggystarproject.com/en/signup";
        }
        sendReg()

    })
    
    
});

