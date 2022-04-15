$(function() {
    // $("#feedback-form").modal({
    //     fadeDuration: 100
    // });

    new WOW().init();
    
    if($(window).width() < 576) {
        $('.reasons__mainitem').insertAfter('.reasons__firstitem');
    }

    document.querySelectorAll('.intro__button').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = "https://foggystarproject.com/en/signup";
        })
    })
    document.querySelectorAll('.reasons__button').forEach(item => {
        item.addEventListener('click', (e) => {
            e.preventDefault()
            window.location.href = "https://foggystarproject.com/en/signup";
        })
    })

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