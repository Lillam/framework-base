const listV = document.querySelectorAll('.navigation-vertical > ul > li'),
      listH = document.querySelectorAll('.navigation-horizontal > ul > li');

function listVActive() {
    listV.forEach((item) => {
        item.classList.remove('active');
        this.classList.add('active');
    });
}

function listHActive() {
    listH.forEach((item) => {
        item.classList.remove('active');
        this.classList.add('active');
    });
}

listV.forEach((item) => item.addEventListener('click', listVActive));
listH.forEach((item) => item.addEventListener('click', listHActive));