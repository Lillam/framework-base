// const listV = document.querySelectorAll('.navigation-vertical > ul > li'),
//       listH = document.querySelectorAll('.navigation-horizontal > ul > li');
//
// function listVActive() {
//     listV.forEach((item) => {
//         item.classList.remove('active');
//         this.classList.add('active');
//     });
// }
//
// function listHActive() {
//     listH.forEach((item) => {
//         item.classList.remove('active');
//         this.classList.add('active');
//     });
// }
//
// listV.forEach((item) => item.addEventListener('click', listVActive));
// listH.forEach((item) => item.addEventListener('click', listHActive));
//
// let jwt = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6MSwiZmlyc3RfbmFtZSI6IkxpYW0iLCJsYXN0X25hbWUiOiJUYXlsb3IiLCJlbWFpbCI6ImxpYW0udGF5bG9yQG91dGxvb2suY29tIiwicGFzc3dvcmQiOiIkMnkkMTAkYUR5ZkFqN3N0OE9ZVHY3RklFMWE5LnJqdlExZTFSRnFtdHh1TllMR1NjcWd6VVF6am80RmkiLCJhcGlfdG9rZW4iOiJ0aGlzaXNhdG9rZW4iLCJyZW1lbWJlcl90b2tlbiI6bnVsbCwiY3JlYXRlZF9hdCI6IjIwMjItMDYtMDMgMjI6NTg6MDUiLCJ1cGRhdGVkX2F0IjoiMjAyMi0xMi0yNyAxNDoxOTozMyIsImV4cCI6MTY3Mjk1MTUzNX0.rq0dVjGK66JMSIgW0N2qQbQwwggOKt1rdGMGKLlGtGc";
//
// class JWTParser {
//     // This is a server parse, a self enclosed asynchronous call that operates in the form of a synchronous method
//     // allowing the developer to just call this without the need of having to await.
//     serverParse = token => {
//         (async () => {
//             const response = await fetch(`/parseToken?token=${token}`);
//             const data = await response.json();
//             console.log(data);
//         })();
//     }
//
//     localParse = token => JSON.parse(
//         decodeURIComponent(
//             atob(token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/'))
//                 .split('')
//                 .map((c) => `%${`00${c.charCodeAt(0).toString(16)}`.slice(-2)}`)
//                 .join(''),
//         )
//     );
//
//     fetcher = async token => {
//         const data = await fetch(`/parseToken?token=${token}`);
//         const response = await data.json();
//         // console.log(response);
//     }
// }
//
// // console.log(new JWTParser().serverParse(jwt));
// // console.log(new JWTParser().localParse(jwt));
//
// class Fetcher {
//     baseUrl = '/';
//
//     constructor(baseUrl) {
//         this.baseUrl = baseUrl;
//     }
//
//     get = (url, options = {}) => fetch(url, options);
//     post = (url, options = {}) => fetch(url, options);
//     put = (url, options = {}) => fetch(url, options);
// }
//
// new Fetcher().get('/api/token').then(d => d.json()).then(d => {
//     console.log(new JWTParser().localParse(d.data));
// }).catch(e => console.log(`the error is: ${e.message}`));
//
// const useState = (initialVal) => {
//     let _val = initialVal ?? null;
//
//     return [
//         _val,
//         () => _val,
//         (newVal) => _val = newVal
//     ];
// }
//
//
// const [val, get, set] = useState('test');
//
// console.log(val, get(), set('testing'), get());

// const InterestCalculator = () => ({
//     funds: 17000,
//     interest: 0.03,
//     predict: (monthlyDeposit) => {
//
//     }
// });

// console.log(17000 * 0.03);


const currentImage = document.querySelector('#my-image');
const remadeImageWrapper = document.querySelector('.swatch');

const getImageData = (src) => new Promise((resolve, reject) => {
    const canvas = document.createElement('canvas')
    const context = canvas.getContext('2d')
    const img = new Image

    img.onload = () => {
        canvas.height = img.height
        canvas.width = img.width
        context.drawImage(img, 0, 0)

        const data = context.getImageData(0, 0, img.width, img.height).data

        resolve(data)
    }
    img.onerror = () => reject(Error('Image loading failed.'))
    img.crossOrigin = ''
    img.src = src
});

// getImageData(currentImage.src).then(colours => {
//     console.log(colours.length / 4);
//     // for (let i = 0; i <= colours.length; i + 4) {
//     //     let r = colours[i];
//     //     let g = colours[i + 1];
//     //     let b = colours[i + 2];
//     //
//     //     let pixel = document.createElement('div');
//     //
//     //     pixel.style.width = '1px';
//     //     pixel.style.height = '1px';
//     //     pixel.style.backgroundColour = `rgb(${r}, ${g}, ${b})`;
//     //     remadeImageWrapper.append(pixel);
//     //
//     //     delete(pixel);
//     // }
// });