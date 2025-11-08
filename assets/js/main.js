/*=============== SHOW MENU ===============*/

const navMenu = document.getElementById('nav-menu'),
    navToggle = document.getElementById('nav-toggle'),
    navClose = document.getElementById('nav-close')

/* Menu show */
if (navToggle) {
    navToggle.addEventListener('click', () => {
        navMenu.classList.add('show-menu')
    })
}

/* Menu hidden */
if (navClose) {
    navClose.addEventListener('click', () => {
        navMenu.classList.remove('show-menu')
    })
}


/*=============== REMOVE MENU MOBILE ===============*/

const navLink = document.querySelectorAll('.nav__link')

const linkAction = () => {
    const navMenu = document.getElementById('nav-menu')
    // When we click on each nav__link, we remove the show-menu class
    navMenu.classList.remove('show-menu')
}
navLink.forEach(n => n.addEventListener('click', linkAction))

/*=============== CHANGE BACKGROUND HEADER ===============*/

const scrollHeader = () => {
    const header = document.getElementById('header')
    // Add a class if the bottom offset is greater than 50 of the viewport
    this.scrollY >= 50 ? header.classList.add('bg-header')
        : header.classList.remove('bg-header')
}
window.addEventListener('scroll', scrollHeader)


/*=============== SCROLL SECTIONS ACTIVE LINK ===============*/

const sections = document.querySelectorAll('section[id]')

const scrollActive = () =>{
  	const scrollDown = window.scrollY

	sections.forEach(current =>{
		const sectionHeight = current.offsetHeight,
			  sectionTop = current.offsetTop - 58,
			  sectionId = current.getAttribute('id'),
			  sectionsClass = document.querySelector('.nav__menu a[href*=' + sectionId + ']')

		if(scrollDown > sectionTop && scrollDown <= sectionTop + sectionHeight){
			sectionsClass.classList.add('active-link')
		}else{
			sectionsClass.classList.remove('active-link')
		}                                                    
	})
}
window.addEventListener('scroll', scrollActive)


/*=============== SHOW SCROLL UP ===============*/

const scrollUp = () =>{
	const scrollUp = document.getElementById('scroll-up')
    // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scrollup class
	this.scrollY >= 350 ? scrollUp.classList.add('show-scroll')
						: scrollUp.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)

/*=============== SCROLL REVEAL ANIMATION ===============*/

const sr = ScrollReveal({
    origin: 'top',
    distance:'100px',
    duration:1500,
    delay:200,
})
// sr.reveal('.nav__link' ,{origin: 'left'}) 
sr.reveal('.home__data, .footer__container, .footer__group')
sr.reveal('.home__img',{delay: 700, origin: 'bottom'})
sr.reveal('.logos__image , .program__card , .pricing__card' ,{interval: 50})
sr.reveal('.choose__img , .calculate__content, calculate__container' ,{origin: 'left'})
sr.reveal('.choose__content , calculate__img' ,{origin: 'right'})


/*=============== CALCULATE JS ===============*/

const calculateForm = document.getElementById('calculate-form'),
    calculateCm = document.getElementById('calculate-cm'),
    calculateKg = document.getElementById('calculate-kg'),
    calculateMessage = document.getElementById('calculate-message');

const calculateBmi = (e) => {
    e.preventDefault();

    // check if field has a value
    if (calculateCm.value === '' || calculateKg.value === '') {
        // add and remove color
        calculateMessage.classList.remove('color-green');
        calculateMessage.classList.add('color-red');

        // show message
        calculateMessage.textContent = 'Fill In Height and Weight 🫡';

        // remove message in 5 seconds
        setTimeout(() => {
            calculateMessage.textContent = '';
        }, 5000);
    } else {
        const cm = calculateCm.value / 100,
            kg = calculateKg.value,
            bmi = Math.round(kg / (cm * cm));

        // reset colors first
        calculateMessage.classList.remove('color-red');

        // Show Health Status
        if (bmi < 18.5) {
            calculateMessage.classList.add('color-green');
            calculateMessage.textContent = `Your BMI is ${bmi} You Are Skinny 😒`;
        } else if (bmi < 25) {
            calculateMessage.classList.add('color-green');
            calculateMessage.textContent = `Your BMI is ${bmi} You Are Healthy ✅`;
        } else {
            calculateMessage.classList.add('color-green');
            calculateMessage.textContent = `Your BMI is ${bmi} You Are Overweight ⚠️`;
        }
    }
};
calculateForm.addEventListener('submit', calculateBmi);


/*=============== EMAIL JS ===============*/

const contactForm = document.getElementById('contact-form'),
    contactMessage = document.getElementById('contact-message'),
    contactUser = document.getElementById('contact-user');

const sendEmail = (e) => {
    e.preventDefault();

    // check if field has a value or not
    if (contactUser.value === '') {
        // add and remove color
        contactMessage.classList.remove('color-green');
        contactMessage.classList.add('color-red');

        // show message
        contactMessage.textContent = 'Hahahaha Got You!! Enter The Email Above';

        // remove message in 4 seconds
        setTimeout(() => {
            contactMessage.textContent = '';
        }, 4000);
    } else {
        // ✅ correct usage of sendForm
        emailjs.sendForm(
            'service_ica7fqe',       // your service ID
            'template_q6xkptv',      // your template ID
            contactForm,             // pass the form element (NOT string)
            '0iQF3FLUmG3D65RNg'      // your public key
        ).then(() => {
            // success message
            contactMessage.classList.remove('color-red');
            contactMessage.classList.add('color-green');
            contactMessage.textContent = 'Registration Done! 🫡';

            // clear message after 4s
            setTimeout(() => {
                contactMessage.textContent = '';
            }, 4000);

            // reset form fields
            contactForm.reset();
        }).catch((error) => {
            // error message
            contactMessage.classList.remove('color-green');
            contactMessage.classList.add('color-red');
            contactMessage.textContent = `Oops! Something went wrong 😢 (${error.text})`;

            setTimeout(() => {
                contactMessage.textContent = '';
            }, 4000);
        });
    }
};

contactForm.addEventListener('submit', sendEmail);
