let toEncode = 'WWWWWWWWBBWWWWWWWWBBBBBBBWWW';

// const rte = () => {
//     const handlers = {
//         "space": (cc, s) => s,
//         "char": (cc, s) => cc <= 1 ? s : cc + s
//     };
//
//     const encode = (s) => {
//         let cc = 0, ns = '';
//         for (let i = 0; i < s.length; i++) {
//             const handler = s[i] === ' ' ? 'space' : 'char';
//             cc ++;
//             ns += handlers[handler](cc, s[i]);
//         }
//         return ns;
//     };
//
//     const decode = (s) => {
//
//     };
//
//     return { encode, decode };
// };

// console.log(rte().encode('XXXXXXXXXXXXAABBBY'));

const rle = () => ({
    encode: s => s.replace(/(.)\1+/g, ({ length }, c) => length + c),
    decode: s => s.replace(/(\d+)(.)/g, (_, l, c) => c.repeat(l))
});

console.log(
    rle().encode('XXXXXXBBYYA'),
    rle().decode('6X2B2YA')
    // rle().encode('  XXXYYB ') === '  3X2YB ',
    // rle().decode(' 3X2YB '),
);

// const newEncode = (s) => {
//     let cc = 0, ns = '';
//     for (let i = 0; i < s.length; i++) {
//         cc ++;
//
//         if (s[i] === ' ') {
//             ns += s[i];
//             continue;
//         }
//
//         if (s[i] !== s[i + 1] || i === s.length) {
//             ns += cc <= 1 ? s[i] : cc + s[i];
//             cc = 0;
//         }
//     }
//
//     return ns;
// };
//
// const newDecode = (s) => {
//     let ns = '', cc, c = '', sc = 0;
//     for (let i = 0; i < s.length; i++) {
//         if (s[i] === ' ') {
//             ns += s[i];
//         }
//     }
// }

// const decode = (s) => {
//     return s.split(/(\d+\w)/).filter(v => !!v).map(v => {
//         const c = v[v.length - 1],
//               d = v.replace(c, '');
//
//         return d === '' ? v : c.repeat(parseInt(d));
//     }).join('');
// }

// console.log(decode('27XYZA'));

// const newEncode = (s) => {
//     return s.slice(1, s.length);
// };
//
// console.log(encode(toEncode));
// console.log(encode('XYZ'));
// console.log(decode(encode('XXXXXXXXXXXXXXXXXXXXXXXXXXXYZA')) === "XXXXXXXXXXXXXXXXXXXXXXXXXXXYZA");
// console.log(decode('2A3XB'), decode('XYZ'));
// console.log(newEncode('XXXXXXXXXXXXAABBBY'));

// console.log(newEncode('hello'));