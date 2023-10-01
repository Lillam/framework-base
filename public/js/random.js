const text = document.querySelector('.text'),
      textcount = text.innerHTML.length;

const characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMOPQRSTUVWXYZ0123456789';

const random = (max) => Math.floor(Math.random() * max);

const randomCharacter = () => characters[random(characters.length)];

const generateRandomString = () => {
    let string = '';

    for (let i = 0; i <= textcount; i++) {
        string += randomCharacter();
    }

    return string;
};

document.querySelector('.text').addEventListener('mousemove', (e) => {
    // e.screenX || e.screenY (position x and position y) for any alterations we might need to make to css...
    // console.log(e);
    text.innerHTML = generateRandomString();
});