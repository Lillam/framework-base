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

// const currentImage = document.querySelector("#my-image");
// const remadeImageWrapper = document.querySelector(".swatch");

// const getImageData = (src) =>
//     new Promise((resolve, reject) => {
//         const canvas = document.createElement("canvas");
//         const context = canvas.getContext("2d");
//         const img = new Image();

//         img.onload = () => {
//             canvas.height = img.height;
//             canvas.width = img.width;
//             context.drawImage(img, 0, 0);

//             const data = context.getImageData(0, 0, img.width, img.height).data;

//             resolve(data);
//         };
//         img.onerror = () => reject(Error("Image loading failed."));
//         img.crossOrigin = "";
//         img.src = src;
//     });

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

// keyboardKeys.forEach(key => {
//     document.createElement('')
// })

// document.addEventListener('keydown', function (event) {
//     if (event.key === ' ' || event.key === 'Tab') {
//         event.preventDefault();
//         event.stopPropagation();
//     }
//
//     console.log(event.key);
//
//     if (event.repeat) {
//         return;
//     }
//
//     const keyElement = document.querySelector(`[data-key="${event.key.toLowerCase()}"]`);
//
//     if (! keyElement) {
//         return;
//     }
//
//     keyElement.classList.add('active');
// });
//
// document.addEventListener('keyup', function (event) {
//     const keyElement = document.querySelector(`[data-key="${event.key.toLowerCase()}"]`);
//
//     if (! keyElement) {
//         return;
//     }
//
//     keyElement.classList.remove('active');
// });

// const useState = (dv = null) => {
//     let _val = dv;

//     const set = (nv) => _val = nv;
//     const get = () => _val;

//     return [get, set];
// };


// const useRouter = () => {
//     const route = window.location.href;

//     const push = (route) => {
//         window.location.href = `${window.location.origin}/${route}`;

//     };

//     return {
//         push
//     };
// };

// const App = (container = document.querySelector('.app')) => {
//     // this state is remembered throughout the entire life-cycle whilst "App" exists...
//     const [getCount, setCount] = useState(0);

//     const { push } = useRouter();

//     // push('test');

//     console.log(getCount());
//     setCount(10);
//     console.log(getCount());
// };

// App(
//     document.querySelector(".app")
// );

// (new IntersectionObserver((entries, observer) => {
//     const adviewer = document.querySelector('.ytp-caption-window-container'),
//           muter = document.querySelector('.ytp-mute-button');

//     entries.forEach(entry => {
//         // In either scenario where the advert is visible or not, we are going to
//         // want to mute the video or unmute if the ad is no longer visible
//         // the aim here is to protect your beautiful brain from disgusting ad visuals and
//         // noises that you don't need to hear why would we bother with that.
//         muter.click();

//         // A rather simple way to just turn the ad screen black; you can let the
//         // Ad play out, but there's no point in you seeing it, I mean; you want
//         // Ads gone, you don't care for them, you're not gonna go out your way
//         // and buy a refrigerator because google decided to push it in your face
//         // are you? - I mean you probably already have a fridge; the audacity to
//         // even assume you want another one. Ads are the crux of the internet
//         // read a book whilst you wait or something; something better than this
//         // god forsaken filth.
//         if (entry.isIntersecting) {
//             adviewer.style.backgroundColor = "black";
//             adviewer.style.zIndex = 10;
//         } else {
//             adviewer.style.backgroundColor = "transparent";
//             adviewer.style.zIndex = 0;
//         }
//     });
// }, {
//     root: null,
//     threshold: 0.05
// })).observe(document.querySelector('.ytp-ad-persistent-progress-bar-container'));






// const Observe = (selector, opt, callback) => {
//     const Obs = new MutationObserver((m) => [...m].forEach(callback));
//     Obs.observe(document.querySelector(selector), opt);
// };

// Observe(".ytp-ad-persistent-progress-bar-container", {
//     attributesList: ["style"], // Only the "style" attribute
//     attributeOldValue: true,   // Report also the oldValue
// }, (mutation) => {
//     const adviewer = document.querySelector('.ytp-caption-window-container'),
//           muter = document.querySelector('.ytp-mute-button');

//     if (mutation.attributeName === "style") {
//         const element = mutation.target;
//         const isVisible = element.style.display !== "none";
//         const interval = setInterval(() => {
//             document.querySelector('.ytp-skip-ad-button')?.click();
//         }, [1000]);

//         // In either scenario where the advert is visible or not, we are going to
//         // want to mute the video or unmute if the ad is no longer visible
//         // the aim here is to protect your beautiful brain from disgusting ad visuals and
//         // noises that you don't need to hear why would we bother with that.
//         if (muter) {
//             muter.click();
//         }

//         // if we don't have an adviewer; something wen't wrong, though this should
//         // be in the document.
//         if (!adviewer) {
//             return;
//         }

//         // A rather simple way to just turn the ad screen black; you can let the
//         // Ad play out, but there's no point in you seeing it, I mean; you want
//         // Ads gone, you don't care for them, you're not gonna go out your way
//         // and buy a refrigerator because google decided to push it in your face
//         // are you? - I mean you probably already have a fridge; the audacity to
//         // even assume you want another one. Ads are the crux of the internet
//         // read a book whilst you wait or something; something better than this
//         // god forsaken filth.
//         if (isVisible) {
//             adviewer.style.backgroundColor = "black";
//             adviewer.style.zIndex = 10;
//             return;
//         } else {
//             clearInterval(interval);
//         }

//         adviewer.style.backgroundColor = "transparent";
//     }
// });





// Select the element you want to observe
// const targetElement = document.querySelector('#yourElement');

// // Create a callback function that will be called when the element's visibility changes
// const observerCallback = (entries, observer) => {
//   entries.forEach(entry => {
//     if (entry.isIntersecting) {
//       // The element is visible
//       console.log('Element is visible');
//     } else {
//       // The element is not visible
//       console.log('Element is not visible');
//     }
//   });
// };

// Create a new IntersectionObserver instance
// const observer = new IntersectionObserver(observerCallback, {
//   root: null,      // Use the viewport as the container
//   threshold: 0.1   // Trigger when at least 10% of the element is visible
// });

// // Start observing the target element
// observer.observe(targetElement);

// const observer = new mutationobserver(function (mutations) {
//     mutations.forEach(function (mutation) {
//         // if the mutation we're dealing with isn't the fact that
//         // youtube has added a class ".ad-showing" to the chosen
//         // element then we're going to ignore it... we only care
//         // about protecting our eyes and ears from the disgusting
//         // adverts forced upon us.
//         if (mutation.attributeName !== "class") {
//             return;
//         }

//         const adPlaying = mutation.target.classList.contains('ad-showing');

//         document.querySelector('.html5-main-video').style.display = adPlaying ? "none" : "block";

//         if (adPlaying) {
//             // if the mute button is enabled, then we wanna disable it.

//         } else {
//             // if the mute button is disabled, then we wanna re-enable it.
//         }

//         document.querySelector('.html5-main-video').style.display = "none";
//         // if ($(mutation.target).hasClass('passed')){
//         //     alert("passed class was added");
//         //     fill();
//         // }
//     });
// });

// observer.observe(document.querySelector('.html5-video-player'), {
//     attributes: true
// });

// this is the only way in which my brain can comprehend something like this
// even being possible... I'm not too sure what happens under the hood behind
// react and would love to see it though when looking through the code you
// don't actually have insight into these things, instead
// you only get to see the interfaces instead - which does make me wonder where
// is the code situated when installing these things in node modules? they've got
// to be there somewhere right?
const useState = (val) => {
    const state = {
        value: val
    };

    const set = (newVal) => state.value = newVal;

    return [ state, set];
};

const [state, setState] = useState(0);


console.log(state.value);

setState(100);

// should be 100..
console.log(state.value);
