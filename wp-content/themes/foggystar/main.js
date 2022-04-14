var swiper = new Swiper(".main__banner", {
    loop: true,
    navigation: {
      nextEl: ".swiper-button-nexts",
      prevEl: ".swiper-button-prevs",
    },
  });

  var swiper = new Swiper(".tournaments", {
 
    navigation: {
      nextEl: ".swiper-button-nexts",
      prevEl: ".swiper-button-prevs",
    },
  });

  /*
  * Sidebar menu
  */

  const burgerBtn = document.querySelector('.burger');
  const sidebarMenu = document.querySelector('.sidebar-menu');
  const sidebarOverlay = document.querySelector('.sidebar-overlay');
  const closeSidebar = sidebarMenu.querySelector('.close');

  burgerBtn.addEventListener('click', (e) => {
    sidebarMenu.classList.add('visible');
    sidebarOverlay.classList.add('active');
    document.body.classList.add('no-scroll');
  })

  closeSidebar.addEventListener('click', (e) => {
    sidebarMenu.classList.remove('visible');
    sidebarOverlay.classList.remove('active')
    document.body.classList.remove('no-scroll');
  })


  /*
  * Langs dropdown
  */ 

  const langDropdown = document.querySelectorAll('.lang-dropdown');

  langDropdown.forEach((el) => {

    const langDropdownBtn = el.querySelector('.lang-dropdown-btn');
    const langDropdownContent = el.querySelector('.lang-dropdown-content');

    langDropdownBtn.addEventListener('click', (e) => {

      langDropdownContent.classList.toggle('active');

    })

  })

const contactForm = document.querySelector('.contact-form');
  const messageSendedModal = document.createElement('div');
  messageSendedModal.classList.add('message-send-modal');
  messageSendedModal.innerHTML = `<h2>Message sent successfully</h2><p>Thank you!</p>`;

  if(contactForm) {

    contactForm.addEventListener('submit', (e) => {
      e.preventDefault();

      sidebarOverlay.classList.add('active');
      document.body.append(messageSendedModal);



    })

  }

sidebarOverlay.addEventListener('click', (e) => {
  sidebarOverlay.classList.toggle('active');
  if(sidebarMenu.classList.contains('visible')) {
    sidebarMenu.classList.remove('visible')
    document.body.classList.remove('no-scroll');
  }
  if(messageSendedModal) {
    messageSendedModal.remove();
    document.body.classList.remove('no-scroll');
  }

})