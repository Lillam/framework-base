// const graph = (element) => ({
//     element: (function () {
//         return typeof element === "string" ? document.querySelector(element) : element;
//     })(),
//     data: [],
//     labels: [],
//     setData: function (data) {
//         this.data = data;
//         return this;
//     },
//     setLabels: function (labels) {
//         this.labels = labels;
//         return this;
//     },
//     render: function () {
//         let html = "";
//         const calculate = (number) => {
//             return (parseInt(number) / Math.max(...this.data)) * 100;
//         };

//         this.data.map((datum, key) => {
//             html += `<div style="height: ${calculate(datum)}%;"><span>${this.labels[key]}</span></div>`;
//         });

//         this.element.innerHTML = html;

//         return this;
//     },
// });

// graph(".graph")
//     .setData([1, 2, 3, 4, 7, 6, 2, 1, 1, 2, 3, 4, 7, 6, 2, 100])
//     .setLabels([
//         "Tests 1",
//         "Tests 2",
//         "Tests 3",
//         "Tests 4",
//         "Tests 5",
//         "Tests 6",
//         "Tests 7",
//         "Tests 8",
//         "Tests 9",
//         "Tests 10",
//         "Tests 11",
//         "Tests 12",
//         "Tests 13",
//         "Tests 14",
//         "Tests 15",
//         "Tests 16",
//     ])
//     .render();
