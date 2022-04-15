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

    $('.start__button').on('click', function(e) {
        e.preventDefault();

        $('.masks').hide();
        $('.start').hide();
        // $('.feedback').show();
        $('body').addClass('active-blur');
        $('.main-chest').addClass('active');
    });

    document.querySelectorAll('.chets .item').forEach(item => {
        item.addEventListener('click', (e) => {
            document.querySelectorAll('.chets .item').forEach(el => {
                el.classList.add('opacity')
            })
            item.classList.remove('opacity')
            item.classList.add('active')
            document.querySelector('.main-chest .title').textContent = 'Congratulations! To get a treasure, you just need to register'
            document.querySelector('.btn-register-open').classList.add('active')
        })
    })


    document.querySelector('.btn-register-open').addEventListener('click', (e) => {
        e.preventDefault()
        // document.querySelector('.main-chest').classList.remove('active')
        // document.querySelector('.feedback').classList.add('active')
        window.location.href = "https://foggystarproject.com/en/signup";
    })

    $(function () {
        $("a[href^='#']").click(function () {
            var _href = $(this).attr("href");
            $("html, body").animate({scrollTop: $(_href).offset().top - scrolTopMinus + "px"}, 1500);
            return false;
        });
    });

    const swiperChets = new Swiper('.swiper-chets', {
        slidesPerView: 'auto',
        breakpoints: {
            320: {
                centeredSlides: true,
            },
            1120: {
                centeredSlides: false,
            }
        }
    })

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